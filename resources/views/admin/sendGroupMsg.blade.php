@include('public.header')


<div class="layui-form-item">
    <label class="layui-form-label">选择上传类型</label>
    <div class="layui-input-block">
        </div>
    </div>
    
</div>


<form class="layui-form layui-form-pane" action="{{ route('addFile') }}" method="post" enctype="multipart/form-data" style="margin: 10px 30px;">
@csrf    
<div class="main_content">

    

</div>

</form>

<script>
    $(function(){
        layui.use(['layer','form'], function(){
            var $ = layui.jquery,
            form = layui.form,
            layer = layui.layer;
            
            
            var type = $('.one').find('input[type=text]').val();
            //提交按钮的监听事件
            form.on('submit(demo1)', function(data){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var type = $(this).parents('.dd').attr('type');
                data.field.type = type;
                if (type == 'text') {
                    var val = $('.one').find('input[type=text]').val();
                    if (val == '') {
                        layer.msg('请输入回复内容',{icon:2});
                        return false;
                    }
                    $.post(
                        "{{ route('addSubscribe') }}",
                        data.field,
                        function(res){
                            layer.msg(res.msg,{icon:res.code});
                        },'json'
                    );
                }
                return false;
            });

            


            
           
        });
    });
</script>
@include('public.footer')