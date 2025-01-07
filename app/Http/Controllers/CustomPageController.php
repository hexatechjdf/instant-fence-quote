<?php

namespace App\Http\Controllers;

use App\Models\CustomPage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Validator;

class CustomPageController extends Controller
{
    //custom pages
    public function customPageList()
    {
        $pages = CustomPage::with('company')->where('is_active', 1)->get();
        return view('admin.custom-pages.list', get_defined_vars());
    }

    public function customPageAdd()
    {
        $companies = User::where(['role' => 0, 'is_active' => 1])->get();
        return view('admin.custom-pages.add', get_defined_vars());
    }

    public function customPageEdit(Request $request, $id)
    {
        $page = CustomPage::findOrFail($id);
        $companies = User::where(['role' => 0, 'is_active' => 1])->get();
        return view('admin.custom-pages.edit', get_defined_vars());
    }

    public function savePage(Request $request, $id = null)
    {
        $chk = Validator::make($request->all(), [
            'page_name' => 'required',
        ]);
        if ($chk->fails()) {
            return redirect()->back()->withErrors("Page name is required");
        }
        

        $iframchk = $request->is_iframe && $request->is_iframe == 1 ? true : false;
        $showallchk = $request->show_to_all && $request->show_to_all == 1 ? true : false;
        $dd_item = $request->is_dd_item && $request->is_dd_item == 1 ? true : false;

        $type = $iframchk ? 'iframe' : 'page';
        $link = $iframchk ? $request->link : Str::slug($request->page_name);
        // $show_to_all = $showallchk ? true : false;
        $show_to_all = true;

        $page = CustomPage::updateOrCreate(
            ['id' => $id],
            [
                'name' => $request->page_name,
                'slug' => Str::slug($request->page_name),
                'description' => $request->page_description,
                'link' => $link,
                'type' => $type,
                'show_to_all' => $show_to_all,
                'company_id' => $request->company_id,
                'is_active' => 1,
                'is_dd_item' => $dd_item
            ]
        );

        return back()->with('success', 'Page saved successfully');
    }

    public function customPageVisit($id)
    {
        $page = CustomPage::findOrFail($id);
        if ($page->type == 'page') {
            return redirect()->route('custom-pages.public', $page->slug);
        }
        return view('admin.custom-pages.visit', get_defined_vars());
    }
    public function saveCustomPage(Request $request, $id = null)
    {
        if (auth()->user()->role == 0) {
            $companyid = $request->company_id;
        } else {
            $companyid = auth()->user()->id;
        }
        if (!is_null($id)) {
            $setting = CustomPage::findOrFail($id);
            $setting->name = $request->account_name;
            $setting->description = $request->account_description;
            $setting->link = Str::slug($request->account_name);
            $setting->type = $request->type;
            $setting->company_id = $companyid;
            $setting->created_by = Auth::id();
            $setting->save();
            return back()->with('success', 'Settings Saved Successfully');
        }
        if ($request->has('account_link')) {
            $account_link = $request->account_link;
        } else {
            //create link from the name slug
            $account_link = Str::slug($request->account_name);
        }

        $setting = CustomPage::where('company_id', Auth::id())->whereName($request->account_name)->first() ?? new CustomPage();
        $setting->name = $request->account_name;
        $setting->description = $request->account_description;
        $setting->link = $account_link;
        $setting->type = $request->type;
        $setting->company_id = $companyid;
        $setting->created_by = Auth::id();
        $setting->save();
        return back()->with('success', 'Settings Saved Successfully');
    }

    public function customPageDelete($id)
    {
        $page = CustomPage::findOrFail($id);
        $page->delete();
        return back()->with('success', 'Page Deleted Successfully');
    }

    public function customPagePublic($slug)
    {
        
        $page = CustomPage::where('slug', $slug)->first();
     
        return view('admin.custom-pages.public', get_defined_vars());
    }
}
