<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class Nav extends Model
{
    public function GetNavData($limit){
    	$datas =db('nav')->where(array('nav_delete_time'=>NULL))->order('nav_create_time','desc')->paginate($limit,1000);
        static $arr = array();
        foreach ($datas as $key => $value){
         	$value['nav_modify_time']= GetTime($value['nav_modify_time']);
            $value['nav_create_time']= GetTime($value['nav_create_time']);
             $arr[] = $value;
         }
        $count=$this->where(array('nav_delete_time'=>NULL))->count();
        $dataJson=["code"=>0,"msg"=>"","count"=>$count,"data"=>$arr];
      	return $dataJson;
    }
    public function GetNavInfoById($nav_id){
        $nav_info=$this->find($nav_id);
        return $nav_info;
    }
}
