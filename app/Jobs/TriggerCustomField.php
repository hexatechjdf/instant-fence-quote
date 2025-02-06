<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TriggerCustomField implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $page;
    public function __construct($page = 1)
    {
        $this->page = $page;
    }

    public function handle()
    {
        $limit = 50;
        $currentPage = $this->page - 1;
        $skip = $currentPage * $limit;

        $companies = User::where(['role' => 0, 'is_active' => true])->orderBy('id')->skip($skip)->take($limit)->get();

        // $companies = User::where('id', 2)->get();

        if ($companies->isNotEmpty()) {
            foreach ($companies as $company) {
                SetCustomField::dispatch($company)->onQueue(env('JOB_QUEUE_TYPE'))->delay(3);
                // $callFn = newSetCustomField($CustomFields, $company);
            }

            if ($companies->count() === $limit) {
                static::dispatch($this->page + 1)->onQueue(env('JOB_QUEUE_TYPE'))->delay(5);
            }
        }
    }

}
