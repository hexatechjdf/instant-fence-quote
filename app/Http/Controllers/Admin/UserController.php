<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
            $query = User::where('id','!=',1)->where('is_active',1)->orderBy('id', 'DESC');
            return DataTables::eloquent($query)
                ->addIndexColumn()
                ->editColumn('status', function($row) {
                    if ($row->is_active==1) {
                        $html= '<span class="badge badge-soft-success">Active</span>';
                    } else {
                        $html= '<span class="badge badge-soft-danger">Disabled</span>';
                    }
                    return $html;
                })
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '
                        <div class="dropdown d-inline-block float-right">
                            <a class="nav-link dropdown-toggle arrow-none" id="dLabel'.$row->id.'" data-bs-toggle="dropdown"
                                  aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <i class="fas fa-ellipsis-v font-20 text-muted"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dLabel4" style="">
                    ';
                    if($row->is_active==1){
                        $html.= '
                        <a class="dropdown-item" href="' . route('user.is-active', $row->id) . '" onclick="event.preventDefault(); statusMsg(\'' . route('user.is-active', $row->id) . '\')">Disable</a>
                        ';
                    }
                    else{
                        $html.= '
                        <a class="dropdown-item" href="' . route('user.is-active', $row->id) . '" onclick="event.preventDefault(); statusMsg(\'' . route('user.is-active', $row->id) . '\')">Activate</a>
                        ';
                    }
                    $html.= '
                                 <a class="dropdown-item" href="'.route('user.edit', $row->id).'" class="mr-2">Edit</a>
                                <a class="dropdown-item" href="'.route('user.delete', $row->id).'" onclick="event.preventDefault(); deleteMsg(\''.route('user.delete', $row->id).'\')">Delete</a>
                        ';
                   return $html;
                })
                ->rawColumns(['action','status'])
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
                ->addColumn('action', function ($row) {
                    $html = '';
                    $html .= '
                       <div class="dropdown d-inline-block float-right">
                            <a class="nav-link dropdown-toggle arrow-none" id="dLabel'.$row->id.'" data-bs-toggle="dropdown"
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
                ->rawColumns(['action', 'status'])
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
        if (is_null($id))
        {
            $req->validate([
                'name'          => 'required',
                'email'          => 'required|email|unique:users',
                'password'   => 'required',
            //    'api_key'      => 'required',
                'location'      => 'required|unique:users'
            ]);
        }

        if (is_null($id)) {
            $user = User::create([
                'name' => $req->name,
                'ghl_api_key'  => $req->api_key ??'',
                'email' => $req->email,
                'password' => Hash::make($req->password),
                'location'  => $req->location ?? rand(11111111111,99999999999990),
                'role'         => 0,
                'is_active' => 1
            ]);
            
            $reason = "Account Created At ";

          
            

            $msg = "Record Added Successfully!";
        } else {
            $req->validate([
                'name'          => 'required',
                'email'          => 'required|email',
               // 'api_key'      => 'required',
                'location'      => 'required'
            ]);
            
            $user = User::findOrFail($id);

            $user->name = $req->name;
            $user->email = $req->email;
            $user->ghl_api_key = $req->api_key ?? '';
           if($req->password){
             $user->password = Hash::make($req->password);
           }
            $user->location  = $req->location;
            $user->save();

            $msg = "Record Edited Successfully!";
            $reason = "Account Updated - " ;
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
        User::find($id)->delete();
        return redirect()->back()->with('success', 'Record Delete Successfully!');
    }

    public function isActive($id)
    {
        $category = User::find($id);
        if($category->is_active==1)
        {
            $category->is_active = 0;
        }
        elseif($category->is_active==0)
        {
            $category->is_active = 1;
        }

        $category->save();
        return back()->with('success','Status Changed Successfully.');
    }

}
