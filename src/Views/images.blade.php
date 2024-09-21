{{-- 设置列布局方式 --}}
<x-col>
    {{-- 渲染表单域标签信息 --}}
    <div class="layui-form-item">
        <x-label :base="$base"></x-label>
        <div class="layui-input-block">
            <div id="img-{{$base->getField()}}" data-name="{{$base->getField()}}" data-limit="10" class="ptadmin-image-list">
                @if($base->getValue() && is_array($base->getValue()))
                    @foreach($base->getValue() as $img)
                        <div class="ptadmin-image image-html">
                            <input type="hidden" value="{{$img}}" name="{{$base->getField()}}[]">
                            <img src="{{$img}}" class="layui-img-content" alt="">
                            <div class="layui-img-delete">
                                <i class="layui-icon layui-icon-delete"></i>
                            </div>
                            <div class="layui-img-bg"></div>
                            <div class="layui-img-btn">
                                <a href="javascript:void(0);" class="layui-btn layui-btn-xs btn-theme layui-img-open">
                                    <i class="layui-icon layui-icon-addition"></i> {{__("system.big_photo")}}
                                </a>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</x-col>
