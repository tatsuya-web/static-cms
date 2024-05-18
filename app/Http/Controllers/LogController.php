<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Models\Log;

class LogController extends Controller
{
    public function __invoke(Request $request): View
    {
        $logs = Log::getAllThisMonth();

        return view('app.log.index', compact('logs'));
    }
}
