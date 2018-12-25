<?php
namespace app\admin\controller;
use think\Db;
use think\Log;
use app\admin\model\Link as LinkModel;
use app\admin\common\Func as FuncModel;
class Link extends Base
{

	public function index(){
		return $this->fetch("index");
	}
	//链接列表
    public function link_lst_data($limit=''){
        if(!empty($limit)){
            $LinkModel = new LinkModel();
            $dataJson = $LinkModel->GetLinkData($limit);
            return $dataJson;
        }else{
            $data=['code'=>'1','count'=>1,'data'=>'false'];
            return json_encode($data);
        }
    }
	//添加链接
	public function add(){
		if(request()->isPost()) {
            $post=request()->post();
            $this->GetAction("link",$post,"add","添加链接");
        }else{
			return $this->fetch("index");
        }
	}
	//修改链接
    public function edit($link_id=''){
        if (request()->isPost()) {
            $post=request()->post();
            $this->GetAction("link",$post,"update","修改链接");
        }else{
        	$LinkModel = new LinkModel();
            $link_info = $LinkModel->GetLinkInfo($link_id);
            $this->assign("link_info",$link_info);
            return $this->fetch("index");
        }
    }
    //删除链接
    public function del(){
        if (request()->isPost()) {
            $post=request()->post();
            $this->GetAction("link",$post,"delete","删除链接");
        }
    }
     //批量删除
    public function link_del_arr(){
        if(request()->isPost()){
            $post=request()->post();
            $this->GetAction("link",$post,"DelArr","批量删除链接");
        }
    }
}