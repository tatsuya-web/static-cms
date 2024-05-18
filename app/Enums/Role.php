<?php

namespace App\Enums;

enum Role : string
{
    case Developper = 'developper';
    case Admin = 'admin';
    case Editor = 'editor';
    case Viewer = 'viewer';

    public static function getRoleName(string $role): string
    {
        return match ($role) {
            'developper' => '開発者',
            'admin' => '管理者',
            'editor' => '編集者',
            'viewer' => '閲覧者',
            default => '不明',
        };
    }

    public function getNameJa(): string
    {
        return match ($this->value) {
            'developper' => '開発者',
            'admin' => '管理者',
            'editor' => '編集者',
            'viewer' => '閲覧者',
            default => '不明',
        };
    }
}
