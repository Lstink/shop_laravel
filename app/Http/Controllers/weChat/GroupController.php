<?php

namespace App\Http\Controllers\weChat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\UserList;
use App\Model\WeChat;
use App\Model\TagList;

class GroupController extends Controller
{
    /**
     * @content 标签列表
     */
    public function tagList()
    {
        return view('admin/tagList');
    }
    /**
     * @content 未打过标签的用户列表获取
     */
    public function getJsonTag()
    {
        $page = request() -> page;
        $limit = request() -> limit;
        // $type = request() -> input('type',null);
        //计算跳过多少条
        $take = ($page-1)*$limit;
        //获取所有已经打过标签的openid
        $openid = $this -> getTagOpenId()??'';
        //获取所有用户的openid
        $userList = new UserList;
        $data = $userList -> whereNotIn('openid',$openid) -> offset($take) -> limit($limit) -> get() ->toArray();
        foreach ($data as $k => $v) {
            $data[$k]['created_at'] = date('Y-m-d H:i:s',$v['created_at']);
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
     * @content 获取所有已经打过标签的openid
     */
    public function getTagOpenId()
    {
        
    }
    /**
     * @content 选择标签
     */
    public function selectTag()
    {
        $id = request() -> id;
        //查询所有标签
        $data = TagList::get();
        return view('admin/selectTag',['id'=>$id,'data'=>$data]);
    }
    /**
     * @content 给用户添加标签
     */
    public function doSelectTag()
    {
        $tagId = request() -> name;
        $id = request() -> id;
        $id = explode(',',$id);
        $data = UserList::whereIn('id',$id) -> select('openid') -> get() -> toArray();
        $openid = [];
        foreach ($data as $k => $v) {
            $openid[] = $v['openid'];
        }
        $access_token = WeChat::getRedisAccess();
        $url = "https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging?access_token=$access_token";
        $info = [
            'openid_list' => [$openid],
            'tagid' => $tagId
        ];
        $json = json_encode($info);
        $res = WeChat::curlPost($url,$json);
        $res = json_decode($res,true);
        if ($res['errcode'] == 0) {
            $this -> getAllTag();
            echo json_encode(['code'=>1,'msg'=>'用户添加标签成功!']);
        }else{
            echo json_encode(['code'=>2,'msg'=>'创建标签失败，错误码为：'.$res['errcode'].'。错误类型为：'.$res['errmsg']]);
        }
    }
    /**
     * @content 创建标签页面展示
     */
    public function createTag()
    {
        return view('admin/createTag');
    }
    /**
     * @content 创建标签
     */
    public function doCreateTag()
    {
        //获取传过来的标签名
        $tagName = request() -> content;
        $access_token = WeChat::getRedisAccess();
        $url = "https://api.weixin.qq.com/cgi-bin/tags/create?access_token=$access_token";
        $arr = [
            'tag' => ['name' => $tagName]
        ];
        $json = json_encode($arr,JSON_UNESCAPED_UNICODE);
        $res = WeChat::curlPost($url,$json);
        $res = json_decode($res,true);
        if (isset($res['tag'])) {
            //获取所有的标签列表
            $this -> getAllTag();
            //新建对应标签的文件
            $this -> createTagFile($res['tag']['id']);
            echo json_encode(['code'=>1,'msg'=>'创建标签成功，标签id为：'.$res['tag']['id'].'。标签名称为：'.$res['tag']['name']]);
        }else if($res['errcode'] == 45157){
            echo json_encode(['code'=>2,'msg'=>'创建标签失败，该名称已存在']);
        }else{
            echo json_encode(['code'=>2,'msg'=>'创建标签失败，错误码为：'.$res['errcode'].'。错误类型为：'.$res['errmsg']]);
        }
    }
    /**
     * @content 创建标签对应的文件
     */
    public function createTagFile($id)
    {
        $path = storage_path('tag'.'/'.$id.'.txt');
        touch($path);
        chmod($path,0777);
    }
    /**
     * @content 获取所有的标签名存入数据库
     */
    public function getAllTag()
    {
        $access_token = WeChat::getRedisAccess();
        $url = "https://api.weixin.qq.com/cgi-bin/tags/get?access_token=$access_token";
        $data = \file_get_contents($url);
        $res = \json_decode($data,true);
        if (isset($res['tags'])) {
            //将数据写入数据库
            $path = storage_path('tag/tag.txt');
            TagList::truncate();
            TagList::insert($res['tags']);
            // file_put_contents($path,$data);
        }
    }
    /**
     * @content 获取粉丝列表数据
     */
    public function getFansByTagId($tagId,$next_openid='')
    {  
        $access_token = WeChat::getRedisAccess();
        $url = "https://api.weixin.qq.com/cgi-bin/user/tag/get?access_token=$access_token";
        $arr = ['tagid' => $tagId,'next_openid'=>$next_openid];
        $json = json_encode($arr);
        $res = WeChat::curlPost($url,$json);
        return $res;
    }
    /**
     * @content 粉丝列表页面
     */
    public function fansList()
    {
        $data = TagList::get();
            
        return view('admin/fansList',['data'=>$data]);
        
    }
    /**
     * @content 获取粉丝列表json格式数据
     */
    public function getJsonFans()
    {
        $page = request() -> page;
        $limit = request() -> limit;
        // //计算跳过多少条
        $take = ($page-1)*$limit;
        //查询数据
        $data = TagList::offset($take) -> limit($limit) -> get() ->toArray();
        $count = TagList::count();
        // dd($data);
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
     * @content 删除标签
     */
    public function delTag()
    {
        $id = request() -> id;
        // dd($id);
        $res = WeChat::delTag($id);
        // $res = '{"errcode":0,"errmsg":"ok"}';
        $res = json_decode($res,true);
        if ($res['errcode'] == 0) {
            //删除成功 再删除数据库
            $re = tagList::where(['id'=>$id]) -> delete();
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
