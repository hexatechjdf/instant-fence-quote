<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FtAvailable;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;

class FtAvailableController extends Controller
{
    public function list(Request $req)
    {
        if ($req->ajax()) {
            $query = FtAvailable::where('user_id',Auth::id());

            return DataTables::eloquent($query)
                ->addIndexColumn()
                ->editColumn('id', function ($row) {
                    return  $row->id;
                })
                ->editColumn('is_active', function($row) {
                    if ($row->is_active==1) {
                        $html= '<span class="badge badge-soft-success">Active</span>';
                    } else {
                        $html= '<span class="badge badge-soft-danger">Disabled</span>';
                    }
                    return $html;
                })

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
                        <a class="dropdown-item" href="' . route('ft_available.is-active', $row->id) . '" onclick="event.preventDefault(); statusMsg(\'' . route('ft_available.is-active', $row->id) . '\')">Disable</a>
                        ';
                    }
                    else{
                        $html.= '
                        <a class="dropdown-item" href="' . route('ft_available.is-active', $row->id) . '" onclick="event.preventDefault(); statusMsg(\'' . route('ft_available.is-active', $row->id) . '\')">Activate</a>
                        ';
                    }

                    $html.= '
                    <a class="dropdown-item" href="' . route('ft_available.edit', $row->id) . '" class="mr-2">Edit</a>
                    <a class="dropdown-item" href="' . route('ft_available.delete', $row->id) . '" onclick="event.preventDefault(); deleteMsg(\'' . route('ft_available.delete', $row->id) . '\')">Delete</a>
                ';

                   return $html;
                })

                ->rawColumns(['action','is_active','is_available'])
                ->make(true);
        }

        return view('admin.ft-available.list', get_defined_vars());
    }

    public function add()
    {
        $users = User::all();
        return view('admin.ft-available.add', get_defined_vars());
    }

    public function edit($id = null)
    {
        $users = User::all();
        $data = FtAvailable::find($id);

        return view('admin.ft-available.edit', get_defined_vars());
    }

    public function save(Request $req, $id = null)
    {

        $req->validate([
            'ft_available_name' => 'required',
        ]);

        if (is_null($id)) {
            FtAvailable::create([
                'ft_available_name' => $req->ft_available_name,
                'user_id'                  => Auth::id()
            ]);

            $msg = "Record Added Successfully!";
        } else {
            FtAvailable::find($id)->update([
                'ft_available_name' => $req->ft_available_name,
                'user_id'                  => Auth::id()
            ]);

            $msg = "Record Edited Successfully!";
        }

        return redirect()->back()->with('success', $msg);
    }

    public function delete($id = null)
    {
        FtAvailable::find($id)->delete();
        return redirect()->back()->with('success', 'Record Delete Successfully!');
    }

    public function isActive($id)
    {
        $category = FtAvailable::find($id);
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

    public function isAvailable($id)
    {
        $category = FtAvailable::find($id);
        if($category->is_active_available==1)
        {
            $category->is_active_available = 0;
            $category->save();
        }
        elseif($category->is_active_available==0)
        {
            $category->is_active_available = 1;
            $category->save();
        }

        return back()->with('success','Status Changed Successfully.');
    }
}
