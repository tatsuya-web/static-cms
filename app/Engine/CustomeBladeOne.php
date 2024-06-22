<?php

namespace App\Engine;

use eftec\bladeone\BladeOne;

class CustomeBladeOne extends BladeOne
{
    public function __construct($views = null, $compiledFolder = null, $mode = 0)
    {
        parent::__construct($views, $compiledFolder, $mode);
    }

    protected function compilePhp($expression): string
    {
        // 何もしないようにします
        return '';
    }
}