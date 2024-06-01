<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class ContentController extends Controller
{
    public function index(Template $template) : View|RedirectResponse
    {
        if($template->is_valided_format === false) {
            Log::error(config('error.invalid_type'));
            return redirect()->back()->with('error', config('error.invalid_type'));
        }

        return view('app.content.index', compact('template'));
    }

    public function create(Template $template) : View|RedirectResponse
    {
        if($template->is_valided_format === false) {
            Log::error(config('error.invalid_type'));
            return redirect()->route('app.site_tree.index')->with('error', config('error.invalid_type'));
        }

        return view('app.content.create', compact('template'));
    }
}
