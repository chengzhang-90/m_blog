<?php
namespace app\admin\model;
use think\Model;
use think\Db;
use app\admin\model\AuthGroup as AuthGroupModel;
class Admin extends Model
{
    public function GetAdminData($data){
    	$datas =db('admin')->where(array('admin_delete_time'=>null))->order('admin_create_time','desc')->paginate($data);
        $AuthGroupModel = new AuthGroupModel();
        static $arr = array();
        foreach ($datas as $key => $value){
         	$value['admin_modify_time']= GetTime($value['admin_modify_time']);
            $value['admin_create_time']= GetTime($value['admin_create_time']);
            $value['admin_last_login_time']= GetTime($value['admin_last_login_time']);
            $value['admin_header_pic']= GetUrl($value['admin_header_pic']);
            if (!empty($value['admin_auth_group_id'])) {
                $value['admin_auth_group_data'] = $AuthGroupModel->GetAuthGroupInfoById($value['admin_auth_group_id']);
            }
            $arr[] = $value;
        }
        $count=db('admin')->where(array('admin_create_time'=>NULL))->count();
        $dataJson=["code"=>0,"msg"=>"","count"=>$count,"data"=>$arr];
        return $dataJson;
    }
    public function GetAdminInfoById($admin_id){
        $admin_info=db("admin")->find($admin_id);
        return $admin_info;
    }
    //角色
    public function GetAuthGroup(){
        $auth_group_select=db("auth_group")->where(array("auth_group_delete_time"=>NULL))->select();
        return $auth_group_select;
    }
}
