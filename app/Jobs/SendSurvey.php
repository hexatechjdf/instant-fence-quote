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

class SendSurvey implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $request;
    protected $type;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($request, $type = 'send')
    {
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

        $request = $this->request;
        $type = $this->type;
        $location = \find_location($request['location_id']);

        $locationId = $request['location_id'];
        // $loc_id = auth()->user()->id ?? $location->id;
        $uuid = $request['estimator_id'] ?? $request['uuid'];
        $chk = Estimate::where('uuid', $uuid)->first();

        if($chk && $location){
            $chk->company_id = $location->id;
            $chk->save();
        }

        \Log::info('Step 5');
        if ($type == 'delete') {
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
                'estimated_range' => $request['fence_estimation'],
                'style_of_fence' => $request['fence_label'],
                'total_linear_feet' => $request['feet'],
                'desired_fence_height' => $request['height_label'],
                'number_of_double_gates' => $request['double_gates'],
                'number_of_single_gates' => $request['single_gates'],
                'fence_type' => $request['category_label'],
                'map_image_url' => $imagedata,
                'is_estimate_completed' => $type == 'incomplete' ? 'Incomplete' : 'completed',
                'installation_address' => $request['address'] ?? '',
            ];
        }

        if ($chk && $chk->is_completed == 0) {
            $send_fields['estimator_link'] = route('estimator.index', $location->location) . '?whereleft=' . $chk->uuid. 'tttttttttttttt';
        }

        // dd($send_fields);

        // $locationId,$send_fields, $request,$type )
        // SendDataOfEstimateToCRM::dispatch($locationId, $send_fields, $request,$type)->onQueue(env('JOB_QUEUE_TYPE'));

        if (\check_ghl($location)) {

            // $cus = \setCustomFields($send_fields, $locationId);

            $cfs = [];
            foreach ($send_fields as $k => $v) {
                $cfs[] = [
                    'key' => $k,
                    'field_value' => $v
                ];
            }
            \Log::info('Step 6');
            $u_obj = new stdClass;
            $u_obj->customField = $cfs;
            $ContData = json_encode($u_obj);
            $ConttUrl = 'contacts/' .  $chk->contact_id;
            //Convert to job
            //UserID, locationID, Url, method, data
            GHLApiCallJob::dispatch($location->id, $locationId, $ConttUrl, 'put', $ContData)->onQueue(env('JOB_QUEUE_TYPE'));
        }

        $est_check = Estimate::where('uuid', $request['estimator_id'] ?? $request['uuid'])->first();

        if ($est_check) {
            if ($type == "send") {
                $est_check->is_completed = true;
                $est_check->save();
            }

            if ($type == "delete") {
                $est_check->delete();
            }


        }


    }
}
