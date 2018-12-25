<?php
namespace app\admin\controller;
use think\Controller;
use think\Log;
use think\Config;
use app\admin\model\Admin as AdminModel;
use app\admin\model\Module as ModuleModel;
use app\admin\model\Index as IndexModel;
use app\admin\common\Func as FuncModel;
class Base extends Controller
{
	public function _initialize(){
    // $data=[
    //   'admin_log_admin_id'=>'1',
    //   'admin_log_title' =>'登陆成功',
    // ];

    // Log::notice($data);
          
        if(!session('admin_username')&&!session('admin_id')){
            return $this->error('请登录!','Login/index');
        }
        $this->GetCommonInfo();
    }

    public function GetCommonInfo(){
        $FuncModel=new FuncModel();
        $url=request()->controller().'/'.request()->action();
        if ($FuncModel->GetAuth(array("url"=>$url))) {
            /*用户信息*/
            $admin_model=new AdminModel();
            $admin_info=$admin_model->GetAdminInfoById(session("admin_id"));
            $this->assign("admin_info",$admin_info);
            $index_model=new IndexModel();
            $module_lst=$index_model->GetModule();
            $this->assign('module_lst',$module_lst);
            $module_info=$index_model->GetModuleName(array('controller'=>request()->controller(),'action'=>request()->action()));
            $this->assign('module_info',$module_info);
        }else{
            return $this->error('window.close()');
        }
    }

    //一级菜单
    public function GetNav()
    {
      $index_model=new IndexModel();
      $module_nav=$index_model->GetModuleNav();
      $this->assign('module_nav',$module_nav);
    }


    //操作
    public function GetAction($db,$value,$type,$note,$true=false){
        $post = [];
        switch ($type) {
            case 'DelArr':
                foreach ($value[$db.'_arr'] as $key => $v) {
                     $post[$key]=[
                        $db.'_id'=>$v
                     ];
                }
                break;
            default:
                $post = $value;
                break;
        }
        $data=[
                'db'=>$db,
                'title'=>$note,
                'note'=>$note,
                'post'=>$post,
                'action'=>$type,
        ];
        $FuncModel = new FuncModel();
        $message= $FuncModel->GetAction($data,$true);
        return $message;
    }
    //上传缓存复制到永久目录
    public function PermanentCopy($data,$type="IMAGE")
    {
        $FuncModel=new FuncModel();
        if (!empty($data)) {
            $dirStr = Config::get("__PUBLIC_UPLOADS_".$type."__");
            $thumbStr= Config::get("DOMAIN").Config::get("__UPLOADS_".$type."__");
            $dir = iconv("UTF-8", "GBK", ROOT_PATH . $dirStr.DS.date("Ymd",time()) );
            $upload=ROOT_PATH . $dirStr.DS.$data;
            $thumb= $thumbStr.DS.$data;
            $cache=ROOT_PATH .Config::get("__PUBLIC_UPLOADS_CACHE__"). DS .$data;
            if (!file_exists($dir)){
                mkdir ($dir,0777,true);
            }
            copy($cache, $upload);
            $merge=[
                'gallery_type'=>$type,
                'gallery_url'=>$thumb,
                'gallery_address'=>$upload,
            ];
            $this->GetAction("gallery",$merge,"add","上传文件",true);
        }else{
            $thumb=null;
            $upload=null;
        }
        $data = [
            "url" => $thumb,
            "address" => $upload,
            "address_type" => 1,
            "type" =>strtolower($type)
        ];
        return json_encode($data,JSON_UNESCAPED_SLASHES);
    }
    public function PermanentUnlink($path){
         return is_file($path) && unlink($path);
    }
    //解压文件
    public function DecompressionFile($dir,$type="FILE"){
        //获取文件类型

        $type_wj = pathinfo($dir, PATHINFO_EXTENSION);
        $path =  ROOT_PATH.Config::get("__PUBLIC_PROJECT__").DS.date("Ymd",time()).DS.date("His",time());
        $url = Config::get("DOMAIN").Config::get("__PROJECT__").DS.date("Ymd",time()).DS.date("His",time());
        //判断文件类型
        $FuncModel = new FuncModel();
        if(strtolower($type_wj) == "zip" ){
            $FileName = $FuncModel->UnzipFile($dir,$path);
            $res = $url .DS.$FileName .DS. "index.html";
            $data = [
                "url" => $res,
                "address" => $path.DS.$FileName,
                "address_type" => 1,
                "type" =>strtolower($type)
            ];
            return json_encode($data,JSON_UNESCAPED_SLASHES);
        }
    }
    public function PicNull(){
        $WebConfigModel = new WebConfigModel();
        $web_config_info=$WebConfigModel->get_web_config_info();
        return  $web_config_info["web_config_default_pic"];
    }
}