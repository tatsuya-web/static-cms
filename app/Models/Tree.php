<?php

namespace App\Models;

use App\Enums\TreeType;
use App\Enums\TreeStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Tree extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'status',
        'user_id',
        'parent_id',
        'template_id',
    ];

    protected $casts = [
        'type' => TreeType::class,
        'status' => TreeStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function children()
    {
        return $this->hasMany(Tree::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(Tree::class, 'parent_id');
    }

    public function media()
    {
        return $this->hasOne(Media::class, 'tree_id');
    }

    public function getIsFileAttribute()
    {
        return $this->type->isFile();
    }

    public function getIsFolderAttribute()
    {
        return $this->type->isFolder();
    }

    /*
    * rootノードを取得
    * @return \Illuminate\Database\Eloquent\Collection
    */
    public static function getRootNodes()
    {
        return self::whereNull('parent_id')->get();
    }

    /*
    * トップ
    * @return \App\Models\Tree
    */
    public static function getIndexHTML(): ?Tree
    {
        return self::whereNull('parent_id')->where('name', 'index.html')->first();
    }

    /*
    * パスからノードを取得
    *
    * @param string $path
    * @return \App\Models\Tree
    */
    public static function getTreeByPath(string $path): ?Tree
    {
        // $pathの末尾が'/'または拡張子がない場合はindex.htmlを付与
        if(substr($path, -1) === '/' || strpos($path, '.') === false) {
            $path .= '/index.html';
        }

        $path = explode('/', $path);

        $tree = self::whereNull('parent_id')->where('name', $path[0])->first();

        if(!$tree) {
            abort(404);
        }

        array_shift($path);

        foreach($path as $name) {
            $tree = $tree->children()->where('name', $name)->first();

            if(!$tree) {
                return null;
            }
        }

        return $tree;
    }

    /*
    * パスを取得
    *
    * @return string
    */
    public function getPath(): string
    {
        $path = $this->is_file ? '' : $this->name;

        $parent = $this->parent;

        while($parent) {
            $path = $parent->name . '/' . $path;
            $parent = $parent->parent;
        }

        return $path;
    }

    /*
    * フォルダを作成
    *
    * @return void
    */
    public function makeFileOrFolder(UploadedFile $file = null)
    {
        $path = $this->getPath();

        if($this->is_folder && Storage::disk('html')->exists($path)) {
            throw new \Exception('File or Folder already exists');
        }

        if($this->is_file && $file && Storage::disk('html')->exists($path . '/' . $file->getClientOriginalName())) {
            throw new \Exception('File or Folder already exists');
        }

        if($file) {

            // 圧縮ファイルの場合解凍してメディアを再起的に作成
            if($file->getClientMimeType() === 'application/zip') {
                $zip = new \ZipArchive();
                $zip->open($file->getPathname());
                $zip->extractTo(storage_path('app/extracted'));
                $zip->close();

                $files = Storage::disk('local')->allFiles('extracted');

                foreach($files as $file) {
                    // フォルダーの場合再帰的にフォルダを作成
                    if(is_dir(storage_path('app/' . $file))) {
                        $folder = $this->children()->create([
                            'name' => $file->getClientOriginalName(),
                            'type' => TreeType::Folder,
                            'status' => TreeStatus::Published,
                            'user_id' => auth()->id(),
                            'parent_id' => $this->id,
                            'template_id' => null,
                        ]);

                        $folder->makeFolder($file);
                    }
                    else {
                        $this->media()->create([
                            'name' => $file->getClientOriginalName(),
                            'path' => $path,
                            'mime' => $file->getMimeType(),
                            'size' => $file->getSize(),
                        ]);
            
                        Storage::disk('html')->putFileAs($path, new UploadedFile($file, $file->getClientOriginalName()), $file->getClientOriginalName());
                    }
                }

                Storage::disk('local')->deleteDirectory('extracted');
            }
            else {
                $this->media()->create([
                    'name' => $file->getClientOriginalName(),
                    'path' => $path . '/' . $file->getClientOriginalName(),
                    'mime' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ]);
    
                Storage::disk('html')->putFileAs($path, $file, $file->getClientOriginalName());
            }

        } else {
            Storage::disk('html')->makeDirectory($path);
        }
    }

    /*
    * ファイルを更新
    *
    * @return void
    */
    public function updateFile(UploadedFile $file)
    {
        $path = $this->getPath();

        $media_name = $this->media->name;

        if($this->is_file && Storage::disk('html')->exists($path . '/' . $this->media->name)) {
            $this->media()->delete();
            Storage::disk('html')->delete($path . '/' . $this->media->name);
        }

        $this->media()->create([
            'name' => $media_name,
            'path' => $path . '/' . $media_name,
            'mime' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ]);

        //ファイル名が変更されて居ても元のファイル名で保存する
        Storage::disk('html')->putFileAs($path, $file, $this->media->name);
    }

    /*
    * ファイルまたはフォルダを削除 フォルダの場合childrenがある場合は削除できない
    *
    * @return void
    */
    public function deleteFileOrFolder()
    {
        $path = $this->getPath();

        if($this->is_folder && $this->children()->exists()) {
            throw new \Exception('Directory not empty');
        }

        if($this->is_file && Storage::disk('html')->exists($path . '/' . $this->media->name)) {
            $this->media()->delete();
            Storage::disk('html')->delete($path . '/' . $this->media->name);
        }

        if($this->is_folider && Storage::disk('html')->exists($path)) {
            Storage::disk('html')->deleteDirectory($path);
        }
    }
}
