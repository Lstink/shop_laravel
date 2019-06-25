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

     <form action="{{ route('doEditAddress') }}" method="post" class="reg-login">
     <input type="hidden" name="address_id" value="{{ $addressInfo -> address_id }}">
     @csrf
      <div class="lrBox">
       <div class="lrList"><input type="text" name="address_name" placeholder="收货人" value="{{ $addressInfo -> address_name??'' }}"/></div>
       <div class="lrList">
        <select name="province"  id="country">
         <option>省份/直辖市</option>
         @foreach($areaInfo as $vo)
         <option value="{{ $vo -> id }}" {{ $addressInfo -> province == $vo -> id ?'selected':''}}>{{ $vo -> name }}</option>
         @endforeach
        </select>
       </div>
       <div class="lrList">
        <select name="city" id="province">
         <option>市</option>
         @foreach($city as $vo)
         <option value="{{ $vo -> id }}" {{ $addressInfo -> city == $vo -> id ?'selected':''}}>{{ $vo -> name }}</option>
         @endforeach
        </select>
       </div>
       <div class="lrList">
        <select name="area" id="city">
         <option>区县</option>
         @foreach($area as $vo)
         <option value="{{ $vo -> id }}" {{ $addressInfo -> area == $vo -> id ?'selected':''}}>{{ $vo -> name }}</option>
         @endforeach
        </select>
       </div>
       <div class="lrList">
        <input type="text" name="address_detail" placeholder="详细地址" value="{{ $addressInfo -> address_detail??'' }}"/>
       </div>
       <div class="lrList">
        <input type="text" name="address_tel" placeholder="手机" value="{{ $addressInfo -> address_tel??'' }}" />
       </div>
       <div class="lrList2">
        <input type="checkbox" name="is_default" align="right" value="1" {{ $addressInfo -> is_default == 1?'checked':'' }}>
        <button Onclick="return false" id="default">设为默认</button>
        </div>
      </div><!--lrBox/-->
      <div class="lrSub">
       <input type="submit" value="保存" />
      </div>
     </form><!--reg-login/-->
     <div class="height1"></div>
<script>
  $(function(){

    //省区的点击事件
    $(document).on('change','#country',function(){
      //获取点击的id
      var id = $(this).val();
      var obj = $(this);
      var content = "<option value='' selected='selected'>市</option>";
      var contents = "<option value='' selected='selected'>区县</option>";
      $('#province').html(content);
      $('#city').html(contents);
      if (id != '') {
        $.ajaxSetup({
          headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.post(
          "{{ route('getCountry') }}",
          {id:id},
          function(res){
            for (var i in res) {
              content += "<option value="+res[i]['id']+">"+res[i]['name']+"</option>";
            }
            $('#province').html(content);
          }
        );
      }
    });

    //市区的点击事件
    $(document).on('change','#province',function(){
      //获取点击的id
      var id = $(this).val();
      if (id != '') {
        var content = "<option value='' selected='selected'>区县</option>";
        $.ajaxSetup({
          headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.post(
          "{{ route('getCountry') }}",
          {id:id},
          function(res){
            for (var i in res) {
              content += "<option value="+res[i]['id']+">"+res[i]['name']+"</option>";
            }
            $('#city').html(content);
          }
        );
      }
    });

    //设为默认的点击事件
    $('#default').click(function(){
      var checked = $('input[name=is_default]').prop('checked');
      if (checked == true) {
        $('input[name=is_default]').prop('checked',false);
      }else{
        $('input[name=is_default]').prop('checked',true);
      }
      return false;
    });


  });
</script>
@endsection