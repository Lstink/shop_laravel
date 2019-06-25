@include('public.header')

<form class="layui-form" action="" style="margin: 10px 30px;">
@csrf
    <div class="layui-form-item">
        <label class="layui-form-label">添加菜单</label>
        <div class="layui-input-inline">
            <select name="num" lay-filter="num">
                <option value="1">一级菜单</option>
                <option value="2">二级菜单</option>
            </select>
        </div>
    </div>

    <div>
        <div class="layui-form-item oneMenu">

            <div class="layui-form-item">
                <label class="layui-form-label">一级菜单</label>
                <div class="layui-input-inline">
                    <input type="tel" name="name"  autocomplete="off" class="layui-input" placeholder="请输入一级菜单名称">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">key值</label>
                <div class="layui-input-inline">
                    <input type="text" name="key"  autocomplete="off" class="layui-input" placeholder="请输入key值">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">事件</label>
                <div class="layui-input-inline">
                    <select name="type" lay-filter="type">
                        <option value="">请选择事件</option>
                        <option value="click">click</option>
                        <option value="view">view</option>
                    </select>
                </div>
            </div>

            <div class="layui-form-item url" style="display: none;">
                <label class="layui-form-label">url</label>
                <div class="layui-input-inline">
                    <input type="text" name="url"  autocomplete="off" class="layui-input" placeholder="请输入url">
                </div>
            </div>

        </div>

        <div class="layui-form-item twoMenu" style="display: none;">

            <div class="layui-form-item">
                <label class="layui-form-label">二级菜单</label>
                <div class="layui-input-inline">
                    <input type="tel" name="t_name"  autocomplete="off" class="layui-input" placeholder="请输入二级菜单名称">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">key值</label>
                <div class="layui-input-inline">
                    <input type="text" name="t_key"  autocomplete="off" class="layui-input" placeholder="请输入key值">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">事件</label>
                <div class="layui-input-inline">
                    <select name="t_type" lay-filter="t_type">
                        <option value="">请选择事件</option>
                        <option value="click">click</option>
                        <option value="view">view</option>
                    </select>
                </div>
            </div>

            <div class="layui-form-item t_url" style="display: none;">
                <label class="layui-form-label">url</label>
                <div class="layui-input-inline">
                    <input type="text" name="t_url"  autocomplete="off" class="layui-input" placeholder="请输入url">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">选择父级</label>
                <div class="layui-input-inline">
                    <select name="pid">
                        <option value="">请选择父级</option>
                        @foreach($data as $v)
                        <option value="{{ $v['id'] }}">{{ $v['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

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
                var num = $('select[name=num]').val();
                data.field.num = num;
                if (num == 1) {
                    var name = $('input[name=name]').val();
                    var key = $('input[name=key]').val();
                    var type = $('select[name=type]').val();
                }else if(num == 2){
                    var name = $('input[name=t_name]').val();
                    var key = $('input[name=t_key]').val();
                    var type = $('select[name=t_type]').val();
                    var pid = $('select[name=pid]').val();
                    if (pid == '') {
                        layer.msg('请选择所属父级',{icon:2});
                        return false;
                    }
                }
                if (name == '') {
                    layer.msg('请填写菜单名称',{icon:2});
                    return false;
                }
                if (key == '') {
                    layer.msg('请填写key',{icon:2});
                    return false;
                }
                if (type == '') {
                    layer.msg('请选择事件',{icon:2});
                    return false;
                }
                $.post(
                    "{{ route('doCreateMenu') }}",
                    data.field,
                    function(res){
                        if (res.code == 1) {
                            layer.msg(res.msg,{icon:res.code,time:1000},function(){
                                if (res.num == 1 || res.twoCount == 4) {
                                    window.location.reload();
                                }
                            });
                        }else{
                            layer.msg(res.msg,{icon:res.code});
                        }
                    },'json'
                );
                return false;
            });

            //菜单的切换事件
            form.on('select(num)', function(data){
                if (data.value == 1) {
                    $('.oneMenu').show().siblings().hide();
                }else if(data.value == 2){
                    $('.twoMenu').show().siblings().hide();
                }
            }); 

            //url的显示事件
            form.on('select(type)', function(data){
                if (data.value == 'view') {
                    $('.url').show();
                }else{
                    $('.url').hide();
                }
            });
            form.on('select(t_type)', function(data){
                if (data.value == 'view') {
                    $('.t_url').show();
                }else{
                    $('.t_url').hide();
                }
            }); 

        });
    });
</script>
@include('public.footer')