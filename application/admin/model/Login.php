<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class Login extends Model
{
	public function dologin($data){
		$captcha = new \think\captcha\Captcha();
        if (!$captcha->check($data['code'])) {
            return 5;
        } 
		$admin=Db::name('admin')->where('admin_username','=',$data['admin_username'])->find();
		
		if($admin){
			if($admin['admin_status'] ==1){
				if($admin['admin_password'] == md5($data['admin_password'])){
					session('admin_username',$admin['admin_username']);
					session('admin_id',$admin['admin_id']);
					return 1; 		
				}else{
					return 2; //密码错误	
				}				
			}else{
				return 3; //用户被禁用	
			}
		}else{
			return 4; //用户不存在
		}
	}
}
