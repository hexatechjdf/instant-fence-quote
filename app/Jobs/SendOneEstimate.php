<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOneEstimate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $estimate;
    protected $compLocation;
    protected $companyId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($estimate, $compLocation, $companyId)
    {
        $this->estimate = $estimate; // Current Estimate
        $this->compLocation = $compLocation; // User Location
        $this->companyId = $companyId; // User ID PK
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

         $estimate = $this->estimate ;
         $compLocation = $this->compLocation ;
         $companyId = $this->companyId ;
         $contact_id = $estimate->contact_id;
        \Log::info('Step 4');
        SendSurvey::dispatch($estimate, 'incomplete')->onQueue(env('JOB_QUEUE_TYPE'));
        \Log::info('Step 7');
        SendDataToWebhookUrl::dispatch($estimate, $companyId)->onQueue(env('JOB_WEBHOOK_TYPE'));
        \Log::info('Step 9');

        //contactId, Tag, User ID, User Location
        AddTagJob::dispatch($contact_id, 'Estimate Incomplete', $companyId, $compLocation)->onQueue(env('JOB_QUEUE_TYPE'));

    }
}
