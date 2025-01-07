<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FenceFtAvailable;
use Illuminate\Http\Request;

class FenceFtAvailableController extends Controller
{
    public function fenceFt()
    {
        $fence_fts = FenceFtAvailable::with('fence','ft_available')->get();
        dd($fence_fts);
        return response()->json([
            'status'         =>  200,
            'data'           =>  $fence_fts,
            'message'     => 'List Of Fence Ft Availables'
        ]);
    }
}
