<?php
namespace app\index\controller;
use think\Controller;
use think\Log;
use app\index\model\Article as ArticleModel;
use app\index\model\ArticleDiscuss as ArticleDiscussModel;
use app\index\model\Label as LabelModel;
class ArticleDetail extends Base
{
    public function index($article_id)
    {
    	if(!empty($article_id)){
    		$ArticleModel = new ArticleModel();
            $LabelModel = new LabelModel();
	        $article_info_data = $ArticleModel->GetArticleInfoById($article_id);
            $article_info=$article_info_data["article_info"];

            $post["article_id"]=$article_id;
            $post["article_views"]=(int)$article_info["article_views"]+1;
            $this->GetAction("article",$post,"update","观看",true);

            // dump($article_info);die;
	        $this->assign("article_info",$article_info);
            $this->assign("article_info_data",$article_info_data);
            $article_label_id_arr=explode(",",$article_info["article_label_id"]);
            foreach ($article_label_id_arr as $key => $value) {
                $label_select[] = $LabelModel->GetLabelInfoById($value);
            }
            // dump($article_info_data );die;
            $this->assign("label_select",$label_select);
        	$this->GetCommon();
        	return $this->fetch("index");
    	}
    }


    //点赞
    public function get_laud($article_id){
        $ArticleModel = new ArticleModel();
        if (!empty($article_id)) {
            $article_info_data = $ArticleModel->GetArticleInfoById($article_id);
                $article_info=$article_info_data["article_info"];
                $post["article_laud"]=(int)$article_info["article_laud"]+1;
                $post["article_id"]=$article_id;
                $this->GetAction("article",$post,"update","点贊");
            // $article_laud_id = $ArticleModel->GetArticleLaudIf(array("article_id"=>$article_id));
            // if ($article_laud_id==null) {
            //     $article_info_data = $ArticleModel->GetArticleInfoById($article_id);
            //     $article_info=$article_info_data["article_info"];
            //     $post["article_laud"]=(int)$article_info["article_laud"]+1;
            //     $post["article_id"]=$article_id;
            //     $this->GetAction("article",$post,"update","点贊");
            // }else{
            //     $msg=[
            //         "code"=>0,
            //         "msg"=>"不好意思,您已经赞过了！谢谢"
            //     ];
            //     return $msg;
            // }
        }
    }

    //评论
    public function article_discuss(){
        $ArticleModel = new ArticleModel();
        if(!session('user_phone')&&!session('user_id')){
            $msg=[
                "code"=>2,
                "msg"=>"请登录!"
            ];
            return $msg;
        }
        if(request()->isPost()) {
            $post=request()->post();
            $ArticleDiscussModel = new ArticleDiscussModel();
            $ArticleDetailInfo=$ArticleDiscussModel->GetArticleDiscussInfoByArticleIdOrUserId($post["article_discuss_article_id"],session("user_id"));
            if ($ArticleDetailInfo==null) {
                $post["article_discuss_user_id"]=session("user_id");
                $this->GetAction("article_discuss",$post,"add","评论");
            }else{
                $msg=[
                    "code"=>0,
                    "msg"=>"不好意思,您已经评论过了！谢谢"
                ];
                return $msg;
            }
        }
    }
}
