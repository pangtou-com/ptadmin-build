{{-- 设置列布局方式 --}}
<x-col>
    <div class="layui-form-item">
        {{-- 渲染表单域标签信息 --}}
        <x-label :base="$base"></x-label>
        <div class="layui-input-block">
            <div style="width: 50%" class="ptadmin-date" {!! PTAdmin\Html\Attributes::render($base->getAttributes()['data']) !!}>
                <input @if($base->getRequired()) lay-verify="required" @endif type="text" class="layui-input" id="{{$base->getField()}}" name="{{$base->getField()}}" value="{{$base->getValue()}}">
            </div>
        </div>
    </div>
</x-col>
