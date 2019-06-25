@extends('layouts.shop')

@section('content')
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <form action="#" method="get" class="prosearch">
          <input type="text" name="goods_name" value="{{$goods_name??''}}"/>
       </form>
      </div>
     </header>
     <ul class="pro-select">
      <li class="pro-selCur"  id="new" types="1" field="is_new"><a href="javascript:;">新品</a></li>
      <li id="num" types="1" field="goods_number"><a href="javascript:;">销量</a></li>
      <li id="price" types="1" field="shop_price"><a href="javascript:;">价格</a></li>
     </ul><!--pro-select/-->
     <div class="prolist">
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
     </div><!--prolist/-->
     <div id="loading" style="display: none;width: 100px;margin: 0 auto;"><img src="{{ asset('shop/images/loading.gif') }}" alt="" width="100px"></div>
     <div class="height1"></div>

<script>
     $(function(){

          //新品的点击事件
          $('#new').click(function(){
               //获取
               var obj = $(this);
               changeType(obj);
               sendAjax(obj);
          });

          //销量的点击事件
          $('#num').click(function(){
               //获取
               var obj = $(this);
               changeType(obj);
               sendAjax(obj);
          });

          //价格的点击事件
          $('#price').click(function(){
               //获取
               var obj = $(this);
               changeType(obj);
               sendAjax(obj);
          });

          //点击改变状态和颜色的方法
          function changeType(obj)
          {
               //获取当前的types
               var types = obj.attr('types');
               //判断types等于1还是2
               if (types == 1) {
                    obj.attr('types','2');
               }else{
                    obj.attr('types','1');
               }
               //点击改变颜色的方法
               obj.addClass('pro-selCur').siblings().removeClass('pro-selCur');
          }
          
          //发送ajax的方法
          function sendAjax(obj)
          {
               //获取当前点击的内容和类型
               var cate_id = '{{ request() -> cate_id }}';
               var field = obj.attr('field');
               var types = obj.attr('types');
               var goods_name = $('input[name=goods_name]').val();
               if (types == 1) {
                    var order = 'desc';
               }else{
                    var order = 'asc';
               }
               $.ajaxSetup({
                    headers: {
                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
               });
               $.post(
                    '/ajax/proList',
                    {field:field,order:order,goods_name,goods_name,cate_id:cate_id},
                    function(res){
                         $('.prolist').html(res);
                    }
               );
          }
          // 窗口滚动事件
          var num = 1;
          $(window).scroll(function () {
               //获取当前点击的内容和类型
               var cate_id = '{{ request() -> cate_id }}';
               var number = '{{ $number }}';
               var field = $('.pro-selCur').attr('field');
               var types = $('.pro-selCur').attr('types');
               var goods_name = $('input[name=goods_name]').val();
               if (types == 1) {
                    var order = 'desc';
               }else{
                    var order = 'asc';
               }
               
               //如果窗口划过的距离等于  页面高度减窗口高度   就说明已经到底部了
               if ($(window).scrollTop() == $(document).height() - $(window).height()) {
                    if (num == number) {
                         $('.prolist').append('<div style="width: 56px;margin: 0 auto; color: red;">全部加载</div>');
                    }else if(num < number){
                         //alert
                         $.ajaxSetup({
                              headers: {
                                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                              }
                         });
                         $("#loading").show();
                         setTimeout(() => {
                              $.ajax({
                                   type:'post',
                                   url:"/ajax/moreProList",
                                   data:{num:num,field:field,order:order,goods_name,goods_name,cate_id:cate_id},
                                   beforeSend: function () {
                                        // $('.prolist').append(11);
                                        $("#loading").show();
                                   },
                                   success: function (res) {
                                        $('.prolist').append(res);
                                   },
                                   complete: function () {
                                        $("#loading").hide();
                                   },
                              });
                         }, 1000);
                    }
                    num++;
               }
          });
     });
</script>
@endsection