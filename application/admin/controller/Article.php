<?php
namespace app\admin\controller;
use think\Db;
use think\Log;
use app\admin\model\Article as ArticleModel;
use app\admin\model\ArticleCate as ArticleCateModel;
use app\admin\common\Func as FuncModel;
use app\admin\model\Label as LabelModel;
class Article extends Base
{
	public function index(){
		return $this->fetch("index");

	}
    //文章列表
    public function article_lst_data($limit=''){
        if(!empty($limit)){
            $article_model = new ArticleModel();
            $dataJson = $article_model->GetArticleData($limit);
            return $dataJson;
        }else{
            $data=['code'=>'1','count'=>1,'data'=>'false'];
            return json_encode($data);
        }
    }
    //获取标签
    public function get_label_data($limit=''){
        if(!empty($limit)){
            $label_model = new LabelModel();
            $dataJson = $label_model->GetLabelJsonData($limit);
            return $dataJson;
        }else{
            $data=['code'=>'1','count'=>1,'data'=>'false'];
            return json_encode($data);
        }
    }
    //添加文章
    public function add(){
        if(request()->isPost()) {
            $post=request()->post();
            if (!empty($post['article_thumb'])){
                $post["article_thumb"]=$this->PermanentCopy($post['article_thumb']);
            }
            if (!empty($post['article_project']) && $post["article_type"] == "0"){
                $post["article_project"]=$this->PermanentCopy($post['article_project'],"FILE");
                if ($post["project_url_type"]=="1") {
                    $post["article_project_url"] = $this->DecompressionFile(GetUrl($post["article_project"],"address"));
                }
            }
            $this->GetAction("article",$post,"add","发布文章");
        }else{
            $ArticleCateModel=new ArticleCateModel();
            $article_catelist=$ArticleCateModel->GetArticleCate();
            $this->assign('article_catelist',$article_catelist);
            return $this->fetch("index");
        }
        
    }
    /*修改文章*/
    public function edit($article_id=''){
        $articlemodel = new ArticleModel();
        $article_info= $articlemodel->GetArticleInfoById($article_id);
        if (request()->isPost()) {
            $post=request()->post();

            if (!empty($post['article_thumb'])){
                $post["article_thumb"]=$this->PermanentCopy($post['article_thumb']);
                $this->PermanentUnlink(GetUrl($article_info['article_thumb'],"address"));
            }else{
                unset($post["article_thumb"]);
            }
            if (!empty($post['article_project']) && $post["article_type"] == "0"){
                $post["article_project"]=$this->PermanentCopy($post['article_project'],"FILE");

                if ($post["project_url_type"]=="1") {
                    $post["article_project_url"] = $this->DecompressionFile(GetUrl($post["article_project"],"address"));
                    $this->PermanentUnlink(GetUrl($article_info['article_project'],"address"));
                }else{
                    $apArr=json_decode($post["article_project"]);
                    $post["article_project_url"] =$apArr["address"];
                }
            }else{
                unset($post["article_project"]);
            }
            $this->GetAction("article",$post,"update","修改文章");
        }else{
           
            $this->assign('article_info',$article_info);
            $ArticleCateModel=new ArticleCateModel();
            $article_catelist=$ArticleCateModel->GetArticleCate();
            $this->assign('article_catelist',$article_catelist);
            // dump($article_catelist);die;
            return $this->fetch("index");
        }
    }
    public function article_del(){
        if(request()->isPost()){
            $post=request()->post();
            $this->GetAction("article",$post,"delete","删除文章");
        }
    }
     //批量删除文章
    public function article_del_arr(){
        if(request()->isPost()){
            $post=request()->post();
            $this->GetAction("article",$post,"DelArr","批量删除文章");
        }
    }
}