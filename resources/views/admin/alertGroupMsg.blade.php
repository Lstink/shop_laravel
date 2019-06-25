@include('public.header')

<form class="layui-form layui-form-pane" method="post" enctype="multipart/form-data" style="margin: 10px 30px;">
@csrf    
<div class="main_content">
    <div class="layui-form-item">
            <label class="layui-form-label">群发消息</label>
            <div class="layui-input-block">
                <input type="text" name="content" autocomplete="off" placeholder="请输入群发消息内容" lay-verify="required" class="layui-input">
            </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </div>

</div>

</form>

<script>
    $(function(){
        layui.use(['layer','form'], function(){
            var $ = layui.jquery,
            form = layui.form,
            layer = layui.layer;
            
            
            //提交按钮的监听事件
            form.on('submit(demo1)', function(data){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                // var val = $('input[type=text]').val();
                data.field.id = '{{ $id }}';
                data.field.type = '{{ $type }}';
                $.post(
                    "{{ route('sendGroupMsg') }}",
                    data.field,
                    function(res){
                        if (res.code == 1) {
                            layer.msg(res.msg,{icon:res.code,time:1000},function(){
                                xadmin.close();
                            });
                        }else{
                            layer.msg(res.msg,{icon:res.code});
                        }
                    },'json'
                );
                return false;
            });

            
           
        });
    });
</script>
@include('public.footer')