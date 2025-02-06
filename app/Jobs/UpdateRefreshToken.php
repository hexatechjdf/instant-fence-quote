<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateRefreshToken implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
    public $timeout = null;
    /**
     * Create a new job instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $rf = $this->user;
            if ($rf) {
                $status = (int) $rf->urefresh();
                if ($status === 500) {
                    dispatch((new UpdateRefreshToken($this->user))->onQueue(env('JOB_QUEUE_TYPE')));
                }
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
