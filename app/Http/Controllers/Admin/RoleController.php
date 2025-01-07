<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use DataTables;

class RoleController extends Controller
{
    public function list(Request $req)
    {
        if ($req->ajax()) {
            $query = Role::orderBy('created_at','DESC');

            return DataTables::eloquent($query)
                ->addIndexColumn()
                ->editColumn('id', function ($row) {
                    return  $row->id;
                })
                ->addColumn('action', function ($row) {
                        $html= '
                                 <a href="' . route('role.edit', $row->id) . '" class="mr-2"><i class="fas fa-edit text-info font-16"></i></a>
                                 <a href="' . route('role.delete', $row->id) . '" onclick="event.preventDefault(); deleteMsg(\'' . route('role.delete', $row->id) . '\')"><i class="fas fa-trash-alt text-danger font-16"></i></a>
                        ';
                    return $html;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.role.list', get_defined_vars());
    }

    public function add()
    {
        return view('admin.role.add', get_defined_vars());
    }

    public function edit($id = null)
    {
        $data = Role::find($id);

        return view('admin.role.edit', get_defined_vars());
    }

    public function save(Request $req, $id = null)
    {

        $req->validate([
            'name' => 'required',
        ]);

        if (is_null($id)) {
            Role::create([
                'name' => $req->name,
                'slug'  => Str::slug($req->name),
            ]);

            $msg = "Record Added Successfully!";
        } else {
            Role::find($id)->update([
                'name' => $req->name,
                'slug'  => Str::slug($req->name),

            ]);

            $msg = "Record Edited Successfully!";
        }

        return redirect()->back()->with('success', $msg);
    }

    public function delete($id = null)
    {
        Role::find($id)->delete();

        return redirect()->back()->with('success', 'Record Delete Successfully!');
    }
}
