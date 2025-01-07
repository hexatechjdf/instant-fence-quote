<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FtAvailable;
use Illuminate\Http\Request;

class FtAvailableController extends Controller
{
    public function ftAvailables()
    {
        $ft_availables = FtAvailable::orderBy('created_at','DESC')->get();
        return response()->json([
            'status'         =>  200,
            'data'           =>  $ft_availables,
            'message'     => 'List Of Ft Availables'
        ]);
    }
}
