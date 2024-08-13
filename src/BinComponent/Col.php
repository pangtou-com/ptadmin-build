<?php

declare(strict_types=1);

namespace PTAdmin\Build\BinComponent;

use Illuminate\View\Component;

class Col extends Component
{
    public $col;

    public function __construct($col = null)
    {
        $this->col = $col;
    }

    public function render()
    {
        return view('layui::components.col');
    }
}
