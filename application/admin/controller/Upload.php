<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;
use think\Log;
use think\Config;
class Upload extends Controller
{
	public function GetUploadImg(){
		if(!empty(request()->file('thumb'))){
			$file =request()->file('thumb');
		 // dump(request()->file());die;
			if($file){
		        $info = $file->move(ROOT_PATH . Config::get("__PUBLIC_UPLOADS_CACHE__"));
		        if($info){
		           	return $info->getSaveName();
		        }else{
		            return $file->getError();
		        }
		    }
		}
	}
	public function GetUploadFile(){
		if(!empty(request()->file())){
			$file =request()->file("file");
			if($file){
		        $info = $file->move(ROOT_PATH . Config::get("__PUBLIC_UPLOADS_CACHE__"));
		        if($info){
		        	$data = [
		        		"code" => 1,
		        		"msg"=>"上传成功",
		        		"data" =>$info->getSaveName()
		        	];
		           	
		        }else{
		            $data = [
		        		"code" => 0,
		        		"msg"=>"上传失败",
		        		"data" =>$info->getError(),
		        	];
		        }
		        return $data;
		    }
		}
	}
	public function getUpload(){
		if ($_SERVER['SERVER_NAME']=="www.hhm-blog.com") {
			if(!empty(request()->file('thumb'))){
				$file =request()->file('thumb');
			}
			if(!empty(request()->file('file_zip'))){
				$file =request()->file('file_zip');
			}
			 // dump(request()->file());die;
			if($file){
		        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads'.DS.'cache');
		        if($info){
		           	return $info->getSaveName();
		        }else{
		            return $file->getError();
		        }
		    }
		}
	}
}