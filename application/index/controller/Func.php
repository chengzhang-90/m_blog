<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Log;
use think\Session;
use app\index\model\Func as FuncModel;
use app\common\controller\Sms;
use app\common\controller\ComFunction;
class Func extends Controller
{

    private static $msg_auth="不好意思！您的权限不够哦！";
    private static $msg_false="失败！";
    private static $msg_true="成功";
    //修改，删除
    public function GetAction($value,$true=false)
    {
        // if ($this->GetAuth(array('url'=>request()->controller().'/'.request()->action()))){

            $action=$value['action'];
            $timeArr=[];
            $modify_time=$value['db'].'_modify_time';
            switch ($action) {
                case 'update':
                     $timeArr=[$modify_time=>time()];
                    break;
                case 'add':
                    $timeArr=[$value['db'].'_create_time'=>time(),$modify_time=>time()];
                    break;
            }
            $merge=array_merge($value['post'],$timeArr);
            $data=array(
                "db"=>$value["db"],
                "data"=>$merge
            );
            $FuncModel = new FuncModel;
            $msg = "";
            switch ($action) {
                case 'add':
                    $save=$FuncModel->GetDataInsert($data);
                    break;
                default:
                    $save=$FuncModel->GetDataEdit($data);
                    break;
            }
            if ($true != true) {
                if ($save ) {
                    return $this->success($value['note'].self::$msg_true. $msg);
                }else{
                    return $this->error($value['note'].self::$msg_false. $msg);
                }
            }else{
                return null;
            }
        // } 
        // $this->error(self::$msg_auth);
    }
    //
    public function get_code_sms($phone=''){
        if (!empty($phone)) {
            $sms = new sms();
            $ComFunction = new ComFunction();
            $code=$ComFunction->get_randStr(array("number"=>6,"type"=>"number"));
            if (Session::has("phone_".$phone)) {
                $phone_code=Session::get("phone_".$phone);
                $outtime = time()-$phone_code["time"];
                if ($outtime > 60) {
                    //大于60秒重置code
                    Session::set("phone_".$phone,array("code"=>$code,"time"=>time()));
                    $sms_msg = $sms->get_sms($phone,$code);
                }else{
                     return $this->error(false,'',['info' => lang('Error-000')]);
                }
            }else{
                //不存在就添加
                Session::set("phone_".$phone,array("code"=>$code,"time"=>time()));
                $sms_msg = $sms->get_sms($phone,$code);
            }
            if ($sms_msg->Code=="OK") {
                Session::set("phone_".$phone,array("code"=>$code,"time"=>time()));
                return $this->success(true,'',['info' => lang('Success-000')]);
            }else{
                return $this->error(false,'',['info' => $sms_msg]);
            }
        }
    }
































    // public function get_code_sms($phone=''){
    //     if (!empty($phone)) {
    //         $sms = new sms();
    //         $ComFunction = new ComFunction();
    //         $code=$ComFunction->get_randStr(array("number"=>6,"type"=>"number"));
    //         if (Session::has("phone_".$phone)) {
    //             $phone_code=Session::get();
    //             foreach ($phone_code as $key => $value) {
    //                 $outtime = time()-$value["time"];
    //                 if ($outtime > 60) {
    //                     $sms_msg = $sms->get_sms($phone,$code);
    //                     if ($outtime > 60*15) {
    //                         if (Session::delete($key)) {
    //                             Session::set("phone_".$phone,array("code"=>$code,"time"=>time()));
    //                         }
    //                         return $this->success(true,'',['info' => lang('Request success')]);
    //                     }
    //                 }else{
    //                     return $this->error(false,'',['info' => lang('Frequent error')]);
    //                 }
    //             }
    //         }else{
    //             $sms_msg = $sms->get_sms($phone,$code);
    //         }
    //         if ($sms_msg->Code=="OK") {
    //             Session::set("phone_".$phone,array("code"=>$code,"time"=>time()));
    //             return $this->success(true,'',['info' => lang('Request success')]);
    //         }else{
    //             return $this->error(false,'',['info' => $sms_msg]);
    //         }
    //     }
    // }
}