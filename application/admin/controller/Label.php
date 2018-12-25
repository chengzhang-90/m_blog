<?php
namespace app\admin\controller;
use think\Db;
use think\Log;
use app\admin\model\Label as LabelModel;
use app\admin\model\LabelType as LabelTypeModel;
use app\admin\common\Func as FuncModel;
class Label extends Base
{

	public function index(){
		return $this->fetch("index");
	}
	//标签列表
    public function label_lst_data($limit=''){
        if(!empty($limit)){
            $LabelModel = new LabelModel();
            $dataJson = $LabelModel->GetLabelData($limit);
            return $dataJson;
        }else{
            $data=['code'=>'1','count'=>0,'data'=>'false'];
            return json_encode($data);
        }
    }
	//添加标签
	public function add(){
		if(request()->isPost()) {
            $post=request()->post();
            if (!empty($post['label_thumb'])){
                $post["label_thumb"]=$this->PermanentCopy($post['label_thumb']);
            }
            $this->GetAction("label",$post,"add","添加标签");
        }else{
            $LabelTypeModel = new LabelTypeModel();
            $label_type_select = $LabelTypeModel->GetLabelTypeSelect();
            $this->assign("label_type_select",$label_type_select);
			return $this->fetch("index");
        }
	}
	//修改标签
    public function edit($label_id=''){
        if (request()->isPost()) {
            $post=request()->post();
            if (!empty($post['label_thumb'])){
                $post["label_thumb"]=$this->PermanentCopy($post['label_thumb']);
            }else{
                unset($post["label_thumb"]);
            }
            $this->GetAction("label",$post,"update","修改标签");
        }else{
        	$LabelTypeModel = new LabelTypeModel();
            $label_type_select = $LabelTypeModel->GetLabelTypeSelect();
            $this->assign("label_type_select",$label_type_select);
            $LabelModel = new LabelModel();
            $label_info = $LabelModel->GetLabelInfoById($label_id);
            $this->assign("label_info",$label_info);
            return $this->fetch("index");
        }
    }
    public function del($label_id=''){
        if (request()->isPost()) {
            $post=request()->post();
            $this->GetAction("label",$post,"delete","删除标签");
        }
    }
     //批量删除
    public function del_arr(){
        if(request()->isPost()){
            $post=request()->post();
            $this->GetAction("label",$post,"DelArr","批量删除标签");
        }
    }


}