<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
