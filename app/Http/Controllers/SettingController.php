<?php

namespace App\Http\Controllers;

use App\Helpers\CRM;
use App\Jobs\AddTagJob;
use App\Jobs\SendDataToWebhookUrl;
use App\Jobs\SendSurvey;
use App\Models\Estimate;
use App\Models\Setting;
use App\Models\User;
use Faker\Provider\bg_BG\PhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use stdClass;
use Validator;

class SettingController extends Controller
{
    public function siteSettings()
    {
        $settings = Setting::pluck('value', 'name')->toArray();
        $scopes = CRM::$scopes;
        $company_name = null;
        $company_id = null;
        $connecturl = CRM::directConnect();
        $authuser = loginUser();
        $crmauth = $authuser->crmtokenagency;
        $role = $authuser->role;
        $location_id = $authuser->location;

        try {
            if ($crmauth) {
                list($company_name, $company_id) = [json_decode($crmauth?->meta)->company_name ?? '', $crmauth?->company_id ?? ''];// CRM::getCompany($authuser);
            }
        } catch (\Exception $e) {
        }
        return view('admin.settings.setting', get_defined_vars());
    }


    public function saveContact(Request $req, $id)
    {

        $ch = Validator::make($req->all(), [
            'email' => 'required|email',
            'name' => 'required',
            'phone' => 'required',
        ]);


        if ($ch->fails()) {
            return redirect()->back()->withErrors($ch)->withInput();
        }

        // $req->merge([
        //     'tags' => ['Instant Fence Quote', 'Incomplete']
        // ]);
        $response = saveContactToGhl($req);

        $res = [
            'status' => 'success',
            // 'contact_id' => $response->contact_id,
            'estimate_id' => $response->estimate_id,
        ];
        // $location = find_location($req->location);
        // add_tags($response->contact_id, ['Instant Fence Quote', 'Incomplete']);
        // $token = getDBCRMToken($location->location);
        // AddTagJob::dispatch($response->contact_id, ['Instant Fence Quote', 'Incomplete'], $token)->onQueue(env('JOB_QUEUE_TYPE'));


        return response()->json($res);
    }

    public function siteSettingsSave(Request $request, $id = null)
    {

        foreach ($request->except(['_token']) as $key => $value) {
            $setting = Setting::whereName($key)->first() ?? new Setting();
            //if the request is file
            if ($request->hasFile($key)) {
                $filepath = uploadFile($value, 'uploads/settings', $key . '-' . rand(1111111, 9999999) . '-' . time());

                $setting->name = $key;
                $setting->value = $filepath;
                $setting->company_id = auth()->user()->id;
                $setting->save();
            } else {
                $setting->name = $key;
                $setting->value = $value;
                $setting->company_id = auth()->user()->id;
                $setting->save();
            }
        }

        $msg = 'Settings Updated Successfully';
        return redirect()->back()->with('success', $msg);
    }

    public function estimator(Request $request, $id = '')
    {
        $whereleft =null;
        if ($request->whereleft) {
            $whereleft = $request->whereleft;
            $whereleft = Estimate::where(['uuid' => $whereleft, 'is_completed' => false])->first();
        }


        if (auth()->check()) {
            $location = auth()->user()->location;
        } else {
            $location = $id;
        }
        $fence = User::with('categories', 'categories.fences', 'categories.fences.ft_available', 'categories.fences.ft_available.ft_available', 'categories.fences.ft_available.prices')->where('is_active', 1)->where('location', $location)->first();
        if ($fence) {
            unset($fence->user);
            unset($fence->email);
            unset($fence->ghl_api_key);
            unset($fence->role);

            $fence = ['all' => $fence];
        } else {
            if (auth()->check()) {
                return back()->with('error', 'No Data Found.');
            }
            abort(404);
        }
        if (!empty($id)) {
            $id = $location;
            return view('admin.estimator.public', get_defined_vars());
        }
        $id = $location;
        return view('admin.estimator.app', get_defined_vars());
    }

    public function estimatorSave(Request $request)
    {
        // dd($request->all());
        $location = find_location($request->location_id);
        SendSurvey::dispatch($request->all())->onQueue(env('JOB_QUEUE_TYPE'));
        // $send =  sendSurvey($request);
        $res = [
            'status' => 'error',
            'message' => 'There is an error while sending your estimations. Please contact us directly with via email or phone'
        ];
        SendDataToWebhookUrl::dispatch($request->all(), $location->id)->onQueue(env('JOB_WEBHOOK_TYPE'))->delay(10);
        // $web = sendToWebhookUrl($request->all(), $location->id);
        // if ($send) {
            if (check_ghl($location)) {
                $locationId = $location->location;
                $userID = $location->id;
                // $token = getDBCRMToken($request->location_id);

                $uuid = $request['estimator_id'] ?? $request['uuid'];
                $chk = Estimate::where('uuid', $uuid)->first();

                $contactId = $chk->contact_id;

                if($contactId){
                    AddTagJob::dispatch($contactId, ['Instant Fence Quote', 'Complete'],  $userID, $locationId)->onQueue(env('JOB_QUEUE_TYPE'));
                    $res = [
                        'status' => 'success',
                        'message' => 'Your Fence Estimate has been Received. We will contact you soon.',
                    ];
                    $this->saveContact($request, $contactId);
                }else {
                    $res = [
                                'status' => 'error',
                                'message' => 'There is an error while sending your estimations. Please contact us directly via email or phone',
                            ];
                }
                // $ghl_tags = add_tags($request->contact_id, ['Instant Fence Quote', 'Complete'], $token);
                // $ghl_tags = add_tags($request->contact_id, ['Instant Fence Quote', 'Complete']);
                // if ($ghl_tags && property_exists($ghl_tags, 'tags')) {
                //     $res = [
                //         'status' => 'success',
                //         'message' => 'Your Fence Estimate has been Received. We will contact you soon.',
                //     ];
                //     $this->saveContact($request, $request->contact_id);
                // } else {
                //     $res = [
                //         'status' => 'error',
                //         'message' => 'There is an error while sending your estimations. Please contact us directly via email or phone',
                //     ];
                // }
            } else {
                $res = [
                    'status' => 'success',
                    'message' => 'Your Fence Estimate has been Received. We will contact you soon.',
                ];
            }
        // }
        return response()->json($res);
    }

    public function thankYou($id = null)
    {
        if (auth()->check()) {
            $estimates  = Estimate::where('company_id', Auth::id())->latest()->get();
            return view('admin.estimator.estimates', get_defined_vars());
        } else {
            $id = $id ?? 'thanks';
            return view('admin.estimator.thank-you', get_defined_vars());
        }
    }

    public function whiteLabel()
    {
        // dd($_SERVER);
        return view('admin.white-domains.index');
    }

    public function saveDomain(Request $request)
    {

        foreach ($request->except(['_token']) as $key => $value) {
            $val = add_whitelabel($value);
            insert_setting($key, $value);
        }

        return redirect()->back()->with('success', 'Domain Added Successfully');
    }

    public function customCss()
    {
        return view('admin.settings.custom-design');
    }
}
