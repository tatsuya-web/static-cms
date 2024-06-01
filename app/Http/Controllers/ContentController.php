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
        try {
            $template->load('fields');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', config('error.invalid_type'));
        }

        return view('app.content.index', compact('template'));
    }

    public function create(Template $template) : View
    {
        try {
            $template->load('fields');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', config('error.invalid_type'));
        }

        return view('app.content.create', compact('template'));
    }
}
