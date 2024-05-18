<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'mime',
        'size',
        'tree_id',
    ];

    public function tree()
    {
        return $this->belongsTo(Tree::class);
    }

    /*
    * ファイルのパスを取得
    *
    * @return string
    */
    public function getFileAttribute(): string
    {
        return Storage::disk('html')->get($this->path);
    }
}
