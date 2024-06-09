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

    public function values()
    {
        return $this->hasMany(Value::class);
    }

    public function contents()
    {
        return $this->hasMany(Content::class);
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
    * テンプレートのbladeの名前を返す
    *
    * @return string
    */
    public function getBladeNameAttribute(): string
    {
        $prefix = 'page_';

        if($this->type->isCommon()) {
            $prefix = 'cmn_';
        }

        return $prefix . $this->name . '.blade.php';
    }

    /*
    * テンプレートのbladeファイルが存在するかどうかを返す
    *
    * @return bool
    */
    public function getIsExistsBladeAttribute(): bool
    {
        return Storage::disk('views')->exists($this->blade_name);
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
    * labelの配列を返す
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
    * テンプレートのソースコードのjsonファイルを読み込みindexがtrueのものだけを返す
    * nameの配列を返す
    *
    * @return array
    */
    public function hasIndexNames(): array
    {
        $format = $this->format_items;

        if(!isset($format)) {
            return [];
        }

        $index_names = [];

        foreach($format as $item) {
            if($item->isIndex()) {
                $index_names[] = $item->getName();
            }
        }

        return $index_names;
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

        $this->updateOrCreateBlade();
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
    * テンプレートのソースコードを削除
    *
    * @return void
    */
    public function deleteSrc(): void
    {
        if($this->src) {
            $this->src->delete();
            Storage::disk('template')->delete($this->src->path);

            if($this->is_exists_blade) {
                Storage::disk('views')->delete($this->blade_name);
            }
        }
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
    * Content,Valuesを作成する
    *
    * @param array $values
    * @return void
    */
    public function makeContent(array $values): bool
    {
        DB::transaction(function() use ($values) {

            $content = $this->contents()->create([
                'user_id' => auth()->user()->id,
            ]);

            foreach($values as $key => $value) {

                $format = $this->format_items->filter(function($item) use ($key) {
                    return $item->getName() === $key;
                })->first();

                if(! $format) {
                    FacadesLog::error(config('error.invalid_format'));
                    return false;
                }

                // $valueが配列の場合は、:で区切って保存する
                if(is_array($value)) {
                    $value = implode(':', $value);
                }

                $content->values()->create([
                    'format' => $format->getType(),
                    'name' => $key,
                    'value' => $value,
                ]);
            }
        });

        return true;
    }

    /*
    * Valuesを更新する
    *
    * @param Content $content
    * @param array $values
    * @return bool
    */
    public function updateContent(Content $content, array $values): bool
    {
        DB::transaction(function() use ($content, $values) {

            $content->values()->delete();

            foreach($values as $key => $value) {

                $format = $this->format_items->filter(function($item) use ($key) {
                    return $item->getName() === $key;
                })->first();

                if(! $format) {
                    FacadesLog::error(config('error.invalid_format'));
                    return false;
                }

                // $valueが配列の場合は、:で区切って保存する
                if(is_array($value)) {
                    $value = implode(':', $value);
                }

                $content->values()->create([
                    'format' => $format->getType(),
                    'name' => $key,
                    'value' => $value ?? '',
                ]);
            }
        });

        return true;
    }

    /*
    * viewで表示するためのbladeファイルを作成する 
    * 保存先はviewsディスク
    * srcファイルが存在しない場合は作成しない
    * 既にbladeファイルが存在する場合は上書きする
    * templateの名前(name)に接頭辞cmn_をつける
    *
    * @return void
    */
    public function updateOrCreateBlade(): void
    {
        if(! $this->src) {
            return;
        }

        $src = Storage::disk('template')->get($this->src->path);

        Storage::disk('views')->put($this->blade_name, $src);
    }
}
