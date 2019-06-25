<?php

namespace App\Http\Controllers\shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\shop\Common;
use App\shop\GoodsModel;
use App\shop\CarModel;
use App\shop\OrderModel;
use App\shop\AddressModel;
use App\shop\AreaModel;

class PayController extends Common
{
    public function pay(Request $request,$goods_id)
    {
        $goods_id = $request -> goods_id;
        // dump($goods_id);
        $goods_ids = explode(',',$goods_id);
        // dd($goods_ids);
        //根据goods_id查询商品
        $goodsInfo = $this -> getGoodsInfo($goods_ids);
        //获得总价
        $sumAllPrice = 0;
        foreach ($goodsInfo as $k => $v) {
            $sumAllPrice += $v['countPrice'];
        }
        //查询用户的收件地址
        $addressInfo = $this -> getAddressInfo();
        if (!empty($addressInfo)) {
            $addressInfo -> province = AreaModel::where('id',$addressInfo ->province) -> value('name');
            $addressInfo -> city = AreaModel::where('id',$addressInfo ->city) -> value('name');
            $addressInfo -> area = AreaModel::where('id',$addressInfo ->area) -> value('name');
        }
        // dd($addressInfo);
        return view('shop.pay.pay',['goodsInfo'=> $goodsInfo,'sumAllPrice'=> $sumAllPrice,'goods_id'=> $goods_id,'addressInfo'=>$addressInfo]);
    }

    public function ajaxSubBuy(Request $request)
    {
        //判断用户是否登陆
        if ($this -> checkLogin()) {
            //用户登陆
            echo json_encode(['code'=>1]);
        }else{
            //用户没登录
            echo json_encode(['code'=>2]);
        }
    }
    
}

