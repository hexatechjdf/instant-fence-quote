<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Estimate;
use App\Models\User;
use Auth;
use Carbon\Carbon;

class EstimateController extends Controller
{
    public function Estimates(Request $req)
    {
        $estimates = Estimate::where('company_id', Auth::id())
                ->latest()
                ->get();


        
        return view('admin.estimator.estimates', get_defined_vars());
    }

    public function saveImage(Request $request)
    {
        if($request->has('file')){
            media($request->file, 'images/estimates/', $request->uuid.'.png');
        }
       return response()->json(['success' => 'success']);
    }
    
    public function resendEstimates($id)
    {
        $data = Estimate::find($id);
        
        if ($data) {
            $location =  $data->user;
        } else {
            $location = auth()->user();
        }

        $send = sendSurvey($data, 'resend');
        
        $web = sendToWebhookUrl($data, $location->id);
        if (!check_ghl($location)) {

            if ($web) {
                $res = [
                    'status' => 'success',
                    'message' => 'Your estimation resent successfully',
                ];
            } else {
                $res = [
                    'status' => 'error',
                    'message' => 'Your estimation can not be resent, please try again',
                ];
            }

            return response()->json($res);
        } else {


            if ($send && property_exists($send, 'contact')) {
                $t = add_tags($data->contact_id, 'Estimate Resent');


                if (property_exists($t, 'contact')) {
                    $res = [
                        'status' => 'success',
                        'message' => 'Your estimation resent to CRM  successfully.',
                    ];
                } else {
                    $res = [
                        'status' => 'success',
                        'message' => 'Your estimation resent to CRM  successfully.',
                    ];
                }

                return response()->json($res);
            }
        }
    }

    public function deleteEstimates($id)
    {
       
       
        $estimate = Estimate::find($id);

        if ($estimate && is_object($estimate->user)) {
            $location = $estimate->user;
        } else {
            $location = auth()->user();
        }

        // $location->location_id = $location->location;


        // Log::info('Location: ' . print_r($estimate->location_id, true));

        try {
            $send = sendSurvey($estimate, 'delete');
            $web = sendToWebhookUrl($estimate, $location->id);
            $estimate->delete();


            // if (!check_ghl($location)) {
            //     return response()->json([
            //         'status' => 'success',
            //         'message' => 'Your estimation deleted to CRM successfully'
            //     ]);
            // } 
            
            // if ((property_exists($send, 'contact'))) {
            //     add_tags($estimate->contact_id, 'Estimate Deleted');

            //     return response()->json([
            //         'status' => 'success',
            //         'message' => 'Your estimation deleted to CRM successfully'
            //     ]);
            // }

            return response()->json([
                'status' => 'success',
                'message' => 'Your estimation deleted successfully'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Error has an occured. Estimate cannot be deleted inside Rogue Leads CRM. Please Contact Support.',
            ]);
        }
    }

    public function saveSteps(Request $request, $id = null)
    {

        $data = Estimate:: where(['uuid'=> $request->estimator_id, 'is_completed'=>0])->first();
        
           if(!$data){
            return response()->json([
                'status' => 'error',
                'message' => 'Estimate not found'
            ]);
           }
           if ($data) {
            $location =  $data->user;
        } else {
            $location = auth()->user();
        }

        if ($request->has('personal')) {
            $data->name = $request->personal['name'] ?? $request->name ?? '';
            $data->email = $request->personal['email'] ?? $request->email ?? '';
            $data->phone = $request->personal['phone'] ?? $request->phone ?? '';
        }

        if ($request->has('location_id')) {
            $data->location_id = $request->location_id ?? '';
        }
        if ($request->has('single_gates')) {
            $data->single_gates = $request->single_gates ?? '';
        }
        if ($request->has('double_gates')) {
            $data->double_gates = $request->double_gates ?? '';
        }
        if ($request->has('fence_estimation')) {
            $data->fence_estimation = $request->fence_estimation ?? '';
        }
        if ($request->has('single_price')) {
            $data->single_price = $request->single_price ?? '';
        }
        if ($request->has('double_price')) {
            $data->double_price = $request->double_price ?? '';
        }
        if ($request->has('category_id')) {
            $data->category_id = $request->category_id ?? '';
        }
        if ($request->has('category_label')) {
            $data->category_label = $request->category_label ?? '';
        }
        if ($request->has('fence_id')) {
            $data->fence_id = $request->fence_id ?? '';
        }
        if ($request->has('fence_label')) {
            $data->fence_label = $request->fence_label ?? '';
        }
        if ($request->has('height_id')) {
            $data->height_id = $request->height_id ?? '';
        }
        if ($request->has('height_label')) {
            $data->height_label = $request->height_label ?? '';
        }
        if ($request->has('min_estimate')) {
            $data->min_estimate = $request->min_estimate ?? 0;
        }
        if ($request->has('max_estimate')) {
            $data->max_estimate = $request->max_estimate ?? 0;
        }
        if ($request->has('feet')) {
            $data->feet = $request->feet ?? 0;
        }
        if ($request->has('company_id')) {
            $data->company_id = $request->company_id ?? '';
        }
        if ($request->has('contact_id')) {
            $data->contact_id = $request->contact_id ?? '';
        }

        if($request->has('current_slide_id')){
            $data->where_left = $request->current_slide_id ?? '';
        }
        
        if($request->has('address')){
            $data->address = $request->address ?? '';
        }


        $data->last_selected = json_encode($request->all());
        $data->save();

        $web = sendToWebhookUrl($request->all(), $location->id);

        return response()->json(['status' => 'success', 'message' => 'Your steps saved successfully']);
    }


    public function InCompleteEstimates()
    {

        $companies = User::where(['role' => 0, 'is_active' => true])->get();
        foreach ($companies as $company) {
            $estimates = Estimate::where([['is_completed', '=', false], ['created_at', '>=', Carbon::now()->subMinutes(25)->format('Y-m-d H:i:s')]])->where('company_id', $company->id);
           
            
            $i = 0;
            if ($estimates->count() > 0) {
                if (check_ghl($company)) {
                    //seding the tag here
                    $estimates = $estimates->whereNotNull('contact_id')->get();
                    // dd($estimates);
                 
                    foreach ($estimates as $estimate) {
                        if ($i % 5 == 0) {
                            sleep(2);
                        }
                        $contact_id = $estimate->contact_id;
                        $lnk = route('estimator.index', $company->location).'?whereleft='.$estimate->uuid;
                        
                        session()->put('incomp_link',$lnk);
                        session()->put('survey_comp','Incomplete');
                        
                         $send = sendSurvey($estimate, 'incomplete');
                         $web = sendToWebhookUrl($estimate, $company->id);
                         $ex_field = ['is_estimate_completed' => 'Incomplete'];
                        $cf = setCustomFields($ex_field , $company->location);
                        $tag = add_tags($contact_id, 'Estimate Incomplete', $cf);
                        if (!$tag || !property_exists($tag, 'contact')) {
                            continue;
                        }
                        $i++;
                    }
                } else {
                    //sending the email to the user here to complete the estimate
                    $estimates = $estimates->where('contact_id',  NULL)->get();
                    
                    // foreach ($estimates as $estimate) {
                    //     if ($i % 5 == 0) {
                    //         sleep(2);
                    //     }
                    //     $web = sendToWebhookUrl($estimate, $company->id);
                    // }

                    //     $credetials = [
                    //         'reason' => 'Incomplete Estimate',
                    //         'name' => $estimate->name,
                    //         'email' => $estimate->email,
                    //         'estimate_link' =>  route('estimator.index', $company->location).'?whereleft='.$estimate->uuid
                    //     ];

                    //     $send = sendEmail1($credetials, 'Incomplete Estimate', true);
                    //     if (!$send) {
                    //         continue;
                    //     }
                    //     $i++;
                    // }
                }
            }
        }


        return response()->json('done');
    }
}
