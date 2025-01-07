<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Fence;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Estimate;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class DashboardController extends Controller
{

    public function dashboard(Request $req)
    {
        $users = User::where('id', '!=', 1)->latest()->take(5)->get();

        if (auth()->user()->role == 1) {
            $fences = Fence::with('user', 'category')->latest()->take(5)->get();
            $types = Category::with('user')->latest()->take(5)->get();
            $estimates = Estimate::latest()->take(5)->get();

            
            // Count for all estimates
            $estimates_count = Estimate::count();

            // Count for successful and failed estimates
            $successful_estimates_count = Estimate::where('is_completed', 1)->count();
            $failed_estimates_count = Estimate::where('is_completed', 0)->count();

        } else {

            $fences = Fence::with('user', 'category')->where('user_id', Auth::id())->latest()->take(5)->get();
            $types = Category::with('user')->where('user_id', Auth::id())->latest()->take(5)->get();
            $estimates = Estimate::where('company_id', Auth::id())->latest()->take(5)->get();

            // Count for estimates specific to the company
            $estimates_count = Estimate::where('company_id', Auth::id())->count();

            // Count for successful and failed estimates specific to the company
            $successful_estimates_count = Estimate::where('company_id', Auth::id())->where('is_completed', 1)->count();
            $failed_estimates_count = Estimate::where('company_id', Auth::id())->where('is_completed', 0)->count();

            // Get the start and end of the period 1 week prior to the current week
            $startOfWeekPrior = Carbon::now()->subWeeks(2)->startOfWeek();
            $endOfWeekPrior = Carbon::now()->subWeeks(1)->endOfWeek();

            $recent_week_prior_estimates_count = Estimate::where('company_id', Auth::id())
                ->whereBetween('created_at', [$startOfWeekPrior, $endOfWeekPrior])
                ->count();
        }

        return view('admin.dashboard', get_defined_vars());
    }


    public function profile()
    {
        $user = Auth::user();
        return view('admin.profile.userprofile', get_defined_vars());
    }
    public function general(Request $req)
    {
        $user = Auth::user();
        $req->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
            'name' => 'required',
            // 'ghl_api_key' => 'required',
            // 'location' => 'required',
        ]);

        if($req->location){
            $req->validate([
            'location' => 'unique:users,location,'.$user->id,
        ]);
        }

        $location = $req->location ?? Str::random(15);

        $user->name = $req->name;
        $user->email = $req->email;
        $user->ghl_api_key = $req->ghl_api_key;
        $user->location = $req->location;
        if ($req->photo) {
            $user->photo = uploadFile($req->photo, 'uploads/profile', $req->name);
        }
        $user->save();
        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    public function password(Request $req)
    {
        $user = Auth::user();
        $req->validate([
            'current_password' => 'required|password',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password'
        ]);
        $user->password = bcrypt($req->password);
        $user->save();

        return redirect()->back()->with('success', 'Password updated Successfully!');
    }
}
