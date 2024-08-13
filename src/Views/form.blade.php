<form action="{{$base->getAction()}}" class="layui-form layui-form-pane" method="post">
    @csrf
    {{-- 当出现修改的情况时应使用put提交方式 --}}
    @method($base->getMethod())

    {{-- 渲染表单数据 --}}
    <x-row :base="$base">
        @foreach($view as $key => $val)
            {!! $val !!}
        @endforeach
    </x-row>

    {{-- 渲染表单按钮组 --}}
    @if(isset($form))
        <div style="text-align: center" class="ptadmin-form-btn">
            @foreach($form->getButton() as $val)
                {!! $val !!}
            @endforeach
        </div>
    @endif
</form>
