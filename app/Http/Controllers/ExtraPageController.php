<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExtraPageController extends Controller
{
    public function about()
    {
        return view('extra-pages.about');
    }

    public function contact()
    {
        return view('extra.contact');
    }

    public function faq()
    {
        return view('extra.faq');
    }

    public function terms()
    {
        return view('admin.settings.term');
    }

    public function termOfService()
    {
        return view('admin.extra-pages.terms');
    }

    public function privacy()
    {
        return view('extra.privacy');
    }
}
