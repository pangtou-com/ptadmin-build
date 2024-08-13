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

use PTAdmin\Html\Html;

/**
 * 按钮组件.
 */
class Button extends Component
{
    protected $type = 'button';

    public function __construct($title = 'submit', $type = 'button')
    {
        parent::__construct();
        $this->rule['title'] = $title;
        $this->addAttribute('type', $type);
        $this->addAttribute('class', 'layui-btn');
    }

    /**
     * 解析通过规则设置的属性.
     */
    public function parser(): void
    {
        if ($this->rule) {
            foreach ($this->rule as $key => $val) {
                if (method_exists($this, $key)) {
                    $this->{$key}($val);

                    continue;
                }
                $this->addAttribute($key, $val);
            }
        }
    }

    public function setRule($rule): Component
    {
        $this->rule = $rule;

        return $this;
    }

    public function render(): string
    {
        $this->parser();
        $type = $this->getAttribute('type', 'button');
        if ('submit' === $type && !blank($this->getAttribute('lay-filter'))) {
            $this->filter();
        }

        switch ($type) {
            case 'submit':
                return Html::submit($this->getTitle(), $this->getAttributes());

            case 'reset':
                return Html::reset($this->getTitle(), $this->getAttributes());

            default:
                return Html::button($this->getTitle(), $this->getAttributes());
        }
    }

    /**
     * 设置按钮名称.
     *
     * @param $title
     *
     * @return Button
     */
    public function title($title): self
    {
        $this->rule['title'] = (string) $title;

        return $this;
    }

    /**
     * 设置按钮大小, 可选值: default / lg / sm / xs.
     *
     * @param $size
     *
     * @return Button
     */
    public function size($size): self
    {
        $sizes = ['default', 'lg', 'sm', 'xs'];
        if (\in_array($size, $sizes, true)) {
            foreach ($sizes as $val) {
                $className = 'layui-btn-'.$val;
                if ('default' === $val) {
                    continue;
                }
                $this->removeClassName($className);
            }
            if ('default' !== $size) {
                $this->addAttribute('class', 'layui-btn-'.$size);
            }
        }

        return $this;
    }

    /**
     * 主题, 可选值: default / primary / normal / warm / danger / disabled.
     *
     * @param $theme
     * @param string $t 默认值: bg 可选值为：border
     *
     * @return $this
     */
    public function theme($theme, string $t = 'bg'): self
    {
        $themeNames = ['default' => '', 'primary' => 'blue', 'normal' => 'purple', 'danger' => 'red', 'warm' => 'orange', 'disabled' => 'disabled'];
        if (\in_array($theme, $themeNames, true)) {
            foreach ($themeNames as $val) {
                $className = "layui-btn-{$t}-{$val}";
                $this->removeClassName($className);
            }
            if ('default' !== $theme) {
                $this->addAttribute('class', "layui-btn-{$t}-{$themeNames[$theme]}");
            }
        }

        return $this;
    }

    /**
     * 设置按钮提交响应事件.
     *
     * @param string $name
     *
     * @return $this
     */
    public function filter(string $name = 'PT-submit'): self
    {
        $this->addAttribute('lay-filter', $name);
        $this->addAttribute('lay-submit', true);

        return $this;
    }

    /**
     * 设置按钮是否为圆角.
     *
     * @param bool $round
     *
     * @return $this
     */
    public function round(bool $round = true): self
    {
        $className = 'layui-btn-radius';
        if ($round) {
            $this->addAttribute('class', $className);
        } else {
            $this->removeClassName($className);
        }

        return $this;
    }

    private function getTitle()
    {
        return $this->rule['title'] ?? 'button';
    }
}
