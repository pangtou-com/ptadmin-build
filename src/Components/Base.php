<?php

declare(strict_types=1);

/**
 *  PTAdmin
 *  ============================================================================
 *  版权所有 2022-2024 重庆胖头网络技术有限公司，并保留所有权利。
 *  网站地址: https://www.pangtou.com
 *  ----------------------------------------------------------------------------
 *  尊敬的用户，
 *     感谢您对我们产品的关注与支持。我们希望提醒您，在商业用途中使用我们的产品时，请务必前往官方渠道购买正版授权。
 *  购买正版授权不仅有助于支持我们不断提供更好的产品和服务，更能够确保您在使用过程中不会引起不必要的法律纠纷。
 *  正版授权是保障您合法使用产品的最佳方式，也有助于维护您的权益和公司的声誉。我们一直致力于为客户提供高质量的解决方案，并通过正版授权机制确保产品的可靠性和安全性。
 *  如果您有任何疑问或需要帮助，我们的客户服务团队将随时为您提供支持。感谢您的理解与合作。
 *  诚挚问候，
 *  【重庆胖头网络技术有限公司】
 *  ============================================================================
 *  Author:    Zane
 *  Homepage:  https://www.pangtou.com
 *  Email:     vip@pangtou.com
 */

namespace PTAdmin\Build\Components;

use Illuminate\Support\Str;
use PTAdmin\Build\Service\Kernel;

abstract class Base
{
    public $id;

    /** @var Kernel 内核 */
    public $kernel;

    /** @var array 属性集合 */
    protected $attributes = [];

    /** @var Base 父节点 */
    protected $parent;

    /** @var array 孩子们 */
    protected $children = [];

    public function __construct()
    {
        $this->id = Str::uuid()->toString();
    }

    public function __toString()
    {
        return $this->render();
    }

    public function setRoot($kernel): self
    {
        $this->kernel = $kernel;

        return $this;
    }

    /**
     * 添加孩子.
     *
     * @param $child
     *
     * @return $this
     */
    public function addChild($child): self
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * 获取孩子们.
     *
     * @return array
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    /**
     * 在指定位置插入孩子.
     *
     * @param $child
     * @param $index
     *
     * @return $this
     */
    public function insertChild($child, $index): self
    {
        array_splice($this->children, $index, 0, $child);

        return $this;
    }

    /**
     * 设置style属性.支持多种格式<br/>
     * 1、style('color:red;font-size:12px')<br/>
     * 2、style('color:red', 'font-size:12px')<br/>
     * 3、style(['color' => 'red', 'font-size' => '12px']).
     *
     * @param array|mixed|string $style
     *
     * @return $this
     */
    public function style($style): self
    {
        if (\func_num_args() > 1) {
            $temp = \func_get_args();
            $style = [];
            foreach ($temp as $item) {
                $style[] = $item;
            }
        }
        if (!\is_array($style)) {
            $style = [$style];
        }
        $this->attributes['style'] = array_merge($this->attributes['style'] ?? [], $style);

        return $this;
    }

    /**
     * 设置class属性.支持多种格式<br/>
     * 1、class('class1 class2')<br/>
     * 2、class('class1', 'class2')<br/>
     * 3、class(['class1', 'class2']).
     *
     * @param array|mixed|string $class
     *
     * @return $this
     */
    public function class($class): self
    {
        if (\func_num_args() > 1) {
            $temp = \func_get_args();
            $class = [];
            foreach ($temp as $item) {
                $class[] = $item;
            }
        }
        if (!\is_array($class)) {
            $class = [$class];
        }
        $this->attributes['class'] = array_merge($this->attributes['class'] ?? [], $class);

        return $this;
    }

    /**
     * 设置属性信息.
     *
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function setAttribute($key, $value): self
    {
        $this->attributes[$key] = $value;

        return $this;
    }

    /**
     * 添加属性信息.
     *
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function addAttribute($key, $value): self
    {
        if ('class' === $key) {
            $this->class($value);

            return $this;
        }
        if ('style' === $key) {
            $this->style($value);

            return $this;
        }
        if ('data' !== $key) {
            $this->setAttribute($key, $value);

            return $this;
        }

        if (!isset($this->attributes[$key])) {
            $this->attributes[$key] = [];
        } else {
            if (!\is_array($this->attributes[$key])) {
                $this->attributes[$key] = [$this->attributes[$key]];
            }
        }
        $this->attributes[$key][] = $value;

        return $this;
    }

    /**
     * 获取属性信息.
     *
     * @param $key
     * @param $default
     *
     * @return null|mixed
     */
    public function getAttribute($key, $default = null)
    {
        return $this->attributes[$key] ?? $default;
    }

    /**
     * 获取所有属性信息.
     *
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * 解析属性信息.
     */
    abstract public function parser(): void;

    abstract public function render(): string;

    /**
     * 移除class属性中的指定类名.
     *
     * @param $className
     */
    protected function removeClassName($className): void
    {
        $class = $this->getAttribute('class');
        $class = \is_array($class) ? $class : [$class];
        foreach ($class as $k => $val) {
            if ($val === $className) {
                unset($class[$k]);

                break;
            }
        }
        $this->setAttribute('class', $class);
    }
}
