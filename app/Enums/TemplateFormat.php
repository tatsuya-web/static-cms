<?php

namespace App\Enums;

enum TemplateFormat : string
{
    case Text = 'text';
    case Url = 'url';
    case Email = 'email';
    case Tel = 'tel';
    case Date = 'date';
    case Time = 'time';
    case DateTime = 'datetime';
    case Number = 'number';
    case Textarea = 'textarea';
    case RicheEditor = 'riche_editor';
    case Select = 'select';
    case Radio = 'radio';
    case Checkbox = 'checkbox';
    case File = 'file';

    public function hasOptions(): bool
    {
        return match ($this->value) {
            'select', 'radio', 'checkbox' => true,
            default => false,
        };
    }

    public function hasPlaceholder(): bool
    {
        return match ($this->value) {
            'text', 'email', 'tel', 'date', 'time', 'datetime', 'number' , 'textarea' => true,
            default => false,
        };
    }

    public function hasAccept(): bool
    {
        return match ($this->value) {
            'file' => true,
            default => false,
        };
    }

    public static function getAcceptType(string $accept): string
    {
        return match ($accept) {
            'image' => 'image/*',
            'video' => 'video/*',
            'document' => 'application/pdf, application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.openxmlformats-officedocument.presentationml.presentation',
            '*' => '*/*',
            default => '',
        };
    }

    public static function getAcceptTypeString(string $accept): string
    {
        return match ($accept) {
            'image' => '画像',
            'video' => '動画',
            'document' => 'ドキュメント',
            '*' => '全て',
            default => '',
        };
    }

    public function hasMin(): bool
    {
        return match ($this->value) {
            'number' => true,
            default => false,
        };
    }

    public function hasMax(): bool
    {
        return match ($this->value) {
            'number' => true,
            default => false,
        };
    }
}