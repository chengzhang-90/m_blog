<?php
namespace app\admin\model;
use think\Model;
use think\Db;
use app\admin\model\WebConfig as WebConfigModel;
class User extends Model
{
    public function get_user_data($data){
      $WebConfigModel = new WebConfigModel();
    	$datas =db('user')
              ->where(array('user_delete_time'=>NULL))
              ->order('user_create_time','desc')
              ->paginate($data["limit"],1000);
        static $arr = array();
        foreach ($datas as $key => $value){
         	if (!empty($value['user_modify_time'])){
         		$value['user_modify_time']= date("Y-m-d H:i:s",$value['user_modify_time']);
         	}
            $value['user_create_time']= date("Y-m-d H:i:s",$value['user_create_time']);
            if ($value['user_headpic']==null) {
                $web_config_info = $WebConfigModel->get_web_config_info();
                $value['user_headpic'] =GetUrl( $web_config_info["web_config_default_pic"]);
            }else{
                $value['user_headpic'] =GetUrl( $value["user_headpic"]);
            }
            $arr[] = $value;
         }
        $count=$this->where(array('user_delete_time'=>NULL))->count();
        $dataJson=["code"=>0,"msg"=>"","count"=>$count,"data"=>$arr];
      	return $dataJson;
    }
    public function get_user_info($user_id){
        $user_info=$this->find($user_id);
        return $user_info;
    }
}