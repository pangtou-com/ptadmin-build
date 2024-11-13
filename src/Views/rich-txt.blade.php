
{{-- 设置列布局方式 --}}
<x-col>
    <div class="layui-form-item">
        {{-- 渲染表单域标签信息 --}}
        <x-label :base="$base"></x-label>
        <div class="layui-input-block">
            <textarea class="ptadmin-editor" data-type="{{setting('editor', 'tiny')}}" name="{{$base->getField()}}" id="{{$base->getField()}}" >{{$base->getValue()}}</textarea>
        </div>
    </div>
</x-col>



