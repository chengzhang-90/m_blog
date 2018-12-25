<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class Module extends Model
{
    public function GetModuleName($data='')
    {
        $module_info=db("module")
        ->where('module_url',"/admin/".$data['controller'].'/'.$data['action'])
        ->find();
        return $module_info;
    }
    public function GetModuleData(){
        
    }
    public function GetModuleInfoBy($id='')
    {
        $module_info=db("module")->find($id);
        return $module_info;
    }
    
    public function GetModuleSelect()
    {
        $module_select=db('module')
        ->where(array("module_delete_time"=>null))
        ->order('module_sort desc')->select();
        return $module_select;
    }
    public function GetModule(){
        $module=db("module")
        ->where(array('module_is_menu'=>"1",'module_delete_time'=>NULL))
        ->order('module_sort desc')
        ->select();
        $modulelist=[];
        $child_three=[];
        $child_four=[];
        foreach ($module as $k => $v) {
            if ($v['module_level']=='1') {
                foreach ($module as $ks => $vs) {
                    if ($v['module_id']==$vs['module_pid']&&$vs['module_level']=='2') {
                        foreach ($module as $kss => $vss) {
                            if ($vs['module_id']==$vss['module_pid']) {
                                foreach ($module as $ksss => $vsss) {
                                    if ($vss['module_id']==$vsss['module_pid']&&$vsss['module_level']=='4') {
                                        $vss["child_four"][]=$vsss;
                                    }
                                }
                                $vs['child_three'][]= $vss;
                            }
                        }
                        $v['child'][]=$vs;
                    }
                }
                $modulelist[]=$v;
            }
        }
        return $modulelist;
    }
    public function getChildrenId($module_list,$module_pid=0,$module_level=1){
        static $arr=array();
        foreach ($module_list as $key => $value) {
            if ($value['module_pid']==$module_pid) {
                $value['module_level']=$module_level;
                $value['str']=str_repeat('———————', $value['module_level']-1);
                $arr[]=$value;
                $this->getChildrenId($module_list,$value['module_id'],$module_level+1);

            }
        }
        return $arr;
    }
}