<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class Link extends Model
{
    public function GetLinkData($limit){
    	$datas =db('link')->where(array('link_delete_time'=>NULL))->order('link_create_time','desc')->paginate($limit,1000);
        static $arr = array();
        foreach ($datas as $key => $value){
         	if (!empty($value['link_modify_time'])){
         		$value['link_modify_time']= date("Y-m-d H:i:s",$value['link_modify_time']);
         	}
            $value['link_create_time']= date("Y-m-d H:i:s",$value['link_create_time']);
             $arr[] = $value;
         }
        $count=$this->where(array('link_delete_time'=>NULL))->count();
        $dataJson=["code"=>0,"msg"=>"","count"=>$count,"data"=>$arr];
      	return $dataJson;
    }
    public function GetLinkInfo($link_id){
        $link_info=$this->find($link_id);
        return $link_info;
    }
}
