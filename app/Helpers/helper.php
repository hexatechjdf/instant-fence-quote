<?php

use App\Models\Category;
use App\Models\CustomField;
use App\Models\FtAvailable;
use App\Models\License;
use App\Models\PriceFit;
use App\Models\Estimate;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Mail\CredentialChangeMail;

function uploadFile($file, $path, $name)
{
    $name = $name . '.' . $file->getClientOriginalExtension();
    $file->move($path, $name);
    return $path . '/' . $name;
}

function ft_price($fence_id, $ft_available_id, $type)
{
    return PriceFit::where(['fence_id' => $fence_id, 'ft_available_id' => $ft_available_id, 'type' => $type])->first();
}

function totalCategory()
{
    if (auth()->user()->role == 1) {
        return Category::count();
    } else {
        return Category::where('user_id', Auth::id())->count();
    }
}

function check_ghl($loc)
{
    if ($loc && $loc->ghl_api_key) {
        return true;
    } else {
        return false;
    }
}


function totalFtAvailable()
{
    if (auth()->user()->role == 1) {
        return FtAvailable::count();
    } else {
        return FtAvailable::where('user_id', Auth::id())->count();
    }
}

function totalCustomFields()
{
    return CustomField::count();
}

function totalUsers()
{
    return User::count();
}

function totalEstimates()
{
    if (auth()->user()->role == 1) {
        return Estimate::count();
    } else {
        return Estimate::where('company_id', Auth::id())->count();
    }
}


function setting($key, $id = 1)
{

    $setting = Setting::where('company_id', $id)->pluck('value', 'name');
    if ($key == 'all') {
        return $setting;
    }
    return $setting[$key] ?? null;
}

function insert_setting($key, $value = '')
{
    $setting = Setting::where(['company_id' => Auth::id(), 'name' => $key])->first() ?? new Setting();
    $setting->name = $key;
    $setting->value = $value;
    $setting->company_id = Auth::id();
    $setting->save();
    return true;
}

function ghl_api_call($url = '', $data = ['method' => 'get', 'body' => '', 'json' => false, 'token' => ''])
{

    // dd($url,$data);
    // if($data['method'] == 'put'){
    //     dd( $url);
    //  }
    if (!isset($data['headers'])) {
        $headers = [];
    }
    if (isset($data['token']) && !empty($data['token'])) {
        $token = $data['token'];
    } else {
        if (session('location_id')) {
            $user = session('location_id');
        } else {
            $user = auth()->user();
        }
        $token = $user->ghl_api_key;
    }

    $headers['Authorization'] = ' Bearer ' . $token;
    if (isset($data['json']) && $data['json']) {
        $headers['Content-Type'] = "application/json";
    }

    $client = new \GuzzleHttp\Client(['http_errors' => false, 'headers' => $headers]);
    $options = [];


    if (isset($data['body']) && !empty($data['body'])) {
        $options['body'] = $data['body'];
    }

    $method = 'get';
    if (isset($data['method'])) {
        $method = $data['method'];
    }

    // temporary disabled for local development
    $options['verify'] = false;


    $response = $client->request($method, 'https://rest.gohighlevel.com/v1/' . $url, $options);
    return json_decode($response->getBody());
}

function saveContactToGhl($request)
{
    // dd($request->all());
    $sab = new stdClass;
    $sab->name = $request->name;
    $sab->email = $request->email;
    $sab->phone = $request->phone;
    $location = find_location($request->location);


    $request->location_id = $request->location;
    $request->company_id = $location->id;




    $request->where_left = $request->current_slide_id ?? "personal";

    //save estimate contact

    if (check_ghl($location)) {
        // $cid='';
        // $met='POST';
        // if($request->has('id') && !empty($request->id)){
        //     $met='PUT';
        //     $cid='/'. $request->id;
        // }
        $res = ghl_api_call('contacts', ['method' => 'POST', 'body' => json_encode($sab), 'json' => true, 'token' => $location->ghl_api_key]);
    }

    $resp = new stdClass;
    $resp->contact_id = $res->contact->id ?? null;
    $id = null;

    if ($request->has('estimator_id') && !empty($request->estimator_id) && !is_null($request->estimator_id)) {
        $id = $request->estimator_id;

    }

    $request->contact_id = $resp->contact_id;
    $data = $request->all();
    // dd($data);

    $data['company_id'] = $location->id ?? null;
    $data['location_id'] = $request->location;
    $data['contact_id'] = $resp->contact_id ?? null;

    try {
        unset($data['estimator_id']);
        unset($data['id']);
        unset($data['location']);
    } catch (\Exception $e) {

    }

    $estimated = Estimate::where(['uuid' => $id, 'is_completed' => 0])->first();

    if ($estimated) {
        //   dd($data['uuid'], "here in update");
        $est1 = Estimate::where('uuid', $id);
        $est1->update($data);
    } else {

        $data['uuid'] = Str::uuid()->toString();
        // dd($data['uuid'], "here in create");
        $est = Estimate::create($data);
    }


    $resp->estimate_id = $est->uuid;

    return $resp;
}

function get_currency1($loc = null)
{
    $loc = User::where('location', $loc)->first();
    if ($loc) {
        $loc = $loc->id;
    } else {
        if (auth()->check()) {
            $loc = auth()->user()->id;
        } else {
            $loc = 1;
        }
    }
    $currency = setting('estimate_currency_symbol', $loc);
    if (empty($currency)) {
        $currency = '$';
    }
    return $currency;
}

function get_loc_setting($key, $loc = null)
{

    $loc = User::where('location', $loc)->first();
    if ($loc) {
        $loc = $loc->id;
    } else {
        if (auth()->check()) {
            $loc = auth()->user()->id;
        } else {
            $loc = 1;
        }
    }
    $currency = setting($key, $loc);
    if (is_null($currency)) {
        return null;
    }
    return $currency;
}



function setCustomFields($request, $loc)
{

    $user_custom_fields = new \stdClass;
    try {
        $request_array = [];
        foreach ($request as $key => $value) {
            $request_array[$key] = $value;
        }


        $loc = find_location($loc);


        $ghl_custom_values = ghl_api_call('custom-fields', ['method' => 'get', 'body' => '', 'json' => false, 'token' => $loc->ghl_api_key]);

        // dd("ghl= ", $ghl_custom_values);
        $custom_values = $ghl_custom_values;
        if (property_exists($custom_values, 'customFields')) {
            $custom_values = $custom_values->customFields;
            $custom_values = array_filter($custom_values, function ($value) use ($request_array) {
                $kn = strtolower(str_replace(' ', '_', $value->name));
                return in_array($kn, array_keys($request_array));
            });

            foreach ($custom_values as $key => $custom) {
                $name = strtolower(str_replace(' ', '_', $custom->name));
                $custom->value = $request[$name];
                $request_array[$name] = $custom;
            }

            $i = 0;



            foreach ($request_array as $key => $custom) {
                // dd($custom);
                $i++;
                $value = '';
                if (is_object($custom)) {
                    $id = $custom->id;
                    $value = $custom->value;

                } else {
                    if ($i % 5 == 0) {
                        sleep(2);
                    }
                    $value = $custom;
                    $type = strpos($key, 'date') !== false ? 'DATE' : 'TEXT';
                    $type = $key == 'map_image_url' ? 'FILE_UPLOAD' : $type;
                    $send_name = ucwords(str_replace('_', ' ', $key));
                    $abc = ghl_api_call('custom-fields', ['method' => 'post', 'body' => json_encode(['name' => $send_name, 'dataType' => $type]), 'json' => true]);
                    $cord = $abc;
                    if ($cord && property_exists($cord, 'id')) {
                        $id = $cord->id;
                    }
                }
                $user_custom_fields->$id = $value;
            }
        }
    } catch (\Exception $e) {
        dd($e->getMessage());
    }
    return $user_custom_fields;
}

function sendToWebhookUrl($data, $id)
{
    $data = webhookFields($data);
    $webhook_url = setting('webhook_url', $id);

    if ($webhook_url) {
        $client = new \GuzzleHttp\Client(['http_errors' => false]);
        $options = [];
        $options['body'] = json_encode($data);
        $options['headers'] = [
            'Content-Type' => 'application/json',
        ];
        $response = $client->request('post', $webhook_url, $options);
        if ($response->getStatusCode() == 200) {
            return true;
        } else {
            return false;
        }
    }
}

function find_location($location)
{
    $user = User::where('location', $location)->first() ?? null;
    if ($user) {
        session(['location_id' => $user]);
    }
    return $user;
}


function call_cp_api($url, $method = 'get', $data = '')
{
    $url = 'https://' . $_SERVER['SERVER_NAME'] . ':2083/' . $url;
    //   $domain='http://hartselledailynews.com';
    //   $port='2083';
    //  $url = $domain.':'.$port.'/' . $url;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    if ($method == 'POST') {
        curl_setopt($curl, CURLOPT_POST, true);

        if ($data != '') {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
    }

    //
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $headers = array(
        "Authorization: cpanel hartsell:VKVQMQU1JNCPMDL1VH06FDMOMIZKC7I7"
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $resp = curl_exec($curl);
    curl_close($curl);
    return $resp;
}


function ssl_check($domain)
{
    try {

        $d = call_cp_api('execute/SSL/remove_autossl_excluded_domains?domains=' . $domain);
        $c = call_cp_api('execute/SSL/start_autossl_check', 'POST');
    } catch (\Exception $e) {
    }
}


// calling inside function

function add_whitelabel($value)
{
    $domain_without_http = $_SERVER['SERVER_NAME'];
    $value = str_replace('https://', '', $value); // domain
    $domain = str_replace('http://', '', $value);
    $domain = str_replace('/', '', $domain);
    $domain = str_replace(' ', '', $domain);

    $dir = 'getinstantfence.com'; //roguebusinessmarketing.net
    $subdomain = explode('.', $value)[0];
    $data = 'cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=AddonDomain&ftp_is_optional=1&cpanel_jsonapi_func=addaddondomain&newdomain=' . $domain . '&subdomain=' . $subdomain . '&dir=' . $dir;

    $domainpointed = call_cp_api('json-api/cpanel', 'POST', $data);
    $cp_result = json_decode($domainpointed);
    $result = $cp_result->cpanelresult;

    if (property_exists($result, 'data')) {
        // dd($result);
        $result = $result->data;
        if (is_array($result))
            $result = $result[0];
        if ($result->result == 0) {
            //interestsniper.com
            if (strpos($result->reason, 'hartselledailynews.com') == true) { //match with cpanel root domain

                $reason = 'domain already exists';
                ssl_check($domain);
            } else {
                $reason = explode('exists.', $result->reason);
                if (count($reason) > 1) {
                    $reason = $reason[0];
                } else {
                    $reason = $result->reason;
                }
            }

            abort(back()->with('error', $reason));
        }
        if ($result->result == 1) {
            insert_setting('whitelabel_domain', $domain);
            ssl_check($domain);
            abort(back()->with('success', 'Domain Added to server. please wait 24/48h incase not working'));
            // abort(response()->json(['result' => 'error', 'action' => 'update', 'message' => 'Domain Added to server. please wait 24/48h incase not working']));
        }
        //

    }
}



//remove white label domain
function remove_whitelabel($value)
{
    $domain = str_replace('http://', '', $value);
    $domain = str_replace('https://', '', $domain);

    $data = 'cpanel_jsonapi_apiversion=2&cpanel_jsonapi_module=Park&cpanel_jsonapi_func=unpark&domain=' . $domain;

    $domainpointed = call_cp_api('json-api/cpanel', 'POST', $data);
    $cp_result = json_decode($domainpointed);
    $result = $cp_result->cpanelresult;

    if (property_exists($result, 'data')) {
        $result = $result->data[0];
        if ($result->result == 0) {
            return response()->json(['result' => 'error', 'action' => 'update', 'message' => $result->reason]);
        }
        if ($result->result == 1) {
            insert_setting('whitelabel_domain', '');
            return response()->json(['result' => 'success', 'action' => 'update', 'message' => 'Domain Removed from server.']);
        }
    }
}


function get_value($ar, $key, $def = '')
{
    return $ar[$key] ?? $def;
}

function contains($key, $domain)
{
    return strpos($domain, $key) !== false;
}

function get_main_css($key, $id)
{
    return setting($key, $id);
}
function getLoginCss($key, $all = false)
{
    //get the domain url  and remove http:// or https://
    $domain = $_SERVER['HTTP_HOST'];
    $domain = str_replace('http://', '', $domain);
    $domain = str_replace('https://', '', $domain);
    // remove :
    $domain = explode(':', $domain);
    $domain = $domain[0];

    $settings = Setting::where('value', $domain)->first();
    if ($settings) {
        if ($all) {
            return setting('all', $settings->company_id);
        }
        return get_main_css($key, $settings->company_id);
    }
}


function sendToGhl($request, $loc_id)
{

    $data = new stdClass;
    $data->contact_id = $request->contact_id ?? '';
    $data->name = $request->personal['name'] ?? $request->name ?? '';
    $data->email = $request->personal['email'] ?? $request->email ?? '';
    $data->phone = $request->personal['phone'] ?? $request->phone ?? '';
    $data->location_id = $request->location_id ?? '';
    $data->single_gates = $request->single_gates ?? '';
    $data->double_gates = $request->double_gates ?? '';
    $data->fence_estimation = $request->fence_estimation ?? '';
    $data->single_price = $request->single_price ?? '';
    $data->double_price = $request->double_price ?? '';
    $data->category_id = $request->category_id ?? '';
    $data->category_label = $request->category_label ?? '';
    $data->fence_id = $request->fence_id ?? '';
    $data->fence_label = $request->fence_label ?? '';

    $data->height_id = $request->height_id ?? '';
    $data->height_label = $request->height_label ?? '';
    $data->min_estimate = $request->min_estimate ?? 0;
    $data->max_estimate = $request->max_estimate ?? 0;
    $data->feet = $request->feet ?? 0;
    $data->company_id = $loc_id ?? '';
    $data->is_completed = true;

    // $estimation = Estimate::create((array) $data);
    $estimation = Estimate::updateOrCreate(
        [
            'id' => $request->estimate_id,
        ],
        (array) $data
    );

    if ($estimation) {
        return true;
    }

    return false;
}


function chk_contact($request)
{

    $check = [
        // 'contact_id' => $request->contact_id,
        'name' => $request->personal['name'] ?? $request->name,
        'email' => $request->personal['email'] ?? $request->email,
        'phone' => $request->personal['phone'] ?? $request->phone,
        'location_id' => $request->location_id,
    ];

    if ($request->estimator_id) {
        $est = Estimate::find($request->estimator_id);
    } else {
        $est = Estimate::where($check)->first();
    }

    if ($est) {
        return true;
    } else {
        return false;
    }
}

function webhookFields($data)
{
    $remove = [
        'category',
        'fence',
        'category_id',
        'fence_id',
        'height_id',
        'height',
        'location_id',
        'current_slide_id',
        'estimator_id'
    ];
    foreach ($remove as $key) {
        unset($data[$key]);
    }
    return $data;
}

function getCustomPages($type)
{
    $outpages = $type == 'both' || $type == 'outer' ? \App\Models\CustomPage::where('is_active', 1)->where('is_dd_item', 0)->get() : [];
    $inpages = $type == 'both' || $type == 'inner' ? \App\Models\CustomPage::where('is_active', 1)->where('is_dd_item', 1)->get() : [];

    return [$outpages, $inpages];
}

function media($file, $path, $name)
{
    $file->move(public_path($path), $name);
}

function sendSurvey($request, $type = "send")
{

    $location = find_location($request->location_id);

    $loc_id = auth()->user()->id ?? $location->id;
    $uuid = $request->estimator_id ?? $request->uuid;
    $chk = Estimate::where('uuid', $uuid)->first();

    if ($type == 'delete') {

        // 'estimated_on' => '',
        // 'estimated_range' => '',
        // 'style_of_fence' => '',
        // 'total_linear_feet' => '',
        // 'desired_fence_height' => '',
        // 'number_of_double_gates' => '',
        // 'number_of_single_gates' => '',
        // 'fence_type' => '',
        // 'is_estimate_completed' => '',
        // 'installation_address' => '',

        $send_fields = [
            'estimated_deleted' => date('Y-m-d H:i:s'),
        ];
    } else {

        $imagedata = null;

        $file_path = 'images/estimates/' . $uuid . '.png';
        if (file_exists(public_path($file_path))) {
            $imagedata = [
                $uuid => [
                    "meta" => [
                        "fieldname" => $uuid,
                        "size" => filesize(public_path($file_path)),
                        "originalname" => "fence_map_data.png",
                        "mimetype" => "image/png",
                        "encoding" => "7bit",
                        "uuid" => $uuid
                    ],
                    "documentId" => $uuid,
                    "url" => asset($file_path)
                ]
            ];
        }


        $send_fields = [
            'estimated_on' => date('Y-m-d H:i:s'),
            'estimated_range' => $request->fence_estimation,
            'style_of_fence' => $request->fence_label,
            'total_linear_feet' => $request->feet,
            'desired_fence_height' => $request->height_label,
            'number_of_double_gates' => $request->double_gates,
            'number_of_single_gates' => $request->single_gates,
            'fence_type' => $request->category_label,
            'map_image_url' => $imagedata,
            'is_estimate_completed' => $type == 'incomplete' ? 'Incomplete' : 'completed',
            'installation_address' => $request->address ?? '',
        ];
    }

    if ($chk->is_completed == 0) {
        $send_fields['estimator_link'] = route('estimator.index', $location->location) . '?whereleft=' . $chk->uuid;
    }

    // dd($send_fields);



    $send_location = $request['location_id'];


    if (check_ghl($location)) {

        $cus = setCustomFields($send_fields, $send_location);

        if (is_object($cus)) {
            $u_obj = new stdClass;
            $u_obj->customField = $cus;

            // $resp =  ghl_api_call('contacts/' . $request->contact_id, 'PUT', json_encode($u_obj), [], true);
            $resp = ghl_api_call('contacts/' . $request->contact_id, ['method' => 'put', 'body' => json_encode($u_obj), 'json' => true]);
            if (!property_exists($resp, 'contact')) {
                return false;
            }

            $est_check = Estimate::where('uuid', $request->estimator_id ?? $request->uuid)->first();
            if ($est_check) {
                if ($type == "send") {
                    $est_check->is_completed = true;
                }

                $est_check->save();
            }
            return $resp;
        }
    } else {

        $est_check = Estimate::where('uuid', $request->estimator_id ?? $request->uuid)->first();
        if ($est_check) {
            if ($type == "send") {
                $est_check->is_completed = true;
            }
            $est_check->save();
        }
        return true;
    }
}


function add_tags($contact_id, $tag, $customFields = null)
{
    // dd($contact_id);
    if (!is_object($contact_id)) {
        $contact_id = str_replace(' ', '', $contact_id);
        $response = ghl_api_call('contacts/' . $contact_id);
    } else {
        $response = new \stdClass;
        $response->contact = $contact_id;
    }


    if ($response && property_exists($response, 'contact')) {
        $contact = $response->contact;

        if (!is_array($contact->tags)) {
            $contact->tags = [];
        }

        $contact->tags = [];
        $contact->tags = array_merge($contact->tags, $tag);

        // Log::info('Contacts:', ['object' => $contact]);

        $response = ghl_api_call('contacts/' . $contact_id, ['method' => 'put', 'body' => json_encode($contact), 'json' => true]);
        return $response;
    }
}


function default_user_permissions()
{
    $perms = '{
        "campaignsEnabled": true,
        "campaignsReadOnly": true,
        "contactsEnabled": true,
        "workflowsEnabled": true,
        "triggersEnabled": true,
        "funnelsEnabled": true,
        "websitesEnabled": true,
        "opportunitiesEnabled": true,
        "dashboardStatsEnabled": true,
        "bulkRequestsEnabled": true,
        "appointmentsEnabled": true,
        "reviewsEnabled": true,
        "onlineListingsEnabled": true,
        "phoneCallEnabled": true,
        "conversationsEnabled": true,
        "assignedDataOnly": true,
        "adwordsReportingEnabled": true,
        "membershipEnabled": true,
        "facebookAdsReportingEnabled": true,
        "attributionsReportingEnabled": true,
        "settingsEnabled": true,
        "tagsEnabled": true,
        "leadValueEnabled": true,
        "marketingEnabled": true
    }';
    $values = json_decode($perms);
    $obj = new \stdClass;
    $obj->permissions = $values;
    return $obj;
}

function breakCamelCase($letter, $separator = ' ')
{
    $letter = ucwords(implode(' ', explode($separator, $letter)));
    return $letter;
}

function sendEmail($data, $reason = "Account Created At")
{

    $credetials = [
        'reason' => $data['reason'] ?? $data->reason ?? 'Account Created At',
        'name' => $data['name'] ?? $data->name,
        'email' => $data['email'] ?? $data->email,
        'password' => $data['password'] ?? $data->password,
    ];

    $company_name = setting('software_name', 1) ?? 'Instant Fence Quote';
    $company_email = setting('software_email', 1) ?? 'roguebusinessmarketing@gmail.com';
    $url = "https://api.sendinblue.com/v3/smtp/email";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $headers = array(
        "accept: application/json",
        "api-key: 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",
        "content-type: application/json",
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $mailto = [];
    $mailto['sender'] = [
        'name' => $company_name,
        'email' => $company_email
    ];
    $mailto['to'] = [
        [
            'name' => $credetials['name'] ?? 'Fence User',
            'email' => $credetials['email']
        ]
    ];
    $mailto['subject'] = $credetials['reason'];
    $mailto['htmlContent'] = view('mail.send-credentials', compact('credetials'))->render();


    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($mailto));



    $resp = curl_exec($curl);
    curl_close($curl);
    $resp = json_decode($resp);

    return ($resp && property_exists($resp, 'messageId'));
}



function sendEmail1($data, $reason = "Reset Password", $estimate = false)
{
    $toview = 'mail.reset';
    if ($estimate) {
        $toview = 'mail.estimate';
        $credetials = [
            'reason' => $data['reason'] ?? $data->reason ?? 'Estimate Created At',
            'name' => $data['name'] ?? $data->name,
            'email' => $data['email'] ?? $data->email,
            'estimate_link' => $data['estimate_link'] ?? $data->estimate_link,
        ];
    } else {
        $credetials = [
            'reason' => $data['reason'] ?? $data->reason ?? 'Reset Password',
            'name' => $data['name'] ?? $data->name,
            'email' => $data['email'] ?? $data->email,
            'resetlink' => $data['link'] ?? $data->link,
            'token' => $data['token']
        ];
    }



    $company_name = setting('software_name', 1) ?? 'Instant Fence Quote';
    $company_email = setting('software_email', 1) ?? 'roguebusinessmarketing@gmail.com';



    $url = "https://api.sendinblue.com/v3/smtp/email";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $headers = array(
        "accept: application/json",
        "api-key: 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",
        "content-type: application/json",
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

    $mailto = [];
    $mailto['sender'] = [
        'name' => $company_name,
        'email' => $company_email
    ];
    $mailto['to'] = [
        [
            'name' => $credetials['name'] ?? 'Fence User',
            'email' => $credetials['email']
        ]
    ];
    $mailto['subject'] = $credetials['reason'];
    $mailto['htmlContent'] = view($toview, compact('credetials'))->render();


    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($mailto));



    $resp = curl_exec($curl);
    curl_close($curl);
    $resp = json_decode($resp);

    return ($resp && property_exists($resp, 'messageId'));
}
