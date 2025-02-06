<?php

namespace App\Jobs;

use App\Models\Estimate;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class InCompleteEstimatesTriger implements ShouldQueue
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
        $limit = 50;
        $currentPage = $this->page - 1;
        $skip = $currentPage * $limit;
        $companies = User::where(['role' => 0, 'is_active' => true])->orderBy('id')->skip($skip)->take($limit)->get();

        // $companies = User::where(['role' => 0, 'is_active' => true, 'id' => 2])->orderBy('id')->skip($skip)->take($limit)->get();

        if($companies->isNotEmpty()){
            foreach ($companies as $company) {
                // \Log::info('Step 2');
                UserEstimates::dispatch($company)->onQueue(env('JOB_QUEUE_TYPE'));
            }
            if ($companies->count() === $limit) {
                static::dispatch($this->page + 1)->onQueue(env('JOB_QUEUE_TYPE'))->delay(5);
            }
        }

    }
}
