
{{-- 设置列布局方式 --}}
<x-col>
    <div class="layui-form-item">
        {{-- 渲染表单域标签信息 --}}
        <x-label :base="$base"></x-label>
        <div class="layui-input-block">
            <input {!! PTAdmin\Html\Attributes::render($base->getAttributes()) !!}>
        </div>
    </div>
</x-col>
