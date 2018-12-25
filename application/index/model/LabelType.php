<?php
namespace app\index\model;
use think\Model;
use think\Db;
use app\index\model\Label as LabelModel;
class LabelType extends Model
{
    public function GetLabelTypeInfoById($label_type_id){
    	$labelTypeInfo =db('label_type')->where(array('label_type_delete_time'=>NULL))->find($label_type_id);
      return $labelTypeInfo;
    }
    public function GetLabelTypeSelect(){
      $labelTypeSelect =db('label_type')
      ->where('label_type_status','1')
      ->where('label_type_delete_time',NULL)
      ->order('label_type_sort desc')
      ->select();
      $LabelModel= new LabelModel();
      $LabelSelect=$LabelModel->GetLabelSelect();
      $newArr= [];
      foreach ($labelTypeSelect as $key => $value) {
        foreach ($LabelSelect as $k => $v) {
            if ($v["label_type_id"] == $value["label_type_id"] ) {
              $value["label_type_child"][] = $v;
            }
        }
          $newArr[]=$value;
      }
      return $newArr;
    }
    public function get_article_label_select(){

    }
}
