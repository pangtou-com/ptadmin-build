<?php

declare(strict_types=1);
/**
 * 弹出提示信息小组件
 * 设置字段提示信息
 *  ptadmin-tips： 信息内容
 *  ptadmin-tips-direction： 信息展示方向，
 *  ptadmin-tips-color: 信息背景色.
 *
 * Author: Zane
 * Email: 873934580@qq.com
 * Date: 2023/11/30.
 */

namespace PTAdmin\Build\Traits;

trait TipsTrait
{
    // 提示信息预设项目
    private $pre_tips = [
        'class' => 'layui-icon layui-icon-question',
        'style' => 'cursor: pointer;font-size: 12px',
    ];

    private $tips;

    public function getTips(): ?array
    {
        return $this->tips;
    }

    /**
     * 设置提示信息.
     * 支持格式有string 和 array
     * string: 为提示信息内容
     * array: [
     *      'ptadmin-tips' => '提示信息内容',
     *      'ptadmin-tips-direction' => '提示信息展示方向',
     *      'ptadmin-tips-color' => '提示信息背景色',
     * ].
     *
     * @param $tips
     *
     * @return TipsTrait|\PTAdmin\Build\Components\Component
     */
    public function setTips($tips): self
    {
        if (!$tips) {
            return $this;
        }
        if (\is_string($tips)) {
            $this->tips = [
                'ptadmin-tips' => $tips,
            ];
        }
        if (\is_array($tips)) {
            $this->tips = $tips;
        }
        $this->tips = array_merge($this->pre_tips, $this->tips);

        return $this;
    }
}
