<?php

namespace App\Models;

use App\Enums\TemplateFormat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Value extends Model
{
    use HasFactory;

    protected $fillable = [
        'format',
        'name',
        'value',
        'template_id',
        'parent_id',
    ];

    protected $casts = [
        'format' => TemplateFormat::class,
    ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function parent()
    {
        return $this->belongsTo(Value::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Value::class, 'parent_id');
    }
}
