<?php

namespace App\Http\Controllers;

use App\Enums\TemplateType;
use App\Http\Requests\Template\CommonTemplateCreateRequest;
use App\Http\Requests\Template\CommonTemplateUpdateRequest;
use App\Http\Requests\Template\PageTemplateCreateRequest;
use App\Http\Requests\Template\PageTemplateUpdateRequest;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TemplateController extends Controller
{
    public function index(): View
    {
        $commons = Template::getCommons();

        $pages = Template::getPages();

        return view('app.template.index', compact('commons', 'pages'));
    }

    public function commonCreate(): View
    {
        return view('app.template.common.create');
    }

    public function commonStore(CommonTemplateCreateRequest $request): RedirectResponse
    {
        $data = $request->validatedData();

        try {
            DB::beginTransaction();
    
            Template::create([
                'type' => TemplateType::Common,
                'name' => $data['name'],
                'show_name' => $data['show_name'],
                'single_value_name' => '',
                'multi_value_name' => '',
                'description' => $data['description'],
                'user_id' => auth()->id(),
            ]);
    
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
    
            return back()->with('error', 'テンプレートの作成に失敗しました。');
        }

        return redirect()->route('app.template.index')->with('success', 'テンプレートを作成しました。ソースコードを登録してください。');
    }

    public function commonEdit(Template $template): View
    {
        return view('app.template.common.edit', compact('template'));
    }

    public function commonUpdate(CommonTemplateUpdateRequest $request, Template $template): RedirectResponse
    {
        $data = $request->validatedData();

        try {
            DB::beginTransaction();
    
            $template->update([
                'name' => $data['name'],
                'show_name' => $data['show_name'],
                'description' => $data['description'],
            ]);

            if($data['src']) {
                $template->updateOrCreateSrc($data['src']);
            }
    
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
    
            return back()->with('error', 'テンプレートの更新に失敗しました。');
        }

        return redirect()->back()->with('success', 'テンプレートを更新しました。');
    }

    public function pageCreate(): View
    {
        return view('app.template.page.create');
    }

    public function pageStore(PageTemplateCreateRequest $request): RedirectResponse
    {
        $data = $request->validatedData();

        try {
            DB::beginTransaction();
    
            $template = Template::create([
                'type' => TemplateType::Page,
                'name' => $data['name'],
                'show_name' => $data['show_name'],
                'single_value_name' => $data['single_value_name'],
                'multi_value_name' => $data['multi_value_name'],
                'description' => $data['description'],
                'user_id' => auth()->id(),
            ]);

            $template->makeFormat($data['format']);

            $template->makeTree($data['path']);
    
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);

            $message = $e->getMessage();

            if($message === config('error.trre_has_alerady_page_template')) {
                return back()->with('error', '指定されたパスには既にページテンプレートが存在します。');
            }
    
            return back()->with('error', 'テンプレートの作成に失敗しました。');
        }

        return redirect()->route('app.template.index')->with('success', 'テンプレートを作成しました。ソースコードを登録してください。');
    }

    public function pageEdit(Template $template): View
    {
        return view('app.template.page.edit', compact('template'));
    }

    public function pageUpdate(PageTemplateUpdateRequest $request, Template $template): RedirectResponse
    {
        $data = $request->validatedData();

        try {
            DB::beginTransaction();
    
            $template->update([
                'name' => $data['name'],
                'show_name' => $data['show_name'],
                'description' => $data['description'],
            ]);

            if($data['format']) {
                $template->updateFormat($data['format']);
            }

            if($data['src']) {
                $template->updateOrCreateSrc($data['src']);
            }
    
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
    
            return back()->with('error', 'テンプレートの更新に失敗しました。');
        }

        return redirect()->back()->with('success', 'テンプレートを更新しました。');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $template = Template::find($request->delete_id);

        if(!$template) {
            return redirect()->route('app.template.index')->with('error', 'テンプレートが見つかりません。');
        }

        try {
            DB::beginTransaction();

            $template->deleteFormat();
    
            $template->deleteSrc();
    
            $template->delete();

            DB::commit();
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
    
            return redirect()->route('app.template.index')->with('error', 'テンプレートの削除に失敗しました。');
        }


        return redirect()->route('app.template.index')->with('success', 'テンプレートを削除しました。');
    }
}
