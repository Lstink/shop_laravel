@include('public.header')
        <div class="x-nav">
          <span class="layui-breadcrumb">
            <a href="">首页</a>
            <a href="">演示</a>
            <a>
              <cite>导航元素</cite></a>
          </span>
          <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
            <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i></a>
        </div>
        <div class="layui-fluid">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-md12">
                    <div class="layui-card">
                        
                        <div class="layui-card-header">
                            <button class="layui-btn" onclick="addMenu()"><i class="layui-icon"></i>添加到微信服务器</button>
                            <button class="layui-btn layui-btn-warm" onclick="xadmin.open('创建个性化菜单','{{ route('alertPersonMenu') }}',470,400)"><i class="layui-icon"></i>创建个性化菜单</button>
                        </div>
                        <div class="layui-card-body layui-table-body layui-table-main">
                            <table class="layui-table layui-form">
                                <thead>
                                  <tr>
                                    <th>ID</th>
                                    <th>菜单名</th>
                                    <th>key</th>
                                    <th>事件类型</th>
                                    <th>url</th>
                                    <th>操作</th></tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $val)
                                    <tr>
                                        @if(!empty($val['child']))
                                        <td><a href="javascript:;" class="slid" id="{{ $val['id'] }}"><i class="layui-icon"></i></a>{{ $val['id'] }}</td>
                                        @else
                                        <td>{{ $val['id'] }}</td>
                                        @endif
                                        <td>{{ $val['name'] }}</td>
                                        <td>{{ $val['key'] }}</td>
                                        <td>{{ $val['type'] }}</td>
                                        <td>{{ $val['url'] }}</td>
                                        <td class="td-manage">
                                        <a title="编辑"  onclick="xadmin.open('编辑','{{ route('editMenu',['id'=>$val['id']]) }}',550,450)" href="javascript:;">
                                            <i class="layui-icon">&#xe642;</i>
                                        </a>
                                        <a title="删除" onclick="member_del(this,'{{ $val['id'] }}','{{ $val['pid'] }}')" href="javascript:;">
                                            <i class="layui-icon">&#xe640;</i>
                                        </a>
                                        </td>
                                    </tr>
                                        @foreach($val['child'] as $v)
                                        <tr align="right" style="display: none;" class="{{ $val['id'] }}">
                                            <td>{{ $v['id'] }}</td>
                                            <td>{{ $v['name'] }}</td>
                                            <td>{{ $v['key'] }}</td>
                                            <td>{{ $v['type'] }}</td>
                                            <td>{{ $v['url'] }}</td>
                                            <td class="td-manage">
                                            <a title="编辑"  onclick="xadmin.open('编辑','{{ route('editMenu',['id'=>$v['id']]) }}',550,450)" href="javascript:;">
                                                <i class="layui-icon">&#xe642;</i>
                                            </a>
                                            <a title="删除" onclick="member_del(this,'{{ $v['id'] }}','{{ $v['pid'] }}')" href="javascript:;">
                                                <i class="layui-icon">&#xe640;</i>
                                            </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </body>
    <script>
      layui.use(['laydate','form'], function(){
        var laydate = layui.laydate;
        var  form = layui.form;


        // 监听全选
        form.on('checkbox(checkall)', function(data){

          if(data.elem.checked){
            $('tbody input').prop('checked',true);
          }else{
            $('tbody input').prop('checked',false);
          }
          form.render('checkbox');
        }); 
        
        //执行一个laydate实例
        laydate.render({
          elem: '#start' //指定元素
        });

        //执行一个laydate实例
        laydate.render({
          elem: '#end' //指定元素
        });

        //slide的点击事件
        $('.slid').click(function(){
            var id = $(this).attr('id');
            $('tr').each(function(){
                if ($(this).attr('class') == id) {
                    $(this).toggle();
                }
            });
            // alert(id);
        });

      });


      /*用户-删除*/
      function member_del(obj,id,pid){
          layer.confirm('确认要删除吗？',function(index){
                //发异步删除数据
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.post(
                    "{{ route('delMenu') }}",
                    {id:id,pid:pid},
                    function(res){
                        layer.msg(res.msg,{icon:res.code});
                        if (res.code == 1) {
                            $(obj).parents("tr").remove();
                        }
                    },'json'
                );
          });
      }

      /*添加菜单*/
      function addMenu()
      {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.post(
            "{{ route('addMenu') }}",
            function(res){
                layer.msg(res.msg,{icon:res.code,time:2000});
            },'json'
        );
      }


      function delAll (argument) {
        var ids = [];

        // 获取选中的id 
        $('tbody input').each(function(index, el) {
            if($(this).prop('checked')){
               ids.push($(this).val())
            }
        });
  
        layer.confirm('确认要删除吗？'+ids.toString(),function(index){
            //捉到所有被选中的，发异步进行删除
            layer.msg('删除成功', {icon: 1});
            $(".layui-form-checked").not('.header').parents('tr').remove();
        });
      }
    </script>
</html>