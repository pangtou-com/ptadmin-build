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

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait ModelTrait
{
    public $keyId;

    /** @var array|Model */
    protected $model;

    /**
     * @return array|Model
     */
    public function getModel()
    {
        return $this->model;
    }

    public function setModel($model): self
    {
        if (\is_array($model) || $model instanceof Collection) {
            $this->model = $model;

            return $this;
        }
        // @var Model
        $this->model = model_build($model);
        // 初始化一下模型，加载数据库添加时设置的默认值
        if (!$this->isExists() && method_exists($this->model, 'fillDefaultValue')) {
            $this->model->fillDefaultValue();
        }

        return $this;
    }

    /**
     * 通过模型获取字段名称.
     *
     * @param $fieldName
     *
     * @return string
     */
    public function getLabel($fieldName): string
    {
        if (method_exists($this->model, 'getFieldTranslation')) {
            return $this->model->getFieldTranslation($fieldName);
        }

        if (method_exists($this->model, 'getTable')) {
            $table = $this->model->getTable();

            return __("table.{$table}.{$fieldName}");
        }

        return $fieldName;
    }

    /**
     * 获取模型字段的值.
     *
     * @param $fieldName
     * @param $default
     *
     * @return null|mixed
     */
    public function getFieldValue($fieldName, $default = null)
    {
        if ($this->model instanceof Model || \is_object($this->model)) {
            // 查看是否需要隐藏的字段
            if (method_exists($this->model, 'getHidden')) {
                if (\in_array($fieldName, $this->model->getHidden(), true)) {
                    return $default;
                }
            }

            return $this->model->{$fieldName} ?? $default;
        }

        if (\is_array($this->model)) {
            return $this->model[$fieldName] ?? $default;
        }

        return $default;
    }

    /**
     * 验证是否存在数据.
     *
     * @return bool
     */
    public function isExists(): bool
    {
        if (!$this->getModel()) {
            return false;
        }
        if (\is_array($this->getModel())) {
            return true;
        }
        if ($this->getModel() instanceof Collection) {
            return $this->getModel()->exists;
        }
        $key = $this->getModel()->getKeyName();
        if (isset($this->model->{$key}) && $this->model->{$key}) {
            return true;
        }

        return false;
    }
}
