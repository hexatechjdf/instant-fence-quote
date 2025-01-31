<?php

namespace App\Jobs;

use App\Helpers\CRM;
use App\Models\Estimate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use stdClass;

class SendDataOfEstimateToCRM implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $locationId;
    protected $send_fields;
    protected $request;
    protected $type;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($locationId, $send_fields, $request, $type)
    {
        $this->locationId = $locationId;
        $this->send_fields = $send_fields;
        $this->request = $request;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $locationId = $this->locationId;
        $send_fields = $this->send_fields;
        $request = $this->request;
        $type = $this->type;


        $location = find_location($request->location_id);
        $userID = $location->id;
        $locationId = $request['location_id'];
        // $loc_id = auth()->user()->id ?? $location->id;
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

        // $locationId,$send_fields, $request,$type )
        // SendDataOfEstimateToCRM::dispatch($locationId, $send_fields, $request,$type)->onQueue(env('JOB_QUEUE_TYPE'));

        if (\check_ghl($locationId)) {

            $cus = \setCustomFields($send_fields, $locationId);

            if (is_object($cus)) {
                $u_obj = new stdClass;
                $u_obj->customField = $cus;

                $ContData = json_encode($u_obj);
                $ConttUrl = 'contacts/' . $request->contact_id;

                GHLApiCallJob::dispatch($userID,  $locationId, $ConttUrl, 'put', $ContData)->onQueue(env('JOB_QUEUE_TYPE'));

                // $token = getDBCRMToken($locationId);
                // CRM::crmV2Loc(1, $locationId, $ConttUrl, 'put', $ContData, $token);

                // $resp =  ghl_api_call('contacts/' . $request->contact_id, 'PUT', json_encode($u_obj), [], true);
                //$resp = ghl_api_call('contacts/' . $request->contact_id, ['method' => 'put', 'body' => json_encode($u_obj), 'json' => true]);

                // return $resp;
            }
        }

        $est_check = Estimate::where('uuid', $request->estimator_id ?? $request->uuid)->first();
        if ($est_check) {
            if ($type == "send") {
                $est_check->is_completed = true;
            }

            $est_check->save();
        }

    }
}
