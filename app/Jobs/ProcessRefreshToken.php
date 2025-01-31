<?php

namespace App\Jobs;

use App\Models\CrmToken;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class ProcessRefreshToken implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $page;
    public $timeout = null;
    /**
     * Create a new job instance.
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
        try {
            $limit = 40;
            $currentPage = $this->page - 1;
            $skip = $currentPage * $limit;
            \Log::info('Skip =' . $skip);
            $rl = CrmToken::where('user_type' , 'Company')->skip($skip)->take($limit)->get();
            \Log::info($rl);
            if ($rl->isNotEmpty()) {
                foreach ($rl as $r) {
                    dispatch((new UpdateRefreshToken($r))->onQueue(env('JOB_QUEUE_TYPE'))->delay(Carbon::now()->addSeconds(2)));
                }
                dispatch((new ProcessRefreshToken($this->page + 1))->onQueue(env('JOB_QUEUE_TYPE'))->delay(Carbon::now()->addSeconds(2)));
            }
        } catch (\Throwable $th) {
            \Log::info($th->getMessage());
            //throw $th;
        }
    }
}
