<?php
namespace app\index\model;
use think\Model;
use think\Db;
class WebConfig extends Model
{
    public function GetWebConfigInfo(){
    	$web_config_info =db('web_config')->find(1);
      	return $web_config_info;
    }
}
