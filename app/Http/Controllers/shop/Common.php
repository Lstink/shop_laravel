<?php

namespace App\Http\Controllers\shop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\shop\GoodsModel;
use App\shop\CarModel;
use App\shop\AddressModel;
use App\shop\User;
use App\shop\AreaModel;
use App\shop\CategoryModel;

class Common extends Controller
{
    /**
     * 获取商品库存的方法
     */
    public function getGoodsNumber($goods_id)
    {
        return GoodsModel::where(['goods_id'=>$goods_id,'is_on_sale'=>1]) -> value('goods_number');
    }
    /**
     * 判断用户是否登陆的方法
     */
    public function checkLogin()
    {
        return session('userInfo');
    }
    /**
     * 获得用户的id的方法
     */
    public function getUserId()
    {
        return session('userInfo')['u_id'];
    }
    /**
     * 获取结算的商品的方法
     * @goods_id 数组
     */
    public function getGoodsInfo($goods_id)
    {
        //获取用户的id
        $user_id = $this -> getUserId();
        //从数据库查询该id的商品
        $car = new CarModel;
        $where = [
            ['is_on_sale','=','1'],
            ['is_del','=','1'],
            ['user_id','=',$user_id],
        ];
        if (count($goods_id) <= 1) {
            //没有或者仅有一个id
            $where[] = ['tp_car.goods_id','=',$goods_id[0]];

            $data = cache('is_on_sale_is_del_user_id_tp_car.goods_id'.'11'.$user_id.$goods_id[0]);
            if (empty($data)) {
                $data = $car -> join('tp_goods','tp_car.goods_id','=','tp_goods.goods_id') 
                -> where($where) 
                -> get();
                cache(['is_on_sale_is_del_user_id_tp_car.goods_id'.'11'.$user_id.$goods_id[0]=>$data],60*24);
            }
            
        }else{
            $goods_ids = implode('_',$goods_id);
            $data = cache('is_on_sale_is_del_user_id'.'11'.$user_id.$goods_ids);
            if (empty($data)) {
                $data = $car -> join('tp_goods','tp_car.goods_id','=','tp_goods.goods_id') 
                -> where($where) 
                -> whereIn('tp_car.goods_id',$goods_id)
                -> get();
                cache(['is_on_sale_is_del_user_id'.'11'.$user_id.$goods_ids=>$data],60*24);
            }
        }
        // dd($goods_id);
        //小计
        foreach ($data as $k => $val) {
            $data[$k]['countPrice'] = $val['shop_price'] * $val['buy_number'];
        }
        return $data;
    }
    
    /**
     * 获取用户默认收件地址的方法
     */
    public function getAddressInfo($address_id='')
    {
        //获取用户的id 
        $user_id = $this -> getUserId();
        //根据用户的id查询用户的收货地址
        if (!empty($address_id)) {
            $addressInfo = cache('address_id_'.$address_id);
            if (empty($addressInfo)) {
                $addressInfo = AddressModel::where(['address_id'=>$address_id]) -> first();
                cache(['address_id_'.$address_id=>$addressInfo],60*24);
            }
        }else{
            $addressInfo = cache('user_id_is_del_is_default'.$user_id.'11');
            if (empty($addressInfo)) {
                $addressInfo = AddressModel::where(['user_id'=>$user_id,'is_del'=>1,'is_default'=>1]) -> first();
                cache(['user_id_is_del_is_default'.$user_id.'11'=>$addressInfo],60*24);
            }
        }
        if (!$addressInfo) {
            $addressInfo = '';
        }
        return $addressInfo;
    }
     /**
     * 获取用户默认收件地址的方法
     */
    public function getAllAddressInfo()
    {
        //获取用户的id 
        $user_id = $this -> getUserId();
        //根据用户的id查询用户的收货地址
        $addressInfo = cache('user_id_is_del_is_default'.$user_id.'1'.'asc');
        if (empty($addressInfo)) {
            $addressInfo = AddressModel::where(['user_id'=>$user_id,'is_del'=>1]) -> orderBy('is_default') -> get();
            cache(['user_id_is_del'.$user_id.'1'=>$addressInfo],60*24);
        }
        if (!$addressInfo) {
            $addressInfo = '';
        }else{
            foreach ($addressInfo as $k => $v) {
                $addressInfo1 = cache('id_'.$v ->province);
                if (empty($addressInfo1)) {
                    $addressInfo1 = AreaModel::where('id',$v ->province) -> value('name');
                    cache(['id_'.$v ->province=>$addressInfo1],60*24);
                }
                $addressInfo2 = cache('id_'.$v ->city);
                if (empty($addressInfo2)) {
                    $addressInfo2 = AreaModel::where('id',$v ->city) -> value('name');
                    cache(['id_'.$v ->city=>$addressInfo2],60*24);
                }
                $addressInfo3 = cache('id_'.$v ->area);
                if (empty($addressInfo3)) {
                    $addressInfo3 = AreaModel::where('id',$v ->area) -> value('name');
                    cache(['id_'.$v ->area=>$addressInfo3],60*24);
                }
                $addressInfo[$k]['province'] = $addressInfo1;
                $addressInfo[$k]['city'] = $addressInfo2;
                $addressInfo[$k]['area'] = $addressInfo3;
            }
        }
        return $addressInfo;
    }
    /**
     * 获取用户信息的方法
     */
    public function getUserName()
    {
        $userInfo = \session('userInfo')['u_email'];
        return $userInfo;
    }
    /**
     * 获取商品楼层数据
     */
    public function getFloorInfo($cate_id,$where,$offset='',$field='goods_id',$order='desc')
    {
        $info = [];
        //获取顶级分类数据 cate_id 和 cate_name
        $firstFloor = [
            'is_show' => '1',
            'cate_id' => $cate_id
        ];

        $data1 = cache('is_show_cate_id_'.'1'.$cate_id);
        if (empty($data1)) {
            $data1 = CategoryModel::select('cate_id','cate_name') -> where($firstFloor) -> first();
            cache(['is_show_cate_id_'.'1'.$cate_id=>$data1],60*24);
        }

        $info['topCate'] = $data1;
        // dump($firstFloor);die;
        //获取顶级分类的子集分类
        $secondFloor = [
            'is_show' => '1',
            'parent_id' => $cate_id
        ];

        $data2 = cache('is_show_parent_id_'.'1'.$cate_id);
        if (empty($data2)) {
            $data2 = CategoryModel::select('cate_id','cate_name') -> where($secondFloor) -> get() -> toArray();
            cache(['is_show_cate_id_'.'1'.$cate_id=>$data2],60*24);
        }

        $info['secondCate'] = $data2;
        // print_r($info);die;
        //获取商品信息分类
        $data3 = cache('CategoryModel_is_show_'.'1');
        if (empty($data3)) {
            $data3 = CategoryModel::where('is_show','1') -> get() -> toArray();
            cache(['CategoryModel_is_show_'.'1'=>$data3],60*24);
        }

        $cateInfo = $data3;
        $c_id = $this -> getCateId($cateInfo,$cate_id);
        // dd($c_id);
        $info['lastCate'] = GoodsModel::where($where) -> whereIn('cate_id',$c_id) -> orderBy($field,$order) -> offset($offset) -> take(6) -> get();
        // dump($info);die;
        return $info;
    }
    /**
     * 无限极分类
     */
    public function getCateId($cateInfo,$parent_id)
    {
        static $c_id = [];
        foreach ($cateInfo as $k => $v) {
            if ($v['parent_id'] == $parent_id) {
                $c_id[] = $v['cate_id'];
                $this -> getCateId($cateInfo,$v['cate_id']);
            }
        }
        return $c_id;
    }
    /**
     * 开启memcache的方式
     * @name 变量名称
     * @id 变量值
     * @存入的值
     */
    public function setCache($name,$id,$res)
    {
        //开启
        $data = cache('pid_'.$pid);
        if (empty($data)) {
            $data = AreaModel::where(['pid'=>$pid]) -> get();
            cache(['pid_'.$pid=>$data],60*24);
        }
    }
    
}
