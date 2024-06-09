<?php

namespace App\Models;

use App\Engine\Engine;
use App\Enums\TreeStatus;
use App\Enums\TreeType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Content extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_id',
        'user_id',
    ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function values()
    {
        return $this->hasMany(Value::class);
    }

    /*
    * htmlファイルの名前を取得
    * @return string
    */
    public function getFileNameAttribute(): string
    {
        return $this->template->name . '_content' . $this->id . '.html';
    }

    /*
    * 引数$nameで指定された値に一致するnameのものをValuesテーブルから取得
    * @param string $name
    * @return string
    */
    public function getIndexValue(string $name): string
    {
        return $this->values->where('name', $name)->first()?->value ?? '';
    }

    /*
    * 引数$nameで指定された値に一致するnameのものをValuesテーブルから取得
    * ただしcheckboxの場合は配列で返す
    * @param Format $format
    * @return string|array
    */
    public function getValue(Format $format)
    {
        $value = $this->values->where('name', $format->getName())->first()?->value ?? '';

        if ($format->getType() === 'checkbox') {
            return explode(':', $value);
        }

        return $value;
    }

    /*
    * renderメソッドから帰ってきた値を$templateの $name + '_content' + $id . '.html' として保存
    * @return void
    */
    public function build(): void
    {
        $content = $this->render();

        // templateのtreeに保存されているパスにhtmlファイルを保存
        $path = $this->template->tree->getPath();

        $tree = Tree::create([
            'name' => $this->file_name,
            'type' => TreeType::File,
            'status' => TreeStatus::Published,
            'user_id' => auth()->id(),
            'parent_id' => $this->template->tree->id,
            'template_id' => null,
        ]);

        $result = Storage::disk('html')->put($path . '/' . $this->file_name, $content);

        if ($result) {
            $tree->media()->create([
                'name' => $this->file_name,
                'path' => $path . '/' . $this->file_name,
                'mime' => 'text/html',
                'size' => strlen($content),
            ]);
        }
    }

    /*
    * bladeファイルに値を与えhtmlを生成
    * @return string
    */
    public function render(): string
    {
        $engine = Engine::factory();

        $view = $this->template->name;

        $compacts = [];

        foreach ($this->values as $value) {
            $compacts[$value->name] = $value->value;
        }

        $result = $engine->render($view, $compacts);

        return $result;
    }

    /*
    * htmlファイルを削除
    * @return void
    */
    public function deleteHtml(): void
    {
        $path = $this->template->tree->getPath();

        $tree = $this->template->tree->children->where('name', $this->file_name)->first();

        if ($tree) {
            Storage::disk('html')->delete($path . '/' . $this->file_name);
            $tree->media->delete();
            $tree->delete();
        }
    }

}
