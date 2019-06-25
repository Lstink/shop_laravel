<?php

namespace App\Http\Controllers\weChat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class QrCodeController extends Controller
{
    /**
     * @content生成二维码的方法
     */
    public function createQrCode()
    {
        //引入二维码类
        $path = public_path('phpqrcode.php');
        include_once($path);
        //生成一个唯一的id
        $uniqid = uniqid();
        $value = "http://www.yeyunyang.xyz/weChatLogin/$uniqid";
        $errorCorrectionLevel = 'L';//容错级别
        $matrixPointSize = 6;//生成图片大小
        unlink('qrcode.png');
        \QRcode::png($value, 'qrcode.png', $errorCorrectionLevel, $matrixPointSize, 2);
        //图片的路径为
        return view('admin.qrcode',['uniqid'=>$uniqid]);
    }
}
