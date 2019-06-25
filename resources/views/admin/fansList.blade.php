@include('public.header')
        <div class="x-nav">
            <span class="layui-breadcrumb">
                <a href="">首页</a>
                <a href="">演示</a>
                <a>
                    <cite>导航元素</cite></a>
            </span>
            <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
                <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i>
            </a>
        </div>
        <div class="layui-fluid">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <!-- <div class="layui-form-item">
                            <label class="layui-form-label">选择标签分类</label>
                            <div class="layui-input-block">
                                <div class="layui-btn-group demoTest" style="margin-top: 5px;">
                                    @foreach($data as $v)
                                    <button class="layui-btn layui-btn-sm btns" tagId="{{ $v -> id }}">{{ $v -> name }}</button>
                                    @endforeach
                                </div>
                            </div>
                        </div> -->
                        <!-- <div class="layui-form-item layui-form">
                            <label class="layui-form-label">选择标签查询</label>
                            <div class="layui-input-inline">
                                <select name="name" lay-search="" lay-filter="select">
                                    <option value="">请选择标签</option>
                                    @foreach( $data as $k => $v )
                                    <option value="{{ $v -> id }}">{{ $v -> name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> -->
                        <div class="layui-card-body ">
                            <table class="layui-table" lay-data="{url:'{{ route('getJsonFans') }}',page:true,toolbar: '#toolbarDemo',id:'test'}" lay-filter="test">
                                <thead>
                                    <tr>
                                        <th lay-data="{type:'checkbox'}">ID</th>
                                        <th lay-data="{field:'id', width:80, sort: true}">tagID</th>
                                        <th lay-data="{field:'name',  sort: true}">标签名</th>
                                        <th lay-data="{field:'count', minWidth: 150}">粉丝数量</th>
                                        <th lay-data="{field:'del',templet: '#del'}">操作</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script type="text/html" id="del">
        <button class="layui-btn-danger layui-btn layui-btn-xs"  onclick="member_del(this)" href="javascript:;" ><i class="layui-icon">&#xe640;</i>删除</button>
    </script>
    <script type="text/html" id="toolbarDemo">
        <div class = "layui-btn-container" > 
            <button class = "layui-btn layui-btn-sm" lay-event = "getCheckData" >获取选中的数据</button>
            <button class="layui-btn layui-btn-sm" lay-event="getCheckLength">获取选中数目</button > 
            <button class = "layui-btn layui-btn-sm" lay-event = "isAll" > 验证是否全选</button>
            <button class = "layui-btn layui-btn-danger layui-btn-sm" lay-event = "send" onclick="">群发消息</button>
        </div > 
    </script>
    <script type="text/html" id="switchTpl">
        <!-- 这里的checked的状态只是演示 -->
    </script>
    <script>layui.use(['laydate','form'],
        function() {
            var laydate = layui.laydate,
            form = layui.form;

            //执行一个laydate实例
            laydate.render({
                elem: '#start' //指定元素
            });

            //执行一个laydate实例
            laydate.render({
                elem: '#end' //指定元素
            });
        });
        /*用户-删除*/
        function member_del(obj){
                layer.confirm('确认要删除吗？',function(index){
                    //发异步删除数据
                    var id = $(obj).parents('td').prev().prev().prev().text();
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.post(
                        "{{ route('delTag') }}",
                        {id:id},
                        function(res){
                            layer.msg(res.msg,{icon:res.code});
                            if (res.code == 1) {
                                $(obj).parents("tr").remove();
                                layer.msg('已删除!',{icon:1,time:1000});
                            }
                        },'json'
                    );
                });
            }
    </script>
    <script>layui.use('table',
        function() {
            var table = layui.table;

            //监听单元格编辑
            // table.on('edit(test)',
            // function(obj) {
            //     var value = obj.value //得到修改后的值
            //     ,
            //     data = obj.data //得到所在行所有键值
            //     ,
            //     field = obj.field; //得到字段
            //     layer.msg('[ID: ' + data.id + '] ' + field + ' 字段更改为：' + value);
            // });

            //头工具栏事件
            table.on('toolbar(test)',
            function(obj) {
                var checkStatus = table.checkStatus(obj.config.id);
                // console.log(checkStatus);
                
                switch (obj.event) {
                    case 'getCheckData':
                        var data = checkStatus.data;
                        layer.alert(JSON.stringify(data));
                        break;
                    case 'getCheckLength':
                        var data = checkStatus.data;
                        layer.msg('选中了：' + data.length + ' 个');
                        break;
                    case 'isAll':
                        layer.msg(checkStatus.isAll ? '全选': '未全选');
                        break;
                    case 'send':
                        var data = checkStatus.data; 
                        var arr = [];
                        for (var i in data) {
                            arr.push(data[i].id);
                        }
                        if (arr.length != 1) {
                            return layer.msg('请勾选一个标签进行群发',{icon:2});
                        }
                        //将数组转化为字符串
                        var id = arr.join(',');
                        xadmin.open('群发消息','{{ route('alertGroupMsg') }}'+'/'+id+'/'+2,600,200);
                        break;
                };
            });
        });
    </script>
    

</html>