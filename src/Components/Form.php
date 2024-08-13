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

/**
 * form表单样式
 * Class FormStyle.
 *
 * @method $this pane()                                           方框表单样式, 开启方框表单样式
 * @method $this labelWidth(string $labelWidth)                   表单域标签的宽度，例如 '50px'。Form子元素 form-item 会继承该值
 * @method $this labelSuffix(string $labelSuffix)                 表单域标签的后缀, 默认为 "："
 * @method $this disabled()                                       是否禁用该表单内的所有组件。若设置则表单内组件上的 disabled 属性不再生效, 默认值: false
 * @method $this hideRequiredAsterisk(bool $hideRequiredAsterisk) 是否显示必填字段的标签旁边的红色星号, 默认值: true
 */
class Form extends Base
{
    /** @var array 表单配置相关属性 */
    private $props = [];

    /** @var array 按钮组 */
    private $button = [];

    /** @var array[] 预设表单按钮 */
    private $pre_button = [
        'submit' => ['title' => '提交', 'class' => 'layui-bg-blue', 'id' => 'ptadmin-submit', 'type' => 'submit'],
        'reset' => ['title' => '重置', 'class' => 'layui-btn-primary', 'id' => 'ptadmin-reset', 'type' => 'reset'],
        'close' => ['title' => '关闭', 'class' => 'layui-bg-red', 'id' => 'ptadmin-close', 'type' => 'button'],
    ];

    public function setProps($props = []): self
    {
        $this->attributes = $props;
        if (isset($props['class'])) {
            $this->addAttribute('class', $props['class']);
        }
        if (isset($props['style'])) {
            $this->addAttribute('style', $props['style']);
        }
        unset($props['class'], $props['style']);
        $this->props = $props;

        return $this;
    }

    public function render(): string
    {
        $this->parser();

        return view('layui::form', ['view' => $this->getChildren(), 'base' => $this->kernel, 'form' => $this])->render();
    }

    /**
     * 解析表单配置信息.
     */
    public function parser(): void
    {
        $this->parserBtn();
    }

    public function getLabelWidth(): string
    {
        return $this->props['labelWidth'] ?? '';
    }

    /**
     * 获取表单按钮组.
     *
     * @return array
     */
    public function getButton(): array
    {
        return $this->button;
    }

    /**
     * 解析按钮组.
     */
    private function parserBtn(): void
    {
        if (!isset($this->props['btn'])) {
            return;
        }
        $btn = \is_array($this->props['btn']) ? $this->props['btn'] : [$this->props['btn']];
        foreach ($btn as $value) {
            // 当按钮为对象时直接使用
            if ($value instanceof Button) {
                $this->button[] = $value->setRoot($this->kernel)->render();

                continue;
            }
            // 当按钮为字符串时使用的为预设按钮类型
            if (\is_string($value)) {
                if (!isset($this->pre_button[$value])) {
                    continue;
                }
                $value = $this->pre_button[$value];
            }

            $this->button[] = (new Button())->setRoot($this->kernel)->setRule($value)->render();
        }
    }
}
