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

namespace PTAdmin\Build\Service;

use Illuminate\Support\Str;
use PTAdmin\Build\Components\Component;
use PTAdmin\Build\Components\Form;
use PTAdmin\Build\Exception\BuilderException;
use PTAdmin\Build\Traits\ModelTrait;

/**
 * @method \PTAdmin\Build\Components\Text     text(string $field, string $label = '', mixed $value = '')
 * @method \PTAdmin\Build\Components\Text     password(string $field, string $label = '', mixed $value = '')
 * @method \PTAdmin\Build\Components\Avatar   avatar(string $field, string $label = '', mixed $value = '')
 * @method \PTAdmin\Build\Components\Img      img(string $field, string $label = '', mixed $value = '')
 * @method \PTAdmin\Build\Components\Radio    radio(string $field, string $label = '', mixed $value = '')
 * @method \PTAdmin\Build\Components\Hidden   hidden(string $field, mixed $value = '')
 * @method \PTAdmin\Build\Components\Number   number(string $field, string $label = '',mixed $value = '')
 * @method \PTAdmin\Build\Components\Textarea textarea(string $field, string $label = '',mixed $value = '')
 * @method \PTAdmin\Build\Components\Select   select(string $field, string $label = '',mixed $value = '')
 * @method \PTAdmin\Build\Components\Icon     icon(string $field, string $label = '',mixed $value = '')
 * @method \PTAdmin\Build\Components\Button   button(string $field, string $label = '',mixed $value = '')
 * @method \PTAdmin\Build\Components\KeyValue keyValue(string $field, string $label = '',mixed $value = '')
 */
class Kernel
{
    use ModelTrait;

    /** @var array 根节点 */
    private $root = [];

    /** @var string 请求地址 */
    private $action = '';

    /** @var Form 表单配置信息 */
    private $form;

    /** @var string 表单请求方法，put|post */
    private $method;

    public function __construct()
    {
        $this->form = new Form();
        $this->form->setRoot($this);
    }

    public function __toString()
    {
        return $this->render();
    }

    public function __call($name, $arguments)
    {
        $className = 'PTAdmin\\Build\\Components\\'.Str::studly($name);

        try {
            $class = (new \ReflectionClass($className))->newInstance(...$arguments);
        } catch (\ReflectionException $e) {
            throw new BuilderException("【{$name}】Unknown");
        }
        $class->setRoot($this);
        $class->initialize();
        $this->root[$class->id] = $class;

        return $class;
    }

    public function render(): string
    {
        foreach ($this->root as $value) {
            $this->form->addChild($value->render());
        }

        return $this->form->render();
    }

    /**
     * 获取表单配置信息.
     *
     * @return Form
     */
    public function form(): Form
    {
        return $this->form;
    }

    /**
     * 设置表单组建属性.
     *
     * @param $props
     *
     * @return $this
     */
    public function setForm($props): self
    {
        if ($props instanceof Form) {
            $this->form = $props;
            if (!$this->form->kernel) {
                $this->form->setRoot($this);
            }

            return $this;
        }
        if (!\is_array($props)) {
            return $this;
        }

        $this->form->setProps($props);

        return $this;
    }

    public function getMethod(): string
    {
        if (null !== $this->method) {
            return $this->method;
        }

        return $this->isExists() ? 'put' : 'post';
    }

    /**
     * 设置请求方法.
     *
     * @param $method
     *
     * @return $this
     */
    public function setMethod($method): self
    {
        $this->method = $method;

        return $this;
    }

    /**
     * 获取请求地址.
     *
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * 设置请求地址.
     *
     * @param mixed $action
     */
    public function setAction($action): self
    {
        $this->action = $action;

        return $this;
    }

    public function rules(): self
    {
        return $this;
    }

    /**
     * 设置表单渲染规则.
     *
     * @param $rules
     */
    public function setRules($rules): void
    {
        foreach ($rules as $rule) {
            /** @var Component $field */
            $field = $this->{$rule['type']}();
            $field->setRule($rule);
        }
    }

    /**
     * 获取行设置项.
     *
     * @return string
     */
    public function getRow(): string
    {
        return '';
    }
}
