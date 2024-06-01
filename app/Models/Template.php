<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use App\Enums\TemplateType;
use App\Enums\TreeStatus;
use App\Enums\TreeType;
use App\Enums\TemplateFormat;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log as FacadesLog;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'show_name',
        'single_value_name',
        'multi_value_name',
        'description',
        'user_id',
    ];

    protected $casts = [
        'type' => TemplateType::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function format()
    {
        return $this->hasOne(Media::class, 'format_id');
    }

    public function src()
    {
        return $this->hasOne(Media::class, 'src_id');
    }

    public function tree()
    {
        return $this->hasOne(Tree::class);
    }

    public function contents()
    {
        return [];
    }

    /*
    * テンプレートのフォーマットのjsonファイルを読み込みコレクションで返す
    *
    * @return \Illuminate\Support\Collection
    */
    public function getFormatItemsAttribute(): Collection | bool
    {
        $format = collect(json_decode(Storage::disk('template')->get($this->format->path)));

        if($format->isEmpty()) {
            return collect();
        }

        $format_items = collect();

        foreach($format['items'] as $item) {
            if(! TemplateFormat::exist($item->type)) {
                return false;
            }
            $format_items->push(new Format($item));
        }

        return collect($format_items);
    }

    /*
    * テンプレートをロードして正常に読み込めたかどうかを返す
    *
    * @return bool
    */
    public function getIsValidedFormatAttribute(): bool
    {
        if($this->format_items === false) {
            return false;
        }

        return true;
    }

    /*
    * 共通テンプレートの一覧を取得
    *
    * @return \Illuminate\Database\Eloquent\Collection
    */
    public static function getCommons()
    {
        return self::where('type', TemplateType::Common)->get();
    }

    /*
    * ページテンプレートの一覧を取得
    *
    * @return \Illuminate\Database\Eloquent\Collection
    */
    public static function getPages()
    {
        return self::where('type', TemplateType::Page)->get();
    }

    /*
    * テンプレートのソースコードのjsonファイルを読み込みindexがtrueのものだけを返す
    *
    * @return array
    */
    public function hasIndexLabels(): array
    {
        $format = $this->format_items;

        if(!isset($format)) {
            return [];
        }

        $index_labels = [];

        foreach($format as $item) {
            if($item->isIndex()) {
                $index_labels[] = $item->getLabel();
            }
        }

        return $index_labels;
    }

    /*
    * テンプレートのフォーマットを作成
    *
    * @param UploadedFile $file
    * @return void
    */
    public function makeFormat(UploadedFile $file): void
    {
        $format = $this->format()->create([
            'name' => $file->getClientOriginalName(),
            'path' => $this->type->value . '/' . $file->getClientOriginalName(),
            'mime' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);

        Storage::disk('template')->put($format->path, $file->get());
    }

    /*
    * テンプレートのフォーマットを更新
    *
    * @param UploadedFile $file
    * @return void
    */
    public function updateFormat(UploadedFile $file): void
    {
        if($this->format) {
            $this->format->delete();
            Storage::disk('template')->delete($this->format->path);
        }

        $this->makeFormat($file);
    }

    /*
    * テンプレートのフォーマットを削除
    *
    * @return void
    */
    public function deleteFormat(): void
    {
        if($this->format) {
            $this->format->delete();
            Storage::disk('template')->delete($this->format->path);
        }
    }

    /*
    * テンプレートのソースコードを登録
    *
    * @param UploadedFile $file
    * @return void
    */
    public function makeSrc(UploadedFile $file): void
    {
        $src = $this->src()->create([
            'name' => $file->getClientOriginalName(),
            'path' => $this->type->value . '/' . $file->getClientOriginalName(),
            'mime' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);

        Storage::disk('template')->put($src->path, $file->get());
    }

    /*
    * テンプレートのソースコードを更新
    *
    * @param UploadedFile $file
    * @return void
    */
    public function updateOrCreateSrc(UploadedFile $file): void
    {
        if($this->src) {
            $this->src->delete();
            Storage::disk('template')->delete($this->src->path);
        }

        $this->makeSrc($file);
    }

    /*
    * ツリーを作成
    * @param string $path
    *
    * @return void
    */
    public function makeTree(string $path): void
    {
        if($path === null || $path === '') {
            $is_tree = Tree::where('name', $this->name)->whereNull('parent_id')->first();

            if($is_tree) {
                throw new \Exception(config('error.trre_has_alerady_page_template'));
            }

            $this->tree()->create([
                'name' => $this->name,
                'type' => TreeType::Folder,
                'status' => TreeStatus::Published,
                'user_id' => $this->user_id,
            ]);
        } else {
            $path = explode('/', $path);

            DB::transaction(function() use ($path) {
                $parent = null;

                foreach($path as $key => $name) {
                    // トップ階層で同じ名前のツリーが存在する場合は作成せずにスキップ
                    $arleady_tree = $parent === null ?
                                        Tree::where('name', $name)->whereNull('parent_id')->first() :
                                        Tree::where('name', $name)->where('parent_id', $parent->id)->first();

                    if($arleady_tree && $arleady_tree->template_id && $key == count($path) - 1) {
                        throw new \Exception(config('error.trre_has_alerady_page_template'));
                    }
    
                    if($arleady_tree && !$arleady_tree->template_id && $key == count($path) - 1) {
                        $arleady_tree->update([
                            'template_id' => $this->id,
                        ]);
                        $parent = $arleady_tree;
                        continue;
                    }

                    if($arleady_tree) {
                        $parent = $arleady_tree;
                        continue;
                    }
    
                    $tree = Tree::create([
                        'name' => $name,
                        'type' => TreeType::Folder,
                        'status' => TreeStatus::Published,
                        'user_id' => $this->user_id,
                        'parent_id' => $parent ? $parent->id : null,
                        'template_id' => $key == count($path) - 1 ? $this->id : null,
                    ]);

                    $tree->makeFileOrFolder();
    
                    $parent = $tree;
                }
            });
        }
    }

    /*
    * テンプレートのソースコードを削除
    *
    * @return void
    */
    public function deleteSrc(): void
    {
        if($this->src) {
            $this->src->delete();
            Storage::disk('template')->delete($this->src->path);
        }
    }
}
