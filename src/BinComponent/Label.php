<?php

declare(strict_types=1);

namespace PTAdmin\Build\BinComponent;

use Illuminate\View\Component;

class Label extends Component
{
    public $base;

    public function __construct($base = null)
    {
        $this->base = $base;
    }

    public function render()
    {
        return view('layui::components.label', ['base' => $this->base]);
    }
}
