<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tree\TreeCreateRequest;
use App\Http\Requests\Tree\TreeUpdateRequest;
use Illuminate\Http\Request;
use \Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Tree;
use App\Enums\TreeStatus;
use App\Enums\TreeType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SiteTreeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $trees = Tree::getRootNodes();

        return view('app.site_tree.index', compact('trees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(?Tree $tree): View
    {
        return view('app.site_tree.create', compact('tree'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param Tree|null $tree
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TreeCreateRequest $request, ?Tree $tree): RedirectResponse
    {
        $data = $request->validatedData();
        $type = TreeType::from($data['type']);
        
        try {
            DB::beginTransaction();
            $new_tree = Tree::create([
                // ファイル名に拡張子を付与
                'name' => $type->isFile() ? $data['file']->getClientOriginalName() : $data['name'],
                'type' => $type,
                'status' => TreeStatus::Published,
                'user_id' => auth()->id(),
                'parent_id' => $tree ? $tree->id : null,
                'template_id' => null,
            ]);

            $new_tree->makeFileOrFolder($data['file'] ?? null);

            DB::commit();
        }
        catch(\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return redirect()->route('app.site_tree.index')->with('error', $type->getNameJa() . 'の作成に失敗しました');
        }

        return redirect()->route('app.site_tree.index')->with('success', $type->getNameJa() . 'ファオルダを作成しました');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tree  $tree
     * @return \Illuminate\View\View
     */
    public function edit(Tree $tree): View
    {
        return view('app.site_tree.edit', compact('tree'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param Tree $tree
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(TreeUpdateRequest $request, Tree $tree): RedirectResponse
    {
        $file = $request->file('file');

        if($file) {
            try {
                $tree->updateFile($file);
            }
            catch(\Exception $e) {
                Log::error($e->getMessage());
                return redirect()->back()->with('error', 'ファイルの更新に失敗しました');
            }
        }

        $type = TreeType::from($tree->type->value);

        return redirect()->back()->with('success', $type->getNameJa() . 'を更新しました');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function destroy(Request $request): RedirectResponse
    {
        $tree = Tree::findOrfail($request->delete_id);

        if($tree->is_file) {
            try {
                $tree->deleteFileOrFolder();
            }
            catch(\Exception $e) {
                Log::error($e->getMessage());
                return redirect()->route('app.site_tree.index')->with('error', 'ファイルの削除に失敗しました');
            }

            if($tree->is_file) {
                $tree->media()->delete();
            }

            $tree->delete();

            return redirect()->route('app.site_tree.index')->with('success', 'ファイルを削除しました');
        }

        try {
            $tree->deleteFileOrFolder();
        }
        catch(\Exception $e) {
            $messege = $e->getMessage();
            if($messege === config('error.directory_not_empty')) {
                return redirect()->route('app.site_tree.index')->with('error', 'フォルダが空ではありません');
            }
            Log::error($e->getMessage());
            return redirect()->route('app.site_tree.index')->with('error', 'フォルダの削除に失敗しました');
        }

        if($tree->media) {
            $tree->media()->delete();
        }

        $tree->delete();

        return redirect()->route('app.site_tree.index')->with('success', '削除しました');
    }
}
