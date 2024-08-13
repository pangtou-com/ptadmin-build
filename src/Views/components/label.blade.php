@if($base && $base->getLabel())
    <label for="{{$base->getField()}}" title="{{$base->getLabel()}}" class="layui-form-label" data-field="{{$base->getField()}}">
        @if($base->getRequired())
            <color style="color: red">*</color>
        @endif
        {{$base->getLabel()}}
        @if($base->getTips())
            {{--设置字段提示信息 ptadmin-tips： 信息内容 ptadmin-tips-direction： 信息展示方向，ptadmin-tips-color: 信息背景色--}}
            <i {!! \PTAdmin\Html\Attributes::render($base->getTips()) !!}></i>
        @endif
    </label>
@endif
