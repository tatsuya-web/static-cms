<?php

namespace App\Enums;

enum TreeType : string
{
    case File = 'file';
    case Folder = 'folder';

    public static function getTreeTypeName(string $type): string
    {
        return match ($type) {
            'file' => 'ファイル',
            'folder' => 'フォルダ',
            default => '不明',
        };
    }

    public function getNameJa(): string
    {
        return match ($this->value) {
            'file' => 'ファイル',
            'folder' => 'フォルダ',
            default => '不明',
        };
    }

    public function isFile(): bool
    {
        return $this === self::File;
    }

    public function isFolder(): bool
    {
        return $this === self::Folder;
    }
}
