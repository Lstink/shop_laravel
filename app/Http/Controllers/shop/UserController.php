<?php

namespace App\Http\Controllers\shop;

use App\Http\Controllers\shop\Common;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\shop\CollectModel;

class UserController extends Common
{
    public function user()
    {
        $userName = $this -> getUserName();

        return view('shop.user.user',['userName'=>$userName]);
    }
    public function collect()
    {
        //获取用户的id
        $user_id = $this -> getUserId();
        //获取用户收藏的商品信息
        $collectInfo = CollectModel::where(['is_del'=>'1','user_id'=>$user_id]) -> join('tp_goods','tp_goods.goods_id','=','tp_collect.goods_id') -> get();
        // dd($collectInfo);
        //获取用户收藏的商品数量
        $count = CollectModel::where(['is_del'=>'1','user_id'=>$user_id]) -> count();
        return view('shop.collect.shoucang',['collectInfo'=>$collectInfo,'count'=>$count]);
    }
    public function ajaxDelCollect()
    {
        $goods_id = request() -> goods_id;
        //获取用户的id
        $user_id = $this -> getUserId();
        if (empty($goods_id)) {
            $res = CollectModel::where(['user_id'=>$user_id,'is_del'=>1]) -> delete();
        }else{
            $res = CollectModel::where(['goods_id'=>$goods_id,'user_id'=>$user_id,'is_del'=>1]) -> delete();
        }
        if ($res) {
            echo 1;
        }
    }
}
