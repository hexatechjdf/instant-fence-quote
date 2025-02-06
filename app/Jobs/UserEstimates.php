<?php

namespace App\Jobs;

use App\Models\Estimate;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UserEstimates implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $company;
    protected $page;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($company, $page = 1)
    {
        $this->company = $company;
        $this->page = $page;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // ->id, $company->location
        $company = $this->company;
        $companyId = $company->id; // User ID
        $location = $company->location; //User Location ID

        $limit = 50;
        $currentPage = $this->page - 1;
        $skip = $currentPage * $limit;

        $estimates = Estimate::where([['is_completed', '=', false], ['created_at', '>=', Carbon::now()->subMinutes(25)->format('Y-m-d H:i:s')]])->where('company_id', $companyId)->orderBy('id')->skip($skip)->take($limit);

        // $estimates = Estimate::where([['is_completed', '=', false], ['id', '=',756]])->where('company_id', $companyId)->orderBy('id')->skip($skip)->take($limit);

            if (\check_ghl($this->company)) {
                $estimates = $estimates->get();
                if($estimates->isNotEmpty()){
                    foreach ($estimates as $estimate) {
                        if($estimate->contact_id !== null && $estimate->contact_id !== '' && !empty($estimate->contact_id)){
                            // Current Estimate, userLocation, UserId
                            SendOneEstimate::dispatch($estimate, $location, $companyId)->onQueue(env('JOB_QUEUE_TYPE'))->delay(3);
                        }
                        //Job To send Data for the each estimate
                    }
                    if ($estimates->count() === $limit) {
                        static::dispatch($company, $this->page + 1)->onQueue(env('JOB_QUEUE_TYPE'))->delay(5);
                    }
                }

            }

    }
}
