<?php
namespace app\admin\controller;
use think\Db;
use think\Log;
use app\admin\model\AuthGroup as AuthGroupModel;
use app\admin\model\Module as ModuleModel;
use app\admin\common\Func as FuncModel;
class AuthGroup extends Base
{

	public function index(){
		return $this->fetch();
	}
	//链接列表
    public function auth_group_lst_data($limit=''){
        if(!empty($limit)){
            $AuthGroupModel = new AuthGroupModel();
            $dataJson = $AuthGroupModel->GetAuthGroupData($limit);
            return $dataJson;
        }else{
            $data=['code'=>'1','count'=>1,'data'=>'false'];
            return json_encode($data);
        }
    }

	public function add(){
		if(request()->isPost()){
			$post=request()->post();
            $this->GetAction("auth_group",$post,"add","添加权限组");
		}else{
			$ModuleModel = new ModuleModel();
	        $select = $ModuleModel->GetModuleSelect();
	        $modulelist = $ModuleModel->GetChildrenId($select);
	        $this->assign('modulelist',$modulelist);
			return $this->fetch("index");
		}
	}
	public function edit($auth_group_id=""){
		if(request()->isPost()){
			$post=request()->post();
            $this->GetAction("auth_group",$post,"update","修改权限组");
		}else{
			$module = new AuthGroupModel();
	        $select = $module->GetModuleSelect();
	        $modulelist = $module->GetChildrenId($select);
	        $this->assign('modulelist',$modulelist);
	        $AuthGroupModel = new AuthGroupModel();
            $auth_group_info = $AuthGroupModel->GetAuthGroupInfoById($auth_group_id);
            $this->assign("auth_group_info",$auth_group_info);
			return $this->fetch("index");
		}
	}
	public function del(){
		if(request()->isPost()){
			$post=request()->post();
            $this->GetAction("auth_group",$post,"update","删除权限组");
		}
	}
    public function auth_group_del_arr(){
        if(request()->isPost()){
            $post=request()->post();
            $this->GetAction("auth_group",$post,"DelArr","删除权限组");
        }
    }










}