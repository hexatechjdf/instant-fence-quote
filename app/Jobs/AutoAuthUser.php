<?php

namespace App\Jobs;

use App\Helpers\CRM;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AutoAuthUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;

    /**
     * Create a new job instance.
     *
     * @param int $userId
     * @return void
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        


        AgencyUserAutoAuth::dispatch()->onQueue(env('JOB_QUEUE_TYPE'));
        LocationUserAutoAuth::dispatch()->onQueue(env('JOB_QUEUE_TYPE'));

        // \Log::info('Job dispatched successfully');
        // $users = User::where('role', 0)->where('is_active', 1)->whereIn('separate_location', [0, 1])->get();
        // $userIdsLocation0 = $users->where('separate_location', 0)->pluck('location')->toArray();
        // $userIdsLocation1 = $users->where('separate_location', 1)->pluck('location', 'id')->toArray();
        // $urlmain = 'locations/search';
        // $locations = CRM::agencyV2($this->userId, $urlmain);

        // if ($locations && property_exists($locations, 'locations')) {
        //     // $locations = json_decode($locations);
        //     $locations = $locations->locations;
        //     foreach ($locations as $key => $loc) {
        //         $locationId = $loc->id;
        //         if (in_array($locationId, $userIdsLocation0)) {
        //             CRM::getLocationAccessToken($this->userId, $locationId);
        //         }
        //     }
        // }

        // foreach ($userIdsLocation1 as $id => $locId) {
        //     CRM::getLocationAccessToken($id, $locId, 'Location');
        // }

    }
}
