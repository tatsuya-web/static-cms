<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StoreTreeRule implements ValidationRule
{
    protected $without = [
        '_app',
        '_config',
        '_database',
        '_upload',
        '_uploads'
    ];

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // nameがwithoutに含まれる場合はエラー
        if (in_array($value, $this->without)) {
            $fail("作成できないフォルダ名が指定されました。");
        }
    }
}
