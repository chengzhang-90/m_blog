<?php
namespace app\index\model;
use think\Model;
use think\Db;
class Label extends Model
{
    public function GetLabelInfoById($label_id){
    	$label_info =db('label')->where(array('label_delete_time'=>NULL))->find($label_id);
      return $label_info;
    }
    public function GetLabelSelect(){
      $label_select =db('label')->where(array('label_delete_time'=>NULL,'label_status'=>"1"))->select();
      return $label_select;
    }
    public function GetArticleLabelSelect(){

    }
}
