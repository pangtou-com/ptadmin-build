
{{-- 设置列布局方式 --}}
<x-col>
    <div class="layui-form-item">
        {{-- 渲染表单域标签信息 --}}
        <x-label :base="$base"></x-label>
        <div class="layui-input-block">
            <div class="layui-input-icon">
                <span class="icon-show">
                    @if($base->getValue())
                        <i class="{{$base->getValue()}}"></i>
                    @endif
                </span>
                <input @if($base->getRequired()) lay-verify="required" @endif type="text" class="layui-input" id="{{$base->getField()}}" name="{{$base->getField()}}" value="{{$base->getValue()}}">
                <span><i class="layui-icon layui-icon-add-circle"></i></span>
            </div>
        </div>
    </div>
</x-col>
