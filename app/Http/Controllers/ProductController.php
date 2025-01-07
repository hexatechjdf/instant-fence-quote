<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function list()
    {
        $products = Product::latest()->get();
        return view('admin.products.list', get_defined_vars());
    }

    public function add()
    {
        return view('admin.products.add');
    }

    public function edit($id)
    {
        $product = Product::find($id);
        return view('admin.products.edit', get_defined_vars());
    }
    public function save(Request $request, $id = null)
    {
        // dd(json_encode(default_user_permissions()->permissions));
        $request->validate([
            'name' => 'required',
            'product_id' => 'required'
        ]);

        if (is_null($id)) {
            $product = new Product();
        } else {
            $product = Product::find($id);
        }

        $product->name = $request->name;
        $product->product_id = $request->product_id;
        $product->permissions = json_encode(default_user_permissions()->permissions);
        $product->snapshot_id = $request->snapshot_id;
        $product->save();
        return back()->with('success', 'Product Added Successfully');
    }

    public function delete($id)
    {
        $product = Product::find($id);
        $product->delete();
        return back()->with("success", 'Product deleted succesfully');
    }

    public function manage($id)
    {
        $product = Product::find($id);
        if ($product) {
            $permissions = json_decode($product->permissions);
        } else {
            $permissions = [];
        }

        // dd($permissions);
        return view('admin.products.permissions', get_defined_vars());
    }

    public function savePermission($id, Request $request)
    {
        $product = Product::find($id);
        $permissions = $request->permissions;
        if (is_array($permissions) && count($permissions) > 0) {
            $obj = new \stdClass;
            foreach ($permissions as $key => $permission) {
                $obj->$key = $permission;
            }
        }
        //  dd();
        $product->permissions = $obj->permissions;
        $product->save();
        return response()->json('Product permission saved successfully');
    }
}
