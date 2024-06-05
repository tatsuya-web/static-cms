<?php

namespace App\Rules;

use App\Enums\TemplateFormat;
use App\Models\Format;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TemplateFormatRule implements ValidationRule
{
    private Format $format;

    public function __construct(Format $format)
    {
        $this->format = $format;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // 必須チェック
        if ($this->format->isRequired() && empty($value)) {
            $fail('必須項目です。');
        }

        // urlだったらURL形式かどうかチェック
        if ($this->format->getType() === TemplateFormat::Url) {
            if (!filter_var($value, FILTER_VALIDATE_URL)) {
                $fail('URLの形式が正しくありません。');
            }
        }

        // emailだったらemail形式かどうかチェック
        if ($this->format->getType() === TemplateFormat::Email) {
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $fail('メールアドレスの形式が正しくありません。');
            }
        }

        // telだったらtel形式かどうかチェック
        if ($this->format->getType() === TemplateFormat::Tel) {
            if (!preg_match('/\A\d{2,4}-\d{2,4}-\d{3,4}\z/', $value)) {
                $fail('電話番号の形式が正しくありません。');
            }
        }

        // dateだったら日付形式かどうかチェック
        if ($this->format->getType() === TemplateFormat::Date) {
            if (!preg_match('/\A\d{4}-\d{2}-\d{2}\z/', $value)) {
                $fail('日付の形式が正しくありません。');
            }
        }

        // timeだったら時間形式かどうかチェック
        if ($this->format->getType() === TemplateFormat::Time) {
            if (!preg_match('/\A\d{2}:\d{2}\z/', $value)) {
                $fail('時間の形式が正しくありません。');
            }
        }

        // datetimeだったら日時形式かどうかチェック
        if ($this->format->getType() === TemplateFormat::DateTime) {
            if (!preg_match('/\A\d{4}-\d{2}-\d{2} \d{2}:\d{2}\z/', $value)) {
                $fail('日時の形式が正しくありません。');
            }
        }

        // numberだったら数値形式かどうかチェック
        if ($this->format->getType() === TemplateFormat::Number) {
            if (!is_numeric($value)) {
                $fail('数値の形式が正しくありません。');
            }
        }

        // max値チェック
        if ($this->format->getType() === TemplateFormat::Number && $this->format->hasMin()) {
            if ($value > $this->format->getMax()) {
                $fail('最大値を超えています。');
            }
        }

        // min値チェック
        if ($this->format->getType() === TemplateFormat::Number && $this->format->hasMin()) {
            if ($value < $this->format->getMin()) {
                $fail('最小値を下回っています。');
            }
        }

        // fileだったらファイル形式かどうかチェック
        if ($this->format->getType() === TemplateFormat::File) {
            if (!is_file($value)) {
                $fail('ファイル形式が正しくありません。');
            }
        }

        // fileの場合はaccept属性をチェック
        if ($this->format->getType() === TemplateFormat::File && $this->format->hasAccept()) {
            $accept = $this->format->getAccept();

            // imageの場合は image/* ならばOK
            if ($accept === 'image/*') {
                if (!preg_match('/\Aimage\/\w+\z/', mime_content_type($value))) {
                    $fail('画像形式が正しくありません。');
                }
            }

            // videoの場合は video/* ならばOK
            if ($accept === 'video/*') {
                if (!preg_match('/\Avideo\/\w+\z/', mime_content_type($value))) {
                    $fail('動画形式が正しくありません。');
                }
            }

            // documentの場合は application/pdf, application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.presentationml.presentation ならばOK
            if ($accept === 'application/pdf, application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.presentationml.presentation') {
                if (!preg_match('/\Aapplication\/(pdf|msword|vnd\.ms-excel|vnd\.ms-powerpoint|vnd\.openxmlformats-officedocument\.wordprocessingml\.document|vnd\.openxmlformats-officedocument\.spreadsheetml\.sheet|vnd\.openxmlformats-officedocument\.presentationml\.presentation)\z/', mime_content_type($value))) {
                    $fail('ドキュメント形式が正しくありません。');
                }
            }
        }

        return;
    }
}
