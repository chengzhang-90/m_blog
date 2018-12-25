<?php
namespace app\index\model;
use think\Model;
use think\Db;
use app\index\model\ArticleDiscuss; 
class Article extends Model
{
    public function GetArticleData($limit=null,$type='1'){
    	$data =db('article')
            ->where('article_type',$type)
            ->where('article_delete_time',NULL)
            ->order('article_sort desc')
            ->order('article_create_time','desc')
            ->limit($limit)
            ->select();
      	return $data;
    }
    public function GetArticleInfoById($article_id){
    	$article_info =db('article')->alias('a')
                    ->join('article_cate c','c.article_cate_id=a.article_article_cate_id')
                    ->field('
                        a.article_id,a.article_title,a.article_author,a.article_source,a.article_create_time,a.article_modify_time,a.article_thumb,a.article_keyword,a.article_desc,a.article_content,a.article_article_cate_id,a.article_status,a.article_push,a.article_fine,a.article_delete_time,a.article_label_id,a.article_type,a.article_views,a.article_laud,a.article_love,a.article_project_url,
                        c.article_cate_name
                        ')->where(array('a.article_delete_time'=>NULL))->find($article_id);
        
    	$article_last =Db::query("select * from m_article where article_id = (select article_id from m_article where article_id < {$article_id} order by article_id desc limit 1)");
    	$article_next =Db::query("select * from m_article where article_id = (select article_id from m_article where article_id > {$article_id} order by article_id ASC limit 1)");
        $ArticleDiscuss = new ArticleDiscuss();
        $article_info["ArticleDiscussSelect"] = $ArticleDiscuss->GetArticleDiscussSelectByArticleId($article_info["article_id"]);
    	if (!empty($article_last[0])) {
    		$article_last =$article_last[0];
    	}else{
    		$article_last = $article_info;
    	}
    	if (!empty($article_next[0])) {
    		$article_next =$article_next[0];
    	}else{
    		$article_next = $article_info;
    	}
    	$article_info_data=[
    		"article_info"=>$article_info,
    		"article_last"=>$article_last,
    		"article_next"=>$article_next
    	];
      	return $article_info_data;
    }
    public function GetArticleSelect($article_select_arr_id){
		$article_select =db('article')->where(array("article_delete_time"=>NULL))->where('article_article_cate_id','in',$article_select_arr_id)->paginate(5);
      	return $article_select;
    }
    //热门
    public function GetHotArticle(){
    	$article_hot=db('article')->where(array("article_delete_time"=>NULL,"article_status"=>"1"))->order("article_views","desc")->select();
    	return $article_hot;
    }
    
    //根据 关键字获取
    public function  GetArticleKeywordSelect($keyword){
    	$article_keyword_select=db('article')->where(array("article_delete_time"=>NULL))->where('article_keyword','LIKE', '%'.$keyword.'%')->order("article_create_time","desc")->paginate(5);
    	return $article_keyword_select;
    }
    //根据标签获取
    public function GetArticleLabelSelect($label_id){
    	$article_label_select=db('article')->where(array("article_delete_time"=>NULL))->where('article_label_id','LIKE', '%'.$label_id.'%')->order("article_create_time","desc")->paginate(5);
    	return $article_label_select;
    }
    public function GetArticleLaudIf($value){
        $article_laud = db("article_laud")->where(array("article_laud_user_id"=>$value["user_id"],"article_laud_article_id"=>$value["article_id"]))->find();
        return $article_laud;
    }


    //获取精选
    public function GetFineArticle($type){
        $data =db('article')
        ->where('article_type',$type)
        ->where('article_fine','1')
        ->where('article_delete_time',NULL)
        ->order('article_sort desc')
        ->order('article_create_time','desc')->select();
        return $data;
    }

    //推荐
    public function GetPushArticle($type){
        $data=db('article')
        ->where('article_type',$type)
        ->where('article_push','1')
        ->where("article_delete_time",NULL)
        ->order("article_views","desc")->select();
        return $data;
    }
}   
