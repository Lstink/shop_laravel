@include('public.header')
<div class="layui-col-md1">
    <div class="layui-card">
        <center style="color:red;"><div class="layui-card-header">以下选择可以为空，但至少选一个</div></center>
    </div>
</div>
<form class="layui-form" action="" style="margin: 10px 30px;">
@csrf

        <div class="layui-form-item">
        
            <div class="layui-form-item">
                <label class="layui-form-label">标签</label>
                <div class="layui-input-inline">
                    <select name="tag_id" lay-filter="type">
                        <option value="">选择标签</option>
                        @foreach($tag_id as $v)
                        <option value="{{ $v -> id }}">{{ $v -> name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">性别</label>
                <div class="layui-input-inline">
                    <select name="sex" lay-filter="type">
                        <option value="">请选择性别</option>
                        <option value="1">男</option>
                        <option value="2">女</option>
                    </select>
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">客户端版本</label>
                <div class="layui-input-inline">
                    <select name="client_platform_type" lay-filter="type">
                        <option value="">请选择客户端版本</option>
                        <option value="1">IOS</option>
                        <option value="2">Android</option>
                        <option value="3">Others</option>
                    </select>
                </div>
            </div>


            <!-- <div class="layui-form-item x-city" id="start">
                <div class="layui-form-item">
                    <label class="layui-form-label">省份</label>
                    <div class="layui-input-inline">
                        <select name="province" lay-filter="province" lay-search="">
                            <option value="">请选择省份</option>
                        </select>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">城市</label>
                    <div class="layui-input-inline">
                        <select name="area" lay-filter="area" lay-search="">
                            <option value="">请选择城市</option>
                        </select>
                    </div>
                </div>
            </div> -->

            <!-- <div class="layui-form-item">
                <label class="layui-form-label">标签</label>
                <div class="layui-input-inline">
                    <select name="client_platform_type" lay-filter="type">
                        <option value="">选择标签</option>
                    </select>
                </div>
            </div> -->

        </div>
        

    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>

</form>
<!-- <script type="text/javascript" src="{{ asset('js/xcity.js') }}"></script>
    <script>
        layui.use(['form','code'], function(){
            form = layui.form;
            //初始化城市
            $('#start').xcity();
        });
    </script> -->
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
                if (data.field.sex == '' && data.field.client_platform_type == '' && data.field.tag_id == '') {
                    layer.msg('请至少选择一个选择项来创建个性化菜单！',{icon:2});
                }
                
                $.post(
                    "{{ route('doCreatePersonMenu') }}",
                    data.field,
                    function(res){
                        if (res.code == 1) {
                            layer.msg(res.msg,{icon:res.code,time:1000},function(){
                                xadmin.close();
                            });
                        }else{
                            layer.msg(res.msg,{icon:res.code,time:3000});
                        }
                    },'json'
                );
                return false;
            });

            

            

        });
    });
</script>
@include('public.footer')