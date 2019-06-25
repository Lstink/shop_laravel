@foreach($orderInfo as $val)
      <table order_id="{{ $val['order_id'] }}">
       <tr>
        <td colspan="2" width="65%">订单号：<strong>{{ $val['order_no'] }}</strong></td>
        <td width="35%" align="right">
        @if($val['pay_status'] == 1 && $val['is_del'] == 1)
          <div class="qingqu pay"><a href="{{ route('success',['order_id'=>$val['order_id']]) }}" class="orange">支付订单</a></div>
          <div class="qingqu del"><a href="javascript:;" class="orange">取消订单</a></div>
        @elseif($val['is_del'] == 2)
          <div class="qingqu"><a href="javascript:;" class="orange">已取消</a></div>
        @endif
        </td>
       </tr>
      @foreach($val['goodsInfo'] as $v)
       <tr onClick="window.location.href='{{ route('proInfo',['goods_id'=> $v['goods_id'] ]) }}'">
        <td class="dingimg" width="15%"><img src="{{ asset('storage').'/uploads'.'/'.$v['goods_img'] }}" /></td>
        <td width="50%">
         <h3>{{ $v['goods_name'] }}</h3>
         <time>下单时间：{{ date('Y-m-d H:i:s',$v['create_time']) }}</time>
        </td>
        <td align="right"><img src="{{asset('shop/images/jian-new.png')}}" /></td>
       </tr>
      @endforeach
       <tr>
        <th colspan="3"><strong class="orange">¥{{ $val['order_amount'] }}</strong></th>
       </tr>
      </table>
    @endforeach