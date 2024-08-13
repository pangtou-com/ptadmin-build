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

namespace PTAdmin\Build\Traits;

trait DisabledTrait
{
    /**
     * 设置组件是否禁用.支持设置为回调的方式实现.<br/>
     * 1、回调调用方式时会传入，当前组件field，value.<br/>
     * 2、当前组件为选项类型时会每个选项都调用回调，增加参数【val】当前选项的值.<br/>
     * example:.<br/>
     * ->disabled() 禁用.<br/>
     * ->disabled(false) 启用.<br/>
     * ->disabled(function($field, $value){return false}) 回调使用.<br/>
     * ->disabled(function($field, $value, $currentVal){return false}) 选项组件的回调.
     *
     * @param bool|callable $dis
     *
     * @return $this
     */
    public function disabled($dis = true): self
    {
        $this->attributes['disabled'] = $dis;

        return $this;
    }

    /**
     * 获取状态
     *
     * @return bool
     */
    public function getDisabled(): bool
    {
        // 未设置则返回false
        if (!isset($this->attributes['disabled'])) {
            return false;
        }
        if (\is_bool($this->attributes['disabled'])) {
            return $this->attributes['disabled'];
        }
        if (\is_callable($this->attributes['disabled'])) {
            return (bool) $this->attributes['disabled']($this->getField(), $this->getValue());
        }

        return false;
    }

    /**
     * 解析出禁用规则.
     * 添加禁用处理更多的支持，可以使用回调的方式调用处理，也可以对选项类的组件单独禁用处理.
     *
     * @param array $rules
     *
     * @return array
     */
    protected function parseDisabled(array $rules): array
    {
        if (!isset($rules['attrs']) || !isset($rules['attrs']['disabled'])) {
            return $rules;
        }
        $disabled = $rules['attrs']['disabled'];
        if (false === $disabled) {
            return $rules;
        }

        if ($this->select) {
            foreach ($rules['options'] as &$value) {
                $value['disabled'] = true === $disabled || $this->handleCall($disabled, $value['value']);
            }
            unset($value);
        } else {
            $rules['attrs']['disabled'] = true === $disabled || $this->handleCall($disabled);
        }

        return $rules;
    }

    private function handleCall($callback, $current = null): bool
    {
        if (\is_callable($callback)) {
            return $callback($this->getField(), $this->getValue(), $current);
        }

        return true;
    }
}
