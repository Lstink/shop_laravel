@include('public.header')

<form class="layui-form layui-form-pane" method="post"  style="margin: 10px 30px;">
@csrf    
    <div class="layui-inline">
        <div class="layui-form-item">
            <label class="layui-form-label">选择标签</label>
            <div class="layui-input-inline">
                <select name="name" lay-search="" lay-filter="select">
                    <option value="">选择标签</option>
                    @foreach( $data as $k => $v )
                    <option value="{{ $v -> id }}">{{ $v -> name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
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
                data.field.id = '{{ $id }}';
                $.post(
                    "{{ route('doSelectTag') }}",
                    data.field,
                    function(res){
                        layer.msg(res.msg,{icon:res.code});
                    },'json'
                );
                return false;
            });

        });
    });
</script>
@include('public.footer')