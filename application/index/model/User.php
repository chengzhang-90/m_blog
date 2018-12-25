<?php
namespace app\index\model;
use think\Model;
use think\Db;
class User extends Model
{
    public function get_value_user_info($uid,$user_email,$user_token){
    	$user_info =db('user')->where(array('user_id'=>$uid,'user_email'=>$user_email,'user_token'=>$user_token))->find();
      	return $user_info;
    }

    //根据phone获取user
     public function get_phone_user_info($user_phone){
    	$user_info =db('user')->where(array('user_phone'=>$user_phone))->find();
      	return $user_info;
    }

    //
    public function GetUserInfoById($user_id){
		$user_info =db('user')->where(array('user_id'=>$user_id))->find();
      	return $user_info;
    }
}
