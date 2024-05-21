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
        'format_id',
        'src_id',
    ];

    public function tree()
    {
        return $this->belongsTo(Tree::class);
    }

    public function formatTemplate()
    {
        return $this->belongsTo(Template::class, 'format_id');
    }

    public function srcTemplate()
    {
        return $this->belongsTo(Template::class, 'src_id');
    }

    /*
    * ファイルのパスを取得
    *
    * @return string
    */
    public function getFileAttribute(): string
    {
        $disk = 'html';

        if(isset($this->format_id) || isset($this->src_id)) {
            $disk = 'template';
        }

        return Storage::disk($disk)->get($this->path);
    }
}
