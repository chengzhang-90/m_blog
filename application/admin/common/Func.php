<?php
namespace app\admin\common;
use think\Controller;
use think\Db;
use think\Log;
use app\admin\model\Article;
use app\admin\model\Link;
use app\admin\model\Label;
use app\admin\model\Nav;
use app\admin\model\AuthGroup;
use app\admin\model\Func as FuncModel;
class Func extends Controller
{

    private static $msg_auth="不好意思！您的权限不够哦！";
    private static $msg_false="失败！";
    private static $msg_true="成功";
    //修改，删除
    public function GetAction($value,$true=false)
    {
        if ($this->GetAuth(array('url'=>request()->controller().'/'.request()->action()))){

        	$action=$value['action'];
            $timeArr=[];
            $modify_time=$value['db'].'_modify_time';
            switch ($action) {
                case 'update':
                     $timeArr=[$modify_time=>time()];
                    break;
                case 'delete':
                     $timeArr=[$value['db'].'_delete_time'=>time()];
                    break;
                case 'add':
                    $timeArr=[$value['db'].'_create_time'=>time(),$modify_time=>time()];
                    break;
                case 'DelArr':

                    foreach ($value['post'] as $k => $v) {
                        $value['post'][$k]=[
                            $value['db']."_delete_time"=>time()
                        ];
                        $value['post'][$k]=array_merge($v,$value['post'][$k]);
                    }
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
                case 'DelArr':
                    $save=$FuncModel->GetDataDelAll($data);
                    $msg ="  ID:(".$save.")";
                    break;
                default:
                    $save=$FuncModel->GetDataEdit($data);
                    break;
            }
            if ($true != true) {
                if ($save ) {
                    $this->GetLog($value['title'],$value['note'],"1");
                    return $this->success($value['note'].self::$msg_true. $msg);
                }else{
                    $this->GetLog($value['title'],$value['note'],"0");
                    return $this->error($value['note'].self::$msg_false. $msg);
                }
            }else{
                return null;
            }
        } 
        $this->error(self::$msg_auth);
    }
    /*权限**/
    public function GetAuth($data){
        if (!empty($data)) {
            $admin_info=db('admin')->find(session('admin_id'));
            $auth_group=db("auth_group")->find($admin_info['admin_auth_group_id']);
            $auth=json_decode($auth_group['auth_group_value']);
            if (session("admin_id")==1) {
                return true;
            }else{
                $module_info=db('module')->where(array('module_url'=>"/admin/".$data['url'],"module_delete_time"=>NULL,"module_is_menu"=>1))->find();
                if (in_array($module_info['module_id'], $auth)&&$auth_group['auth_group_status']==0) {
                   return true;
                }else{
                   return false;
                }
            }
        }
        return false;
    }


    //操作记录
    public function GetLog($title,$note,$type){
        switch ($type) {
            case '1':
                $operation = $note.self::$msg_true;
                break;
            
            case '0':
                $operation = $note.self::$msg_false;
                break;
        }
        $log=[
            'admin_log_admin_id'=>session('admin_id'),
            'admin_log_name'=>session('admin_username'),
            'admin_log_title' =>$title,
            'admin_log_action_info'=>$note,
            'admin_log_operation'=>$operation
        ]; 
        Log::notice($log);
    }
    /**
     *生成随机数，可用户验证码
     *@param
     */
    public function get_randStr($m) {
      $new_str = '';
      $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwsyz0123456789';
      $max=strlen($str)-1;
      for ($i = 1; $i <= $m; ++$i) {
        $new_str .=$str[mt_rand(0, $max)];
      }
      return $new_str;
    }
    /**
     *解压zip
     *@param
     */
    public function UnzipFile($file, $dir){ 
        // 实例化对象 
        $zip = new \ZipArchive; 
        //打开zip文档，如果打开失败返回提示信息 
        if ($zip->open($file) !== TRUE) { 
          die ("Could not open archive"); 
        } 
        //将压缩文件解压到指定的目录下
        $zip->extractTo($dir); 
        //关闭zip文档 
        $zip->close();
        $fileName =  $this->LoopFun($dir);
        return  $fileName;
    }
    public function LoopFun($dir)  
    {  
        $handle = opendir($dir."/.");
        //定义用于存储文件名的数组
        $array_file = NULL;
        while (false !== ($file = readdir($handle)))
        {
            if ($file != "." && $file != "..") {
                $array_file =$file; //输出文件名
            }
        }
        closedir($handle);
        return $array_file;
        //print_r($array_file);
    }
}