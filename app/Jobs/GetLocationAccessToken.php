<?php

namespace App\Jobs;

use App\Helpers\CRM;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GetLocationAccessToken implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;
    protected $locationId;
    protected $type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId, $locationId, $type = null)
    {
        $this->userId = $userId;
        $this->locationId = $locationId;
        $this->type = $type;
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
        $type = $this->type;


        if($type == 'viaAgency'){
            CRM::getLocationAccessTokenFirstTimeByCompany($userId, $locationId);
        }
        if($type == 'Location'){
            CRM::getLocationAccessToken($userId, $locationId, 'Location');
        }

        // getLocationAccessTokenFirstTime


        // if($type == null){
        //     \Log::info('step 2');
        //     CRM::getLocationAccessToken($userId, $locationId);
        // }else{
        //     CRM::getLocationAccessToken($userId, $locationId, $type);
        // }

    }
}
