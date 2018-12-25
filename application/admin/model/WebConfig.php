<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class WebConfig extends Model
{
	public function get_web_config_info(){
		$web_cofig_info=db("web_config")->find(1);
		return $web_cofig_info;
	}
}
