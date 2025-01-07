<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function list(Request $req)
    {
        if ($req->ajax()) {
            $query = Category::where('user_id', Auth::id());

            return DataTables::eloquent($query)
                ->addIndexColumn()
                ->editColumn('id', function ($row) {
                    return  $row->id;
                })
                ->editColumn('image', function ($row) {
                    if (!$row->image) {
                        $row->image = 'https://via.placeholder.com/150x150.png?text=No+Image+For' . $row->name;
                    }
                    return '<img src="' . $row->image . '" class="img-fluid thumb-md rounded">';
                    // return $row->image;
                })
                ->editColumn('status', function ($row) {
                    if ($row->is_active == 1) {
                        $html = '<span class="badge badge-soft-success">Active</span>';
                    } else {
                        $html = '<span class="badge badge-soft-danger">Disabled</span>';
                    }
                    return $html;
                })
                ->editColumn('image', function ($row) {
                    if (!$row->image) {
                        $row->image = 'https://via.placeholder.com/200x200.png?text=No+Image+Available';
                    }
                    return '<img src="' . asset($row->image) . '" class="img-fluid thumb-md rounded">';
                })
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
                        <a class="dropdown-item" href="' . route('category.status', $row->id) . '" onclick="event.preventDefault(); statusMsg(\'' . route('category.status', $row->id) . '\')">Disable</a>
                        ';
                    } else {
                        $html .= '
                        <a class="dropdown-item" href="' . route('category.status', $row->id) . '" onclick="event.preventDefault(); statusMsg(\'' . route('category.status', $row->id) . '\')">Activate</a>
                        ';
                    }
                    $html .= '
                                 <a class="dropdown-item" href="' . route('category.edit', $row->id) . '" class="mr-2">Edit</a>
                                 <a class="dropdown-item" href="' . route('category.delete', $row->id) . '" onclick="event.preventDefault(); deleteMsg(\'' . route('category.delete', $row->id) . '\')">Delete</a>
                        ';
                    return $html;
                })

                ->rawColumns(['action', 'status', 'image'])
                ->make(true);
        }

        return view('admin.category.list', get_defined_vars());
    }

    public function add()
    {
        return view('admin.category.add', get_defined_vars());
    }

    public function edit($id = null)
    {
        $data = Category::find($id);
 
        return view('admin.category.edit', get_defined_vars());
    }

    public function save(Request $req, $id = null)
    {
        $req->validate([
            'name' => 'required',
            // 'image' => 'required'
        ]);

        if (is_null($id)) {
            $cat = new Category();
            $cat->name = $req->name;
            $cat->user_id = auth()->user()->id;
            if ($req->image) {
                $cat->image = uploadFile($req->image, 'uploads/catgeories', 'fence_type-' . auth()->user()->id . '-' . time());
            }
           
            $cat->save();
            $msg = "Record Added Successfully!";
        } else {
            $cat = Category::find($id);
            $cat->name = $req->name;
            $cat->user_id = auth()->user()->id;
            if ($req->image) {
                $cat->image = uploadFile($req->image, 'uploads/catgeories', 'fence_type-' . auth()->user()->id . '-' . time());
            }
            $cat->save();
            $msg = "Record Edited Successfully!";
        }

        return redirect()->back()->with('success', $msg);
    }

    public function delete($id = null)
    {
        Category::find($id)->delete();
        return redirect()->back()->with('success', 'Record Delete Successfully!');
    }

    public function status($id)
    {
        $category = Category::find($id);
        if ($category->is_active == 1) {
            $category->is_active = 0;
        } elseif ($category->is_active == 0) {
            $category->is_active = 1;
        }

        $category->save();
        return back()->with('success', 'Status Changed Successfully.');
    }
}
