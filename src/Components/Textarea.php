<?php

declare(strict_types=1);
/**
 * Author: Zane
 * Email: 873934580@qq.com
 * Date: 2023/11/16.
 */

namespace PTAdmin\Build\Components;

class Textarea extends Component
{
    protected $type = 'textarea';

    public function __construct(string $field = '', string $label = '', $value = null)
    {
        parent::__construct($field, $label, $value);
        $this->setAttribute('class', 'layui-textarea');
    }
}
