<?php

namespace App\Http\Controllers;

use App\Jobs\GetLocationAccessToken;
use App\Jobs\LocationWebhookListenJob;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function createProduct(Request $request)
    {
        \Storage::put('test.txt', json_encode($request->all()));
        $password = $request->password ?? "Getleads2022!";
        $user =  User::where('email', $request->email)->first();
        if (!$user) {

            $us = new User();
            $us->name = $request->full_name ?? $request->first_name ?? '' . ' ' . $request->last_name ?? '';
            $us->email = $request->email;
            $us->password = Hash::make($password);
            $us->role = 0;
            $us->ghl_api_key = $request->apikey ?? '';
            $locationID = $request->location_id ?? $request->location->id ?? $request->contact_id;
            $us->location = $locationID;
            $us->survey_id = $locationID;
            $us->is_active = 1;
            $us->save();

            if ($us) {
                $credetials = [
                    'reason' => 'Account Created At',
                    'name' => $us->name,
                    'email' => $us->email,
                    'password' => $password,
                ];

                $mail =  sendEmail($credetials);
            }

            if ($us) {
                GetLocationAccessToken::dispatch($us->id,  $locationID, 'viaAgency')->onQueue(env('JOB_QUEUE_TYPE'));
            }

            return  "account created successfully";
        } else {

            $user->ghl_api_key = $request->apikey ?? '';
            $loc_id = $request->location_id ?? $request->location->id ?? $request->contact_id ?? rand(111111111111, 999999999999);
            $user->location = $loc_id;
            $user->survey_id = $loc_id;
            $user->is_active = 1;
            $user->save();

            $credetials = [
                'reason' => 'Account Updated! ',
                'name' => $user->name,
                'email' => $user->email,
                'password' => 'Use Existing Password'
            ];

            $mail =  sendEmail($credetials);
            return "account updated succesfully";
        }
    }
    public function createProductNew(Request $request)
    {
        $loc_time = 30;
        $lock_key = 'token_refresh_' . $request->id;
        $loc_block = cache()->lock($lock_key, $loc_time);
        if ($loc_block->get()) {
            try {
                LocationWebhookListenJob::dispatch($request->all())->onQueue(env('JOB_QUEUE_TYPE'));
            } finally {
                $loc_block->release();
            }
        }
    }


}
