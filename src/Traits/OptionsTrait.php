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

use Illuminate\Support\Collection;

trait OptionsTrait
{
    /**
     * @var array
     */
    protected $options = [];
    private $key = 'id';
    private $keyName = 'name';

    /**
     * 添加选项.
     *
     * @param $value
     * @param string $label
     * @param bool   $disabled
     *
     * @return $this
     */
    public function appendOption($value, string $label, bool $disabled = false): self
    {
        $this->options[] = compact('value', 'label', 'disabled');

        return $this;
    }

    /**
     * 添加选项.字符串的解析选项。一行代表一条数据
     * 支持类型有：
     * 1. 1=男
     *    2=女.
     * 2. 男
     *    女.
     *
     * @param mixed $str
     *
     * @return $this
     */
    public function optionString($str): self
    {
        $data = explode("\n", $str);
        foreach ($data as $key => $datum) {
            $temp = explode('=', $datum);
            if (1 === \count($temp)) {
                $this->appendOption($key, $temp[0]);
            }
            if (1 < \count($temp)) {
                $this->appendOption($temp[0], $temp[1]);
            }
        }

        return $this;
    }

    /**
     * 批量设置的选项.支持model模型，集合，数组.
     *
     * @param mixed      $options
     * @param null|mixed $key
     * @param null|mixed $val
     *
     * @return $this
     */
    public function setOptions($options, $key = null, $val = null): self
    {
        if (null !== $key) {
            $this->key = $key;
        }
        if (null !== $val) {
            $this->keyName = $val;
        }
        $this->options($options);

        return $this;
    }

    /**
     * 批量设置选项 支持匿名函数.
     *
     * @param array|callable|mixed $options
     *
     * @return $this
     */
    public function options($options): self
    {
        if (method_exists($options, 'getOption')) {
            // 当存在getOption方法时，调用该方法获取选项
            if (\is_string($options)) {
                $options = app($options);
            }
            $temp = $options->getOption($this->getField(), $this->getValue());
        } elseif (\is_array($options)) {
            $temp = $options;
        } elseif ($options instanceof Collection) {
            $temp = $this->handleCollection($options);
        } elseif (\is_callable($options)) {
            $temp = $options($this);
        } elseif (\is_string($options)) {
            $temp = $this->handleModel($options);
        } else {
            $temp = [];
        }
        $this->options = $this->parseOptions($temp);

        return $this;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param mixed $temp
     *
     * @return array
     */
    protected function parseOptions($temp): array
    {
        $options = [];
        foreach ($temp as $key => $option) {
            if (\is_array($option)) {
                $options[] = $option;

                continue;
            }
            $options[] = [
                'value' => $key,
                'label' => $option,
            ];
        }

        return $options;
    }

    /**
     * 传入为字符串格式 尝试解析为 模型.
     *
     * @param mixed $model
     */
    private function handleModel($model)
    {
        if (!\is_string($model)) {
            return false;
        }

        $model = model_build($model);
        $model = $model->newQuery()->get();

        return $this->handleCollection($model);
    }

    private function handleCollection($model): array
    {
        $options = [];
        foreach ($model as $val) {
            $options[] = [
                'value' => $val->{$this->key},
                'label' => $val->{$this->keyName},
            ];
        }

        return $options;
    }
}
