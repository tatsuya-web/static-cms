<?php

namespace App\Enums;

enum TreeStatus : string
{
    case Draft = 'draft';
    case Published = 'published';

    public static function getTreeStatusName(string $status): string
    {
        return match ($status) {
            'draft' => '下書き',
            'published' => '公開',
            default => '不明',
        };
    }

    public function getNameJa(): string
    {
        return match ($this->value) {
            'draft' => '下書き',
            'published' => '公開',
            default => '不明',
        };
    }
}
