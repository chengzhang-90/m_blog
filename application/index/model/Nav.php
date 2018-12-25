<?php
namespace app\index\model;
use think\Model;
use think\Db;
class Nav extends Model
{
    public function GetNavData(){
    	$data =db('nav')
    	->where('nav_status',"1")
		->where('nav_delete_time',NULL)
    	->order('nav_create_time','desc')
    	->order('nav_sort','desc')->select();
    	// dump($data);die;
      	return $data;
    }
}
