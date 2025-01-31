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
            $user_id =loginUser()->id;
            $code = CRM::crm_token($code, '');
            $code = json_decode($code);
            $user_type = $code->userType ?? null;
            $main = route('setting.index');
            if ($user_type) {
                $token = $user->crmtokenagency ?? null;
                list($connected, $con) = CRM::go_and_get_token($code, '', $user_id, $token);
                if ($connected) {
                    // AutoAuthUser::dispatch($user_id)->onQueue(env('JOB_QUEUE_TYPE'));
                    return redirect($main)->with('success', 'Connected Successfully');
                }
                return redirect($main)->with('error', json_encode($code));

            }
            return redirect($main)->with('error', 'Not allowed to connect');
        }
    }
}
