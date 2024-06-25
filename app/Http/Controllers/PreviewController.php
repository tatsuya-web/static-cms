<?php

namespace App\Http\Controllers;

use App\Engine\Engine;
use App\Models\Tree;
use Illuminate\Support\Facades\Storage;

class PreviewController extends Controller
{
    public function __invoke(string $any = null)
    {
        if (is_null($any)) {
            // any が指定されていない場合は、トップページを表示
            $tree = Tree::getIndexHTML();
        } 
        // anyが/_uploads/以下の場合は、そのまま表示
        elseif (strpos($any, '_uploads/') === 0) {
            // htmlドライバーの_uploads内のファイルを表示する
            return response()->file(Storage::disk('html')->path($any));
        }else {
            // any が指定されている場合は、該当するページを表示
            $tree = Tree::getTreeByPath($any);
        }

        if (is_null($tree)) {
            abort(404);
        }

        $file = $tree->media->file;

        if ($tree->is_html) {

            $engine = Engine::factory();

            $file = $engine->renderString($file, []);
        }

        // ファイルをそのまま表示
        return response($file)
            ->header('Content-Type', $tree->media->mime_type);
    }
}
