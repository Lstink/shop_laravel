<?php

namespace App\Http\Controllers\shop;

use App\shop\GoodsModel;
use App\shop\CarModel;
use App\shop\CollectModel;
use Illuminate\Http\Request;
use App\Http\Controllers\shop\Common;

class ProListController extends Common
{
    public function proList(Request $request)
    {
        $goods_name = $request -> goods_name;
        $cate_id = $request -> cate_id;
        // dd($cate_id);
        //判断goods_name是否存在
        $where = [];
        if ($goods_name) {
            $where =[
                ['goods_name','like',"%$goods_name%"],
                ['is_on_sale','=','1'],
                ['is_new','=','1'],
            ];
        }
        if ($cate_id) {
            $floorInfo = $this -> getFloorInfo($cate_id,$where);
            $goodsInfo = $floorInfo['lastCate'];
            // dd($goodsInfo);
        }else{
            $goodsInfo = GoodsModel::where($where) -> orderBy('goods_id','desc') -> take(6) -> get();
        }
        // dd($goods_name);
        //查询商品的总条数
        $count = GoodsModel::count();
        $number = ceil($count/6);
        return view('shop.prolist.prolist',['goods_name'=>$goods_name,'goodsInfo'=>$goodsInfo,'number'=>$number]);
    }
    public function ajaxProList(Request $request)
    {
        $order = $request -> order;
        $field = $request -> field;
        $goods_name = $request -> goods_name;
        $cate_id = $request -> cate_id;
        // dd($request -> all());
        //判断goods_name是否存在
        $where = [
            ['is_on_sale','=','1'],
        ];

        if ($field == 'is_new') {
            $where[] = ['is_new','=','1'];
            $field = 'goods_id';
        }
        
        if ($goods_name) {
            $where[] = ['goods_name','like',"%$goods_name%"];
        }

        if ($cate_id) {
            $offset = '';
            $floorInfo = $this -> getFloorInfo($cate_id,$where,$offset,$field,$order);
            $goodsInfo = $floorInfo['lastCate'];
            // dd($goodsInfo);
        }else{
            $goodsInfo = GoodsModel::where($where) -> orderBy($field,$order) -> take(6) -> get();
        }
        // dd($goods_name);
        return view('shop.public.goodsAjax',['goodsInfo'=>$goodsInfo]);
    }
    /**
     * 商品信息的显示
     */
    public function proInfo($goods_id)
    {
        //轮播图数据
        $data = GoodsModel::where('is_on_sale','1') -> orderBy('shop_price','desc') -> take(5) -> get();
        //该条商品的数据
        $where = [
            ['is_on_sale','=','1'],
            ['goods_id','=',$goods_id],
        ];
        $goodsInfo = GoodsModel::where($where) -> first();
        $user_id = $this -> getUserId();
        //查询该商品是否存被收藏
        $res = CollectModel::where(['goods_id'=>$goods_id,'user_id'=>$user_id,'is_del'=>1]) -> count();
        return view('shop.prolist.proInfo',['data'=>$data,'goodsInfo'=>$goodsInfo,'res'=>$res,]);
        
    }
    /**
     * 加入购物车
     */
    public function ajaxAddBuy()
    {
        $data = request() -> input();
        request() -> validate([
            'buy_number' => 'required|numeric',
            'goods_id' => 'required|numeric',
        ]);
        $goods_number = $this -> getGoodsNumber($data['goods_id']);
        //判断用户是否登陆
        if ($this -> checkLogin()) {
            //用户登陆 获取id
            $data['user_id'] = $this -> getUserId();
            $this -> addBuyCarToSql($data);
        }else{
            //用户未登录 强制登陆
            echo json_encode(['code'=>3,'msg'=>'请登录后加入购物车']);
        }
        
    }
    /**
     * 登陆购物车存数据库
     */
    public function addBuyCarToSql($data)
    {
        //登陆 
        //获得用户购物车中的商品数量
        $num = CarModel::where(['goods_id'=>$data['goods_id'],'user_id'=>$data['user_id'],'is_del'=>1]) -> first();
        $goods_num = $this -> getGoodsNumber($data['goods_id']);
        //判断购物车中是否存在商品
        if ($num) {
            $num = $num -> toArray();
            //购物车中已经存在该商品
            $resNum = $goods_num - $num['buy_number'] - $data['buy_number'];

            if ($resNum >= 0) {
                //合法 
                $res = CarModel::where([ 'car_id' => $num['car_id'] ]) -> update([ 'buy_number' => $num['buy_number'] + $data['buy_number'] ]);
                if ($res) {
                    echo json_encode(['code'=>1,'msg'=>'加入购物车成功']);
                }else{
                    echo json_decode(['code'=>2]);
                }
            }else{
                //不合法
                $ress = $goods_num-$num['buy_number'];
                echo json_encode(['code'=>2,'msg'=>"该商品最大还能添加 {$ress} 件"]);
            }
        }else{
            //购物车中不存在该商品  判断该值不能比库存的数值大
            if ($data['buy_number'] > $goods_num) {
                echo json_encode(['code'=>2,'msg'=>"该商品最大只能添加 {$goods_num} 件"]);
            }else{
                $car = new CarModel;
                $data['create_time'] = time();
                $data['update_time'] = time();
                $res = CarModel::insert($data);
                if ($res) {
                    echo json_encode(['code'=>1,'msg'=>'加入购物车成功']);
                }else{
                    echo json_decode(['code'=>2]);
                }
            }
        }
    }

     /**
      * 未登录购物车存cookie
      */
    public function addBuyCarToCookie($data)
    {
        //用户未登录 判断cookie中购物车中是否存在商品信息
        

        if (cookie('buy')) {
            //存在cookie 判断cookie中是否存在当前商品
            $buyInfo = array_decode(cookie('buy'));
            //判断购物车中是否存在商品
            if ($buyInfo) {
                //商品的种类数
                $cateNum = count($buyInfo);
                //商品的总数
                $goodsNum = 0;
                foreach ($buyInfo as $k => $val) {
                    $goodsNum += $val['buy_number'];
                }
                // dump($cateNum);
                // dump($goodsNum);die;
            }
            //遍历cookie中的数组 
            $flag = 0;
            foreach ($buyInfo as $k => $val) {
                if ($val['goods_id'] == $data['goods_id']) {
                    //存在当前商品
                    $resNum = $goods_num - $val['buy_number'] - $data['buy_number'];
                    if ($resNum >= 0) {
                        //合法
                        $buyInfo[$k]['buy_number'] = $val['buy_number'] + $data['buy_number'];
                        $buyInfo[$k]['update_time'] = time();
                        //给cookie加密  再次储存
                        $buyInfo = array_encode($buyInfo);
                        cookie('buy',$buyInfo);
                        echo json_encode(['code'=>1,'cateNum'=>$cateNum,'goodsNum'=>$goodsNum+$data['buy_number'],'msg'=>'加入购物车成功']);
                    }else{
                        //不合法
                        $ress = $goods_num-$val['buy_number'];
                        fail("该商品最大还能添加 {$ress} 件");
                    }
                    $flag = 1;
                }
            }
            // dump($flag);die;
            if ($flag == 0) {
                //不存在该商品
                $buyInfo[] = [
                    'goods_id' => $data['goods_id'],
                    'buy_number' => $data['buy_number'],
                    'create_time' => time(),
                    'update_time' => time(),
                ];
                $res = array_encode($buyInfo);
                cookie('buy',$res);
                echo json_encode(['code'=>1,'cateNum'=>$cateNum+1,'goodsNum'=>$goodsNum+$data['buy_number'],'msg'=>'加入购物车成功']);
            }
        }else{
            //不存在cookie
            //购物车中不存在该商品  判断该值不能比库存的数值大
            if ($data['buy_number'] > $goods_num) {
                fail("该商品最大只能添加 {$goods_num} 件");
            }else{
                //存入cookie
                $buyInfo[] = [
                    'goods_id' => $data['goods_id'],
                    'buy_number' => $data['buy_number'],
                    'create_time' => time(),
                    'update_time' => time(),
                ];
                //加密数组重新存入cookie
                $res = array_encode($buyInfo);
                cookie('buy',$res);
                echo json_encode(['code'=>1,'cateNum'=>1,'goodsNum'=>$data['buy_number'],'msg'=>'加入购物车成功']);
                // dump($res);
            }
        }
    }

    public function ajaxCollect(Request $request)
    {
        $goods_id = $request -> goods_id;
        $user_id = $this -> getUserId();
        //根据获取的id加入收藏
        $data = [
            'goods_id'=>$goods_id,
            'user_id'=>$user_id,
            'is_del'=>1,
        ];
        //查询数据库中是否存在该收藏  
        $res = CollectModel::where($data) -> count();
        if ($res) {
            //存在则删除
            $res = CollectModel::where($data) -> delete();
            if ($res) {
                echo 0;
            }
        }else{
            //不存在则添加
            $res = CollectModel::insert($data);
            if ($res) {
                echo 1;
            }
        }
        
    }
    public function ajaxMoreProList(Request $request)
    {
        $num = request() -> num;
        $order = $request -> order;
        $field = $request -> field;
        $goods_name = $request -> goods_name;
        $cate_id = $request -> cate_id;
        //跳过的行数
        $offset = $num*6;
        // dd($request -> all());
        //判断goods_name是否存在
        $where = [
            ['is_on_sale','=','1'],
        ];

        if ($field == 'is_new') {
            $where[] = ['is_new','=','1'];
            $field = 'goods_id';
        }
        
        if ($goods_name) {
            $where[] = ['goods_name','like',"%$goods_name%"];
        }

        if ($cate_id) {
            $floorInfo = $this -> getFloorInfo($cate_id,$where,$offset,$field,$order);
            $goodsInfo = $floorInfo['lastCate'];
            // dd($goodsInfo);
        }else{
            $goodsInfo = GoodsModel::where($where) -> orderBy($field,$order) -> offset($offset) -> take(6) -> get();
        }
        
        return view('shop.public.ajaxMore',['goodsInfo'=>$goodsInfo]);
    }
}
