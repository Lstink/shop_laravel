@include('public.header')

<form class="layui-form" action="" style="margin: 10px 30px;">
@csrf

        @if($info -> pid == 0)
        <div class="layui-form-item oneMenu">
        
            <div class="layui-form-item">
                <label class="layui-form-label">一级菜单</label>
                <div class="layui-input-inline">
                    <input type="tel" name="name" value="{{ $info -> name }}" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入一级菜单名称">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">key值</label>
                <div class="layui-input-inline">
                    <input type="text" name="key" value="{{ $info -> key }}" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入key值">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">事件</label>
                <div class="layui-input-inline">
                    <select name="type" lay-filter="type" lay-verify="required">
                        <option value="">请选择事件</option>
                        <option value="click" {{ $info -> type=='click'?'selected':'' }}>click</option>
                        <option value="view" {{ $info -> type=='view'?'selected':'' }}>view</option>
                    </select>
                </div>
            </div>

            @if($info -> type == 'view')
            <div class="layui-form-item url">
                <label class="layui-form-label">url</label>
                <div class="layui-input-inline">
                    <input type="text" name="url" value="{{ $info -> url }}" autocomplete="off" lay-verify="urls" class="layui-input" placeholder="请输入url">
                </div>
            </div>
            @else
            <div class="layui-form-item url" style="display: none;">
                <label class="layui-form-label">url</label>
                <div class="layui-input-inline">
                    <input type="text" name="url" autocomplete="off" lay-verify="urls" class="layui-input" placeholder="请输入url">
                </div>
            </div>
            @endif

        </div>
        @else
        <div class="layui-form-item twoMenu">

            <div class="layui-form-item">
                <label class="layui-form-label">二级菜单</label>
                <div class="layui-input-inline">
                    <input type="tel" name="name" value="{{ $info -> name }}" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入二级菜单名称">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">key值</label>
                <div class="layui-input-inline">
                    <input type="text" name="key" value="{{ $info -> key }}" lay-verify="required" autocomplete="off" class="layui-input" placeholder="请输入key值">
                </div>
            </div>

            <div class="layui-form-item">
                <label class="layui-form-label">事件</label>
                <div class="layui-input-inline">
                    <select name="type" lay-verify="required"  lay-filter="type">
                        <option value="">请选择事件</option>
                        <option value="click" {{ $info -> type=='click'?'selected':'' }}>click</option>
                        <option value="view" {{ $info -> type=='view'?'selected':'' }}>view</option>
                    </select>
                </div>
            </div>

            @if($info -> type == 'view')
            <div class="layui-form-item url">
                <label class="layui-form-label">url</label>
                <div class="layui-input-inline">
                    <input type="text" name="url" value="{{ $info -> url }}" autocomplete="off" lay-verify="urls" class="layui-input" placeholder="请输入url">
                </div>
            </div>
            @else
            <div class="layui-form-item url" style="display: none;">
                <label class="layui-form-label">url</label>
                <div class="layui-input-inline">
                    <input type="text" name="url" autocomplete="off" lay-verify="urls" class="layui-input" placeholder="请输入url">
                </div>
            </div>
            @endif

            <div class="layui-form-item">
                <label class="layui-form-label">选择父级</label>
                <div class="layui-input-inline">
                    <select name="pid" lay-verify="required">
                        <option value="">请选择父级</option>
                        @foreach($data as $v)
                        <option value="{{ $v['id'] }}" {{ $info -> pid==$v['id']?'selected':'' }}>{{ $v['name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


        </div>
        @endif

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
                data.field.id = '{{ $info -> id }}';
                $.post(
                    "{{ route('doEditMenu') }}",
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

            //监听下拉列表的显示
            form.on('select(type)', function(data){
                if (data.value == 'view') {
                    $('.url').show();
                }else{
                    $('.url').hide();
                }
            });

            form.verify({
                urls: function(value, item){ //value：表单的值、item：表单的DOM对象
                    if($('select[name=type]').val() == 'view'){
                        if (value == '') {
                            return 'url不能为空';
                        }
                    }
                }
            });

        });
    });
</script>
@include('public.footer')