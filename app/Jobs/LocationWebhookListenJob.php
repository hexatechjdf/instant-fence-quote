<?php

namespace App\Jobs;

use App\Helpers\CRM;
use App\Models\User;
use Hash;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LocationWebhookListenJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $request = $this->request;
        \Storage::put('test.txt', json_encode($request));
        try {
            @ini_set('max_execution_time', 120);
            @set_time_limit(120);
            $type = $request['type'] ?? '';
            $isCreate = $type == 'LocationCreate';
            if ($isCreate || $type == 'LocationUpdate') {
                // $compid = User::where('company_id', $request->companyId)->value('id');
                $compid = User::where('id', 1)->value('id');
                if (empty($compid)) {
                    return response()->json(['status' => 'failed', 'message' => "Data not saved due to Agency error"]);
                }
                $userEmail = $request['email'] ?? '';
                if (empty($userEmail) && $isCreate) {
                    $urlmain = 'locations/' . $request['id'];
                    $location = CRM::agencyV2($compid, $urlmain);
                    // $location = json_decode($location);
                    if ($location && property_exists($location, 'location')) {
                        $loc = $location->location ?? null;
                        $userEmail = $loc->email ?? "";
                        if (property_exists($loc, 'business')) {
                            $userEmail = $loc->business->email ?? "";
                        }
                    }
                }
                $userDet = User::where(['location' => $request['id'] ])->first();
                if (!$userDet) {
                    $password = $request['password'] ?? "Getleads2022!";
                    $userDet = new User();
                    $userDet->name = $request['name'] ?? '';
                    $userDet->location = $request['id'];
                    $userDet->role = 0;
                    $userDet->password = Hash::make($password);
                    $userDet->email = $userEmail;
                    $userDet->is_active = 1;
                    $userDet->separate_location = 0;
                    $userDet->save();

                    GetLocationAccessToken::dispatch($userDet->id, $request['id'], 'viaAgency')
                        ->onQueue(env('JOB_QUEUE_TYPE'));

                    $credetials = [
                        'reason' => 'Account Created At',
                        'name' => $request['name'],
                        'email' => $userEmail,
                        'password' => $password,
                    ];
                } else {
                    $userDet->name = $request['name'] ?? '';
                    $userDet->email = $userEmail;
                    $userDet->save();
                    $credetials = [
                        'reason' => 'Account Updated! ',
                        'name' => $request['name'],
                        'email' => $userEmail,
                        'password' => 'Use Existing Password'
                    ];
                    // $mail =  sendEmail($credetials);
                }
                sendEmail($credetials);

                // return response()->json(['status' => 'success', 'message' => "Location Data Saved"]);
            }
        } catch (\Throwable $th) {
            // saveLogs("Webhook Not Processed", $th->getMessage() . ' - ' . $th->getLine());
        }


    }
}
