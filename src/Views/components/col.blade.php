@if(isset($col))
    <div class="{{$col}}">
        {{$slot}}
    </div>
@else
    {{$slot}}
@endif

