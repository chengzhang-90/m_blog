<?php
namespace app\common\model;
use think\Model;
use think\Db;
use think\Log;
use app\admin\model\Admin;          //管理员
use app\admin\model\User;           //会员
use app\admin\model\AuthGroup;      //权限
use app\admin\model\Nav;            //导航
use app\admin\model\Location;
use app\admin\model\Module;         //模块
use app\admin\model\WebConfig;
use app\admin\model\Article;
use app\admin\model\ArticleCate;    
use app\admin\model\Link;
use app\admin\model\Label;
use app\admin\model\LabelType;
use app\admin\model\Gallery;
use app\admin\model\Product;
use app\admin\model\ProductCategory;
use app\admin\model\ProductType;
class Func extends Model
{
    //修改,删除
    public function GetDataEdit($value)
    {
        $db = $value['db'];

        $model = $this->GetModel($db);
        return $model->allowField(true)->save($value["data"],[$db.'_id' =>(int)$value["data"][$db.'_id']]);
    }
    //添加
    public function GetDataInsert($value)
    {
        $data=$value["data"];
        
        $db = $value['db'];
        $model = $this->GetModel($db,$data);
        return $model->allowField(true)->save();
    }
    public function GetDataDelAll($value){
        $db = $value['db'];
        $model = $this->GetModel($db);
        $save=$model->isUpdate()->saveAll($value['data']);

        $result="";
        foreach ($save as $k => $v){
            $result_data=$v -> GetData();
            $result.=$result_data[$value['db']."_id"].",";
        }
        $result_id=substr($result, 0, -1);
        return  $result_id;
    }


    public function GetModel($db,$data=''){
        if (empty($data) || $data === null || $data === "") {
            switch ($db) {
                case 'user':
                    $model = new User();
                    break;
                case 'admin':
                    $model = new Admin();
                    break;
                case 'auth_group':
                    $model = new AuthGroup();
                    break;
                case 'article':
                    $model = new Article();
                    break;
                case 'article_cate':
                    $model = new ArticleCate();
                    break;
                case 'nav':
                    $model = new Nav();
                    break;
                case 'link':
                    $model = new Link();
                    break;
                case 'label':
                    $model = new Label();
                    break;
                case 'label_type':
                    $model = new LabelType();
                    break;
                case 'location':
                    $model = new Location();
                    break;
                case 'module':
                    $model = new Module();
                    break;
                case 'gallery':
                    $model = new Gallery();
                    break;
                case 'web_config':
                    $model = new WebConfig();
                    break;
            }
        }else{
             switch ($db) {
                case 'user':
                    $model = new User($data);
                    break;
                case 'admin':
                    $model = new Admin($data);
                    break;
                case 'auth_group':
                    $model = new AuthGroup($data);
                    break;
                case 'article':
                    $model = new Article($data);
                    break;
                case 'article_cate':
                    $model = new ArticleCate($data);
                    break;
                case 'nav':
                    $model = new Nav($data);
                    break;
                case 'label':
                    $model = new Label($data);
                    break;
                case 'label_type':
                    $model = new LabelType($data);
                    break;
                case 'link':
                    $model = new Link($data);
                    break;
                case 'location':
                    $model = new Location($data);
                    break;
                case 'module':
                    $model = new Module($data);
                    break;
                case 'gallery':
                    $model = new Gallery($data);
                    break;
                case 'web_config':
                    $model = new WebConfig($data);
                    break;
            }
        }
        return $model;
    }
}