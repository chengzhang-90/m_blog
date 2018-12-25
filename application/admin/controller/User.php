<?php
namespace app\admin\controller;
use think\Db;
use think\Log;
use app\admin\model\User as UserModel;
use app\admin\common\Func as FuncModel;
class User extends Base
{

	public function index(){
		return $this->fetch("index");
	}
	//会员列表
    public function user_lst_data($limit='',$user_username='',$user_nickname='',$user_phone='',$user_email=""){
        if(!empty($limit)){
            $data=[
                'user_username'=>$user_username,
                'user_nickname'=>$user_nickname,
                'user_phone'=>$user_phone,
                'user_email'=>$user_email,
                'limit'=>$limit
            ];
            $UserModel = new UserModel();
            $dataJson = $UserModel->get_user_data($data);
            return $dataJson;
        }else{
            $data=['code'=>'1','count'=>1,'data'=>'false'];
            return json_encode($data);
        }
    }


	//添加会员
	public function add(){
		if(request()->isPost()) {
            $post=request()->post();
            unset($post["link_id"]);
            $data=[
                'db'=>'link',
                'title'=>$post['link_title'],
                'note'=>'添加链接',
                'post'=>$post,
                'action'=>"add",
            ];
            $FuncModel = new FuncModel();
            $message= $FuncModel->get_action($data);
            return $message;
        }else{
			$this->get_common_info();
			return $this->fetch("index");
        }
	}
	//修改会员
    public function edit($user_id=''){
        if (request()->isPost()) {
            $post=request()->post();
            $data=[
                'db'=>'user',
                'title'=>$post['user_username'],
                'note'=>'修改会员',
                'post'=>$post,
                'action'=>"update",
            ];
            $FuncModel = new FuncModel();
            $message= $FuncModel->get_action($data);
            return $message;
        }else{
        	$UserModel = new UserModel();
            $user_info = $UserModel->get_user_info($user_id);
            $this->assign("user_info",$user_info);
            $this->get_common_info();
            return $this->fetch("index");
        }
    }
    //删除链接
    public function del(){
        if (request()->isPost()) {
            $post=request()->post();
            $LinkModel = new LinkModel();
            $link_info = $LinkModel->get_link_info($post["link_id"]);
            $data=[
                'db'=>'link',
                'title'=>$link_info['link_title'],
                'note'=>'删除链接',
                'post'=>$post,
                'action'=>"delete",
            ];
            $FuncModel = new FuncModel();
            $message= $FuncModel->get_action($data);
            return $message;
        }
    }
     //批量删除
    public function link_del_arr(){
        if(request()->isPost()){
            $post=request()->post();
            $arr=[];
            foreach ($post['link_arr'] as $key => $value) {
                 $arr[]=[
                    'link_id'=>$value
                 ];
            }
            $data=[
                'db'=>'link',
                'title'=>"批量删除链接",
                'note'=>'批量删除链接',
                'post'=>$arr,
            ];
            $FuncModel = new FuncModel();
            $message= $FuncModel->get_del_arr($data);
            return $message;
        }
    }
}