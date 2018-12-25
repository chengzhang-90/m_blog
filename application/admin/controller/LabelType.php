<?php
namespace app\admin\controller;
use think\Db;
use think\Log;
use app\admin\model\LabelType as LabelTypeModel;
use app\admin\common\Func as FuncModel;
class LabelType extends Base
{


    public function index(){
        return $this->fetch("label/type");
    }

    //标签类型列表
    public function label_type_lst_data($limit=''){
        if(!empty($limit)){
            $LabelTypeModel = new LabelTypeModel();
            $dataJson = $LabelTypeModel->GetLabelTypeData($limit);
            return $dataJson;
        }else{
            $data=['code'=>'1','count'=>0,'data'=>'false'];
            return json_encode($data);
        }
    }
    // 添加标签类型
    public function add(){
        if(request()->isPost()) {
            $post=request()->post();
            $this->GetAction("label_type",$post,"add","添加标签类型");
        }else{
            return $this->fetch("label/type");
        }
    }
    //修改标签类型
    public function edit($label_type_id=''){
        if (request()->isPost()) {
            $post=request()->post();
            $this->GetAction("label_type",$post,"update","修改标签类型");
        }else{
            $LabelTypeModel = new LabelTypeModel();
            $label_type_info = $LabelTypeModel->GetLabelTypeInfo($label_type_id);
            $this->assign("label_type_info",$label_type_info);
            return $this->fetch("label/type");
        }
    }
    public function del(){
        if(request()->isPost()){
            $post=request()->post();
            $this->GetAction("label_type",$post,"delete","删除标签类型");
        }
    }
}