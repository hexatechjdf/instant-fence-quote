<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Fence;
use App\Models\User;
use App\Models\FtAvailable;
use App\Models\Category;

use Illuminate\Http\Request;

class FenceController extends Controller
{
    public function fences()
    {
        $fences = Fence::with('user', 'category', 'prices')->orderBy('created_at', 'DESC')->get();
        // dd($fences);
        return response()->json([
            'status'         =>  200,
            'data'           =>  $fences,
            'message'     => 'List Of Fences'
        ]);
    }

    public function getAllWithLocation($location)
    {
        // $ftavailables = FtAvailable::where('is_active','1')->orderBy('ft_available_name')->get();
        //$category = Category::where('is_active','1')->orderBy('name')->get();
        //'fences.prices'
        $user = User::with('categories', 'categories.fences', 'categories.fences.ft_available', 'categories.fences.ft_available.ft_available', 'categories.fences.ft_available.prices')->where('is_active', 1)->where('location', $location)->first()->makeHidden('user');

        // dd($user);
        if ($user) {
            unset($user->email);
            unset($user->ghl_api_key);
            unset($user->role);
        }
        $data = $user;
        return response()->json([
            'status'         => 200,
            'message'      => 'All Record Location',
            'data'            => ['all' => $user],
        ]);
    }

    public function allAllWithId($id)
    {
        $user = Fence::with(['user' => function ($query) use ($id) {
            $query->where('id', $id);
        }])->with('category', 'prices')->get()->makeHidden('user');

        return response()->json([
            'status'         => 200,
            'message'      => 'All Record',
            'data'            => $user,
        ]);
    }

    public function singleFencePrice()
    {
        $fences = Fence::with(['prices' => function ($query) {
            $query->where('type', 'single');
        }])->get();

        return response()->json([
            'status'         =>  200,
            'data'           =>  $fences,
            'message'     => 'List Of Single Price Type Fences'
        ]);
    }
    public function doubleFencePrice()
    {
        $fences = Fence::with(['prices' => function ($query) {
            $query->where('type', 'double');
        }])->get();

        return response()->json([
            'status'         =>  200,
            'data'           =>  $fences,
            'message'     => 'List Of Single Price Type Fences'
        ]);
    }
}
