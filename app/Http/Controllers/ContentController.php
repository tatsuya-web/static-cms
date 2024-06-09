<?php

namespace App\Http\Controllers;

use App\Http\Requests\Content\ContentCreateRequest;
use App\Models\Content;
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

    public function store(ContentCreateRequest $request, Template $template) : RedirectResponse
    {
        if($template->is_valided_format === false) {
            Log::error(config('error.invalid_type'));
            return redirect()->route('app.site_tree.index')->with('error', config('error.invalid_type'));
        }

        $result = $template->makeContent($request->validatedData());

        if(! $result) {
            Log::error(config('error.failed_save'));
            return redirect()->route('app.site_tree.index', ['template' => $template])->with('error', config('error.failed_save'));
        }

        return redirect()->route('app.content.index', ['template' => $template])->with('success', '登録しました');
    }

    public function edit(Template $template, Content $content) : View|RedirectResponse
    {
        if($template->is_valided_format === false) {
            Log::error(config('error.invalid_type'));
            return redirect()->route('app.site_tree.index')->with('error', config('error.invalid_type'));
        }

        return view('app.content.edit', compact('template', 'content'));
    }

    public function update(ContentCreateRequest $request, Template $template, Content $content) : RedirectResponse
    {
        if($template->is_valided_format === false) {
            Log::error(config('error.invalid_type'));
            return redirect()->route('app.site_tree.index')->with('error', config('error.invalid_type'));
        }

        $result = $template->updateContent($content, $request->validatedData());

        if(! $result) {
            Log::error(config('error.failed_save'));
            return redirect()->route('app.site_tree.index', ['template' => $template])->with('error', config('error.failed_save'));
        }

        return redirect()->back()->with('success', '更新しました');
    }

    public function destroy(Request $request, Template $template) : RedirectResponse
    {
        $content = Content::findOrFail($request->delete_id);

        $content->deleteHtml();

        $result = $content->delete();

        if(! $result) {
            Log::error(config('error.failed_delete'));
            return redirect()->route('app.content.index', ['template' => $template])->with('error', config('error.failed_delete'));
        }

        return redirect()->route('app.content.index', ['template' => $template])->with('success', '削除しました');
    }
}
