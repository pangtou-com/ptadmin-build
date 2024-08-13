@if($base && $base->getRow())
    <div class="layui-row {{$base->getRow()}}">
        {{$slot}}
    </div>
@else
    {{$slot}}
@endif
