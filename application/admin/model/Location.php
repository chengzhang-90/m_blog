<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class Location extends Model
{
    public function get_location_data($travel_location_pid){
    	$datas =db('travel_location')->where(array('travel_location_pid'=>$travel_location_pid))->select();
        $dataJson=["code"=>0,"data"=>$datas];
      	return $dataJson;
    }
}
