<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class LabelType extends Model
{
    public function GetLabelTypeSelect(){
        $label_type_select=db("label_type")->where(array("label_type_status"=>1,"label_type_delete_time"=>null))->select();
        return $label_type_select;
    }
    public function GetLabelTypeData($limit)
    {
        $label_class_data=db('label_type')->where(array("label_type_delete_time"=>null))->order('label_type_create_time desc')->paginate($limit,1000)->Toarray();
        foreach ($label_class_data["data"] as $key => $value) {
            $value['label_type_modify_time']= GetTime($value['label_type_modify_time']);
            $value['label_type_create_time']= GetTime($value['label_type_create_time']);
            
            $arr[] = $value;
        }
        $count=db('label_type')->where(array('label_type_delete_time'=>NULL))->count();
        $dataJson=["code"=>0,"msg"=>"","count"=>$count,"data"=>$arr];
        return $dataJson;
    }
    public function GetLabelTypeInfo($label_type_id){
        $label_type_info=db("label_type")->find($label_type_id);
        return $label_type_info;
    }
}
