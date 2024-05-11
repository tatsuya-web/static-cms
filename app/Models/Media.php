<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
