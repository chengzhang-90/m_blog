<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class AuthGroup extends Model
{

	public function GetModuleSelect()
    {
        $module_select=db('module')->where(array("module_delete_time"=>null))->order('module_sort desc')->select();
        return $module_select;
    }
    public function GetChildrenId($module_list,$module_pid=0,$module_level=1){
        static $arr=array();
        foreach ($module_list as $key => $value) {
            if ($value['module_pid']==$module_pid) {
                $value['module_level']=$module_level;
                $value['str']=str_repeat('———————', $value['module_level']-1);
                $arr[]=$value;
                $this->GetChildrenId($module_list,$value['module_id'],$module_level+1);

            }
        }
        return $arr;
    }

    public function GetAuthGroupData($limit){
        $datas =db('auth_group')->where(array('auth_group_delete_time'=>NULL))->order('auth_group_create_time','desc')->paginate($limit,1000);
        static $arr = array();
        foreach ($datas as $key => $value){
            $value['auth_group_modify_time']=GetTime($value['auth_group_modify_time']);
            $value['auth_group_create_time']=GetTime($value['auth_group_create_time']);
            $arr[] = $value;
         }
        $count=$this->where(array('auth_group_delete_time'=>NULL))->count();
        $dataJson=["code"=>0,"msg"=>"","count"=>$count,"data"=>$arr];
        return $dataJson;
    }

    public function GetAuthGroupInfoById($auth_group_id){
        $auth_group_info=$this->find($auth_group_id);
        return $auth_group_info;
    }
}