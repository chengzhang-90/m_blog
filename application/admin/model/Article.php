<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class Article extends Model
{
    public function GetArticleData($limit){
    	$datas =db('article')->alias('a')
                    ->join('article_cate c','c.article_cate_id=a.article_article_cate_id')
                    ->field('
                        a.article_id,a.article_title,a.article_author,a.article_source,a.article_create_time,a.article_modify_time,a.article_thumb,a.article_keyword,a.article_desc,a.article_content,a.article_article_cate_id,a.article_status,a.article_push,a.article_fine,a.article_delete_time,a.article_label_id,a.article_type,
                        c.article_cate_name
                        ')->where(array('a.article_delete_time'=>NULL))->order('a.article_create_time','desc')->paginate($limit,1000);
         static $arr = array();
         foreach ($datas as $key => $value){
         	$value['article_modify_time']=GetTime($value['article_modify_time']);
            if (!empty($value['article_thumb'])){
                $value['article_thumb']= GetUrl($value['article_thumb']);
            }
            $value['article_create_time']=GetTime($value['article_create_time']);
            $value['article_title']=GetMbSubstr($value["article_title"],10);
            $value['article_desc']=GetMbSubstr($value["article_desc"],18);
            $arr[] = $value;
         }
        $count=$this->where(array('article_delete_time'=>NULL))->count();
        $dataJson=["code"=>0,"msg"=>"","count"=>$count,"data"=>$arr];
      	return $dataJson;
    }
    public function GetArticleInfoById($id){
        $article_info=$this->find($id);
        return $article_info;
    }

    
}