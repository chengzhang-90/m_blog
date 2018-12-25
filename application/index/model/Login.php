<?php
namespace app\index\model;
use think\Model;
use think\Db;
class Login extends Model
{
	public function dologin($data){
		if (!empty($data["user_email"])) {
			$user=Db::name('user')->where('user_email','=',$data['user_email'])->find();
		}
		if (!empty($data["user_phone"])) {
			$user=Db::name('user')->where('user_phone','=',$data['user_phone'])->find();
		}
		
		if($user){
			if($user['user_status'] ==0 && $user['user_verification'] == 0 ){
				if($user['user_password'] == md5($data['user_password'])){
					session('user_email',$user['user_email']);
					session('user_phone',$user['user_phone']);
					session('user_id',$user['user_id']);
					return 1; 		
				}else{
					return 2; //密码错误	
				}				
			}else if ($user['user_verification'] == 1) {
				return 5; //用户没验证
			}else{
				return 3; //用户被禁用	
			}
		}else{
			return 4; //用户不存在
		}
	}
}
