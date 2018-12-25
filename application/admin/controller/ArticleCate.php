<?php
namespace app\admin\controller;
use think\Db;
use think\Log;
use app\admin\model\ArticleCate as ArticleCateModel;
use app\admin\common\Func as FuncModel;
use app\admin\model\Label as LabelModel;
class ArticleCate extends Base
{
	//文章分类
	public function index(){
		$ArticleCateModel=new ArticleCateModel();
		$article_catelist=$ArticleCateModel->GetArticleCate();
		$this->assign('article_catelist',$article_catelist);
		return $this->fetch("article/cate");
	}
	//添加分类
	public function add(){
		$ArticleCateModel=new ArticleCateModel();
		if (request()->isPost()) {
			$post=request()->post();
            $article_cate_info=$ArticleCateModel->GetArticleCateInfoBy($post['article_cate_pid']);
            $level=$article_cate_info['article_cate_level']+1;
            $merge=array_merge($post,['article_cate_level'=>$level]);
            $this->GetAction("article_cate",$merge,"add","添加文章分类");
		}else{
			$article_catelist=$ArticleCateModel->GetArticleCate();
            $this->assign('article_catelist',$article_catelist);
			return $this->fetch("article/cate");
		}
	}
	//修改分类
	public function edit($article_cate_id=''){
		$ArticleCateModel=new ArticleCateModel();
        if (request()->isPost()) {
            $post=request()->post();
            if (!empty($post['article_cate_pid'])) {
            	 $article_cate_info=$ArticleCateModel->GetArticleCateInfoBy($post['article_cate_pid']);
	            $this_article_cate_info=$ArticleCateModel->GetArticleCateInfoBy($article_cate_id);
	            $level=$article_cate_info['article_cate_level']+1;
	            $post=array_merge($post,['article_cate_level'=>$level]);
            }
            $this->GetAction("article_cate",$post,"update","修改文章分类");
        }else{
            $this_article_cate_info=$ArticleCateModel->GetArticleCateInfoBy($article_cate_id);
            $this->assign('this_article_cate_info',$this_article_cate_info);
            // dump($module_info);die;
            $article_catelist=$ArticleCateModel->GetArticleCate();
            $this->assign('article_catelist',$article_catelist);
            return $this->fetch("article/cate");
        }
	}
	//删除
	public function del($article_cate_id){
		$ArticleCateModel=new ArticleCateModel();
        $article_cate_info= $ArticleCateModel->GetArticleCateInfoBy($article_cate_id);
        $post=[
            'article_cate_id'=>$article_cate_id,
            ];
        $this->GetAction("article_cate",$post,"delete","删除文章分类");
	}
	public function get_article_cate_json(){
		$ArticleCateModel=new ArticleCateModel();
		$article_catelist=$ArticleCateModel->GetArticleCate();
		return $article_catelist;
	}
}