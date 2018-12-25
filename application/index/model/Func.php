<?php
namespace app\index\model;
use think\Model;
use think\Db;
use think\Log;
use app\index\model\User; 
use app\index\model\ArticleDiscuss; 
use app\index\model\Article; 
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
                case 'article_discuss':
                    $model = new ArticleDiscuss();
                    break;
                case 'article':
                    $model = new Article();
                    break;
            }
        }else{
             switch ($db) {
                case 'user':
                    $model = new User($data);
                    break;
                case 'article_discuss':
                    $model = new ArticleDiscuss($data);
                    break;
                case 'article':
                    $model = new Article($data);
                    break;
            }
        }
        return $model;
    }
}