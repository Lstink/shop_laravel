<?php

namespace App\Http\Controllers\shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\shop\GoodsModel;
use App\shop\CarModel;
use App\Http\Controllers\shop\Common;

class CarController extends Common
{
    public function car()
    {
        if ($this -> checkLogin()) {
            $num = $this -> getCarNumInfo();
            //用户登录 查询数据库购物车
            $carInfo = $this -> getCarInfoFromSql();
        }else{
            //用户未登录
            
        }
        return view('shop.car.car',['carInfo'=>$carInfo,'num'=>$num]);
    }
    /**
     * 查询数据库中购物车数据的方法
     */
    public function getCarInfoFromSql()
    {
        //获取用户的id
        $user_id = $this -> getUserId();
        //根据id查询购物车数据 双表联查
        $carInfo = CarModel::join('tp_goods','tp_car.goods_id', '=', 'tp_goods.goods_id') 
        -> where(['tp_car.is_del' => 1,'tp_car.user_id' => $user_id,'tp_goods.is_on_sale' => 1]) 
        -> orderBy('tp_car.update_time','desc')
        -> get();
        if (!$carInfo) {
            //如果不存在数据
            $carInfo = '';
        }
        return $carInfo;
    }
    /**
     * 获取购物车商品的种类数量
     */
    public function getCarNumInfo()
    {
        //获取用户的id
        $user_id = $this -> getUserId();
        //查询购物车商品的种类数量
        $num = CarModel::where(['is_del' => 1,'user_id' => $user_id]) -> count();
        if (!$num) {
            $num = 0;
        }
        return $num;
    }
    public function ajaxChangeNum(Request $request)
    {
        $goods_id = $request -> post('goods_id');
        $buy_number = $request -> post('buy_number');
        //查询该商品的价格
        $price = GoodsModel::where('goods_id',$goods_id) -> value('shop_price');
        //获取该商品的库存
        $goods_num = $this -> getGoodsNumber($goods_id);
        if ($buy_number > $goods_num) {
            echo json_encode(['code'=>2,'msg'=>'库存不足']);
        }
        //判断用户是否登陆
        if ($this -> checkLogin()) {
            //用户登陆 获取用户的id
            $goodsPrice = $this -> changeNumOfCar($goods_id,$buy_number,$price);
            return $goodsPrice;
        }else{
            //用户未登录 从cookie中取值
            // $cookieInfo = array_decode(cookie('buy'));
            // // dump($cookieInfo);die;
            // foreach ($cookieInfo as $k => $val) {
            //     if ($val['goods_id'] == $goods_id) {
            //         $cookieInfo[$k]['buy_number'] = $buy_number;
            //         $cookieInfo[$k]['update_time'] = time();
            //         $price_sum = $cookieInfo[$k]['buy_number'] * $price;
            //     }
            // }
            // // dump($cookieInfo);die;
            // cookie('buy',array_encode($cookieInfo));
            // echo $price_sum;
        }
    }
    public function ajaxSumAll(Request $request)
    {
        $ids = $request -> post('ids');
        if (!$ids) {
            echo 0;die;
        }
        //判断用户是否登陆
        if ($this -> checkLogin()) {
            //用户登录 查询这些商品的单价
            $priceAll = 0;
            foreach ($ids as $k => $val) {
                //查询该商品的价格
                $price = GoodsModel::where('goods_id',$val) -> value('shop_price');
                //查询该商品购物车中的数量
                $num = CarModel::where(['goods_id'=>$val,'is_del'=>1]) -> value('buy_number');
                $priceAll += $num * $price;
            }
        }else{
            //用户未登录
            // $cookieInfo = array_decode(cookie('buy'));
            // // dump($cookieInfo);die;
            // $priceAll = 0;
            // foreach ($cookieInfo as $k => $val) {
            //     foreach ($ids as $key => $value) {
            //         if ($val['goods_id'] == $value) {
            //             //查询该商品的价格
            //             $goods = model('Goods');
            //             $price = $goods -> where('goods_id',$val['goods_id']) -> value('shop_price');
            //             $priceAll += $val['buy_number'] * $price;
            //         }
            //     }
            // }
        }
        echo $priceAll;
    }
    /**
     * 修改数据库中购物车商品的数量 并且获得单个物品总价格
     */
    public function changeNumOfCar($goods_id,$buy_number,$price)
    {
        $user_id = $this -> getUserId();
        //修改数据库中的购买数量
        $res = CarModel::where(['goods_id'=>$goods_id,'user_id'=>$user_id,'is_del'=>1]) -> update(['buy_number'=>$buy_number]);
        //判断是否修改成功
        if ($res) {
            return  $buy_number * $price;
        }
    }
}
