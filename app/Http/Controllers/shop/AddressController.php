<?php

namespace App\Http\Controllers\shop;

use Illuminate\Http\Request;
use App\Http\Controllers\shop\Common;
use App\shop\AreaModel;
use App\shop\AddressModel;

class AddressController extends Common
{
    public function address(Request $request)
    {
        //获取用户的id
        $goods_id = $request -> goods_id;
        //获取地区信息
        $areaInfo = $this -> getAreaInfo(0);
        //查询数据库中是否存在该用户的默认地址
        $default = $this -> getAddressInfo();
        return view('shop.address.address',['areaInfo'=>$areaInfo,'goods_id'=>$goods_id,'default'=>$default]);
    }
    public function editAddress(Request $request)
    {
        //获取地区信息
        $areaInfo = $this -> getAreaInfo(0);
        //获取修改的地址id
        $address_id = $request -> address_id;
        //存在地址id 查询用户的id
        $addressInfo = $this -> getAddressInfo($address_id);
        // dd($addressInfo);
        //根据查询到的地区id再次查询地区
        $city = $this -> getAreaInfo($addressInfo -> province);
        $area = $this -> getAreaInfo($addressInfo -> city);
        return view('shop.address.editAddress',['areaInfo'=>$areaInfo,'addressInfo'=>$addressInfo,'city'=>$city,'area'=>$area]);
    }
    /**
     * 获取地区信息的方法
     */
    public function getAreaInfo($pid)
    {
        //查询下一级的所有区域名称
        $data = cache('pid_'.$pid);
        if (empty($data)) {
            $data = AreaModel::where(['pid'=>$pid]) -> get();
            cache(['pid_'.$pid=>$data],60*24);
        }
        return $data;
    }
    public function AjaxGetCountry()
    {
        //接收id
        $id = request() -> id;
        //查询
        $data = $this -> getAreaInfo($id);
        return $data;
    }
    public function doAddress(Request $request)
    {
        $data = $request -> except('_token','goods_id');
        $goods_id = $request -> goods_id;
        $user_id = $this -> getUserId();
        //查询是否存在默认地址
        $default = $this -> getAddressInfo();
        if ($default) {
            //存在默认地址 判断是否勾选默认
            if (empty($data['is_default'])) {
                //没有勾选
                $data['is_default'] = 2;
            }else{
                //设置默认地址 修改所有地址为2
                AddressModel::where(['user_id'=>$user_id,'is_del'=>1]) -> update(['is_default'=>2]);
            }
        }else{
            //不存在默认地址
            $data['is_default'] = 1;
        }
        $data['user_id'] = $user_id;
        $data['create_time'] = $data['update_time'] = time();
        // dd($data);
        $res = AddressModel::insert($data);
        if (empty($goods_id)) {
            return redirect('addressInfo') -> with(['msg'=>'地址新增成功']);
        }
        if ($res) {
            return redirect('/pay/'.$goods_id) -> with(['msg'=>'地址新增成功']);
        }else{
            return back() -> with(['msg'=>'地址新增失败']);
        }
    }
    public function doEditAddress(Request $request)
    {
        $data = $request -> except('_token');
        $user_id = $this -> getUserId();
        if (empty($data['is_default'])) {
            //不设置默认地址
            $data['is_default'] = 2;
        }else{
            //设置默认地址 修改所有地址为2
            AddressModel::where(['user_id'=>$user_id,'is_del'=>1]) -> update(['is_default'=>2]);
        }
        $res = AddressModel::where('address_id',$request -> address_id) -> update($data);
        if ($res) {
            return redirect('/addressInfo') -> with(['msg'=>'地址修改成功']);
        }else{
            return redirect('/addressInfo') -> with(['msg'=>'地址修改失败']);
        }
    }
    public function AddressInfo()
    {
        //获取用户的收货地址信息
        $addressInfo = $this -> getAllAddressInfo();
        // dd($addressInfo);
        return view('shop.address.add-address',['addressInfo'=>$addressInfo]);
    }
}
