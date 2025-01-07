<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Hash;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
     public function createProduct(Request $request)
    {
        \Storage::put('test.txt',json_encode($request->all()));
        $password = $request->password ?? "Getleads2022!";
        $user =  User::where('email', $request->email)->first();
        if (!$user) {

            $us = new User();
            $us->name = $request->full_name ?? $request->first_name ?? '' . ' ' . $request->last_name ?? '';
            $us->email = $request->email;
            $us->password = Hash::make($password);
            $us->role = 0;
            $us->ghl_api_key = $request->apikey ?? '';
            $us->location = $request->location_id ?? $request->location->id ?? $request->contact_id;
            $us->is_active = 1;
            $us->save();

            if($us){
                $credetials = [
                    'reason' => 'Account Created At',
                    'name' => $us->name,
                    'email' => $us->email,
                    'password' => $password,
                 ];

                $mail =  sendEmail($credetials);
            }


            return  "account created successfully";
        }else{

            $user->ghl_api_key = $request->apikey ?? '';
            $user->location = $request->location_id ?? $request->location->id ?? $request->contact_id ?? rand(111111111111,999999999999);
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

}
