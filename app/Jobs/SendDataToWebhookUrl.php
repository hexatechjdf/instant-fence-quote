<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendDataToWebhookUrl implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $id; //User ID

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $id)
    {
        $this->data = $data;
        $this->id = $id; //User ID
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->data;
        $id = $this->id; //User ID

        // $data = webhookFields($data);

        $remove = [
            'category',
            'fence',
            'category_id',
            'fence_id',
            'height_id',
            'height',
            'location_id',
            'current_slide_id',
            'estimator_id'
        ];
        foreach ($remove as $key) {
            unset($data[$key]);
        }
        $webhook_url = \setting('webhook_url', $id);

        if ($webhook_url) {
            $client = new \GuzzleHttp\Client(['http_errors' => false]);
            $options = [];
            $options['body'] = json_encode($data);
            $options['headers'] = [
                'Content-Type' => 'application/json',
            ];
            $client->request('post', $webhook_url, $options);
            // if ($response->getStatusCode() == 200) {
            //     return true;
            // } else {
            //     return false;
            // }
        }
    }
}
