<?php
namespace app\index\controller;
use think\Controller;
use think\Log;
use app\index\model\Nav as NavModel;
use app\index\model\LabelType as LabelTypeModel;
use app\index\model\Article as ArticleModel;
use app\index\model\ArticleCate as ArticleCateModel;
class Home extends Base
{
    public function index()
    {	
        $ArticleModel = new ArticleModel();
        $article_select = $ArticleModel->GetArticleData(10);
        $this->assign("article_select",$article_select);
        $ProjectFineSelect = $ArticleModel->GetFineArticle('0');
        $this->assign("ProjectFineSelect",$ProjectFineSelect);
        $ProjectPushSelect = $ArticleModel->GetPushArticle('0');
        $this->assign("ProjectPushSelect",$ProjectPushSelect);
        $LabelTypeModel = new LabelTypeModel();
        $LabelTypeSelect = $LabelTypeModel->GetLabelTypeSelect();
       
        $this->assign("LabelTypeSelect",$LabelTypeSelect);
        $this->GetCommon();
        return $this->fetch("index");
    }

    //搜索
    public function search(){
        if(request()->isPost()) {
            $post=request()->post();
            
        }
    }
}