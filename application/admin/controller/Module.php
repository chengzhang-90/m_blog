<?php
namespace app\admin\controller;
use think\Db;
use think\Log;
use app\admin\model\Module as ModuleModel;
use app\admin\common\Func as FuncModel;
class Module extends Base
{

    public function index(){

        $module = new ModuleModel();
        $select = $module->GetModuleSelect();
        $modulelist = $module->GetChildrenId($select);
        $this->assign('modulelist',$modulelist);
        return $this->fetch("index");
    }
    public function module_lst_data(){
        $ModuleModel = new ModuleModel();
        $select = $ModuleModel->GetModuleSelect();
        $modulelist = $ModuleModel->GetChildrenId($select);
        $count=db("module")->where(array('module_delete_time'=>NULL))->count();
        // dump($modulelist);die;
        $dataJson=["code"=>0,"msg"=>"","count"=>$count,"data"=>$modulelist];
        return $dataJson;
    }
    public function add(){
        $module_model=new ModuleModel();
        if (request()->isPost()) {
            $post=request()->post();
            $module_info=$module_model->GetModuleInfoBy($post['module_pid']);
            $level=$module_info['module_level']+1;
            $merge=array_merge($post,['module_level'=>$level]);
            $this->GetAction("module",$merge,"add","添加模块");
        }else{
            $modulelist=$module_model->GetModule();
            $this->assign('modulelist',$modulelist);
            return $this->fetch("index");
        }
    }
    public function edit($module_id=""){
        $module_model=new ModuleModel();
        if (request()->isPost()) {
            $post=request()->post();
            if (!empty($post['module_pid'])) {
                $module_info=$module_model->GetModuleInfoBy($post['module_pid']);
                $this_module_info=$module_model->GetModuleInfoBy($module_id);
                $level=$module_info['module_level']+1;
                $post=array_merge($post,['module_level'=>$level]);
            }
            $this->GetAction("module",$post,"update","修改模块");
        }else{
            $this_module_info=$module_model->GetModuleInfoBy($module_id);
            $this->assign('this_module_info',$this_module_info);
            // dump($module_info);die;
            $modulelist=$module_model->GetModule();
            $this->assign('modulelist',$modulelist);
            return $this->fetch("index");
        }
    }
    public function del($module_id){
        $module_model=new ModuleModel();
        $module_info= $module_model->GetModuleInfoBy($module_id);
        $post=[
            'module_id'=>$module_id,
         ];
        $this->GetAction("module",$post,"delete","删除模块");
    }

}