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

class LocationUserAutoAuth implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $page;

    public function __construct(int $page = 1)
    {
        $this->page = $page;
    }

    public function handle()
    {
        try {
            $limit = 50;
            $skip = ($this->page - 1) * $limit;
            $users = User::where('role', 0)->where('is_active', 1)->where('separate_location', 1)->skip($skip)->take($limit)->pluck('location', 'id')->toArray();
            if ($users->isNotEmpty()) {
                foreach ($users as $userId => $locationId) {
                    GetLocationAccessToken::dispatch($userId, $locationId, 'Location')->onQueue(env('JOB_QUEUE_TYPE'));
                    // CRM::getLocationAccessToken($userId, $locationId, 'Location');
                }
                if ($users->count() === $limit) {
                    static::dispatch($this->page + 1)->onQueue(env('JOB_QUEUE_TYPE'))->delay(5);

                }
            }
        } catch (\Throwable $th) {
            \Log::error($th);
        }
    }
}
