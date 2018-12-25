<?php
namespace app\index\controller;
use think\Controller;
use think\Log;
use think\Request;
use think\Session;
use app\common\controller\ComFunction;
use app\common\controller\Mailer;
use app\index\controller\Func;
use app\index\model\User as UserModel;
use app\index\model\Login as LoginModel;
class Login extends Base
{
    public function index()
    {	
        $this->GetCommon();
        return $this->fetch("index");
    }
    //登录
    public function tologin(){
    	if (request()->isPost()) {
    		$ComFunction=new ComFunction();
    		$LoginModel=new LoginModel();
    		$post = request()->post();
    		$isEmail=$ComFunction->isEmail($post["user_email_phone"]);

    		if ($isEmail) {
    			$post["user_email"]=$post["user_email_phone"];
    		}else{
    			$post["user_phone"]=$post["user_email_phone"];
    		}
    		$num=$LoginModel->dologin($post);
            if($num==1){
                $ip = $_SERVER["REMOTE_ADDR"];
                $log_data=[
                    'index_log_user_id'=>session('user_id'),
                    'index_log_title' =>'登陆成功',
                ];
              	Log::notice($log_data);
              	$index_data=[
                	'user_id'=>session('user_id'),
                    'user_last_login_time'=>time(),
                    'user_last_login_ip'=>$ip,
                ];
              	$save=db('user')->update($index_data);
                if ($save) {
                  return $this->success(1,'index/index/index',['info' => '登陆成功']);
                }
            }elseif($num==5){
                return $this->error(5,'',['info' => '登录失败，您还没有通过验证']);
            }elseif($num==3){
                return $this->error(3,'',['info' => '用户被禁用,请联系管理员']);
            }elseif($num==4){
                return $this->error(4,'',['info' => '用户不存在']);
            }elseif($num==2){
                return $this->error(2,'',['info' => '用户名与密码错误']);
            }else{
                return $this->error(0,'',['info' => '未知错误']);
            }
    	}
    }
    //注册
    public function register()
    {	
    	if (request()->isPost()) {
            $ComFunction = new ComFunction();
            $randStr = $ComFunction->get_randStr(array("number"=>25));
            $post = request()->post();
            //生成token
            $user_token = $randStr.time();
            $user_phone=$post["user_phone"];
            $user_email=$post["user_email"];
            $user_nickname=$post["user_nickname"];
            $user_password=$post["user_password"];
            $code= $post["code"];
            //手机
            if (!empty($user_phone) && !empty($code)) {
                $Session = Session::get("phone_".$user_phone);
                if ($Session["time"]!=null) {
                    $outtime =time() - $Session["time"];
                    Session::delete("phone_".$user_phone);
                    if ($outtime > 60*15) {
                        return $this->error(false,'',['info' =>lang("Error-002")]);
                        //验证码已经过期,请重新获取
                    }else{
                        if ($Session["code"] == $code) {
                            $UserModel = new UserModel();
                            if ($UserModel->get_phone_user_info($user_phone)) {
                                //已经存在此手机号码
                                return $this->error(false,'',['info' =>lang("Error-005")]);
                            }else{
                                $value = [
                                    "user_phone"        =>      $user_phone,
                                    "user_token"        =>      $user_token
                                ];
                                //验证码正确
                                $data=[
                                    'db'=>'user',
                                    'title'=>$user_phone,
                                    'note'=>'注册会员',
                                    'post'=>$value,
                                    'action'=>"add",
                                    'type'=>"insertGetId"
                                ];
                                $Func = new Func();
                                $user_id= $Func->get_action($data);
                                if (!empty($user_id)) {
                                     return $this->success(true,'',['info' =>lang("Success-005")]);
                                }else{
                                    return $this->error(false,'',['info' => lang("Error-003")]);
                                }
                            }
                            
                        }else{
                            return $this->error(false,'',['info' =>lang("Error-004")]);
                            //验证码失误，请重新输入
                        }
                    }
                }else{
                    return $this->error(false,'',['info' =>lang("Error-002")]);
                }
            }
            if (!empty($user_email) && !empty($user_nickname) && !empty($user_password)) {
                $value = [
                    "user_password"     =>      md5($user_password),
                    "user_nickname"     =>      $user_nickname,
                    "user_email"        =>      $user_email,
                    "user_token"        =>      $user_token
                ];
                $data=[
                    'db'=>'user',
                    'title'=>$user_nickname,
                    'note'=>'注册会员',
                    'post'=>$value,
                    'action'=>"add",
                    'type'=>"insertGetId"
                ];
                $Func = new Func();
                $user_id= $Func->get_action($data);
                if (!empty($user_id)) {
                    $request = Request::instance();
                    $mailer = new Mailer();
                    $toemail=$user_email;
                    $name=$user_nickname;
                    $subject=lang("Success-004");
                    $content='<style type="text/css">
                                    body{}
                                    .container{background:#eeeeee;border-radius:5px;width:100%;height:100%;padding:100px 0;}
                                    .box{width:600px;height:300px;background:#fff;margin:0 auto;border-radius:5px;}
                                    .header{height:80px;text-align:center;background:#212b39;line-height:80px;color:#fff;border-top-left-radius:5px;border-top-right-radius:5px;}
                                    .box-b{height:200px;margin:0 auto 0;width:80%;text-align:center;}
                                    .box-b a{padding:10px 80px;border-radius:5px;background:#1ABC9C;text-align:center;color:#fff;}
                                    .box-b-t{color:#555;text-align:left;line-height:80px;}
                                    .box-b p{color:#555;position:absolute;bottom:20px;}
                                </style>
                                </head>
                                <body>
                                    <div class="container" >
                                        <div class="box">
                                            <div class="header">'.$user_nickname.',你好!你已经成功注册了blog</div>
                                            <div class="box-b">
                                                <div class="box-b-t">请验证帐户，完成注册步骤。验证帐户请打开以下链接进行验证：</div>
                                                <a href="http://'.$_SERVER['SERVER_NAME'].'/index/login/verification.html?user_token='.$user_token.'&user_email='.$user_email.'&user_id='.$user_id.'">点我进行验证哦</a>
                                                <p>你可以在 blog 与亲友即时交流，保持联络<br/>加入 blog，和大家一起分享照片、组织活动，畅享新鲜功能</p>
                                            </div>
                                        </div>
                                    </div>
                                </body>';
                    if ($mailer->send_mail($toemail,$name,$subject,$content)) {
                        return $this->success(true,'',['info' =>lang("Success-002")]);//注册成功,请登录邮箱去完成验证
                    }else{
                        return $this->error(false,'',['info' =>lang("Success-003")]);
                        //注册成功,由于服务器错误发送邮件失败，请联系管理员！！
                    }
                }else{
                    return $this->error(false,'',['info' => lang("Error-003")]);
                    //注册失败
                }
            }
    	}
    }
    //邮箱验证verification
    public function verification(){
        if (request()->isGet()) {
            $UserModel = new UserModel();
            $value=request()->get();
            if (!empty($value["user_email"])) {
                $user_info = $UserModel->get_value_user_info($value["user_id"],$value["user_email"],$value["user_token"]);
                $bool =false;
                if (!empty($user_info )) {
                   $bool = $UserModel->where('user_id', $user_info["user_id"])->update(['user_verification' => 0]);
                }
                if ($bool) {
                   $this->GetCommon();
                   $this->assign("user_info",$user_info);
                   return $this->fetch("login/index");
                }else{
                    $this->error("Message-000",url('login/index'));//已验证
                }
            }
        }
    }



	//退出
	public function logout(){
		session('user_id',null);
		session('user_phone',null);
		session('user_id',null);
		return $this->success(true,'self_url',['info' => '退出成功']);
	}
}