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

class AgencyUserAutoAuth implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $page;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($page = 1)
    {
        $this->page = $page;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        //AIk job just agency token update

        try {
            $limit = 50;
            $skip = ($this->page - 1) * $limit;

            $userId = 1;
            $users = User::where('role', 0)->where('is_active', 1)->where('separate_location', 0)->skip($skip)->take($limit)->pluck('location')->toArray();

            if ($users->isNotEmpty()) {
                $urlmain = 'locations/search';
                $locations = CRM::agencyV2($userId, $urlmain);

                if ($locations && property_exists($locations, 'locations')) {
                    $locations = $locations->locations;

                    foreach ($locations as $loc) {
                        if (in_array($loc->id, $users)) {
                            GetLocationAccessToken::dispatch($userId, $loc->id, 'viaAgency')->onQueue(env('JOB_QUEUE_TYPE'));
                            // CRM::getLocationAccessToken($userId, $loc->id);
                        }
                    }
                }

                if ($users->count() === $limit) {
                    static::dispatch($userId, $this->page + 1)->delay(5);
                }

            }
        } catch (\Throwable $th) {
            \Log::error($th);
        }
    }
}
