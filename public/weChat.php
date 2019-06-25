<?php
/**
 * @content 微信服务器配置
 */


class weChat
{
    /**
     * 构造函数
     */
    const TOKEN = 'yyy950118';
    function __construct()
    {
        $this -> valid();
    }
    /**
     * 检测验证是否通过
     */
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if ($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    /**
     * 验证消息的确来自微信服务器
     */
    private function checkSignature()
    {
        $timestamp = $_GET["timestamp"];
        $signature = $_GET["signature"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
}

$obj = new weChat;

