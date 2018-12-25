<?php
namespace app\index\model;
use think\Model;
use think\Db;
class ArticleCate extends Model
{
    public function GetArticleCateData(){
    	$data =db('article_cate')->where(array('article_cate_delete_time'=>NULL))->order('article_cate_sort desc')->order('article_cate_create_time','desc')->select();
      	return $data;
    }
   	public function GetArticleCateInfoById($article_cate_id){
    	$article_cate_info = db("article_cate")->find($article_cate_id);
    	return $article_cate_info;
    }
    public function GetArticleCateValue($article_cate_id){
    	$article_cate_info =$this->GetArticleCateInfoById($article_cate_id);
    	if($article_cate_info["article_cate_level"]==1){
    		$article_cate_sublevel=$this->GetArticleCateSublevel($article_cate_info["article_cate_id"]);
	    	$data=array_merge($article_cate_info,array("article_cate_sublevel"=>$article_cate_sublevel));
    	}else{
    		$data=$this->GetArticleCateParent($article_cate_info["article_cate_pid"]);
    	}
    	return $data;
    }
    // 获取子级
    public function GetArticleCateSublevel($article_cate_id){
    	$article_cate_select = db("article_cate")->where(array("article_cate_pid"=>$article_cate_id,"article_cate_delete_time"=>NULL))->select();
    	return $article_cate_select;
    }
    // 获取父级
    public function GetArticleCateParent($article_cate_pid){
    	$article_cate_info = $this->GetArticleCateInfoById($article_cate_pid);
    	$article_cate_info["article_cate_sublevel"]=$this->GetArticleCateSublevel($article_cate_info["article_cate_id"]);
    	return $article_cate_info;
    }



}
