{{-- 多选 --}}
{{-- 设置列布局方式 --}}
<x-col>
    {{-- 渲染表单域标签信息 --}}
    <div class="layui-form-item">
        <x-label :base="$base"></x-label>
        <div class="layui-input-block">
            @foreach($base->getOptions() as $key => $val)
                <input
                    type="checkbox"
                    name="{{$base->getField()}}"
                    value="{{$val['value']}}"
                    title="{{$val['label']}}"
                    @if($val['value'] == $base->getValue()) checked @endif
                    @if((isset($val['disabled']) && $val['disabled']) || $base->getDisabled()) disabled @endif>
            @endforeach
        </div>
    </div>
</x-col>
