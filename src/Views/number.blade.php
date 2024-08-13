
{{-- 设置列布局方式 --}}
<x-col>
    <div class="layui-form-item">
        {{-- 渲染表单域标签信息 --}}
        <x-label :base="$base"></x-label>
        <div class="layui-input-block">
            <div class="ptadmin-number">
                <span ptadmin-symbol="-"> - </span>
                <input class="layui-input" name="{{$base->getField()}}" type="number" value="{{$base->getValue()}}">
                <span ptadmin-symbol="+"> + </span>
            </div>
        </div>
    </div>
</x-col>
