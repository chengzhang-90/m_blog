<?php
namespace app\index\controller;
use think\Controller;
use think\Log;
use app\index\model\ArticleCate as ArticleCateModel;
use app\index\model\Article as ArticleModel;
use app\index\model\Label as LabelModel;
class ArticleCate extends Base
{
    public function index($article_cate_id='',$keyword='',$label_id='')
    {
    	$ArticleCateModel = new ArticleCateModel();
        $ArticleModel = new ArticleModel();
        $LabelModel = new LabelModel();
    	if(!empty($article_cate_id)){
    		$article_cate_info = $ArticleCateModel->GetArticleCateInfoById($article_cate_id);
    		$this->assign("article_cate_info",$article_cate_info);
    		$article_cate_value = $ArticleCateModel->GetArticleCateValue($article_cate_id);
    		$this->assign("article_cate_value",$article_cate_value);
            $article_cate_son = $ArticleCateModel->GetArticleCateSublevel($article_cate_id);
            $this->assign("article_cate_son",$article_cate_son);
            $article_cate_arr[0] =$article_cate_info;
            $article_cate_arr=array_merge($article_cate_arr,$article_cate_son);
            foreach ($article_cate_arr as $key => $value) {
                $article_select_arr_id[] = $value["article_cate_id"];
            }
            $article_select_arr= $ArticleModel->GetArticleSelect($article_select_arr_id);
            $this->assign("article_cate_id",$article_cate_id);
            $this->assign("article_select_arr",$article_select_arr);
    	}else if (!empty($keyword)) {
            $article_select_arr=$ArticleModel->GetArticleKeywordSelect($keyword);
            $this->assign("keyword",$keyword);
            $this->assign("article_select_arr",$article_select_arr);
        }else if(!empty($label_id)){
            $label_info = $LabelModel->GetLabelInfoById($label_id);
            $label_select=$LabelModel->GetLabelSelect();
            $article_select_arr=$ArticleModel->GetArticleLabelSelect($label_id);
            $this->assign("label_info",$label_info);
            $this->assign("label_select",$label_select);
            $this->assign("article_select_arr",$article_select_arr);
        }
        //分页
        if(!empty($article_select_arr)){
            $page = $article_select_arr->render();
            $this->assign('page', $page);
        }
    	$article_cate_data=$ArticleCateModel->GetArticleCateData();
    	$this->assign("article_cate_data",$article_cate_data);
        $this->GetCommon();
        return $this->fetch("index");
    }
}
