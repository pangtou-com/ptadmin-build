
{{-- 设置列布局方式 --}}
<x-col>
    {{-- 渲染表单域标签信息 --}}
    <div class="layui-form-item">
        <x-label :base="$base"></x-label>
        <div class="layui-input-block">
            <textarea {!! PTAdmin\Html\Attributes::render($base->getAttributes()) !!} >{{$base->getValue()}}</textarea>
        </div>
    </div>
</x-col>
