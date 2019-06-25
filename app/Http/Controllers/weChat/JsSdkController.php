<?php

namespace App\Http\Controllers\weChat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\WeChat;

class JsSdkController extends Controller
{
  private $appId;
  private $appSecret;

  public function __construct() {
    $this->appId = env('APPID');
    $this->appSecret = env('APPSECRET');
  }

  public function getSignPackage() {
    $jsapiTicket = $this->getJsApiTicket();

    // 注意 URL 一定要动态获取，不能 hardcode.
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    $timestamp = time();
    $nonceStr = $this->createNonceStr();

    // 这里参数的顺序要按照 key 值 ASCII 码升序排序
    $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

    $signature = sha1($string);

    $signPackage = array(
      "appId"     => $this->appId,
      "nonceStr"  => $nonceStr,
      "timestamp" => $timestamp,
      "url"       => $url,
      "signature" => $signature,
      "rawString" => $string
    );
    return $signPackage; 
  }

  private function createNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }

  private function getJsApiTicket() {
    // dd(1);
    $path = public_path('jsapi_ticket.txt');
    $data = json_decode(file_get_contents($path),true);
    if (empty($data) || $data['expire_time'] < time()) {
      //重新生成ticket
      $accessToken = WeChat::getRedisAccess();
      $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
      $res = json_decode(file_get_contents($url),true);
      $ticket = $res['ticket'];
      if ($ticket) {
        $data = [
          'expire_time' => time() + 7000,
          'jsapi_ticket' => $ticket,
        ];
        //写入文件
        file_put_contents($path,json_encode($data));
      }
    }

    return $data['jsapi_ticket'];
  }

}

