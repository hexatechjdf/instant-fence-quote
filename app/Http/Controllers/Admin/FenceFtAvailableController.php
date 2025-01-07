<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FenceFtAvailable;
use Illuminate\Http\Request;
use DataTables;

class FenceFtAvailableController extends Controller
{
    public function list(Request $req)
    {
        $data = FenceFtAvailable::with('fence','ft_available','fence.user')->get()->unique('fence_id');
        return view('admin.fence-ft-available.fence-ft-available', get_defined_vars());
    }
}
