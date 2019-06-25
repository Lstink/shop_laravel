<?php


namespace App\Model;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;
use App\shop\User;
use Mail;

class WeChat extends Model
{
    /**
     * @content 发送文字消息
     */
    public static function sendTextMsg($postObj,$Content)
    {
        //获取发送人
        $ToUserName = $postObj -> FromUserName;
        //获取接收人
        $FromUserName = $postObj -> ToUserName;
        //当前时间戳
        $CreateTime = time();
        $textMsg = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA[%s]]></Content>
        </xml>";
        $resultStr = sprintf($textMsg,$ToUserName,$FromUserName,$CreateTime,$Content);
        echo $resultStr;
    }
    /**
     * @content 发送图片消息
     */
    public static function sendImageMsg($postObj,$MediaId)
    {
        //获取发送人
        $ToUserName = $postObj -> FromUserName;
        //获取接收人
        $FromUserName = $postObj -> ToUserName;
        //当前时间戳
        $CreateTime = time();
        $textMsg = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[image]]></MsgType>
            <Image>
                <MediaId><![CDATA[%s]]></MediaId>
            </Image>
        </xml>";
        $resultStr = sprintf($textMsg,$ToUserName,$FromUserName,$CreateTime,$MediaId);
        echo $resultStr;
    }
    /**
     * @content 发送语音消息
     */
    public static function sendVoiceMsg($postObj,$MediaId)
    {
        //获取发送人
        $ToUserName = $postObj -> FromUserName;
        //获取接收人
        $FromUserName = $postObj -> ToUserName;
        //当前时间戳
        $CreateTime = time();
        $textMsg = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[voice]]></MsgType>
            <Voice>
                <MediaId><![CDATA[%s]]></MediaId>
            </Voice>
        </xml>";
        $resultStr = sprintf($textMsg,$ToUserName,$FromUserName,$CreateTime,$MediaId);
        echo $resultStr;
    }
    /**
     * @content 发送视频消息
     */
    public static function sendVideoMsg($postObj,$data)
    {
        //获取发送人
        $ToUserName = $postObj -> FromUserName;
        //获取接收人
        $FromUserName = $postObj -> ToUserName;
        //当前时间戳
        $CreateTime = time();
        $textMsg = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[video]]></MsgType>
            <Video>
                <MediaId><![CDATA[%s]]></MediaId>
                <Title><![CDATA[%s]]></Title>
                <Description><![CDATA[%s]]></Description>
            </Video>
        </xml>";
        $resultStr = sprintf($textMsg,$ToUserName,$FromUserName,$CreateTime,$data['media_id'],$data['title'],$data['desc']);
        echo $resultStr;
    }
    /**
     * @content 发送图文消息
     */
    public static function sendNewsMsg($postObj,$data)
    {
        //获取发送人
        $ToUserName = $postObj -> FromUserName;
        //获取接收人
        $FromUserName = $postObj -> ToUserName;
        //当前时间戳
        $CreateTime = time();
        $item = "<item>
            <Title><![CDATA[%s]]></Title>
            <Description><![CDATA[%s]]></Description>
            <PicUrl><![CDATA[%s]]></PicUrl>
            <Url><![CDATA[%s]]></Url>
        </item>";
        $itemStr = sprintf($item,$data['title'],$data['desc'],$data['picUrl'],$data['url']);
        $textMsg = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[news]]></MsgType>
            <ArticleCount>1</ArticleCount>
            <Articles>
                %s
            </Articles>
        </xml>";
        
        $resultStr = sprintf($textMsg,$ToUserName,$FromUserName,$CreateTime,$itemStr);
        echo $resultStr;
    }
    /**
     * @content 发送图文消息
     */
    public static function sendMusicMsg($postObj,$data)
    {
        //获取发送人
        $ToUserName = $postObj -> FromUserName;
        //获取接收人
        $FromUserName = $postObj -> ToUserName;
        //当前时间戳
        $CreateTime = time();
        $textMsg = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[music]]></MsgType>
            <Music>
                <Title><![CDATA[%s]]></Title>
                <Description><![CDATA[%s]]></Description>
                <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
            </Music>
        </xml>";
        
        $resultStr = sprintf($textMsg,$ToUserName,$FromUserName,$CreateTime,$data['title'],$data['desc'],$data['media_id']);
        echo $resultStr;
    }
    /**
     * @content 发送模版消息
     */
    public static function sendTemplateMsg($postObj,$data)
    {
        // dump($postObj);
        //获取发送人
        $ToUserName = (string)$postObj -> FromUserName;
        // dd($ToUserName);
        //处理数据
        $payStatus = [
            '1' => '未支付',
            '2' => '已支付',
        ];
        $payType = [
            '1' => '支付宝支付',
            '2' => '微信支付',
        ];
        $orderStatus = [
            '1' => '未支付',
            '2' => '待发货',
            '3' => '已发货',
            '4' => '已收货',
        ];
        $data['pay_status'] = $payStatus[$data['pay_status']];
        $data['pay_type'] = $payType[$data['pay_type']];
        $data['order_status'] = $orderStatus[$data['order_status']];
        $data['create_time'] = date('Y-m-d H:i:s',$data['create_time']);
        
        //获取模版ID
        $template_id = 'OoIiFOg1xL3pGOotgAw_GIVDg0MZXIRpcUL4ZPYszmk';
        $url = 'http://www.yeyunyang.xyz';
        //当前时间戳
        $CreateTime = time();
        $access_token = self::getRedisAccess();
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$access_token";
        $arr = [
            'touser' => $ToUserName,
            'template_id' => $template_id,
            'url' => $url,
            'data' => [
                'order_no' => [
                    'value' => $data['order_no'],
                    'color' => "#173177"
                ],
                'order_amount' => [
                    'value' => $data['order_amount'],
                    'color' => "#173177"
                ],
                'pay_status' => [
                    'value' => $data['pay_status'],
                    'color' => "#173177"
                ],
                'pay_type' => [
                    'value' => $data['pay_type'],
                    'color' => "#173177"
                ],
                'order_status' => [
                    'value' => $data['order_status'],
                    'color' => "#173177"
                ],
                'create_time' => [
                    'value' => $data['create_time'],
                    'color' => "#173177"
                ]
            ]
        ];
        // dump($arr);
        $json = json_encode($arr);
        // dump($json);
        $res = self::curlPost($url,$json);
        // dd($res);
        return $res;
    }
    /**
     * @content 发送天气消息
     */
    public static function sendWeatherMsg($postObj,$data)
    {
        // dump($postObj);
        //获取发送人
        $ToUserName = (string)$postObj -> FromUserName;
        //获取模版ID
        $template_id = 'QlDh7InmaAf8EBj15cVuWPjyBd3QIWsAoEAbRtA4svo';
        //当前时间戳
        $access_token = self::getRedisAccess();
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$access_token";
        $arr = [
            'touser' => $ToUserName,
            'template_id' => $template_id,
            'data' => [
                'city' => [
                    'value' => $data['city'],
                    'color' => "#173177"
                ],
                'date' => [
                    'value' => $data['date'],
                    'color' => "#173177"
                ],
                'week' => [
                    'value' => $data['week'],
                    'color' => "#173177"
                ],
                'update_time' => [
                    'value' => $data['update_time'],
                    'color' => "#173177"
                ],
                'wea' => [
                    'value' => $data['wea'],
                    'color' => "#173177"
                ],
                'tem' => [
                    'value' => $data['tem'],
                    'color' => "#173177"
                ],
                'win' => [
                    'value' => $data['win'],
                    'color' => "#173177"
                ],
                'win_meter' => [
                    'value' => $data['win_meter'],
                    'color' => "#173177"
                ],
                'visibility' => [
                    'value' => $data['visibility'],
                    'color' => "#173177"
                ],
                'pressure' => [
                    'value' => $data['pressure'],
                    'color' => "#173177"
                ],
                'air_pm25' => [
                    'value' => $data['air_pm25'],
                    'color' => "#173177"
                ],
                'air_level' => [
                    'value' => $data['air_level'],
                    'color' => "#173177"
                ],
                'air_tips' => [
                    'value' => $data['air_tips'],
                    'color' => "#173177"
                ]
                
            ]
        ];
        // dump($arr);
        $json = json_encode($arr);
        // dump($json);
        $res = self::curlPost($url,$json);
        // dd($res);
        return $res;
    }
    /**
     * @content 发送群发消息
     */
    public static function sendGroupMsgById($openid,$content)
    {
        $access_token = self::getRedisAccess();
        $url = "https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=$access_token";
        $data = [
            'touser' => $openid,
            'msgtype' => 'text',
            'text' => ['content' => urlencode($content)]
        ];
        $data = urldecode(json_encode($data));
        $res = self::curlPost($url,$data);
        return $res;
    }
    /**
     * @content通过标签群发消息
     */
    public static function sendGroupMsgByTag($tag,$content)
    {
        $access_token = self::getRedisAccess();
        $url = "https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=$access_token";
        $data = [
            'filter' => [
                'is_to_all' => false,
                'tag_id' => $tag,
            ],
            'text' => ['content' => urlencode($content)],
            'msgtype' => 'text',
        ];
        $data = urldecode(json_encode($data));
        // dd($data);
        $res = self::curlPost($url,$data);
        return $res;
    }
    /**
     * @content 图灵机器人的回复方法
     */
    public static function tuLing($keyWords)
    {
        $url = 'http://openapi.tuling123.com/openapi/api/v2';
        $data = [
            'reqType' => 0,
            'perception' => [
                'inputText' => [
                    'text' => $keyWords
                ],
            ],
            'userInfo' => [
                'apiKey' => '3afa48e52770400b8a4c0a5c723fc2b5',
                'userId' => '3afa48e52770400b8a4c0a5c723fc2b5',
            ]
        ];
        $dataJson = \json_encode($data);
        $output = self::curlPost($url,$dataJson);
        $output = \json_decode($output,true);
        return $output;
    }
    /**
     * @content 发送请求
     */
    public static function curlPost($url,$data)
    {
        //初使化init方法
        $ch = curl_init();
        //指定URL
        curl_setopt($ch, CURLOPT_URL, $url);
        //设定请求后返回结果
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //声明使用POST方式来进行发送
        curl_setopt($ch, CURLOPT_POST, 1);
        //发送什么数据呢
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        //忽略证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        //忽略header头信息
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //设置超时时间
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        //发送请求
        $output = curl_exec($ch);
        //关闭curl
        curl_close($ch);
        //返回数据
        return $output;
    }
    /**
     * @content 获取access_token存文件
     */
    public static function getToken()
    {
        //文件的路径
        $tokenUrl = public_path('access_token.txt');
        //读取文件信息
        $accessInfo = file_get_contents($tokenUrl);
        //判断是否过期
        $accessInfo = unserialize($accessInfo);
        if (empty($accessInfo['getTime']) || $accessInfo['getTime']<time()) {
            //过期 重新获取 access_token
            $appId = env('APPID');
            $appsecret = env('APPSECRET');
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appId.'&secret='.$appsecret;
            $accessInfo = json_decode(file_get_contents($url),true);
            unset($accessInfo['expires_in']);
            $accessInfo['getTime'] = time()+7000;
            //存入文件中
            // chmod($tokenUrl,777);
            file_put_contents($tokenUrl,serialize($accessInfo));
        }
        return $accessInfo['access_token'];
    }
    /**
     * @content 获取access_token存 Redis
     */
    public static function getRedisAccess()
    {
        //从Redis中获取数据
        $accessInfo = Redis::get('access_token');
        //判断是否过期
        if (empty($accessInfo)) {
            //过期 重新获取 access_token
            $appId = env('APPID');
            $appsecret = env('APPSECRET');
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appId.'&secret='.$appsecret;
            $accessInfo = json_decode(file_get_contents($url),true);
            unset($accessInfo['expires_in']);
            //存入Redis中
            $accessInfo = serialize($accessInfo);
            Redis::Setex('access_token','7000',$accessInfo);
        }
        $accessInfo = unserialize($accessInfo);
        return $accessInfo['access_token'];
    }
    /**
     * @content 文件上传
     * 
     */
    public static function uploads($file)
    {
        if (request()->hasFile($file) && request()->file($file)->isValid()) {
            //文件上传
            $file = request()->file($file);
            //生成时间
            $fileName = date('Ymd');
            $extension = $file->getClientOriginalExtension();
            $name = time().rand(1000,9999).'.'.$extension;
            // dd($extension);
            $store_result = $file->storeAs($fileName,$name);
            //获取文件的类型
            $ext = $file -> getClientMimeType();//"image/gif"
            //上传成功
            return \json_encode(['code'=>0,'msg'=>$store_result,'ext'=>$ext]);
        }
    }
    /**
     * @content 确认文件类型
     */
    public static function getType($type)
    {
        $typeArr = [
            'image' => 'image',
            'audio' => 'voice',
            'video' => 'video',
            'thumb' => 'thumb',
        ];
        if (!empty($typeArr[$type])) {
            return $typeArr[$type];
        }
    }
    /**
     * @content 文件上传微信临时素材
     * @data 上传服务器完成后返回的字符串 json 
     */
    public static function upFewTimeWeChat($data)
    {
        //转化为数组
        $data = json_decode($data,true);
        //文件类型
        $ext = \explode('/',$data['ext'])[0];
        //服务器图片的真实路径
        $path = storage_path('app/storage/uploads'.'/'.$data['msg']);
        //转化文件类型
        $type = self::getType($ext);
        //获取token
        $access_token = self::getRedisAccess();
        $url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=".$access_token."&type=".$type;
        //提交文件
        $info = ['media'=> new \CURLFile(realpath($path))];
        //利用curl提交文件
        $res = WeChat::curlPost($url,$info);
        return $res;
    }
    /**
     * @content 文件上传微信永久素材
     * @data 上传服务器完成后返回的字符串 json 
     */
    public static function permanentMaterial($data,$title='',$desc='')
    {
        //转化为数组
        $data = json_decode($data,true);
        //文件类型
        $ext = \explode('/',$data['ext'])[0];
        //服务器图片的真实路径
        $path = storage_path('app/storage/uploads'.'/'.$data['msg']);
        //转化文件类型
        $type = self::getType($ext);
        //获取token
        $access_token = self::getRedisAccess();
        $url = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=".$access_token."&type=".$type;
        //提交文件
        $info['media'] = new \CURLFile(realpath($path));

        if ($title && $desc) {
            //如果存在$title和$desc则为上传视频
            $info['description'] = json_encode([
                'title' => $title,
                'introduction' => $desc,
            ]);
        }
        //利用curl提交文件
        $res = WeChat::curlPost($url,$info);
        // $res = '{"media_id":123456789,"url":"56555555"}';
        // dd($res);
        return $res;
    }
    /**
     * @content 新增图文素材
     */
    public static function uploadNewsMaterial($jsonStr,$data)
    {
        //上传图文图片
        $resJson = self::upNewsPhoto($jsonStr);
        $resStr = json_decode($resJson,true);
        $picUrl = $resStr['url'];
        //上传该图片到永久素材获取media_id
        $materialJson = self::permanentMaterial($jsonStr);
        $materialArr = json_decode($materialJson,true);
        // dd($materialArr);
        $media_id = $materialArr['media_id'];
        $info = [
            "articles" => [
                [
                    "title" => $data['title'],
                    "thumb_media_id" => $media_id,
                    "author" => 'Lstink',
                    "show_cover_pic" => 1,
                    "content" => $data['desc'],
                    "content_source_url" => $data['url'],
                    "need_open_comment" => 1,
                    "only_fans_can_comment" => 1
                ],
   
           ]
        ];
        $info = json_encode($info);
        //获取token
        $access_token = WeChat::getRedisAccess();
        $url = 'https://api.weixin.qq.com/cgi-bin/material/add_news?access_token='.$access_token;
        $res = WeChat::curlPost($url,$info);
        // $res = '{"media_id":"8L9459eH8W2_O-GTinboQxrQvCjvafEPamT5K0Mx4kA"}';
        // dd($res);
        $picUrl = $materialArr['url'];
        $resArr = json_decode($res,true);
        $resArr['picUrl'] = $picUrl;
        $resJson = json_encode($resArr);
        return $resJson;
    }
    /**
     * @content 上传图文素材的图片
     */
    public static function upNewsPhoto($jsonStr)
    {
        $arr = json_decode($jsonStr,true);
        //服务器图片的真实路径
        $path = storage_path('app/storage/uploads'.'/'.$arr['msg']);
        $access_token = self::getRedisAccess();
        $url = "https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token=$access_token";
        $info = ['media'=> new \CURLFile(realpath($path))];
        $res = self::curlPost($url,$info);
        // $res = '{"url":"http:\/\/mmbiz.qpic.cn\/mmbiz_jpg\/cwzGpibB3ibPOPVsuN6m73LnX7M6ul8Ozcp8DgRv4QuqiaFeLhNXJ9aq2ZUBooN4L95UNXTRWgZvW5b7QgRxoLj4w\/0"}';
        return $res;

    }
    /**
     * @content 处理错误请求信息
     */
    public static function getError($json)
    {
        $data = \json_decode($json,true);
        if (!empty($data['errmsg'])) {
            return \json_encode(['code'=>2,'msg'=>'错误码：'.$data['errcode'].'，错误信息：'.$data['errmsg']]);exit;
        }
    }
    /**
     * @content 删除永久素材
     */
    public static function delMaterial($media_id)
    {
        $access_token = self::getRedisAccess();
        $url = "https://api.weixin.qq.com/cgi-bin/material/del_material?access_token=$access_token";
        $info = [
            'media_id' => $media_id,
        ];
        $info = json_encode($info);
        $res = self::curlPost($url,$info);
        return $res;
    }
    /**
     * @content 删除标签
     */
    public static function delTag($id)
    {
        $access_token = self::getRedisAccess();
        $url = "https://api.weixin.qq.com/cgi-bin/tags/delete?access_token=$access_token";
        $info = [
            'tag' => ['id'=>$id],
        ];
        $info = json_encode($info);
        $res = self::curlPost($url,$info);
        return $res;
    }
    /**
     * @content 调用天气接口
     * @ $city 地址名称 如：北京
     */
    public static function sendWeather($city)
    {
        $host = "https://www.tianqiapi.com/api/?version=v6&city=$city";
        $info = file_get_contents($host);
        $info = json_decode($info,true);
        return $info;
        
    }
    /**
     * @content 获取用户列表
     */
    public static function getUserList()
    {
        $access_token = self::getRedisAccess();
        $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=$access_token";
        $json = file_get_contents($url);
        $arr = json_decode($json,true);
        return $arr;
    }
    /**
     * @content 创建自定义菜单
     * @ $arr 三维数组
     */
    public static function createMenu($data,$person='')
    {
        $arr = [];
        foreach ($data as $k => $v) {
            //判断是否有子类
            $secondArr = [];
            if (isset($v['child']) && !empty($v['child'])) {
                //如果有二级分类 便利二级分类数组 并拼接数组
                foreach ($v['child'] as $key => $val) {
                    //判断该条数据的type是什么
                    if ($val['type'] == 'click') {
                        $type = 'key';
                    }else{
                        $type = 'url';
                    }
                    $secondArr[] = [
                        'type' => $val['type'],
                        'name' => $val['name'],
                        $type => $val[$type],
                    ];
                }
                //一级分类的拼接
                $arr[] = [
                    'name' => $v['name'],
                    'sub_button' => $secondArr,
                ];
            }else{
                //如果没有二级菜单 一级分类的拼接
                //判断该条数据的type是什么
                if ($v['type'] == 'click') {
                    $type = 'key';
                }else{
                    $type = 'url';
                }
                $arr[] = [
                    'type' => $v['type'],
                    'name' => $v['name'],
                    $type => $v[$type],
                ];
            }
        }
        $newArr['button'] = $arr;
        $access_token = self::getRedisAccess();
        if (!empty($person)) {
            //获取数组中的值 个性化菜单
            $newArr['matchrule'] = $person;
            $url = "https://api.weixin.qq.com/cgi-bin/menu/addconditional?access_token=$access_token";
        }else{
            //创建普通菜单
            $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$access_token";
        }
        $json = json_encode($newArr,JSON_UNESCAPED_UNICODE);
        $res = self::curlPost($url,$json);
        return $res;
    }
    /**
     * @content 删除个性化菜单
     */
    public static function delPersonMenu($id)
    {
        $access_token = self::getRedisAccess();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/delconditional?access_token=$access_token";
        $arr = [
            'menuid' => $id
        ];
        $json = json_encode($arr);
        $res = self::curlPost($url,$json);
        return $res;
    }
    /**
     * @content 发送验证码的方法
     */
    public static function sendCode($account)
    {
        //检查账号是否存在
        $res = User::where('u_email',$account) ->orWhere('u_phone',$account) -> count();
        if (!$res) {
            return json_encode(['code'=>2,'msg'=>'该账号不存在请核对后重试']);
        }else{
            //判断是手机号还是邮箱
            //生成验证码
            $code = rand(1,9).rand(1,9).rand(1,9).rand(1,9).rand(1,9).rand(1,9);
            if (preg_match("/^\d{11}$/", $account)) {
                //手机号
                $host = "http://dingxin.market.alicloudapi.com";
                $path = "/dx/sendSms";
                $method = "POST";
                $appcode = "f6496824839f44b2bba9bafe8b154d95";
                $headers = array();
                array_push($headers, "Authorization:APPCODE " . $appcode);
                $querys = "mobile=".$account."&param=code%3A".$code."&tpl_id=TP1711063";
                $bodys = "";
                $url = $host . $path . "?" . $querys;

                $curl = curl_init();
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($curl, CURLOPT_FAILONERROR, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HEADER, false);
                if (1 == strpos("$".$host, "https://"))
                {
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                }
                $resPhone = curl_exec($curl);
                // dump($resPhone);
                $resPhone = json_decode($resPhone);
                // dd($resPhone);
                if ($resPhone -> return_code == '00000') {
                    //发送成功 存session
                    request() -> session() -> forget('code');
                    $code = [
                        'account' => $account,
                        'code' => $code,
                    ];
                    session(['code'=>$code]);
                    return json_encode(['code'=>1,'msg'=>'短信发送成功，请查看']);
                }else{
                    return json_encode(['code'=>2,'msg'=>'请重试']);
                }
            }else{
                //不是手机号 发送邮件
                Mail::send(
                    'shop.email.view',
                    ['code' => $code],
                    function($message)use($account,$code){
                        $res = $message -> subject('验证码') -> to($account);
                    }
                );
                if ($res) {
                    //发送成功 存session
                    request() -> session() -> forget('code');
                    $code = [
                        'account' => $account,
                        'code' => $code,
                    ];
                    session(['code'=>$code]);
                    return json_encode(['code'=>1,'msg'=>'邮件发送成功，请查收']);
                }else{
                    return json_encode(['code'=>1,'msg'=>'邮件发送失败，请检查后再试']);
                }
            }
        }
    }
}

