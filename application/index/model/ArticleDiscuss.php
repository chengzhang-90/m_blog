<?php
namespace app\index\model;
use think\Model;
use think\Db;
class ArticleDiscuss extends Model
{
	public function GetArticleDiscussSelectByArticleId($article_id){
		$ArticleDiscuss =db('article_discuss')->alias('d')
                    ->join('user u','u.user_id=d.article_discuss_user_id')
                    ->field('
                        d.article_discuss_id,d.article_discuss_content,d.article_discuss_assess,d.article_discuss_create_time,
                        u.user_id,u.user_nickname,u.user_headpic
                        ')
                    ->where('d.article_discuss_delete_time',NULL)
                    ->where('d.article_discuss_article_id',$article_id)
                    ->select();
      	return $ArticleDiscuss;
	}

	public function GetArticleDiscussInfoByArticleIdOrUserId($article_id,$user_id){
		$ArticleDiscuss =db('article_discuss')
		->where("article_discuss_user_id",$user_id)
		->where("article_discuss_article_id",$article_id)
		->find();
      	return $ArticleDiscuss;
	}
}