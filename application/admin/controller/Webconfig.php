<?php
namespace app\admin\controller;
use think\Db;
use think\Log;
use app\admin\model\WebConfig as WebConfigModel;
use app\admin\common\Func as FuncModel;
class WebConfig extends Base
{

	public function index(){
		$WebConfigModel=new WebConfigModel();
		$web_config_info=$WebConfigModel->get_web_config_info();
		$this->assign("web_config_info",$web_config_info);
		return $this->fetch();
	}
	public function edit(){
		if (request()->Ispost()) {
			$post=request()->post();
			if (!empty($post['web_config_logo'])){
                $post["web_config_logo"]=$this->PermanentCopy($post['web_config_logo'],"FILE");
            }else{
                unset($post["web_config_logo"]);
            }
            if (!empty($post['web_config_icon'])){
                $post["web_config_icon"]=$this->PermanentCopy($post['web_config_icon'],"FILE");
            }else{
                unset($post["web_config_icon"]);
            }
            if (!empty($post['web_config_wechat_share_logo'])){
                $post["web_config_wechat_share_logo"]=$this->PermanentCopy($post['web_config_wechat_share_logo'],"FILE");
            }else{
                unset($post["web_config_wechat_share_logo"]);
            }
            if (!empty($post['web_config_code'])){
                $post["web_config_code"]=$this->PermanentCopy($post['web_config_code'],"FILE");
            }else{
                unset($post["web_config_code"]);
            }
            if (!empty($post['web_config_wechat_code'])){
                $post["web_config_wechat_code"]=$this->PermanentCopy($post['web_config_wechat_code'],"FILE");
            }else{
                unset($post["web_config_wechat_code"]);
            }
            if (!empty($post['web_config_default_pic'])){
                $post["web_config_default_pic"]=$this->PermanentCopy($post['web_config_default_pic'],"FILE");
            }else{
                unset($post["web_config_default_pic"]);
            }
            $this->GetAction("web_config",$post,"update","修改配置");
		}
	}
}