<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Fence;
use App\Models\fence_ft_available;
use App\Models\FenceFtAvailable;
use App\Models\FtAvailable;
use App\Models\PriceFit;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Auth;

class FenceController extends Controller
{

    public function list(Request $req)
    {
        //     $ft_availables = FenceFtAvailable::with('ft_available')->where('fence_id',1)->get();
        // dd($ft_availables[1]->ft_available->ft_available_name);
        if ($req->ajax()) {
            $query = Fence::with('category')->where('user_id', Auth::id());

            return DataTables::eloquent($query)
                ->addIndexColumn()
                ->editColumn('id', function ($row) {
                    return  $row->id;
                })
                // ->editColumn('user', function($row) {
                //     return $row->user->name;
                // })
                ->editColumn('fence_image', function ($row) {
                    if (!$row->fence_image) {
                        $row->fence_image = config('constant.placeholder.url').'150x150?text=No+Image+Available+For' . $row->fence_name;
                    }
                    return '<img src="' . asset($row->fence_image) . '" class="img-fluid thumb-md rounded">';
                    // return $row->image;
                })
                ->editColumn('ft_available', function ($row) {
                    $ft_availables = FenceFtAvailable::with('ft_available')->where('fence_id', $row->id)->get();
                    $fts = [];
                    foreach ($ft_availables as $index => $data) {
                        array_push($fts,  $data->ft_available->ft_available_name);
                    }
                    return $fts;
                })
                ->editColumn('category', function ($row) {
                    return $row->category->name;
                })
                ->addColumn('prices', function ($row) {
                    return '<a class="btn btn-primary" href="' . route('fence.price', $row->id) . '" class="mr-2">Set/Update Price</a>';
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
                        <a class="dropdown-item" href="' . route('fence.status', $row->id) . '" onclick="event.preventDefault(); statusMsg(\'' . route('fence.status', $row->id) . '\')">Disable</a>
                        ';
                    } else {
                        $html .= '
                        <a class="dropdown-item" href="' . route('fence.status', $row->id) . '" onclick="event.preventDefault(); statusMsg(\'' . route('fence.status', $row->id) . '\')">Activate</a>
                        ';
                    }
                    $html .= '
                                 <a class="dropdown-item" href="' . route('fence.edit', $row->id) . '" class="mr-2">Edit</a>
                                 <a class="dropdown-item" href="' . route('fence.delete', $row->id) . '" onclick="event.preventDefault(); deleteMsg(\'' . route('fence.delete', $row->id) . '\')">Delete</a>

                                 ';
                    return $html;
                })

                ->rawColumns(['action', 'status', 'fence_image', 'prices'])
                ->make(true);
        }

        return view('admin.fence.list', get_defined_vars());
    }

    public function add()
    {
        $users = User::all();
        $categories = Category::where(['is_active' => 1, 'user_id' => Auth::id()])->get();
        $ft_availables = FtAvailable::where(['is_active' => 1, 'user_id' => Auth::id()])->get();
        return view('admin.fence.add', get_defined_vars());
    }

    public function edit($id = null)
    {
        $users = User::all();
        $categories = Category::where(['is_active' => 1, 'user_id' => Auth::id()])->get();
        $ft_availables = FtAvailable::where(['is_active' => 1, 'user_id' => Auth::id()])->get();
        $data = Fence::find($id);
        $check = FenceFtAvailable::where('fence_id', $id)->where('is_available', 1)->pluck('ft_available_id')->toArray();
        session(['ft_available_old_ids' => $check]);
        // dd($check);
        return view('admin.fence.edit', get_defined_vars());
    }

    public function save(Request $req, $id = null)
    {
        // dd($req->all())
        $req->validate([
            'category_id'       => 'required',
            'fence_name'       => 'required',
            'ft_available_id'  => 'required'
        ]);

        if ($req->fence_image) {
            $fence_image = uploadFile($req->fence_image, 'uploads/fences', 'fence_style-' . auth()->user()->id . '-' . time());
        }

        if (is_null($id)) {
            $fence = Fence::create([
                'user_id'        => Auth::id(),
                'category_id' => $req->category_id,
                'fence_name' => $req->fence_name,
                'fence_image' => $fence_image ?? null,
            ]);

            foreach ($req->ft_available_id as $index => $ft) {
                FenceFtAvailable::create([
                    'fence_id'              => $fence->id,
                    'ft_available_id'    => $ft,
                ]);
            }

            $msg = "Record Added Successfully!";
        } else {
            $data = [
                'category_id' => $req->category_id,
                'fence_name' => $req->fence_name

            ];
            if ($req->fence_image) {
                $data['fence_image'] =  $fence_image;
            }
            $fe = Fence::find($id)->update($data);

            // FenceFtAvailable::where('fence_id',$id)->delete();
            // FenceFtAvailable::where('fence_id',$id)->delete();
            //new array
            $oldids = session('ft_available_old_ids');
            $newids = $req->ft_available_id;

            $setzeroarry = array_diff($oldids, $newids);
            $setonearry = array_diff($newids, $oldids);

            // dd($setzeroarry,$setonearry);

            $fenc = Fence::find($id);
            // dd($fenc);
            foreach ($setzeroarry as $index => $ft) {
                FenceFtAvailable::where([
                    'fence_id'              => $fenc->id,
                    'ft_available_id'    => $ft
                ])->update([
                    'is_available'              => 0
                ]);
            }
            foreach ($setonearry as $index => $ft) {

                $ae = FenceFtAvailable::where([
                    'fence_id'              => $fenc->id,
                    'ft_available_id'    => $ft,
                ])->first();
                if (!$ae) {
                    $ae = new FenceFtAvailable;
                    $ae->fence_id             = $fenc->id;
                    $ae->ft_available_id             = $ft;
                }
                $ae->is_available = 1;
                $ae->save();
            }

            $msg = "Record Edited Successfully!";
        }

        return redirect()->back()->with('success', $msg);
    }

    public function delete($id = null)
    {
        Fence::find($id)->delete();
        return redirect()->back()->with('success', 'Record Delete Successfully!');
    }

    public function status($id)
    {
        $category = Fence::find($id);
        if ($category->is_active == 1) {
            $category->is_active = 0;
        } elseif ($category->is_active == 0) {
            $category->is_active = 1;
        }

        $category->save();
        return back()->with('success', 'Status Changed Successfully.');
    }

    public function priceFit($id = null)
    {

        $fence = Fence::find($id);
        $ft_availables = FenceFtAvailable::with('ft_available')->where('fence_id', $id)->get();
        return view('admin.fence.price', get_defined_vars());
    }

    public function fenceTypePrice(Request $request)
    {
        $request->validate([
            'price' => 'numeric',
            'range' => 'numeric'
        ]);
        FenceFtAvailable::updateOrCreate([
            'fence_id'  => $request->fence_id,
            'ft_available_id' => $request->ft_available_id,
        ], [
            'fence_id' => $request->fence_id,
            'ft_available_id' => $request->ft_available_id,
            'price'          => $request->price,
            'range'        => $request->range,
            'is_min_fee' => $request->is_min_fee==1
        ]);
        $msg = "Price Set Successfully.";
        return response()->json($msg);
    }

    public function priceSave(Request $request)
    {
        // dd($request->all());
        if (isset($request->is_active)) {
            $is_active = $request->is_active;
        } else {
            $is_active = 0;
        }
        PriceFit::updateOrCreate([
            'fence_id'  => $request->fence_id,
            'ft_available_id' => $request->ft_available_id,
            'type'                 => $request->type,
        ], [
            'fence_id' => $request->fence_id,
            'ft_available_id' => $request->ft_available_id,
            'type' => $request->type,
            'ft_price'          => $request->price,
            'is_active'        => $is_active
        ]);
        if ($request->type == "single") {
            $msg = "Single Price Set Successfully.";
        } else {
            $msg = "Double Price Set Successfully.";
        }
        return response()->json($msg);
    }
}
