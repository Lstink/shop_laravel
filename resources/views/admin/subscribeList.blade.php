@include('public.header')
        <div class="x-nav">
            <span class="layui-breadcrumb">
                <a href="">首页</a>
                <a href="">演示</a>
                <a>
                    <cite>导航元素</cite></a>
                    <button class="layui-btn" onclick="xadmin.open('添加素材','{{ route('addMaterial') }}',600,400)"><i class="layui-icon"></i>添加素材</button>
            </span>
            <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right" onclick="location.reload()" title="刷新">
                <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i>
            </a>
        </div>
        <div class="layui-fluid">
            <div class="layui-row layui-col-space15">
                <div class="layui-col-md12">
                    <div class="layui-card">
                        <!-- <div class="layui-card-body ">
                            <form class="layui-form layui-col-space5">
                                <div class="layui-inline layui-show-xs-block">
                                    <input class="layui-input" autocomplete="off" placeholder="开始日" name="start" id="start"></div>
                                <div class="layui-inline layui-show-xs-block">
                                    <input class="layui-input" autocomplete="off" placeholder="截止日" name="end" id="end"></div>
                                <div class="layui-inline layui-show-xs-block">
                                    <input type="text" name="username" placeholder="请输入用户名" autocomplete="off" class="layui-input"></div>
                                <div class="layui-inline layui-show-xs-block">
                                    <button class="layui-btn" lay-submit="" lay-filter="sreach">
                                        <i class="layui-icon">&#xe615;</i></button>
                                </div>
                            </form>
                        </div> -->
                        <form class="layui-form layui-form-pane" action="">
                            <div class="layui-inline">
                                <label class="layui-form-label">搜索选择</label>
                                <div class="layui-input-inline">
                                    <select name="modules" lay-search="" lay-filter="select">
                                        <option value="">选择素材类型</option>
                                        <option value="image">image</option>
                                        <option value="video">video</option>
                                        <option value="voice">voice</option>
                                        <option value="news">news</option>
                                        <option value="music">music</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                        <div class="layui-card-body ">
                            <table class="layui-table" lay-data="{url:'{{ route('subscribeJson') }}',page:true,toolbar: '#toolbarDemo',id:'test'}" lay-filter="test">
                                <thead>
                                    <tr>
                                        
                                        <th lay-data="{field:'id', sort: true,width: 80}">ID</th>
                                        <th lay-data="{field:'type',  sort: true,width: 80}">类型</th>
                                        <th lay-data="{field:'media_id', sort: true,width: 240}">media_id</th>
                                        <th lay-data="{field:'name'}">name</th>
                                        <th lay-data="{field:'picUrl'}">图片地址</th>
                                        <th lay-data="{field:'url', edit: 'text'}">跳转地址</th>
                                        <th lay-data="{field:'title', edit: 'text'}">标题</th>
                                        <th lay-data="{field:'desc', edit: 'text'}">描述</th>
                                        <th lay-data="{field:'update_time' ,templet: '#time',width: 120}">更新时间</th>
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
    <!-- <script type="text/html" id="toolbarDemo">
        <div class = "layui-btn-container" > 
            <button class = "layui-btn layui-btn-sm" lay-event = "getCheckData" > 获取选中行数据 </button>
            <button class="layui-btn layui-btn-sm" lay-event="getCheckLength">获取选中数目</button > 
            <button class = "layui-btn layui-btn-sm" lay-event = "isAll" > 验证是否全选</button>
        </div > 
    </script> -->
    <script type="text/html" id="del">
        <button class="layui-btn-danger layui-btn layui-btn-xs"  onclick="member_del(this)" href="javascript:;" ><i class="layui-icon">&#xe640;</i>删除</button>
    </script>
    
    <script>
    layui.use(['laydate','form','table'],
        function() {
            var laydate = layui.laydate,
            table = layui.table,
            form = layui.form;

            //执行一个laydate实例
            laydate.render({
                elem: '#start' //指定元素
            });

            //执行一个laydate实例
            laydate.render({
                elem: '#end' //指定元素
            });

            //下拉菜单的监听
            form.on('select(select)', function(data){
                table.reload('test', {
                    url: "{{ route('subscribeJson') }}"
                    ,where: {type:data.value} //设定异步数据接口的额外参数
                    ,page: {
                        curr: 1 //重新从第 1 页开始
                    }
                });
               
                console.log(data.value); //得到被选中的值
            }); 

        });

        /*用户-删除*/
        function member_del(obj){
            layer.confirm('确认要删除吗？',function(index){
                //发异步删除数据
                var media_id = $(obj).parents('td').prev().prev().text();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.post(
                    "{{ route('delMaterial') }}",
                    {media_id:media_id},
                    function(res){
                        layer.msg(res.msg,{icon:res.code});
                        if (res.code == 1) {
                            $(obj).parents("tr").remove();
                            // layer.msg('已删除!',{icon:1,time:1000});
                        }
                    },'json'
                );
            });
        }
    </script>
    <script>
    layui.use('table',
        function() {
            var table = layui.table;

            //监听单元格编辑
            table.on('edit(test)',
            function(obj) {
                var value = obj.value //得到修改后的值
                ,
                data = obj.data //得到所在行所有键值
                ,
                field = obj.field; //得到字段
                //ajax的编辑
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.post(
                    "{{ route('editMaterial') }}",
                    {id:data.id,field:field,value:value},
                    function(res){
                        if (res.code == 1) {
                            if (value == '') {
                                layer.msg('[ID: ' + data.id + '] ' + field + ' 字段清空');
                            }else{
                                layer.msg('[ID: ' + data.id + '] ' + field + ' 字段更改为：' + value);
                            }
                        }else{
                            layer.msg(res.msg,{icon:res.code});
                        }
                    },'json'
                );
            });

            // //头工具栏事件
            // table.on('toolbar(test)',
            // function(obj) {
            //     var checkStatus = table.checkStatus(obj.config.id);
            //     switch (obj.event) {
            //         case 'getCheckData':
            //             var data = checkStatus.data;
            //             layer.alert(JSON.stringify(data));
            //             break;
            //         case 'getCheckLength':
            //             var data = checkStatus.data;
            //             layer.msg('选中了：' + data.length + ' 个');
            //             break;
            //         case 'isAll':
            //             layer.msg(checkStatus.isAll ? '全选': '未全选');
            //             break;
            //     };
            // });
        });
    </script>
    <script>var _hmt = _hmt || []; (function() {
            var hm = document.createElement("script");
            hm.src = "https://hm.baidu.com/hm.js?b393d153aeb26b46e9431fabaf0f6190";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();
    </script>

</html>