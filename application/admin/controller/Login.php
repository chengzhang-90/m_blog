<?php
namespace app\admin\controller;
use think\Controller;
use think\Log;
use think\Lang;
use app\admin\model\Login as LoginModel;
use app\common\model\Getip;
class Login extends Controller
{
    public function index()
    {
        //cookie('think_var','zh-cn');
        if(request()->isPost()){
            $admin=new LoginModel();
            $data=input('post.');
            $num=$admin->dologin($data);
            if($num==1){
                $ip = $_SERVER["REMOTE_ADDR"];
                // $Getip = new Getip; 
                // $ipInfos = $Getip->GetIpLookup($ip);
                $log_data=[
                    'admin_log_admin_id'=>session('admin_id'),
                    'admin_log_title' =>'登陆成功',
                ];
              	Log::notice($log_data);
              	$admin_data=[
                	'admin_id'=>session('admin_id'),
                    'admin_last_login_time'=>time(),
                    'admin_last_login_ip'=>$ip,
                ];
              	$save=db('admin')->update($admin_data);
                if ($save) {
                  $this->success('登陆成功,正在跳转...',url('admin/index/index'));
                }
            }elseif($num==5){
                $this->error('验证码错误',null,5);
            }elseif($num==3){
                $this->error('用户被禁用,请联系管理员',null,3);
            }elseif($num==4){
                $this->error('用户不存在',null,4);
            }elseif($num==2){
                $this->error('用户名与密码错误',null,2);
            }else{
                $this->error('未知错误');
            }
        }else{
            return $this->fetch();
        }
    }
    //退出
    public function logout(){
        session('admin_username',null);
        session('admin_id',null);
        return $this->success(true,'self_url',['info' => '退出成功']);
    }
}
