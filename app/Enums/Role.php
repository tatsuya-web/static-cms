<?php

namespace App\Enums;

enum Role : string
{
    case developper = 'developper';
    case Admin = 'admin';
    case Editor = 'editor';
    case Viewer = 'viewer';

    public static function getRole(string $role): string
    {
        return match ($role) {
            'developper' => '開発者',
            'admin' => '管理者',
            'editor' => '編集者',
            'viewer' => '閲覧者',
            default => '不明',
        };
    }
}
