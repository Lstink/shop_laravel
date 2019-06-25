@foreach($goodsInfo as $val)
    <dl>
        <dt><a href="{{route('proInfo',['goods_id'=>$val->goods_id])}}"><img src="{{ asset('storage').'/uploads'.'/'.$val -> goods_img }}" width="100" height="100" /></a></dt>
            <dd>
                <h3><a href="{{route('proInfo',['goods_id'=>$val->goods_id])}}">{{ $val -> goods_name }}</a></h3>
                <div class="prolist-price"><strong>¥{{ $val -> shop_price }}</strong> <span>¥{{ $val -> market_price }}</span></div>
                <div class="prolist-yishou"><span>5.0折</span> <em>库存：{{ $val -> goods_number }}</em></div>
            </dd>
        <div class="clearfix"></div>
    </dl>
@endforeach