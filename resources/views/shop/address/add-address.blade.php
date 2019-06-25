@extends('layouts.shop')

@section('content')
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>收货地址</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="{{asset('shop/images/head.jpg')}}" />
     </div><!--head-top/-->

     <table class="shoucangtab">
      <tr>
       <td width="75%"><a href="{{ route('address') }}" class="hui"><strong class="">+</strong> 新增收货地址</a></td>
       <td width="25%" align="center" style="background:#fff url({{asset('shop/images/xian.jpg')}}) left center no-repeat;"><a href="javascript:;" class="orange">删除信息</a></td>
      </tr>
     </table>

      @if(session('msg'))
            <font color="red" id="msg">{{session('msg')}}</font>
            <script>
              //清楚错误信息的方法
                setTimeout(() => {
                  $('#msg').empty();
                }, 2000);
            </script>
      @endif

      @if ($errors->any())
            @foreach ($errors->all() as $error)
                <font color="red">{{ $error }}</font>
            @endforeach
      @else
            <font color="red" id="msg"></font>
      @endif
    
     <div class="dingdanlist" onClick="window.location.href='proinfo.html'">
      @foreach($addressInfo as $val)
      <table>
       <tr>
        <td width="50%">
         <h3>{{ $val -> address_name }} {{ $val -> address_tel }}</h3>
         <time>{{ $val -> province }}{{ $val -> city }}{{ $val -> area }}{{ $val -> address_detail }}</time>
         @if($val -> is_default == 1)
         <font color="green">默认收货地址</font>
         @endif
        </td>
        <td align="right">
        <a href="{{ route('editAddress',['address_id'=>$val -> address_id]) }}" class="hui">
        <span class="glyphicon glyphicon-check"></span> 修改信息</a>
        </td>
       </tr>
      </table>
      @endforeach
     </div><!--dingdanlist/-->
      
     
     <div class="height1"></div>
     <div class="footNav">
      <dl>
       <a href="index.html">
        <dt><span class="glyphicon glyphicon-home"></span></dt>
        <dd>微店</dd>
       </a>
      </dl>
      <dl>
       <a href="prolist.html">
        <dt><span class="glyphicon glyphicon-th"></span></dt>
        <dd>所有商品</dd>
       </a>
      </dl>
      <dl>
       <a href="car.html">
        <dt><span class="glyphicon glyphicon-shopping-cart"></span></dt>
        <dd>购物车 </dd>
       </a>
      </dl>
@endsection