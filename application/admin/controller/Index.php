<?php
namespace app\admin\controller;
use think\Db;
use think\Log;
use app\admin\model\Common;
use app\admin\model\Admin as AdminModel;
use app\admin\model\Index as IndexModel;
use app\admin\model\Module as ModuleModel;
class Index extends Base
{
    public function index()
    {
        
    	// $this->get_common_info();
    	$this->GetNav();
      	return $this->fetch();
    }
    public function get_nav_json($module_level=""){
    	$data=["module_pid"=>$module_level,"module_delete_time"=>null];
    	$index_model=new IndexModel();
        $module=$index_model->GetNavJson($data);
		$modulelist=[];
        $module_data=$index_model->GetModuleSelect();
		foreach ($module as $k => $v) {
			foreach ($module_data as $ks => $vs) {
				if($v["module_id"]==$vs["module_pid"]){
                    foreach ($module_data as $kss => $vss) {
                       if($vss["module_level"]=="4"&&$vss["module_pid"]==$vs['module_id']){
                            $vs['childrens'][]=$vss;
                       }
                    }
					$v['children'][]=$vs;
				}
			}
			$modulelist[]=$v;
		}
        return json_encode( $modulelist,JSON_UNESCAPED_UNICODE);
    }
    public function GetModule(){
    	$module=db("module")->where(array("module_delete_time"=>null))->select();
    	return $module;
    }
    public function lockpage($admin_lockpage_code=""){
        if (request()->isPost()) {
            $post=request()->post();
            $admin_model=new AdminModel();
            $admin_info=$admin_model->GetAdminInfoById(session("admin_id"));
            if($admin_info["admin_lockpage_code"]==$post["admin_lockpage_code"]){
                $message=['msg'=>"解屏成功",'code'=>'true'];
            }else{
                $message=['msg'=>"解屏失败",'code'=>'false'];
            }
            return $message;
        }
    }
}
