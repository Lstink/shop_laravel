<?php

namespace App\Http\Controllers\weChat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Subscribe;
use App\Model\WeChat;
use App\Model\Keywords;
use App\Model\Material;
use App\Model\UserList;
use App\Model\ShopUser;
use App\Model\WxUser;
use App\shop\User;
use Illuminate\Support\Facades\Redis;

class AdminController extends Controller
{
    /**
     * @content  后台页面显示
     */
    public function index()
    {
        // $a = WeChat::getRedisAccess();
        // dd($a);
        return view('admin.index');
    }
    /**
     * @content 首次关注回复消息
     */
    public function subscribe()
    {
        return view('admin.subscribe');
    }
    /**
     * @content 上传图片到服务器
     */
    public function uploadNews()
    {
        //上传图片
        $photo = WeChat::uploads('file');
        $data = \json_encode(['code'=>1,'msg'=>$photo]);
        echo $data;
    }
    /**
     * @content 上传文件
     */
    public function uploadFile()
    {

        //上传文件
        $file = WeChat::uploads('file');
        //转化为数组
        $files = json_decode($file,true);
        //文件类型
        $ext = \explode('/',$files['ext'])[0];
        //上传素材
        $res = WeChat::permanentMaterial($file);
        //上传失败
        if (isset(json_decode($res,true)['errmsg'])) {
            //上传失败
            echo json_encode(['code'=>2,'msg'=>'上传失败，错误原因：'.\json_decode($res,true)['errmsg']]);
            exit;
        }
        //转化为数组
        $data = \json_decode($res,true);
        // dump($data);
        if (isset($data['media_id'])) {
            $info['media_id'] = $data['media_id'];
            $info['picUrl'] = $data['url']??null;
            $info['type'] = WeChat::getType($ext);
            $info['created_at'] = time();
            $re = Subscribe::insert($info);
            unset($info['created_at']);
            $info['update_time'] = time();
            $re = Material::insert($info);
            $data['code'] = 0;
        }else{
            $data['code'] = 2;
        }
        // dd($file);
        $data = \json_encode(['code'=>1,'msg'=>$file]);
        echo $data;
    }
    /**
     * @content 上传永久文件素材
     */
    public function uploadFileMaterial()
    {
        //上传文件
        $file = WeChat::uploads('file');
        //转化为数组
        $files = json_decode($file,true);
        //文件类型
        $ext = \explode('/',$files['ext'])[0];
        //上传素材
        $res = WeChat::permanentMaterial($file);
        //上传失败
        if (isset(json_decode($res,true)['errmsg'])) {
            //上传失败
            echo json_encode(['code'=>2,'msg'=>'上传失败，错误原因：'.\json_decode($res,true)['errmsg']]);
            exit;
        }
        //转化为数组
        $data = \json_decode($res,true);
        // dump($data);
        if (isset($data['media_id'])) {
            $info['media_id'] = $data['media_id'];
            $info['picUrl'] = $data['url']??null;
            $info['type'] = WeChat::getType($ext);
            $info['update_time'] = time();
            $re = Material::insert($info);
            $data['code'] = 0;
        }else{
            $data['code'] = 2;
        }
        // dd($file);
        $data = \json_encode(['code'=>1,'msg'=>$file]);
        echo $data;
    }
    /**
     * @content 上传视频
     */
    public function uploadVideo()
    {
        //上传视频
        $photo = WeChat::uploads('file');
        $data = \json_encode(['code'=>1,'msg'=>$photo]);
        echo $data;
    }
    /**
     * @content ajax添加关注自动回复
     */
    public function addSubscribe()
    {
        $type = request() -> type;
        if ($type == 'text') {
            $data = request() -> only(['content','type']);
        }else if($type == 'news'){
            //上传微信图文素材
            $jsonStr = request() -> p_json;
            $data = request() -> only(['title','desc','url']);
            $info = WeChat::uploadNewsMaterial($jsonStr,$data);
            //上传失败
            if (isset(\json_decode($info,true)['errmsg'])) {
                //上传失败
                echo json_encode(['code'=>2,'msg'=>'上传失败，错误原因：'.\json_decode($info,true)['errmsg']]);
                exit;
            }
            //获取id
            $media_id = \json_decode($info,true)['media_id'];
            //获取picUrl
            $picUrl = \json_decode($info,true)['picUrl']??null;
            $data['type'] = request() -> type;
            $data['media_id'] = $media_id;
            $data['picUrl'] = $picUrl;
        }else if($type == 'video'){
            //上传微信视频素材
            $data = request() -> v_json;
            $title = request() -> v_title;
            $desc = request() -> v_desc;
            //上传微信视频素材
            $info = WeChat::permanentMaterial($data,$title,$desc);
            //上传失败
            if (isset(\json_decode($info,true)['errmsg'])) {
                //上传失败
                echo json_encode(['code'=>2,'msg'=>'上传失败，错误原因：'.\json_decode($info,true)['errmsg']]);
                exit;
            }
            // dump($info);
            //获取media_id
            $media_id = \json_decode($info,true)['media_id'];
            $data = request() -> only('type');
            $data['title'] = $title;
            $data['desc'] = $desc;
            $data['media_id'] = $media_id;
        }else if($type == 'music'){
            //上传微信视频素材
            $data = request() -> m_json;
            //上传微信音乐素材
            $info = WeChat::permanentMaterial($data);
            //上传失败
            if (isset(\json_decode($info,true)['errmsg'])) {
                //上传失败
                echo json_encode(['code'=>2,'msg'=>'上传失败，错误原因：'.\json_decode($info,true)['errmsg']]);
                exit;
            }
            // dump($info);
            //获取media_id
            $media_id = \json_decode($info,true)['media_id'];
            $data = request() -> only('type');
            $data['title'] = request() -> v_title;
            $data['desc'] = request() -> v_desc;
            $data['media_id'] = $media_id;
        }
        // dd($data);
        // dump(request() -> all());
        if (request() ->table == 2) {
            $data['update_time'] = time();
            //添加素材到 material 表
            $res = Material::insert($data);
        }else{
            if ($type == 'text') {
                $data['created_at'] = time();
                //添加素材到 subscribe 表
                $res = Subscribe::insert($data);
            }else{
                $data['created_at'] = time();
                //添加素材到 subscribe 表
                $res = Subscribe::insert($data);
                unset($data['created_at']);
                $data['update_time'] = time();
                //添加素材到 material 表
                $res = Material::insert($data);
            }
        }
        if ($res) {
            echo json_encode(['code'=>1,'msg'=>'添加成功']);
        }else{
            echo json_encode(['code'=>2,'msg'=>'添加失败，请重新添加']);
        }
    }
    
    /**
     * @content 登陆页面
     */
    public function login()
    {
        return view('admin.login');
    }
    /**
     * @content 处理登陆
     */
    public function doLogin()
    {
        $username = request() -> username;
        $password = request() -> password;
        // $password = md5($password );
        if ($username == 'admin' && $password == '654321') {
            session(['wxUserInfo'=>'admin']);
            echo json_encode(['code'=>1,'msg'=>'登陆成功']);
        }else{
            echo json_encode(['code'=>2,'msg'=>'账号密码错误']);
        }
    }
    /**
     * 新增临时素材
     */
    public function doAdd()
    {
        //获取 access_token
        $access_token = WeChat::getRedisAccess();
        dd($access_token);
    }
    /**
     * @content 关键字回复页面
     */
    public function keywords()
    {
        return view('admin.keywords');
    }
    /**
     * @content 关键字列表展示
     */
    public function wordsList()
    {
        return view('admin.wordsList');
    }
    /**
     * @content 关键字添加
     */
    public function addKeywords()
    {
        $data = request() -> except('_token');
        $data['created_at'] = time();
        $res = Keywords::insert($data);
        if ($res) {
            echo json_encode(['code'=>1,'msg'=>'添加成功']);
        }else{
            echo json_encode(['code'=>0,'msg'=>'添加失败，请重新添加']);
        }
    }
    
    /**
     * @content 修改配置文件的回复类型
     */
    public function changeResponseType()
    {
        $type = request() -> type;
        //将配置写入配置文件中
        $arr = ['responseType'=>$type];
        $str = '<?php return '.var_export($arr,true).';';
        $res = \file_put_contents(\config_path('responseType.php'),$str);
        $typeArr = [
            'text' => '文字消息',
            'image' => '图片消息',
            'voice' => '语音消息',
            'music' => '音乐消息',
            'video' => '视频消息',
            'news' => '图文消息',
        ];
        if ($res) {
            echo \json_encode(['code'=>1,'msg'=>'成功设置回复类型为 '.$typeArr[$type]]);
        }
    }
    /**
     * @content 图文素材列表展示
     */
    public function subscribeList()
    {
        return view('admin.subscribeList');
    }
    /**
     * @content 获取json格式数据
     */
    public function subscribeJson()
    {
        $page = request() -> page;
        $limit = request() -> limit;
        $type = request() -> input('type',null);
        //计算跳过多少条
        $take = ($page-1)*$limit;
        // dd($take);
        //查询数据
        // $typeArr = ['image','voice','news','music','video'];
        if ($type) {
            //有类型的时候
            $data = Material::where('type',$type) ->offset($take) -> limit($limit) -> get() ->toArray();
            $count = Material::where('type',$type) -> count();
        }else{
            //没有类型的时候
            $data = Material::offset($take) -> limit($limit) -> get() ->toArray();
            $count = Material::count();
        }
        // dd($data);
        foreach ($data as $k => $v) {
            $data[$k]['update_time'] = date('Y-m-d H:i:s',$v['update_time']);
        }
        $arr = [
            'code' => 0,
            'msg' => '',
            'count' => $count,
            'data' => $data,
        ];
        $info = \json_encode($arr);
        echo $info;
    }
    /**
     * @content 从微信获取永久素材列表
     */
    public function getRemoteMaterial()
    {
        $access_token = WeChat::getRedisAccess();
        $url = "https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=$access_token";
        // $typeArr = ['voice'];
        $typeArr = ['image','video','voice','news','music'];
        $sum = count($typeArr);
        //循环$num次获取素材数据
        for ($i=0; $i < $sum; $i++) { 
            $type = $typeArr[$i];
            //查询数据库中有多少条数据
            $offset = Material::where('type',$type) -> count();
            $count = 20;
            $data = [
                'type' => $type,
                'offset' => $offset,
                'count' => $count,
            ];
            $data = json_encode($data);
            //发送请求
            $info = WeChat::curlPost($url,$data);
            $data = \json_decode($info,true);
            //判断是否有错误
            if (!empty($data['errmsg'])) {
                dd(['msg'=>'错误码：'.$data['errcode'].'，错误信息：'.$data['errmsg']]);
                exit;
            }
            // WeChat::getError($info);
            dump($info);
            $info = \json_decode($info,true)['item'];
            //处理数组
            foreach ($info as $k => $v) {
                $info[$k]['type'] = $type;
                if (isset($v['url'])) {
                    $info[$k]['picUrl'] = $v['url'];
                    unset($info[$k]['url']);
                }
            }
            //存入数据库
            dump($type);
            dump($info);
            $res = Material::insert($info);
            // dd($info);
        }

        
    }
    /**
     * @content 删除永久素材
     */
    public function delMaterial()
    {
        $media_id = request() -> media_id;
        $res = WeChat::delMaterial($media_id);
        // $res = '{"errcode":0,"errmsg":"ok"}';
        $res = json_decode($res,true);
        if ($res['errcode'] == 0) {
            //删除成功 再删除数据库
            $re = Material::where(['media_id'=>$media_id]) -> delete();
            if ($re) {
                echo json_encode(['code'=>1,'msg'=>'删除成功']);
            }else{
                echo json_encode(['code'=>0,'msg'=>'本地数据库删除失败']);
            }
            
        }else{
            echo json_encode(['code'=>2,'msg'=>'删除失败，错误码：'.$res['errcode'].'，错误信息：'.$res['errmsg']]);
        }
    }
    /**
     * @content 展示上传素材的页面
     */
    public function addMaterial()
    {
        return view('admin/addMaterial');
    }
    /**
     * @content 编辑素材
     */
    public function editMaterial()
    {
        $data = request() -> post();
        $id = $data['id'];
        $field = $data['field'];
        $value = $data['value'];
        $res = Material::where('id',$id) -> update([$field=>$value]);
        if ($res) {
            echo \json_encode(['code'=>1,'msg'=>'修改成功']);
        }else{
            echo \json_encode(['code'=>2,'msg'=>'修改失败']);
        }
    }
    
    /**
     * 将城市信息入库
     */
    public function addCity()
    {
        $config = config('city');
        // dump($cong);
        // $config = json_decode($config[0],true);
        $str = trim($config,'""');
        dd($str);
    }
    /**
     * @content 群发消息列表
     */
    public function userList()
    {
        return view('admin/userList');
    }
    /**
     * @content 获取用户列表信息
     */
    public function getUserList()
    {
        $page = request() -> page;
        $limit = request() -> limit;
        $type = request() -> input('type',null);
        //计算跳过多少条
        $take = ($page-1)*$limit;
        //获取所有用户的openid
        $userList = new UserList;
        $data = $userList -> offset($take) -> limit($limit) -> get() ->toArray();
        foreach ($data as $k => $v) {
            $data[$k]['created_at'] = date('Y-m-d H:i:s',$v['created_at']);
        }
        $count = $userList -> count();
        $arr = [
            'code' => 0,
            'msg' => '',
            'count' => $count,
            'data' => $data,
        ];
        $info = \json_encode($arr);
        echo $info;
    }
    /**
     * @content 从微信服务器获取关注用户的oppenid
     */
    public function getRemoteUser()
    {
        //获取所有用户的openid
        $info = WeChat::getUserList();
        // dump($info);
        $data = $info['data']['openid'];
        $arr = [];
        foreach ($data as $k => $v) {
            $arr[] = ['openid'=>$v,'created_at'=>time()];
        }
        dump($arr);
        $userList = new UserList;
        $userList -> truncate();
        $res = $userList -> insert($arr);
        dd($res);
    }
    /**
     * @content 发送群组信息
     */
    public function sendGroupMsg()
    {
        $ids = request() -> id;
        $content = request() -> content;
        $type = request() -> type??null;
        $ids = explode(',',$ids);
        if(!$type){
            //根据id查询oppenid
            $data = UserList::whereIn('id',$ids) -> select('openid') -> get() -> toArray();
            // dd($data);
            $openids = [];
            foreach ($data as $k => $v) {
                array_push($openids,$v['openid']);
            }
            // $content = '您好啊';
            $res = WeChat::sendGroupMsgById($openids,$content);
        }else{
            
            $res = WeChat::sendGroupMsgByTag($ids[0],$content);
        }
        $res = json_decode($res,true);
        // dump($res);
        if (!$type) {
            if ($res['errcode'] == 0) {
                echo json_encode(['code'=>1,'msg'=>'群发成功']);
            }else if($res['errcode'] == 45065){
                echo json_encode(['code'=>2,'msg'=>'该消息发送重复']);
            }else if($res['errcode'] == 40130){
                echo json_encode(['code'=>2,'msg'=>'无效的用户id,请选择至少两个用户']);
            }else{
                echo json_encode(['code'=>2,'msg'=>'未知错误'.$res['errcode'].$res['errmsg']]);
            }
        }else{
            if ($res['errcode'] == 0) {
                echo json_encode(['code'=>1,'msg'=>'群发成功']);
            }else if($res['errcode'] == 45065){
                echo json_encode(['code'=>2,'msg'=>'该消息发送重复']);
            }else if($res['errcode'] == 40130){
                echo json_encode(['code'=>2,'msg'=>'无效的标签id']);
            }else{
                echo json_encode(['code'=>2,'msg'=>'未知错误'.$res['errcode'].$res['errmsg']]);
            }
        }
        
    }
    /**
     * @content 弹出窗口展示
     */
    public function alertGroupMsg()
    {
        $id = request() -> id;
        $type = request() -> type;
        // dd($type);
        return view('admin.alertGroupMsg',['id'=>$id,'type'=>$type]);
    }
    /**
     * @content 清空Redis中的access_token
     */
    public function clearToken()
    {
        $re = Redis::del('access_token');
        dump($re);
        $res = Redis::get('access_token');
        dd($res);
    }
    /**
     * @content 获取微信授权信息
     */
    public function weChatLogin()
    {
        //接收code
        $code = request() -> code??null;
        $uniqid = request() -> uniqid??456;
        $state = request() -> state??null;
        $appid = env('APPID');
        //判断code是否存在
        if (empty($code)) {
            //判断uniqid是否存在
            if ($uniqid != 456) {
                //扫码登陆
                if (Redis::get($uniqid)) {
                    //存在说明二维码已经被扫过
                    echo '该二维码已被其它用户使用！';
                    exit;
                }else{
                    //第一次扫
                    $val = serialize(['uniqid'=>$uniqid,'status'=>1]);
                    Redis::Setex($uniqid,60,$val);
                }
            }
            //不存在则为跳转授权页面
            $redirect_uri = urlencode(route('weChatLogin'));
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=$uniqid#wechat_redirect";
            return redirect($url);
        }else{
            //存在则为授权回调页面
            $secret = env('APPSECRET');
            //获取access_token
            $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
            $res = file_get_contents($url);
            $arr = json_decode($res,true);
            // dump($arr);
            $access_token = $arr['access_token'];
            $openid = $arr['openid'];
            //拉取用户信息
            $url = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
            $res = file_get_contents($url);
            $arr = json_decode($res,true);
            // dd($arr);
            //判断是否扫码登陆
            if (!empty($state)) {
                //扫码登陆
                $arr = unserialize(Redis::get($state));
                $arr['openid'] = $openid;
                $arr['status'] = 2;
                $str = serialize($arr);
                Redis::set($state,$str);
                
            }
            //先查询是否绑定过
            unset($arr['privilege']);
            //查询是否绑定过
            $res = WxUser::where('openid',$arr['openid']) -> value('wx_id');
            if (!$res) {
                //没绑定过
                return redirect('/binding')->with('msg','请先绑定商城账号');
            }
            //查询该用户的数据
            $res = User::where('wx_id',$res) -> first();
            dump($arr);
            if ($res) {
                //添加成功
                $userInfo = [
                    'u_id' => $res['u_id'],
                    'u_email' => $arr['nickname'],
                    'headimgurl' => $arr['headimgurl'],
                ];
                session(['userInfo'=>$userInfo]);
                return redirect('/index');
            }else{
                echo '未知错误，请重试';
                return redirect('/index');
            }
        }
        
    }
    /**
     * @content 展示绑定表单页面
     */
    public function binding()
    {
        return view('admin.binding');
    }
    /**
     * @content 发送验证码
     */
    public function sendCode()
    {
        $account = request() -> account;
        //查询数据库中该账号是否存在
        $res = User::where('u_email',$account) ->orWhere('u_phone',$account) -> count();
        if ($res) {
            //存在则发送验证码
            $res = WeChat::sendCode($account);
            echo $res;
        }else{
            //不存在提醒注册
            echo json_encode(['code'=>3,'msg'=>'该账号不存在，请先注册商城账号']);
        }
    }
    /**
     * @content 绑定商城账号
     */
    public function doBinding()
    {
        $data = request() -> post();
        $account = $data['account'];
        //判断验证码是否正确
        $code = session('code');
        if ($data['code'] != $code['code']) {
            echo json_encode(['code'=>2,'msg'=>'验证码不正确']);
            exit;
        }
        if ($data['account'] != $code['account']) {
            echo json_encode(['code'=>2,'msg'=>'两次输入邮箱不一致']);
            exit;
        }
        
        //查询该账号是否绑定过
        $res = User::where('u_email',$account) ->orWhere('u_phone',$account) -> value('wx_id');
        if ($res) {
            echo json_encode(['code'=>2,'msg'=>'该账号已绑定商城，请勿重新绑定']);
            exit;
        }
        //绑定商城用户
        $res = $this -> getWxUserInfo($account);
        // dump($res);
        $re = json_decode($res,true);
        if ($re['code'] == 3) {
            echo $res;
        }
    }
    /**
     * @content 获取微信授权信息
     */
    public function getWxUserInfo($account='')
    {
        //接收code
        $code = $_GET['code']??null;
        // dump($code);
        $appid = env('APPID');
        //判断code是否存在
        if (empty($code)) {
            //不存在则为跳转授权页面
            
            $redirect_uri = urlencode(route('getWxUserInfo'));
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=458#wechat_redirect";
            return json_encode(['code'=>3,'url'=>$url]);
        }else{
            $account = session('code')['account'];
            //存在则为授权回调页面
            $secret = env('APPSECRET');
            //获取access_token
            $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
            $res = file_get_contents($url);
            $arr = json_decode($res,true);
            $access_token = $arr['access_token'];
            $openid = $arr['openid'];
            //拉去用户信息
            $url = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
            $res = file_get_contents($url);
            $arr = json_decode($res,true);
            // dd($arr);
            //先查询是否绑定过
            unset($arr['privilege']);
            //将数据添加到数据库
            $id = WxUser::insertGetId($arr);
            //绑定商城账号
            $res = User::where('u_email',$account) ->orWhere('u_phone',$account) -> first();
            $res -> wx_id = $id;
            $result = $res -> save();
            // dd($res);
            if ($result) {
                $userInfo = [
                    'u_id' => $res['u_id'],
                    'u_email' => $arr['nickname'],
                    'headimgurl' => $arr['headimgurl'],
                ];
                session(['userInfo'=>$userInfo]);
                return redirect('/index');
            }else{
                echo '绑定失败，请重试';
            }
        }
    }
    /**
     * @content 获取redis中的扫码状态
     */
    public function getUniqidStatus()
    {
        //接收uniqid
        $uniqid = request() -> uniqid;
        //获取当前redis中的值
        $str = Redis::get($uniqid);
        if (!empty($str)) {
            //存在
            $arr = unserialize($str);
            $status = $arr['status'];
        }else{
            $status = 0;
        }
        //判断status的值
        echo $status;
    }
}
