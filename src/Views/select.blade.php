{{-- 下拉选项 --}}
{{-- 设置列布局方式 --}}
<x-col>
    {{-- 渲染表单域标签信息 --}}
    <div class="layui-form-item">
        <x-label :base="$base"></x-label>
        <div class="layui-input-block">
            <select @if($base->getDisabled()) disabled @endif name="{{$base->getField()}}" @if($base->getRequired()) lay-verify="required" @endif lay-filter="{{$base->getField()}}" id="{{$base->getField()}}">
                @if($base->getPlaceholder())
                    <option value="">{{$base->getPlaceholder()}}</option>
                @endif

                @foreach($base->getOptions() as $key => $val)
                    <option value="{{$val['value']}}" @if($val['value'] == $base->getValue()) selected @endif  @if(isset($val['disabled']) && $val['disabled']) disabled @endif >{{$val['label']}}</option>
                @endforeach
            </select>
        </div>
    </div>
</x-col>
