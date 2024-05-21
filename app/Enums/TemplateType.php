<?php

namespace App\Enums;

enum TemplateType : string
{
    case Common = 'common';
    case Page = 'page';

    public function isCommon(): bool
    {
        return $this === self::Common;
    }

    public function isPage(): bool
    {
        return $this === self::Page;
    }
}
