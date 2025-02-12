<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CRM;
use App\Http\Controllers\Controller;
use App\Jobs\GetLocationAccessToken;
use App\Jobs\LocationUserAutoAuth;
use App\Mail\CredentialChangeMail;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function list(Request $req)
    {
        if ($req->ajax()) {
            $query = User::where('id', '!=', 1)->where('is_active', 1)->orderBy('id', 'DESC');
            return DataTables::eloquent($query)
                ->addIndexColumn()
                ->editColumn('status', function ($row) {
                    if ($row->is_active == 1) {
                        $html = '<span class="badge badge-soft-success">Active</span>';
                    } else {
                        $html = '<span class="badge badge-soft-danger">Disabled</span>';
                    }
                    return $html;
                })
                ->editColumn('separate_location', function ($row) {
                    if ($row->separate_location == 1) {
                        $html = '<span class="badge badge-soft-success">Yes</span>';
                    } else {
                        $html = '<span class="badge badge-soft-danger">No</span>';
                    }
                    return $html;
                })
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '
                        <div class="dropdown d-inline-block float-right">
                            <a class="nav-link dropdown-toggle arrow-none" id="dLabel' . $row->id . '" data-bs-toggle="dropdown"
                                  aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <i class="fas fa-ellipsis-v font-20 text-muted"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dLabel4" style="">
                    ';
                    if ($row->is_active == 1) {
                        $html .= '
                        <a class="dropdown-item" href="' . route('user.is-active', $row->id) . '" onclick="event.preventDefault(); statusMsg(\'' . route('user.is-active', $row->id) . '\')">Disable</a>
                        ';
                    } else {
                        $html .= '
                        <a class="dropdown-item" href="' . route('user.is-active', $row->id) . '" onclick="event.preventDefault(); statusMsg(\'' . route('user.is-active', $row->id) . '\')">Activate</a>
                        ';
                    }
                    $html .= '
                                 <a class="dropdown-item" href="' . route('user.edit', $row->id) . '" class="mr-2">Edit</a>
                                <a class="dropdown-item" href="' . route('user.delete', $row->id) . '" onclick="event.preventDefault(); deleteMsg(\'' . route('user.delete', $row->id) . '\')">Delete</a>
                        ';


                        $html .= '
                         <a class="dropdown-item" href="' . route('loginwith', encrypt($row['email'])) . '" class="mr-2">Login as user</a>
                        ';

                    return $html;
                })
                ->rawColumns(['action', 'status', 'separate_location'])
                ->make(true);
        }

        return view('admin.user.list', get_defined_vars());
    }

    public function add()
    {
        return view('admin.user.add', get_defined_vars());
    }

    public function edit($id = null)
    {
        $data = User::find($id);
        return view('admin.user.edit', get_defined_vars());
    }


    public function  pending(Request $req)
    {
        if ($req->ajax()) {
            $query = User::where('id', '!=', 1)->where('is_active', 0)->orderBy('id', 'DESC');
            return DataTables::eloquent($query)
                ->addIndexColumn()
                ->editColumn('status', function ($row) {
                    if ($row->is_active == 1) {
                        $html = '<span class="badge badge-soft-success">Active</span>';
                    } else {
                        $html = '<span class="badge badge-soft-danger">Disabled</span>';
                    }
                    return $html;
                })
                ->editColumn('separate_location', function ($row) {
                    if ($row->separate_location == 1) {
                        $html = '<span class="badge badge-soft-success">Yes</span>';
                    } else {
                        $html = '<span class="badge badge-soft-danger">No</span>';
                    }
                    return $html;
                })
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '
                       <div class="dropdown d-inline-block float-right">
                            <a class="nav-link dropdown-toggle arrow-none" id="dLabel' . $row->id . '" data-bs-toggle="dropdown"
                                  aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <i class="fas fa-ellipsis-v font-20 text-muted"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dLabel4" style="">
                    ';
                    if ($row->is_active == 1) {
                        $html .= '
                        <a class="dropdown-item" href="' . route('user.is-active', $row->id) . '" onclick="event.preventDefault(); statusMsg(\'' . route('user.is-active', $row->id) . '\')">Disable</a>
                        ';
                    } else {
                        $html .= '
                        <a class="dropdown-item" href="' . route('user.is-active', $row->id) . '" onclick="event.preventDefault(); statusMsg(\'' . route('user.is-active', $row->id) . '\')">Activate</a>
                        ';
                    }
                    $html .= '
                                 <a class="dropdown-item" href="' . route('user.edit', $row->id) . '" class="mr-2">Edit</a>
                                <a class="dropdown-item" href="' . route('user.delete', $row->id) . '" onclick="event.preventDefault(); deleteMsg(\'' . route('user.delete', $row->id) . '\')">Delete</a>
                        ';
                    return $html;
                })
                ->rawColumns(['action', 'status', 'separate_location'])
                ->make(true);
        }

        return view('admin.user.pending', get_defined_vars());
    }

    public function approveAll()
    {
        $users = User::where('is_active', 0)->get();
        foreach ($users as $user) {
            $user->is_active = 1;
            $user->save();
        }
        return redirect()->back()->with('success', 'All users approved successfully');
    }

    public function deleteAll()
    {

        $users = User::where('is_active', 0)->get();
        foreach ($users as $user) {
            $user->delete();
        }
        return redirect()->back()->with('success', 'All users deleted successfully');
    }




    public function save(Request $req, $id = null)
    {
        if (is_null($id)) {
            $req->validate([
                'name'          => 'required',
                'email'          => 'required|email|unique:users',
                'password'   => 'required',
                //    'api_key'      => 'required',
                'survey_id'      => 'required|unique:users'
            ]);
        }

        if (is_null($id)) {
            $locSurId = $req->survey_id ?? rand(11111111111, 99999999999990);
            $user = User::create([
                'name' => $req->name,
                'ghl_api_key'  => $req->api_key ?? '',
                'email' => $req->email,
                'password' => Hash::make($req->password),
                'survey_id'  => $locSurId,
                'location'  => $locSurId,
                'role'         => 0,
                'is_active' => 1,
                'separate_location' => $req->has('separate_location') ? 1 : 0
            ]);

            $reason = "Account Created At ";




            $msg = "Record Added Successfully!";
        } else {
            $req->validate([
                'name'          => 'required',
                'email'          => 'required|email',
                // 'api_key'      => 'required',
                'survey_id'      => 'required|unique:users,survey_id,'.$id
            ]);

            $user = User::findOrFail($id);

            $user->name = $req->name;
            $user->email = $req->email;
            $user->ghl_api_key = $req->api_key ?? '';
            $user->separate_location = $req->has('separate_location') ? 1 : 0;
            if ($req->password) {
                $user->password = Hash::make($req->password);
            }
            $user->survey_id  = $req->survey_id;
            $user->save();

            $msg = "Record Edited Successfully!";
            $reason = "Account Updated - ";
        }

        $credetials = [
            'reason' => $reason,
            'name' => $req->name,
            'email' => $req->email,
            'password' => $req->password ?? "Use Old Password",
        ];

        $mail =  sendEmail($credetials);


        return redirect()->back()->with('success', $msg);
    }

    public function delete($id = null)
    {
        User::where('role', 0)->find($id)->delete();
        return redirect()->back()->with('success', 'Record Delete Successfully!');
    }

    public function isActive($id)
    {
        $category = User::where('role', 0)->find($id);

        $category->is_active = $category->is_active==1?0:1;
        $category->save();
        return back()->with('success', 'Status Changed Successfully.');
    }

    public function manageSubaccount()
    {
        $userId = 1;
        // $users = User::where('role', 0)->where('is_active', 1)->where('separate_location', 0)->pluck('location', 'id')->toArray();
        $users = User::where('role', 0)->where('is_active', 1)->whereNotNull('location')->get();
        $crmlocationID = [];

        $allLocations = [];
        $limit = 100;
        $skip = 0;

        do {
            $urlmain = "locations/search?deleted=false&limit={$limit}&skip={$skip}";
            $locations = CRM::agencyV2($userId, $urlmain);
            $locations = $locations->locations ?? [];

            $allLocations = array_merge($allLocations, $locations);
            $hasMore = count($locations) === $limit;
            $skip += $limit;
        } while ($hasMore);

        foreach ($allLocations as $loc) {
            $crmlocationID[] = $loc->id;
        }
        return view('admin.user.manage-subaccounts', get_defined_vars());
    }

    public function getLocations(Request $request)
    {
        $userId = 1; // Use the appropriate user ID or fetch from session/auth.
        $limit = 100;
        $skip = $request->input('skip', 0);  // Get skip value from the request

        // Fetch locations from CRM API
        $urlmain = "locations/search?deleted=false&limit={$limit}&skip={$skip}";
        $locations = CRM::agencyV2($userId, $urlmain);
        $locations = $locations->locations ?? [];

        // Return the locations as JSON
        return response()->json([
            'locations' => $locations,
            'has_more' => count($locations) === $limit, // Indicate if there are more locations
        ]);
    }

    public function connectLocations(Request $request)
    {
        \Artisan::call('optimize:clear');
        \Artisan::call('config:clear');
        \Artisan::call('cache:clear');
        $data = $request->all();
        foreach ($data as $key => $d) {
            if ($d['is_manual'] == false && $d['is_crm_user'] == true) {
                GetLocationAccessToken::dispatch($d['userId'], $d['locationId'], 'viaAgency')->onQueue(env('JOB_DASHBOARD_TYPE'))->delay(now()->addSeconds(2));
            }
        }
        return response()->json(['status' => 'success',  'message' => 'Your agancy locations are connecting in the background']);
    }

    public function saveUserDetail(Request $request)
    {
        $data = $request->all();
        $errors = [];
        foreach ($data as $key => $d) {
            $user = User::find($d['userId']);
            if ($user) {
                // Check if the locationId is already assigned to another user
                $exists = User::where('id', '!=', $d['userId'])
                    ->where('location', $d['locationId'])
                    ->exists();
                if ($exists) {
                    $errors[] = "Location ID {$d['locationId']} is already assigned to another user.";
                    continue;
                }
                if($d['is_manual'] == true){
                    $user->separate_location = 1;
                }
                if($d['is_crm_user'] == false){
                    $user->is_crm_user = 0;
                }
                // Save location if it's unique for this user
                $user->location = $d['locationId'];
                $user->save();
            } else {
                $errors[] = "User ID {$d['userId']} not found.";
            }
        }
        if (!empty($errors)) {
            return response()->json(['status' => 'error', 'message' => $errors], 422);
        }
        return response()->json(['status' => 'success', 'message' => 'Data saved successfully']);
    }
}
