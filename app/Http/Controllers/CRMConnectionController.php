<?php

namespace App\Http\Controllers;

use App\Helpers\CRM;
use App\Jobs\AutoAuthUser;
use Illuminate\Http\Request;

class CRMConnectionController extends Controller
{
    public function crmCallback(Request $request)
    {
        $code = $request->code ?? null;
        if ($code) {
            $user = loginUser();
            $user_id =$user->id;
            $code = CRM::crm_token($code, '');
            $code = json_decode($code);
            $user_type = $code->userType ?? null;
            $main = route('setting.index');
            $locId = $code->locationId ?? null;
            if($locId && $user){
                $verify = CRM::getCrmToken(['location_id'=>$locId]);
                if($verify && $user->id !=$verify->user_id){
                    return redirect($main)->with('error','This location '.$locId.' is already attached to another User - '.$verify->user_id);
                }
            }
            if ($user_type) {
                $token = $user->crmtoken ?? null;
                list($connected, $con) = CRM::go_and_get_token($code, '', $user_id, $token);
                if ($connected) {
                    try {
                        if($user && $user->location !== $con->location_id &&  $user_type == 'Location'){
                            $user->location = $con->location_id;
                            $user->save();
                        }
                    } catch (\Throwable $th) {
                        return redirect($main)->with('error', 'Location already connected - please select an other unique location');
                    }
                    // AutoAuthUser::dispatch($user_id)->onQueue(env('JOB_QUEUE_TYPE'));
                    return redirect($main)->with('success', 'Connected Successfully');
                }
                return redirect($main)->with('error', json_encode($code));

            }
            return redirect($main)->with('error', 'Not allowed to connect');
        }
    }
}
