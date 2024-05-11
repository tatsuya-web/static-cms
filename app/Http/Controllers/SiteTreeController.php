<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \Illuminate\View\View;

class SiteTreeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        return view('app.site_tree.index');
    }
}
