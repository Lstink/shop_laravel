<?php

namespace App\Http\Controllers\shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\shop\OrderModel;
use Illuminate\Support\Facades\Log;
// use App\shop\CarModel;

class AliPayController extends Controller
{
    public function aliPay($order_id)
    {
        $data = OrderModel::join('tp_order_detail','tp_order.order_id','=','tp_order_detail.order_id')
        -> where('tp_order.order_id',$order_id) 
        -> first();
        $config = config('aliPay');
        // dd($config);
        require_once '../app/libs/alipay/pagepay/service/AlipayTradeService.php';
        require_once '../app/libs/alipay/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php';

        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = $data['order_no'];

        //订单名称，必填
        $subject = $data['goods_name'];

        //付款金额，必填
        $total_amount = $data['order_amount'];

        //商品描述，可空
        $body = '';

        //构造参数
        $payRequestBuilder = new \AlipayTradePagePayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setOutTradeNo($out_trade_no);

        $aop = new \AlipayTradeService($config);

        /**
         * pagePay 电脑网站支付请求
         * @param $builder 业务参数，使用buildmodel中的对象生成。
         * @param $return_url 同步跳转地址，公网可以访问
         * @param $notify_url 异步通知地址，公网可以访问
         * @return $response 支付宝返回的信息
        */
        $response = $aop->pagePay($payRequestBuilder,$config['return_url'],$config['notify_url']);

        //输出表单
        var_dump($response);
    }
    public function paySuccess(Request $request)
    {
        // //获取支付的订单号
        // $order_no = $request -> get('out_trade_no');
        // //根据订单号修改数据库order表
        // $res = OrderModel::where('order_no',$order_no) -> update(['pay_status'=>2,'order_status'=>2]);
        // if ($res) {
        //     return redirect('/order/2');
        // }
        /* *
        * 功能：支付宝页面跳转同步通知页面
        * 版本：2.0
        * 修改日期：2017-05-01
        * 说明：
        * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。

        *************************页面功能说明*************************
        * 该页面可在本机电脑测试
        * 可放入HTML等美化页面的代码、商户业务逻辑程序代码
        */
        $config = config('aliPay');
        require_once '../app/libs/alipay/pagepay/service/AlipayTradeService.php';
        // dump($config);
        $arr=$_GET;
        $alipaySevice = new \AlipayTradeService($config); 
        $result = $alipaySevice->check($arr);

        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
        //商户订单号
        $out_trade_no = htmlspecialchars($_GET['out_trade_no']);
        //交易金额
        $total_amount = htmlspecialchars($_GET['total_amount']);
        $res = OrderModel::where(['order_no'=>$out_trade_no,'order_amount'=>$total_amount]) -> count();
        if (!$res) {
            //写入日志
            Log::channel('aliPay')->info('订单交易号和金额不正确');
        }
        if($result && $res) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代码
            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
            // dd($_GET);
            
            //支付宝交易号
            $trade_no = htmlspecialchars($_GET['trade_no']);
            //写入支付宝交易号
            Log::channel('aliPay')->info('支付宝交易号为：'.$trade_no);
            return redirect('/order/2');
        }else {
            //验证失败
            echo "验证失败";
        }
    }
    public function notify_url()
    {
        // Log::channel('aliPay') -> info('异步通知');die;
        $config = config('aliPay');
        require_once '../app/libs/alipay/pagepay/service/AlipayTradeService.php';

        $arr=$_POST;
        $alipaySevice = new \AlipayTradeService($config); 
        $alipaySevice->writeLog(var_export($_POST,true));
        // dd(5555);die;
        $result = $alipaySevice->check($arr);

        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
        // dd($arr);
        //商户订单号
        $out_trade_no = $_POST['out_trade_no'];
        //交易金额
        $total_amount = $_POST['total_amount'];
        $res = OrderModel::where(['order_no'=>$out_trade_no,'order_amount'=>$total_amount]) -> count();
        if (!$res) {
            //写入日志
            Log::channel('aliPay')->info('订单交易号和金额不正确');
        }
        // Log::channel('aliPay') -> info(var_export($_POST,true));
        if($result && $res) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代
            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
            //支付宝交易号
            $trade_no = $_POST['trade_no'];
            //交易状态
            $trade_status = $_POST['trade_status'];
            if($_POST['trade_status'] == 'TRADE_FINISHED') {

                //判断该笔订单是否在商户网站中已经做过处理
                    //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                    //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                    //如果有做过处理，不执行商户的业务程序
                        
                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
            }
            else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                //根据订单号修改数据库order表
                $res = OrderModel::where('order_no',$out_trade_no) -> update(['pay_status'=>2,'order_status'=>2]);
                
                //判断该笔订单是否在商户网站中已经做过处理
                    //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                    //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                    //如果有做过处理，不执行商户的业务程序			
                //注意：
                //付款完成后，支付宝系统发送该交易状态通知
            }
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
            echo "success";	//请不要修改或删除
        }else {
            //验证失败
            echo "fail";

        }
    }
    
}
