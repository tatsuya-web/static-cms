<?php

namespace App\Http\Controllers;

use App\Engine\Engine;
use App\Models\Tree;

class PreviewController extends Controller
{
    public function __invoke(string $any = null)
    {
        if (is_null($any)) {
            // any が指定されていない場合は、トップページを表示
            $tree = Tree::getIndexHTML();
        } else {
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
