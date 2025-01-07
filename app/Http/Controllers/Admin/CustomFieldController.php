<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CustomField;
use Illuminate\Http\Request;

class CustomFieldController extends Controller
{
    public function list(Request $req)
    {
        $custom_fields = CustomField::get();
        return view('admin.custom-fields.list', get_defined_vars());
    }

    public function add()
    {
        return view('admin.custom-fields.add', get_defined_vars());
    }

    public function edit($id = null)
    {

        $data = CustomField::find($id);
        return view('admin.custom-fields.edit', get_defined_vars());
    }

    public function save(Request $req, $id = null)
    {
        $req->validate([
            'field_name' => 'required',
        ]);
        if (is_null($id)) {
            $fence = CustomField::create([
                'custom_field_name' => $req->field_name,
            ]);

            $msg = "Record Added Successfully!";
        } else {
            $fe = CustomField::find($id)->update([
                'custom_field_name' => $req->field_name,
            ]);

            $msg = "Record Edited Successfully!";
        }

        return redirect()->back()->with('success', $msg);
    }

    public function delete($id = null)
    {
        CustomField::find($id)->delete();
        return redirect()->back()->with('success', 'Record Delete Successfully!');
    }

}
