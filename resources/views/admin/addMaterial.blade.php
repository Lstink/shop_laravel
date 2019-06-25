@include('public.header')

<!-- <div class="loading" style="position:fixed;top:14%;left:22%;display:none;">
    <img src="{{ asset('images/loading.gif') }}" alt="">
</div> -->

<div class="layui-form-item">
    <label class="layui-form-label">选择上传类型</label>
    <div class="layui-input-block">
        <div class="layui-btn-group demoTest" style="margin-top: 5px;">
            <button class="layui-btn layui-btn-sm btns" type="file">文件消息</button>
            <button class="layui-btn layui-btn-sm btns" type="news">图文消息</button>
            <button class="layui-btn layui-btn-sm btns" type="video">视频消息</button>
            <button class="layui-btn layui-btn-sm btns" type="music">音乐消息</button>
        </div>
    </div>
    
</div>


<form class="layui-form layui-form-pane" action="{{ route('addFile') }}" method="post" enctype="multipart/form-data" style="margin: 10px 30px;">
@csrf    
    <div class="main_content">

        <div class="two dd" type="file" style="display:none;">
            <div class="layui-upload">
            <button type="button" class="layui-btn" id="test1"><i class="layui-icon"></i>上传文件</button>
            </div>
        </div>
        
        <div class="three dd" type="news" style="display:none;">
            
            <div class="layui-upload">
            <button type="button" class="layui-btn" id="test2"><i class="layui-icon"></i>上传图片</button>
            <input type="hidden" name="p_json" >
                <div class="layui-upload-list">
                    <img class="layui-upload-img" id="demo1" width="200px">
                    <p id="demoText"></p>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">标题</label>
                <div class="layui-input-block">
                    <input type="text" name="title" autocomplete="off" placeholder="请输入标题" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item layui-form-text">
                <label class="layui-form-label">描述</label>
                <div class="layui-input-block">
                    <textarea placeholder="请输入内容" class="layui-textarea desc" name="desc"></textarea>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">跳转地址</label>
                <div class="layui-input-block">
                    <input type="text" name="url" autocomplete="off" placeholder="请输入跳转地址" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn sub" type="button" lay-submit="" lay-filter="demo1" id="test5">立即提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </div>

        <div class="four dd" type="video" style="display: none;">
            
            <div class="layui-upload">
                <button type="button" class="layui-btn" id="test3"><i class="layui-icon"></i>上传视频</button>
                <input type="hidden" name="v_json">
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">标题</label>
                <div class="layui-input-block">
                    <input type="text" name="v_title" autocomplete="off" placeholder="请输入标题" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item layui-form-text">
                <label class="layui-form-label">描述</label>
                <div class="layui-input-block">
                    <textarea placeholder="请输入内容" class="layui-textarea v_desc" name="v_desc"></textarea>
                </div>
            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </div>

        <div class="five dd" type="music" style="display: none;">
            
            <div class="layui-upload">
                <button type="button" class="layui-btn" id="test4"><i class="layui-icon"></i>上传音乐</button>
                <input type="hidden" name="m_json">
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">标题</label>
                <div class="layui-input-block">
                    <input type="text" name="m_title" autocomplete="off" placeholder="请输入标题" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item layui-form-text">
                <label class="layui-form-label">描述</label>
                <div class="layui-input-block">
                    <textarea placeholder="请输入内容" class="layui-textarea m_desc" name="m_desc"></textarea>
                </div>
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
        layui.use(['layer','form','upload'], function(){
            var $ = layui.jquery,
            form = layui.form,
            upload = layui.upload,
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
                data.field.table = 2;
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
                }else if(type == 'news'){
                    // 获取desc
                    var desc = $('.desc').val();
                    var title = $('input[name=title]').val();
                    var url = $('input[name=url]').val();
                    var p_json = $('input[name=p_json]').val();
                    if (title == '') {
                        layer.msg('请输入标题',{icon:2});
                        return false;
                    }
                    if (desc == '') {
                        layer.msg('请输入描述信息',{icon:2});
                        return false;
                    }
                    if (url == '') {
                        layer.msg('请输入跳转地址',{icon:2});
                        return false;
                    }
                    if (p_json == '') {
                        layer.msg('请上传图片',{icon:2});
                        return false;
                    }
                    $.post(
                        "{{ route('addSubscribe') }}",
                        data.field,
                        function(res){
                            layer.msg(res.msg,{icon:res.code});
                        },'json'
                    );
                }else if(type == 'video'){
                    // 获取标题
                    var title = $('input[name=v_title]').val();
                    //获取描述
                    var desc = $('.v_desc').val();
                    //获取上传的文件地址
                    var v_json = $('input[name=v_json]').val();
                    
                    if (title == '') {
                        layer.msg('请输入标题',{icon:2});
                        return false;
                    }
                    if (desc == '') {
                        layer.msg('请输入描述信息',{icon:2});
                        return false;
                    }
                    if (v_json == '') {
                        layer.msg('请上传视频',{icon:2});
                        return false;
                    }
                    $.post(
                        "{{ route('addSubscribe') }}",
                        data.field,
                        function(res){
                            layer.msg(res.msg,{icon:res.code});
                        },'json'
                    );
                }else if(type == 'music'){
                    // 获取标题
                    var title = $('input[name=m_title]').val();
                    //获取描述
                    var desc = $('.m_desc').val();
                    //获取上传的文件地址
                    var m_json = $('input[name=m_json]').val();

                    if (title == '') {
                        layer.msg('请输入标题',{icon:2});
                        return false;
                    }
                    if (desc == '') {
                        layer.msg('请输入描述信息',{icon:2});
                        return false;
                    }
                    if (m_json == '') {
                        layer.msg('请上传音乐',{icon:2});
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

            //事件
            $(document).on('click','.btns',function(){
                //改变背景颜色
                $(this).css('background-color', '#5FB878').siblings().removeAttr('style');
                //后去类型
                var type = $(this).attr('type');
                // console.log(type);
                if (type == 'text') {
                    $('.one').show().siblings().hide();
                }else if(type == 'file'){
                    $('.two').show().siblings().hide();
                }else if(type == 'news'){
                    $('.three').show().siblings().hide();
                }else if(type == 'video'){
                    $('.four').show().siblings().hide();
                }else if(type == 'music'){
                    $('.five').show().siblings().hide();
                }
                //展示页面
            });

            //普通文件上传
            upload.render({
                elem: '#test1'
                ,url: "{{ route('uploadFileMaterial') }}"
                ,accept: 'file' //普通文件
                ,done: function(res){
                    if (res.code == 2) {
                        return layer.msg(res.msg,{icon:2});
                    }else if(res.code == 1){
                        return layer.msg('上传成功',{icon:1});
                    }
                }
            });

            //视频上传
            upload.render({
                elem: '#test3'
                ,url: "{{ route('uploadVideo') }}"
                ,accept: 'file' //普通文件
                ,done: function(res){
                    if (res.code == 2) {
                        return layer.msg(res.msg,{icon:2});
                    }else if(res.code == 1){
                        $('input[name=v_json]').val(res.msg);
                        return layer.msg('上传成功',{icon:1});
                    }
                    
                }
            });
            

            //音乐上传
            upload.render({
                elem: '#test4'
                ,url: "{{ route('uploadVideo') }}"
                ,accept: 'file' //普通文件
                ,done: function(res){
                    if (res.code == 2) {
                        return layer.msg(res.msg,{icon:2});
                    }else if(res.code == 1){
                        $('input[name=m_json]').val(res.msg);
                        return layer.msg('上传成功',{icon:1});
                    }
                    
                }
            });

            //普通图片上传
            var uploadInst = upload.render({
                elem: '#test2'
                ,url: "{{ route('uploadNews') }}"
                ,before: function(obj){
                //预读本地文件示例，不支持ie8
                obj.preview(function(index, file, result){
                    $('#demo1').attr('src', result); //图片链接（base64）
                });
                }
                ,done: function(res){
                    //如果上传失败
                    if(res.code != 1){
                        return layer.msg('上传失败',{icon:2});
                    }
                    //上传成功
                    $('input[name=p_json]').val(res.msg);
                    return layer.msg('上传成功',{icon:1});
                }
                ,error: function(){
                //演示失败状态，并实现重传
                var demoText = $('#demoText');
                demoText.html('<span style="color: #FF5722;">上传失败</span> <a class="layui-btn layui-btn-xs demo-reload">重试</a>');
                demoText.find('.demo-reload').on('click', function(){
                    uploadInst.upload();
                });
                }
            });
           
        });
    });
</script>
@include('public.footer')