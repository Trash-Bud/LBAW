<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticPageController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }
    

    public function showContacts()
    {
        return view('static_pages.contactsPage');
    }


    public function showFAQ()
    {
        return view('static_pages.faq');
    }
    

    public function showAboutUs()
    {
        return view('static_pages.aboutUs');
    }
}