<?php

namespace App\Jobs;

use App\Helpers\CRM;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddTagJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    // $contact_id, 'Estimate Incomplete', $token

    protected $contact_id;
    protected $tag;
    protected $companyId;
    protected $location;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($contact_id, $tag, $companyId, $location )
    {
        $this->contact_id = $contact_id;
        $this->tag = $tag;
        $this->companyId = $companyId; // User ID
        $this->location = $location; //Location ID CRM
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $contact_id = $this->contact_id;
        $tag = $this->tag;
        $companyId = $this->companyId; // USer ID
        $location = $this->location; // Location ID

        \Log::info('Step 10');
        if (!is_object($contact_id)) {
            $contact_id = str_replace(' ', '', $contact_id);
            $response = CRM::crmV2Loc($companyId, $location, 'contacts/' . $contact_id, 'get');
            // dd($response);
            // $response = ghl_api_call('contacts/' . $contact_id);
        } else {
            $response = new \stdClass;
            $response->contact = $contact_id;
        }
        if ($response && property_exists($response, 'contact')) {
            $contact = $response->contact;
            $alreadyTags = $contact->tags;
            // $contact
            if(is_array($tag)){
                $newTag = $tag;
            }else{
                $newTag[] = $tag;
            }

            $addTags = new \stdClass;
            $addTags->tags =  array_merge($alreadyTags, $newTag);
            // Log::info('Contacts:', ['object' => $contact]);


            //UserID, locatiom Id CRM, URL, Method, Data
            GHLApiCallJob::dispatch($companyId,  $location, 'contacts/'.$contact_id.'/tags', 'post', $addTags)->onQueue(env('JOB_QUEUE_TYPE'));

            // $response = CRM::crmV2Loc($companyId,  $location, 'contacts/'.$contact_id.'/tags', 'post', $addTags);
            // $response = ghl_api_call('contacts/' . $contact_id, ['method' => 'put', 'body' => json_encode($contact), 'json' => true]);
            // return $response;
        }
    }
}
