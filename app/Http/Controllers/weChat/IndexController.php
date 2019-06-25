<?php

namespace App\Http\Controllers\weChat;

use Illuminate\Http\Request;
use App\Model\WeChat;
use App\Http\Controllers\Controller;
use App\Model\Subscribe;
use App\Model\Keywords;
use App\shop\OrderModel;

class IndexController extends Controller
{
    /**
     * @content 判断链接
     */
    public function valid(Request $request)
    {
        //接收echostr
        $echoStr = $request -> echostr;
        //判断$echoStr是否存在
        if (isset($echoStr)) {
            //存在
            if ($this -> checkSignature($request)) {
                echo $echoStr;
            }
        }else{
            //不存在
            $this -> responseMsg();
        }
        
    }

    /**
     * @centent 响应接收的消息
     */
    public function responseMsg()
    {
        //将请求的所有信息读取为字符串
        $postStr = file_get_contents('php://input');
        //将字符串转化为对象
        $postObj = simplexml_load_string($postStr,'SimpleXMLElement',LIBXML_NOCDATA);
        //获取对象中的属性
        $MsgType = $postObj -> MsgType;
        //数据类型为事件
        if ($MsgType == 'event') {
            //关注事件
            if ($postObj -> Event == 'subscribe') {
                $configType = config('responseType.responseType');
                // dd($configType);
                $info = Subscribe::where('type',$configType) -> orderBy('id','desc') -> first();
                $type = ucfirst($info -> type);
                switch ($configType) {
                    case 'text':
                        $data = $info -> content;
                        break;
                    case 'image':
                        $data = $info -> media_id;
                        break;
                    case 'music':
                        $data = $info;
                        break;
                    case 'voice':
                        $data = $info -> media_id;
                        break;
                    case 'video':
                        $data['media_id'] = $info -> media_id;
                        $data['title'] = $info -> title;
                        $data['desc'] = $info -> desc;
                        break;
                    case 'news':
                        $data = $info;
                        break;
                }
                // dd($type);
                $method = 'send'.$type.'Msg';
                WeChat::$method($postObj,$data);
                exit;
            }
        }
        //获取用户发送的信息
        $keyWords = (string)$postObj -> Content;
        //关键字回复
        $data = Keywords::where('keywords',$keyWords) -> first();
        if ($data) {
            $type = ucfirst($data -> type);
            $Content = $data -> content;
            $method = 'send'.$type.'Msg';
            WeChat::$method($postObj,$Content);
            exit;
            // $content = $data -> content;
            // WeChat::sendTextMsg($postObj,$content);
        }else{
            //调用订单回复
            $this -> getOrder($keyWords,$postObj);
            //调用天气回复
            $this -> getWether($keyWords,$postObj);
            //调用图灵机器人
            $res = WeChat::tuLing($keyWords);
            $Content = $res['results'][0]['values']['text'];
            //发送消息
            WeChat::sendTextMsg($postObj,$Content);
        }
    }
    /**
     * @content 获取用户发送的查询订单方法
     */
    public function getOrder($keyWords,$postObj)
    {
        $reg = '/^订单[\s:：.]?(\w+)$/';
        preg_match($reg,$keyWords,$arr);
        if (!empty($arr)) {
            $order_no = $arr[1];
            //查询数据库
            $res = OrderModel::where(['is_del'=>1,'order_no'=>$order_no]) -> first();
            if ($res) {
                $data = $res -> toArray();
                //调用发送模版的方法
                $re = WeChat::sendTemplateMsg($postObj,$data);
                $re = json_decode($re,true);
                if ($re['errcode'] != 0) {
                    WeChat::sendTextMsg($postObj,'未知错误订单信息获取失败'.$re['errmsg']);
                }
                exit;
            }else{
                //没查到
                WeChat::sendTextMsg($postObj,'您发送的订单号有误，请核实');
            }
        }else{
            return true;
        }
    }
    /**
     * @content 获取用户查询天气的方法
     */
    public function getWether($keyWords,$postObj)
    {
        //正则匹配
        $reg = '/^.*天气$/';
        preg_match($reg,$keyWords,$arr);
        if (!empty($arr)) {
            //获取匹配的内容
            $weather = $arr[0];
            //获取字符串长度
            $length = mb_strlen($weather);
            //获取城市名称
            if ($length > 2) {
                $city = mb_substr($weather,'0',$length-2);
            }else{
                $city = '北京';
            }
            //根据城市名称请求天气信息
            $res = WeChat::sendWeather($city);
            if (isset($res['city'])) {
                $res = WeChat::sendWeatherMsg($postObj,$res);
                $re = json_decode($res,true);
                if ($re['errcode'] != 0) {
                    WeChat::sendTextMsg($postObj,'未知错误天气获取失败'.$re['errmsg']);
                }
                exit;
            }else{
                return true;
            }
        }else{
            return true;
        }
        
    }
    /**
     * @content 验证消息来自微信服务器
     */
    public function checkSignature($request)
    {
        $timestamp = $request -> timestamp;
        $signature = $request -> signature;
        $nonce = $request -> nonce;
        $token = env('WXTOKEN');
        $tmpArr = [$timestamp,$nonce,$token];
        sort($tmpArr,SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);
        if ($tmpStr == $signature) {
            return true;
        }else{
            return false;
        }
    }
    
}
