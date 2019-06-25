<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>扫码登陆</title>
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
</head>
<style>
    .qrcode{
        width: 174px;
        margin: 50px auto;
    }
    .p{
        text-align: center;
    }
</style>
<body>
    <div class="qrcode">
        <img src="{{ asset('qrcode.png') }}" alt="">
        <p class="p"></p>
    </div>
</body>
</html>
<script>
    $(function(){

        //轮训
        setInterval(getStatus, 1000);

        //方法
        function getStatus()
        {
            var uniqid = '{{ $uniqid }}';
            var token = '{{ csrf_token() }}';
            $.post(
                '{{ route('getUniqidStatus') }}',
                {uniqid:uniqid,_token:token},
                function(res){
                    if (res == 1) {
                        $('.p').html('用户已扫描');
                    }else if(res == 2){
                        $('.p').html('用户已确认');
                    }
                }
            );
        }
    });
</script>