<?php
namespace app\admin\controller;
use think\Db;
use think\Log;
use app\admin\model\Admin as AdminModel;
use app\admin\common\Func as FuncModel;
class Admin extends Base
{

	public function index(){
		return $this->fetch("index");
	}
	//列表
    public function admin_lst_data($limit=''){
        if(!empty($limit)){
            $AdminModel = new AdminModel();
            $dataJson = $AdminModel->GetAdminData($limit);
            return $dataJson;
        }else{
            $data=['code'=>'1','count'=>1,'data'=>'false'];
            return json_encode($data);
        }
    }
	//添加
	public function add(){
		if(request()->isPost()) {
            $FuncModel = new FuncModel();
            $post=request()->post();
            $rule = [
                'admin_username'  =>  $post["admin_username"],
                'admin_email'  =>  $post["admin_email"],
            ];
            $resultValidate = $this->validate($rule,'Admin');
            if(true !== $resultValidate){
                return ['status'=>0,'msg'=>"$resultValidate"];
            }else{
                if (!empty($post['admin_header_pic'])){
                    $post["admin_header_pic"]=$this->PermanentCopy($post['admin_header_pic']);
                }
                $post['admin_password']=md5($post['admin_password']);
                $post['admin_token']=$FuncModel->get_randStr(32);
                $this->GetAction("admin",$post,"add","添加博主");
            }
        }else{
            $AdminModel = new AdminModel();
            $auth_group = $AdminModel->GetAuthGroup();
            $this->assign("auth_group",$auth_group);
			return $this->fetch("index");
        }
	}
	//修改
    public function edit($admin_id=''){
        $AdminModel = new AdminModel();
        $admin_edit_info = $AdminModel->GetAdminInfoById($admin_id);
        $this->assign("admin_edit_info",$admin_edit_info);
        if (request()->isPost()) {
            $post=request()->post();
            if (!empty($post['admin_header_pic'])){
                $post["admin_header_pic"]=$this->PermanentCopy($post['admin_header_pic']);
                $this->PermanentUnlink(GetUrl($admin_edit_info['admin_header_pic'],"address"));
            }else{
                unset($post["admin_header_pic"]);
            }
            $this->GetAction("admin",$post,"update","修改博主信息");
        }else{
            $AdminModel = new AdminModel();
            $auth_group = $AdminModel->GetAuthGroup();
            $this->assign("auth_group",$auth_group);
            return $this->fetch("index");
        }
    }
    //删除
    public function del(){
        if (request()->isPost()) {
            $post=request()->post();
            $this->GetAction("admin",$post,"delete","删除管理员");
        }
    }
     //批量删除
    public function admin_del_arr(){
        if(request()->isPost()){
            $post=request()->post();
            $this->GetAction("admin",$post,"DelArr","批量删除管理员");
        }
    }
}