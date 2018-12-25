<?php
namespace app\index\model;
use think\Model;
use think\Db;
class Link extends Model
{
    public function GetLinkSelect (){
    	$link_select =db('link')
    	->where('link_status','1')
    	->where('link_delete_time',NULL)
    	->order('link_create_time','desc')
    	->order('link_sort desc')->select();
      	return $link_select;
    }
}
