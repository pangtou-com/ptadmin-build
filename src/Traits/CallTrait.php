<?php

declare(strict_types=1);
/**
 * Author: Zane
 * Email: 873934580@qq.com
 * Date: 2023/11/15.
 */

namespace PTAdmin\Build\Traits;

use Illuminate\Support\Str;
use PTAdmin\Build\Exception\BuilderException;

trait CallTrait
{
    public function __call($name, $arguments)
    {
        return static::{$name}(...$arguments);
    }

    public static function __callStatic($name, $arguments)
    {
        $className = 'PTAdmin\\Build\\Components\\'.Str::studly($name);

        try {
            $class = (new \ReflectionClass($className))->newInstance(...$arguments);
        } catch (\ReflectionException $e) {
            throw new BuilderException("【{$name}】Unknown");
        }

        return $class;
    }
}
