<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class Label extends Model
{
    public function GetLabelData($limit){
    	$datas =db('label')->alias('l')
                    ->join('label_type t','t.label_type_id=l.label_type_id')
                    ->field('
                        l.label_id,l.label_name,l.label_status,l.label_create_time,l.label_modify_time,l.label_delete_time,l.label_thumb,l.label_colorTo,l.label_colorFrom,
                        t.label_type_id,t.label_type_name,t.label_type_status
                        ')->where(array('l.label_delete_time'=>NULL))->order('l.label_create_time','desc')->paginate($limit,1000);
        static $arr = array();
        foreach ($datas as $key => $value){
         	$value['label_modify_time']= GetTime($value['label_modify_time']);
            $value['label_create_time']= GetTime($value['label_create_time']);
            $value['label_thumb']= GetUrl($value['label_thumb']);
             $arr[] = $value;
         }
        $count=$this->where(array('label_delete_time'=>NULL))->count();
        $dataJson=["code"=>0,"msg"=>"","count"=>$count,"data"=>$arr];
      	return $dataJson;
    }
    public function GetLabelInfoById($label_id){
        $label_info=$this->find($label_id);
        return $label_info;
    }
    public function GetLabelSelect(){
        $label_select=$this->where(array("label_status"=>'1',"label_delete_time"=>null))->select();
        return $label_select;
    }
    public function GetLabelJsonData($limit){
        $label_select=$this->where(array("label_status"=>'1',"label_delete_time"=>null))->paginate($limit,1000);
        return $label_select;
    }
}
