<?php

namespace App\Jobs;

use App\Helpers\CRM;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use stdClass;
use Str;

class UploadContactImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // protected $token;
    protected $imgNameCustom;
    protected $location_id;
    protected $userId;
    protected $contactId;

    // $token,$imgNameCustom, $location_id,$userId

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($imgNameCustom, $location_id,$userId, $contactId )
    {
        // $this->token = $token;
        $this->imgNameCustom = $imgNameCustom;
        $this->location_id = $location_id;
        $this->userId = $userId;
        $this->contactId = $contactId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $token = $this->token;
        $imgNameCustom = $this->imgNameCustom;
        $location_id = $this->location_id;
        $userId = $this->userId;
        $contactId = $this->contactId;

        $getFileUrl = 'medias/files?sortBy=createdAt&sortOrder=asc&query=' . $imgNameCustom . '&altType=location&altId=' . $location_id;
        //Call for the getting image url
        $responseGetFileUrl = CRM::crmV2Loc($userId, $location_id, $getFileUrl, 'GET');
        \Log::info(json_encode($responseGetFileUrl));
        if ($responseGetFileUrl && property_exists($responseGetFileUrl, 'files')) {
            // $responseGetFileUrl = json_decode($responseGetFileUrl);
            $file = $responseGetFileUrl->files[0] ?? null;
            if ($file) {
                $fileUrl = $file->url;
                $contactID = $contactId;
                $urlContactUpdate = 'contacts/' . $contactID;
                $det = new stdClass();
                $customFieldDet = new stdClass();

                $uuid = (string) Str::uuid();
                $imagedata = \customFieldFile($uuid, $fileUrl , "fencedraw.png");

                $customFieldDet->value = $imagedata;
                $customFieldDet->key = 'drawcanvas';
                $customField[] = $customFieldDet;
                $det->customFields = $customField;
                $data = json_encode($det);
                \Log::info($data);
                // sleep(10);

                GHLApiCallJob::dispatch($userId, $location_id, $urlContactUpdate, 'PUT', $data)->onQueue(env('JOB_QUEUE_TYPE'));
                // CRM::crmV2Loc($userId, $location_id, $urlContactUpdate, 'PUT', $data);
            }
        }







    }
}
