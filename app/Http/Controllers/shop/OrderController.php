<?php

namespace App\Http\Controllers\shop;

use Illuminate\Http\Request;
use App\Http\Controllers\shop\Common;
use App\shop\GoodsModel;
use App\shop\AddressModel;
use App\shop\OrderAddressModel;
use App\shop\OrderDetailModel;
use App\shop\OrderModel;
use App\shop\CarModel;
use Illuminate\Support\Facades\DB;

class OrderController extends Common
{
    public function success($order_id)
    {
        //根据order_id查询数据库内容
        $orderInfo = OrderModel::find($order_id);
        $end_time = $orderInfo -> toArray();
        $end_time = date('Y-m-d H:i:s',$end_time['create_time'] + 60*60*24);
        return view('shop.order.success',['orderInfo'=>$orderInfo,'end_time'=>$end_time]);
    }
    public function ajaxSubOrder()
    {
        //判断用户是否存在默认的收获地址
        $goods_id = request() -> goods_id;
        $user_id = $this -> getUserId();
        $res = AddressModel::where(['user_id'=>$user_id,'is_default'=>1,'is_del'=>1]) -> count();
        if ($res) {
            //存在默认的收货地址 生成订单信息
            $order_id = $this -> setOrder($goods_id);
            if (!empty($order_id)) {
                echo json_encode(['code'=>1,'msg'=>$order_id]);
            }else{
                echo json_encode(['code'=>3]);
            }
        }else{
            echo json_encode(['code'=>0]);
        }
    }
    /**
     * 生成订单号的方法
     */
    public function getOrderNumber()
    {
        $str = 'qwertyuioplkjhgfdsazxcvbnmQAZXSWEDCVFRTGBNHYUJMKIOPL123456789';
        $str = str_shuffle($str);
        $num = substr($str,'1','5').date('Ymd').rand(1000,9999);
        // dd($num);
        //查询数据库中是否有这个订单号
        $res = OrderModel::where(['order_no'=>$num]) -> count();
        while ($res >= 1) {
            $str = str_shuffle($str);
            $num = substr($str,'1','5').date('Ymd').rand(1000,9999);
        }
        return $num;
    }
    /**
     * 生成订单的方法
     */
    public function setOrder()
    {
        //开启事务
        DB::beginTransaction();
        try {
            //获得订单号
            $goods_id = request() -> input('goods_id');
            $goods_id = explode(',',$goods_id);
            $order_no = $this -> getOrderNumber();

            //根据商品id查询购物车内的数据
            $goodsInfo = $this -> getGoodsInfo($goods_id);
            // dd($goodsInfo);

            //获得总价
            $order_amount = 0;
            foreach ($goodsInfo as $k => $v) {
                $order_amount += $v['countPrice'];
            }
            $user_id = $this -> getUserId();
            //入库
            $data = [
                'order_no'=>$order_no,
                'order_amount'=>$order_amount,
                'order_no'=>$order_no,
                'pay_type'=>1,
                'create_time'=>time(),
                'update_time'=>time(),
            ];
            $order_id = OrderModel::insertGetId($data);
            //查询收货人信息
            $addressInfo = $this -> getAddressInfo() -> toArray();
            unset($addressInfo['create_time']);
            unset($addressInfo['update_time']);
            unset($addressInfo['address_mail']);
            unset($addressInfo['address_id']);
            unset($addressInfo['is_default']);
            $addressInfo['order_id'] = $order_id;
            $addressInfo['create_time'] = $addressInfo['update_time'] = time();
            //将数据添加到数据表中
            $res2 = OrderAddressModel::insert($addressInfo);
            //获取商品的信息
            $goodsInfo = GoodsModel::whereIn('goods_id',$goods_id) -> select('goods_id','goods_name','shop_price','goods_img') -> get();
            foreach ($goodsInfo as $k => $val) {
                $goodsInfo[$k]['buy_number'] = CarModel::where(['goods_id'=>$val['goods_id'],'user_id'=>$user_id,'is_del'=>1]) -> value('buy_number');
                $goodsInfo[$k]['order_id'] = $order_id;
                $goodsInfo[$k]['user_id'] = $user_id;
                $goodsInfo[$k]['create_time'] = $goodsInfo[$k]['update_time'] = time();
            }
            //将数据添加到数据表中
            $res3 = OrderDetailModel::insert($goodsInfo -> toArray());
            
            //减少库存
            $goodsWhere = [
                'goods_id' => $goods_id,
                'is_on_sale' => 1,
            ];
            foreach ($goodsInfo as $k => $val) {
                //查询库存
                $goodsInfos = GoodsModel::find($val['goods_id']);
                $goodsInfos -> goods_number = $goodsInfos['goods_number']-$val['buy_number'];
                $res5 = $goodsInfos -> save();
                
            }
            //删除购物车数据
            $res4 = CarModel::where('user_id',$user_id) ->whereIn('goods_id',$goods_id) -> update(['is_del'=>2]);
            if ($res2 && $res3 && $res4) {
                DB::commit();
                return $order_id;
            }
        } catch (\Throwable $th) {
            DB::rollback();
        }
        
    }
    public function order(Request $request)
    {
        $type = $request -> type;
        if (empty($type)) {
            $type = '1';
        }
        $user_id = $this -> getUserId();

        if ($type == 4) {
            $where[] =['tp_order.is_del','=',2];
        }else if($type){
            $where[] =['pay_status','=',$type];
            $where[] =['tp_order.is_del','=',1];
        }
        //查询根据用户查询order_id 不重复查询
        $order_id = OrderDetailModel::where('user_id',$user_id) -> select('order_id') -> distinct() -> get() -> toArray();
        //将二维数组转化为一维数组
        foreach ($order_id as $k => $v) {
            $order_id[$k] = $v['order_id'];
        }
        //根据order_id 查询order表 查询数据
        $orderInfo = OrderModel::where($where) -> whereIn('order_id',$order_id) -> orderBy('create_time','desc') -> get() -> toArray();
        //根据order_id 查询order_detail表中的商品数据 
        if ($type == 4) {
            $goodsInfo = OrderDetailModel::where('is_del',2) -> whereIn('order_id',$order_id) -> get() -> toArray();
        }else{
            $goodsInfo = OrderDetailModel::where('is_del',1) -> whereIn('order_id',$order_id) -> get() -> toArray();
        }
        //处理数组
        foreach ($orderInfo as $k => $v) {
            $orderInfo[$k]['goodsInfo']=[];
            foreach ($goodsInfo as $key => $val) {
                if ($v['order_id'] == $val['order_id']) {
                    array_push($orderInfo[$k]['goodsInfo'],$val);
                }
            }
        }

        // $orderInfo = OrderModel::join('tp_order_detail','tp_order.order_id','=','tp_order_detail.order_id') -> where($where) -> get();
        if ($request -> ajax == 1) {
            return view('shop.order.ajaxOrder',['orderInfo'=>$orderInfo,'type'=>$type]);
        }else{
            return view('shop.order.order',['orderInfo'=>$orderInfo,'type'=>$type]);
        }
    }
    public function ajaxDelOrder(Request $request){
        $order_id = $request -> post('order_id');
        //根据订单id删除订单表中的数据
        //开启事务
        DB::beginTransaction();
        $where = [
            'order_id'=>$order_id,
        ];
        try {
            //删除订单表中的数据
            $res1 = OrderModel::where($where) -> update(['is_del'=>2]);
            //删除订单地址中的数据
            $res2 = OrderAddressModel::where($where) -> update(['is_del'=>2]);
            //查询订单详情的数据
            $data = OrderDetailModel::where($where) -> select('goods_id','buy_number') -> get() -> toArray();
            // dd($data);
            //遍历数组
            foreach ($data as $k => $v) {
                //修改goods表的语句
                GoodsModel::where('goods_id',$v['goods_id']) -> increment('goods_number',$v['buy_number']);
                // dd($v['buy_number']);
            }
            $res3 = OrderDetailModel::where($where) -> update(['is_del'=>2]);
            if ($res1 && $res2 && $res3) {
                echo 1;
            }
            DB::commit();
        } catch (\Throwable $th) {
            throw $th;
            DB::rollback();
        }
    }

}
