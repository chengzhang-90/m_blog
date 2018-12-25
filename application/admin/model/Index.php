<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class Index extends Model

{
	public function GetModuleSelect()
    {
        $module_select=db('module')->where(array('module_is_menu'=>"1","module_delete_time"=>null))->order('module_sort desc')->select();
        return $module_select;
    }
    public function GetModuleNav()
    {
        $module_nav_select=db('module')->where(array("module_level"=>1,"module_delete_time"=>null))->order('module_sort desc')->select();
        return $module_nav_select;
    }
    public function GetNavJson($value){
        $data=[
            'module_is_menu'=>"1",
            'module_delete_time'=>NULL
        ];
        $where=array_merge($data,$value);
        $module_nav_select=db('module')->where($where)->order('module_sort desc')->select();
        return $module_nav_select;
    }
    public function GetModule(){
        $module=db("module")->where(array('module_is_menu'=>'1','module_delete_time'=>NULL))->order('module_sort desc')->select();
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
    public function GetModuleName($data='')
    {
        $module_info=db("module")->where('module_url',"/admin/".$data['controller'].'/'.$data['action'])->find();
        return $module_info;
    }
}