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
use Str;

class AddContactAndTagJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;
    protected $locationId;
    protected $data;
    protected $tags;
    protected $requestAll;
    protected $estimatorId;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct($userId, $locationId, $data, $requestAll, $estimatorId, $tags = null)
    {
        $this->userId = $userId;
        $this->locationId = $locationId;
        $this->data = $data;
        $this->tags = $tags;
        $this->requestAll = $requestAll;
        $this->estimatorId = $estimatorId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $userId = $this->userId;
        $locationId = $this->locationId;
        $data = $this->data;
        $estimatorId = $this->estimatorId;
        $request = $this->requestAll;
        $res = CRM::crmV2Loc($userId, $locationId, 'contacts', 'post', $data);

        if($res && property_exists($res, 'contact') && $this->tags){
            $contact_id = $res->contact->id ?? null;

             //contactId, Tag, User ID, User Location
            AddTagJob::dispatch($contact_id, $this->tags,  $userId, $locationId)->onQueue(env('JOB_QUEUE_TYPE'));
        }

        $resp = new stdClass;
        $resp->contact_id = $res->contact->id ?? null;
        $id = null;

        if ($request['estimator_id'] !== null ||  $estimatorId !== null) {
            $id = $estimatorId ?? $request['estimator_id'];
        }

        $request['contact_id'] = $resp->contact_id;
        $data = $request;
        unset($data['tags']);
        // dd($data);

        $data['company_id'] = $userId ?? $location->id ?? null;
        $data['location_id'] = $request['location'];
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

            $data['uuid'] = $id;
            // $data['uuid'] = Str::uuid()->toString();
            // dd($data['uuid'], "here in create");
            $est = Estimate::create($data);
        }
        // $resp->estimate_id = $est->uuid;

    }
}
