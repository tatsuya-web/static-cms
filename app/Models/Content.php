<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

}
