<?php

namespace App\Jobs;

use App\Helpers\CRM;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GHLApiCallJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $userId;
    protected $locationId;
    protected $url;
    protected $method;
    protected $data;
    protected $token;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId,  $locationId,$url, $method, $data, $token = null)
    {
        $this->userId = $userId;
        $this->locationId = $locationId;
        $this->url = $url;
        $this->method = $method;
        $this->data = $data;
        $this->token = $token;
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
        $url = $this->url;
        $method = $this->method;
        $data = $this->data;
        $token = $this->token;
        CRM::crmV2Loc($userId, $locationId, $url, $method, $data, $token);
    }
}
