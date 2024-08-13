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
use PTAdmin\Build\Traits\DisabledTrait;
use PTAdmin\Build\Traits\OptionsTrait;
use PTAdmin\Build\Traits\TipsTrait;

/**
 * @method self            view(string $view)                             自定义渲染模版
 * @method self            placeholder(string $placeholder)               输入框占位文本
 * @method self            disabled(mixed $disabled = true)               禁用
 * @method self            type(string $type)                             组件类型
 * @method self            field(string $field)                           组件字段名
 * @method self            label(string $label)                           字段label
 * @method self            name(string $name)                             组件名称
 * @method self            info(string | string[] $info)                  提示信息
 * @method self            native(bool $native = true)                    是否原样生成组件,不嵌套的FormItem中
 * @method self            col(int $col)                                  组件布局规则
 * @method self            value(mixed $value)                            组件的值
 * @method self            hidden(bool $hidden = true)                    组件显示状态
 * @method self            visibility(bool $visibility = true)            组件显示状态
 * @method self            setOptions($options, $key = null, $val = null)
 * @method string          getField()                                     组件字段名
 * @method string          getLabel()                                     字段label
 * @method string          getName()                                      组件名称
 * @method string|string[] getInfo()                                      提示信息
 * @method bool            getNative()                                    是否原样生成组件,不嵌套的FormItem中
 * @method int             getCol()                                       组件布局规则
 * @method mixed           getValue()                                     组件的值
 * @method bool            getHidden()                                    组件显示状态
 * @method bool            getVisibility()                                组件显示状态
 * @method bool            getRequired()                                  是否必填
 *
 * @property string          $type       组件类型
 * @property string          $field      组件字段名
 * @property string          $label      字段label
 * @property string          $name       组件名称
 * @property string|string[] $info       提示信息
 * @property bool            $native     是否原样生成组件,不嵌套的FormItem中
 * @property int             $col        组件布局规则
 * @property mixed           $value      组件的值
 * @property bool            $hidden     组件显示状态
 * @property bool            $visibility 组件显示状态
 */
abstract class Component extends Base
{
    use DisabledTrait;
    use OptionsTrait;
    use TipsTrait;

    /** @var string 组件类型 */
    protected $type;

    /** @var string 组件渲染的模版，如不指定就按照type寻找 */
    protected $view;

    /** @var string 组件字段名. */
    protected $field;

    /** @var string 字段label */
    protected $label;

    /** @var string 组件名称. */
    protected $name;

    /** @var string|string[] 提示信息 */
    protected $info;

    /** @var bool 是否原样生成组件,不嵌套的FormItem中. */
    protected $native;

    /** @var int 组件布局规则 */
    protected $col;

    /** @var mixed 组件的值 */
    protected $value;

    /** @var bool 组件显示状态 */
    protected $hidden = true;

    /** @var bool 组件显示状态 */
    protected $visibility = true;

    /** @var bool 是否为选项类型 */
    protected $select = false;

    /** @var bool 是否必填 */
    protected $required = false;

    /** @var string 占位符 */
    protected $placeholder;

    /** @var array 原始规则 */
    protected $rule = [];

    public function __construct(string $field = '', string $label = '', $value = null)
    {
        $this->field = $field;
        $this->label = $label;
        $this->value = $value;
        parent::__construct();

        $this->setAttribute('type', $this->type);
    }

    public function __call($name, $arguments)
    {
        if (property_exists($this, $name)) {
            $this->{$name} = $arguments[0];
        }
        if (Str::startsWith($name, 'get')) {
            $name = Str::camel(Str::substr($name, 3));
            if (property_exists($this, $name)) {
                return $this->{$name};
            }
        }

        return $this;
    }

    public function __set($name, $value): void
    {
        if (property_exists($this, $name)) {
            $this->{$name} = $value;
        }
    }

    public function initialize(): void
    {
        if (null !== $this->label) {
            $this->label = $this->kernel->getLabel($this->field);
        }
        if (null === $this->value) {
            $this->value = $this->kernel->getFieldValue($this->field);
        }
    }

    /**
     * input占位符.
     *
     * @return string
     */
    public function getPlaceholder(): string
    {
        if (isset($this->placeholder)) {
            return $this->placeholder;
        }

        return ($this->select ? '请选择' : '请输入').$this->getLabel();
    }

    public function render(): string
    {
        $this->parser();
        $view = $this->view ?? "layui::{$this->type}";

        return view($view, ['base' => $this])->render();
    }

    /**
     * 解析组建参数.
     */
    public function parser(): void
    {
        if ('' !== $this->getField() && null !== $this->getField()) {
            $this->setAttribute('name', $this->getField());
            $this->setAttribute('id', $this->getField());
        }
        if ('' !== $this->getPlaceholder()) {
            $this->setAttribute('placeholder', $this->getPlaceholder());
        }
    }

    /**
     * 设置规则.
     *
     * @param $rule
     *
     * @return $this
     */
    public function setRule($rule): self
    {
        $this->rule = $rule;
        if (isset($rule['field'])) {
            $this->field($rule['field']);
        }

        if (isset($rule['title'])) {
            $this->label($rule['title']);
        }
        if (isset($rule['options'])) {
            $this->options($rule['options']);
        }

        if (null !== $this->kernel && !$this->kernel->isExists()) {
            $this->default($rule['value'] ?? null);
        } else {
            if (isset($rule['field'])) {
                $this->value($this->kernel->getFieldValue($rule['field']));
            }
        }
        // 是否必填
        if (isset($rule['validated'], $rule['validated']['rule'])) {
            if (\in_array('required', $rule['validated']['rule'], true)) {
                $this->required();
            }
        }

        return $this;
    }

    public function required($message = ''): self
    {
        $this->required = true;
        $this->setAttribute('lay-verify', 'required');
        if ($message) {
            $this->setAttribute('lay-verify-msg', $message);
        }

        return $this;
    }

    /**
     * 设置默认值
     *
     * @param $val
     *
     * @return $this
     */
    public function default($val): self
    {
        if (null === $this->value) {
            $this->value = $val;
        }

        return $this;
    }

    /**
     * 设置组件值，会覆盖初始化值
     *
     * @param $val
     *
     * @return $this
     */
    public function setValue($val): self
    {
        $this->value = $val;

        return $this;
    }
}
