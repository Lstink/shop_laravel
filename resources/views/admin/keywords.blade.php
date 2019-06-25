@include('public.header')

<div class="x-nav">
    <span class="layui-breadcrumb">
    <a href="">首页</a>
    <a href="">演示</a>
    <a>
        <cite>导航元素</cite></a>
    <a href="{{ route('wordsList') }}" class="layui-btn layui-btn-warm">关键字列表</a>

    </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
    <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
</div>


<form class="layui-form layui-form-pane" action="">
    @csrf
    <div class="layui-form-item">
        <label class="layui-form-label">添加关键字</label>
        <div class="layui-input-block">
        <input type="text" name="keywords" autocomplete="off" placeholder="请输入关键字" lay-verify="required" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">关键字回复</label>
        <div class="layui-input-block">
        <input type="text" name="content" autocomplete="off" placeholder="请输入回复内容" lay-verify="required" class="layui-input">
        </div>
    </div>
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>

<script>
    $(function(){
        layui.use(['layer','form'], function(){
            var form = layui.form,
            layer = layui.layer;
            
            form.on('submit(demo1)', function(data){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.post(
                    "{{ route('addKeywords') }}",
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