<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'format',
        'src',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
