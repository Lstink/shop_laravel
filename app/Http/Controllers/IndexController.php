<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\WeChat;

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
                $Content = env('CONTENT');
                WeChat::sendMsg($postObj,$Content);
            }
        }
        //获取用户发送的信息
        $keyWords = (string)$postObj -> Content;
        //关键字回复
        $config = config('keywords');
        if (isset($config[$keyWords])) {
            WeChat::sendMsg($postObj,$config[$keyWords]);
        }else{
            //调用图灵机器人
            $res = WeChat::tuLing($keyWords);
            $Content = $res['results'][0]['values']['text'];
            //发送消息
            WeChat::sendMsg($postObj,$Content);
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
