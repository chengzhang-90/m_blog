<?php
namespace app\index\controller;
use think\Controller;
use think\Log;
use think\Config;
use app\index\model\Nav as NavModel;
use app\index\model\User as UserModel;
use app\index\model\Article as ArticleModel;
use app\index\model\ArticleCate as ArticleCateModel;
class User extends Base
{
    public function index()
    {	
    	if (empty(session("user_id"))) {
    		$this->error('请登录!','Login/index');
    	}
    	
    	$this->GetCommon();
        return $this->fetch("index");
    }
    public function GetUploadImg(){
		if(!empty(request()->file('thumb'))){
			$file =request()->file('thumb');
		 // dump(request()->file());die;
			if($file){
				$path =ROOT_PATH . Config::get("__PUBLIC_UPLOADS_IMAGE__");
				$url =$thumbStr= Config::get("DOMAIN").Config::get("__UPLOADS_IMAGE__");
		        $info = $file->move($path);
		        if($info){
		        	$data = [
			            "url" => $url.DS.$info->getSaveName(),
			            "address" =>$path.DS.$info->getSaveName(),
			            "address_type" => 1,
			            "type" =>"image"
			        ];
			        $str = json_encode($data,JSON_UNESCAPED_SLASHES);
			        $post = ["user_id"=>session("user_id"),"user_headpic"=>$str];
			        $this->GetAction("user",$post,"update","修改头像",true);
		           	return GetUrl($str);
		        }else{
		            return $file->getError();
		        }
		    }
		}
	}
}