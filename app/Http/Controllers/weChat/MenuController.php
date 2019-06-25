<?php

namespace App\Http\Controllers\weChat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\UserList;
use App\Model\WeChat;
use App\Model\Menu;
use App\Model\TagList;
use App\Model\PersonMenu;

class MenuController extends Controller
{
    /**
     * @content 添加菜单展示页面
     */
    public function createMenu()
    {
        //查询一级分类
        $data = Menu::where(['pid'=>0,'status'=>1]) -> get();
        // dd($data);
        if (!empty($data)) {
            $data = $data -> toArray();
            foreach ($data as $k => $v) {
                //排除type不为空的
                if ($v['type'] == 'view') {
                    unset($data[$k]);
                }else{
                    //查询每条一级分类下的二级分类的数量
                    $count = Menu::where(['pid'=>$v['id'],'status'=>1]) -> count();
                    if ($count >= 5) {
                        unset($data[$k]);
                    }
                }
                
            }
        }
        return view('admin/createMenu',['data'=>$data]);
    }
    /**
     * @content 添加菜单
     */
    public function doCreateMenu()
    {
        $num = request() -> num;
        if ($num == 1) {
            //添加一级菜单
            $data = request() -> only(['name','key','type']);
            $data['pid'] = 0;
            if (mb_strlen($data['name']) > 4) {
                echo json_encode(['code'=>2,'msg'=>'菜单名称最多四个汉字']);
                exit;
            }
            //查询一级菜单有几个
            $oneCount = Menu::where(['pid'=>0,'status'=>1]) -> count();
            if ($oneCount >= 3) {
                echo json_encode(['code'=>2,'msg'=>'添加失败,一级菜单最多三个']);
                exit;
            }
            if ($data['type'] == 'view') {
                $data['url'] = request() -> url;
            }
        }else if($num == 2){
            //添加二级菜单
            $data['name'] = request() -> t_name;
            $data['key'] = request() -> t_key;
            $data['type'] = request() -> t_type;
            $data['pid'] = request() -> pid;
            //判断name的长度最大为7个汉字
            if (mb_strlen($data['name']) > 7) {
                echo json_encode(['code'=>2,'msg'=>'菜单名称最多七个汉字']);
                exit;
            }
            //查询该父级下有多少个二级菜单
            $twoCount = Menu::where(['pid'=>$data['pid'],'status'=>1]) -> count();
            if ($twoCount >= 5) {
                echo json_encode(['code'=>2,'msg'=>'添加失败,该分类下二级菜单最多五个']);
                exit;
            }
            //如果是 view 则接收url
            if ($data['type'] == 'view') {
                $data['url'] = request() -> t_url;
            }
            //如果一级菜单 type 为 view 则不能添加二级菜单
            //查询该父级type 的类型是什么
            $type = Menu::where(['id'=>$data['pid'],'status'=>1]) -> value('type');
            if ($type == 'view') {
                echo json_encode(['code'=>2,'msg'=>'添加失败,父级分类事件为view时不能添加二级分类']);
                exit;
            }
        }
        // dd($data);
        $res = Menu::insert($data);
        if ($res) {
            echo json_encode(['code'=>1,'msg'=>'添加成功','num'=>$num,'twoCount'=>$twoCount??'']);
        }else{
            echo json_encode(['code'=>2,'msg'=>'添加失败','num'=>$num,'twoCount'=>$twoCount??'']);
        }

    }
    /**
     * @content 菜单列表
     */
    public function MenuList()
    {
        //查询一级菜单
        $data = $this -> getMenuArr();
        return view('admin/menuList',['data'=>$data]);
    }
    /**
     * @content 获得菜单的三维数组
     */
    public function getMenuArr()
    {
        //查询一级菜单
        $data = Menu::where(['pid'=>0,'status'=>1]) -> get();
        //声明一个数组
        if (!empty($data)) {
            $data = $data -> toArray();
            foreach ($data as $k => $v) {
                //查询每条一级分类下的所有二级分类
                $two = Menu::where(['pid'=>$v['id'],'status'=>1]) -> get();
                if (!empty($two)) {
                    //将所有二级分类写入一级分类的child字段中
                    $data[$k]['child'] = $two -> toArray();
                }
            }
        }
        return $data;
    }
    /**
     * @content 编辑菜单
     */
    public function editMenu()
    {
        $id = request() ->id;
        //根据id查询该条数据
        $info = Menu::where(['id'=>$id,'status'=>1]) -> first();
        //查询一级分类
        $data = Menu::where(['pid'=>0,'status'=>1]) -> get();
        // dd($id);
        if (!empty($data)) {
            $data = $data -> toArray();
            foreach ($data as $k => $v) {
                //排除本身和类型不为空的
                if ($info['pid'] != $v['id']) {
                    unset($data[$k]);
                }else{
                    //查询每条一级分类下的二级分类的数量
                    $count = Menu::where(['pid'=>$v['id'],'status'=>1]) -> count();
                    if ($count >= 5) {
                        unset($data[$k]);
                    }
                }
                
            }
        }
        return view('admin.editMenu',['info'=>$info,'data'=>$data]);
    }
    /**
     * @content 处理编辑菜单
     */
    public function doEditMenu()
    {
        $data = request() -> except(['_token','id']);
        $id = request()  -> id;
        //如果一级分类下有子类，则不能把click 修改为view 和click
        if ($data['type'] == 'view') {
            //view 查询是否有子集分类
            $count = Menu::where(['pid'=>$id,'status'=>1]) -> count();
            if ($count > 0) {
                echo json_encode(['code'=>2,'msg'=>'修改失败,子类有二级分类存在，事件类型不能为view']);
                exit;
            }
        }else if($data['type'] == 'click'){
            //把 click 的 url 清空
            $data['url'] = null;
        }
        $res = Menu::where('id',$id) -> update($data);
        if ($res) {
            echo \json_encode(['code'=>1,'msg'=>'修改成功']);
        }else{
            echo \json_encode(['code'=>2,'msg'=>'修改失败，数据无改动']);
        }
    }
    /**
     * @content 删除菜单
     */
    public function delMenu()
    {
        $id = request() -> id;
        $pid = request() -> pid;
        if ($pid == 0) {
            //查询该分类下是否存在子类
            $count = Menu::where('pid',$id) -> count();
            if ($count > 0) {
                echo json_encode(['code'=>2,'msg'=>'该分类下存在二级分类，不能删除']);
                exit;
            }
        }
        //删除菜单 改变状态
        $re = Menu::where('id',$id)->update(['status'=>2]);
        if ($re) {
            echo json_encode(['code'=>1,'msg'=>'删除成功']);
        }else{
            echo json_encode(['code'=>2,'msg'=>'该数据已删除']);
        }
    }
    /**
     * @content 添加菜单到微信服务器
     */
    public function addMenu()
    {
        $data = $this -> getMenuArr();
        // dump($data);
        $res = WeChat::createMenu($data);
        $arr = json_decode($res,true);
        if ($arr['errcode'] == 0) {
            echo json_encode(['code'=>1,'msg'=>'自定义菜单添加到微信服务器成功']);
        }else{
            echo json_encode(['code'=>2,'msg'=>'添加失败，错误码：'.$res['errcode'].'，错误信息：'.$res['errmsg']]);
        }
        
    }
    /**
     * @个性化菜单创建表单
     */
    public function alertPersonMenu()
    {
        //查询所有tag_id
        $tag_id = TagList::select('id','name') -> get();
        
        return view('admin.alertPersonMenu',['tag_id'=>$tag_id]);
    }
    /**
     * @content 创建个性化菜单
     */
    public function doCreatePersonMenu()
    {
        $data = request() -> except('_token');
        //去除数组中的空值
        $arr = array_filter($data);
        //获取数据库数组
        $data = $this -> getMenuArr();
        //调用创建菜单的方法
        $res = WeChat::createMenu($data,$arr);
        $res = json_decode($res,true);
        if (isset($res['menuid'])) {
            //个性化菜单内容添加到数据库
            $arr['menuid'] = $res['menuid'];
            PersonMenu::insert($arr);
            echo json_encode(['code'=>1,'msg'=>'个性化菜单添加到微信服务器成功']);
        }else{
            echo json_encode(['code'=>2,'msg'=>'删除失败，错误码：'.$res['errcode'].'，错误信息：'.$res['errmsg']]);
        }
    }
    /**
     * @content 个性化菜单列表
     */
    public function personMenuList()
    {
        return view('admin.personMenuList');
    }
    /**
     * @content 从数据库中获取个性化菜单数据
     */
    public function getJsonMenu()
    {
        $page = request() -> page;
        $limit = request() -> limit;
        // $type = request() -> input('type',null);
        //计算跳过多少条
        $take = ($page-1)*$limit;
        $userList = new PersonMenu;
        $data = $userList -> offset($take) -> limit($limit) -> get();
        if (!empty($data)) {
            $data = $data -> toArray();
            foreach ($data as $k => $v) {
                //查询标签id的名称
                $data[$k]['tag_name'] = TagList::where('id',$v['tag_id']) -> value('name');
            }
        }
        $count = $userList -> count();
        $arr = [
            'code' => 0,
            'msg' => '',
            'count' => $count,
            'data' => $data,
        ];
        $info = \json_encode($arr);
        echo $info;
    }
    /**
     * @content 删除个性化菜单
     */
    public function delPersonMenu()
    {
        $id = request() -> id;
        // dd($id);
        $res = WeChat::delPersonMenu($id);
        // $res = '{"errcode":0,"errmsg":"ok"}';
        $res = json_decode($res,true);
        if ($res['errcode'] == 0) {
            //删除成功 再删除数据库
            $re = PersonMenu::where(['menuid'=>$id]) -> delete();
            if ($re) {
                echo json_encode(['code'=>1,'msg'=>'删除成功']);
            }else{
                echo json_encode(['code'=>0,'msg'=>'本地数据库删除失败']);
            }
        }else{
            echo json_encode(['code'=>2,'msg'=>'删除失败，错误码：'.$res['errcode'].'，错误信息：'.$res['errmsg']]);
        }
    }
}
