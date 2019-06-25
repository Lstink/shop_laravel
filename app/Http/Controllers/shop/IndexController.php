<?php

namespace App\Http\Controllers\shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\shop\GoodsModel;
use App\shop\CategoryModel;
use App\Http\Controllers\shop\Common;

class IndexController extends Common
{
    public function index()
    {
        $where = [
            ['is_on_sale','=','1'],
            ['is_hot','=','1'],
        ];
        $hot = cache('GoodsModel_is_on_sale_is_hot_goods_id_take_'.'11desc4');
        if (empty($hot)) {
            $hot = GoodsModel::where($where) -> orderBy('goods_id','desc') -> take(4) -> get();
            cache(['GoodsModel_is_on_sale_is_hot'.'11'=>$hot],60*24);
        }
        $data = cache('GoodsModel_is_on_sale_is_hot_offset_take'.'11desc44');
        if (empty($data)) {
            $data = GoodsModel::where($where) -> orderBy('goods_id','desc') -> offset(4) -> take(8) -> get();
            cache(['GoodsModel_is_on_sale_is_hot_offset_take'.'11desc44'=>$data],60*24);
        }
        //查询顶级分类
        $max = GoodsModel::where('is_on_sale','1') -> orderBy('shop_price','desc') -> take(4) -> get();
        $top = CategoryModel::where('parent_id',0) -> get();
        $sum = GoodsModel::where('is_on_sale','1') -> count();
        // dd($data);
        return view('shop.index.index',['hot'=>$hot,'data'=>$data,'max'=>$max,'sum'=>$sum,'top'=>$top]);
    }
    
    
    
}

