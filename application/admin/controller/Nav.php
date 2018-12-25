<?php
namespace app\admin\controller;
use think\Db;
use think\Log;
use app\admin\model\Nav as NavModel;
use app\admin\common\Func as FuncModel;
class Nav extends Base
{

	public function index(){
		return $this->fetch("index");
	}
	//列表
    public function nav_lst_data($limit=''){
        if(!empty($limit)){
            $NavModel = new NavModel();
            $dataJson = $NavModel->GetNavData($limit);
            return $dataJson;
        }else{
            $data=['code'=>'1','count'=>1,'data'=>'false'];
            return json_encode($data);
        }
    }
	//添加
	public function add(){
		if(request()->isPost()) {
            $post=request()->post();
            $this->GetAction("nav",$post,"add","添加导航");
        }else{
			return $this->fetch("index");
        }
	}
	//修改
    public function edit($nav_id=''){
        if (request()->isPost()) {
            $post=request()->post();
            $this->GetAction("nav",$post,"update","修改导航");
        }else{
        	$NavModel = new NavModel();
            $nav_info = $NavModel->GetNavInfoById($nav_id);
            $this->assign("nav_info",$nav_info);
            return $this->fetch("index");
        }
    }
    //删除
    public function del(){
        if (request()->isPost()) {
            $post=request()->post();
            $this->GetAction("nav",$post,"delete","删除导航");
        }
    }
     //批量删除
    public function nav_del_arr(){
        if(request()->isPost()){
            $post=request()->post();
            $this->GetAction("nav",$post,"DelArr","批量刪除导航");
        }
    }
}