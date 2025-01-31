<?php

namespace App\Jobs;

use App\Helpers\CRM;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SetCustomField implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $loc;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($loc)
    {
        $this->loc = $loc;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $loc = $this->loc;

        $keys =   [
            'estimated_on',
            'estimated_range',
            'style_of_fence',
            'total_linear_feet',
            'desired_fence_height',
            'number_of_double_gates',
            'number_of_single_gates',
            'fence_type',
            'map_image_url',
            'is_estimate_completed',
            'installation_address',
            'estimator_link',
            'is_estimate_completed',
            'estimated_deleted'
        ];

        try {
            // Convert the keys into a request-like array with empty values
            // $request_array = array_fill_keys($keys, null);

            // Locate the given location
            $locationId = $loc->location;
            $userID = $loc->id;
            // Retrieve the CRM token
            // $token = getDBCRMToken($locationId);

            // Fetch existing custom fields
            $url = 'locations/' . $locationId . '/customFields';
            $ghl_custom_values = CRM::crmV2Loc($userID, $locationId, $url, 'get');

            $custom_values = $ghl_custom_values;

            if (property_exists($custom_values, 'customFields')) {
                $custom_values = $custom_values->customFields;

                // Filter keys that already exist
                $existing_keys = array_map(function ($value) {
                    return strtolower(str_replace(' ', '_', $value->name));
                }, $custom_values);

                $keys_to_create = array_filter($keys, function ($key) use ($existing_keys) {
                    return !in_array($key, $existing_keys);
                });

                // Process each key and create custom fields if needed
                foreach ($keys_to_create as $key) {
                    $type = strpos($key, 'date') !== false ? 'DATE' : 'TEXT';
                    $type = $key == 'map_image_url' ? 'FILE_UPLOAD' : $type;
                    $send_name = ucwords(str_replace('_', ' ', $key));

                    $CustData = json_encode(['name' => $send_name, 'dataType' => $type]);
                    $CustUrl = 'locations/' . $locationId . '/customFields';
                    GHLApiCallJob::dispatch($userID, $locationId, $CustUrl, 'post', $CustData)->onQueue(env('JOB_QUEUE_TYPE'))->delay(2);
                }
            }
        } catch (\Exception $e) {
            \Log::info($e->getMessage());
        }

    }
}
