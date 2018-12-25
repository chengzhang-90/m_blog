<?php
namespace app\index\controller;
use think\Controller;
use think\Log;
use app\index\model\WebConfig as WebConfigModel;
use app\index\model\Nav as NavModel;
use app\index\model\Article as ArticleModel;
use app\index\model\Label as LabelModel;
use app\index\model\Link as LinkModel;
use app\index\model\ArticleCate as ArticleCateModel;
use app\index\controller\Func;
use app\index\model\User as UserModel;
class Base extends Controller
{
    public function GetCommonInfo(){
        /*用户信息*/
        $UserModel=new UserModel();
        $user_info=$UserModel->get_user_info(session("admin_id"));
        $this->assign("user_info",$user_info);
        $index_model=new IndexModel();
        $module_lst=$index_model->GetModule();
        $this->assign('module_lst',$module_lst);
        $module_info=$index_model->GetModuleName(array('controller'=>request()->controller(),'action'=>request()->action()));
        $this->assign('module_info',$module_info);
    }

    public function GetCommon()
    {

        $hot_article_keyword=null;
        $hot_article_arr = [] ;
        $hot_article_str = null ;
        $hot_label_select=[];
        //网站配置
        $WebConfigModel = new WebConfigModel();
        $web_config_info=$WebConfigModel->GetWebConfigInfo();
        //导航
        $NavModel = new NavModel();
        $nav_select = $NavModel->GetNavData();
        //栏目
        $ArticleCateModel = new ArticleCateModel();
        $article_cate_select = $ArticleCateModel->GetArticleCateData();
        //热门文章
        $ArticleModel = new ArticleModel();
        $hot_article=$ArticleModel->GetHotArticle();
        foreach ($hot_article as $key => $value) {
            $hot_article_arr[]=$value["article_label_id"];
            //热门关键字
            if ($value["article_keyword"]!= "") {
                $value["article_keyword"] = $value["article_keyword"].",";
                $hot_article_keyword .= $value["article_keyword"];
            }
        }
        $hot_article_keyword_arr = array_values(array_flip(array_flip(explode(",", $hot_article_keyword))));

        foreach ($hot_article_arr as $key => $value) {
            if ( $value!= "") {
                $value = $value.",";
                $hot_article_str .= $value;
            }
        }
        $hot_article_arr = array_values(array_flip(array_flip(explode(",", $hot_article_str))));
        $LabelModel = new LabelModel();
        foreach ($hot_article_arr as $key => $value) {
            if (!empty($LabelModel->GetLabelInfoById($value))) {
                //热门标签
               $hot_label_select[]=$LabelModel->GetLabelInfoById($value);
            }
        }
        //推荐
        $push_article=$ArticleModel->GetPushArticle('1');
        //精选
        $fine_article=$ArticleModel->GetFineArticle('1');
        //友情链接
        $LinkModel = new LinkModel();
        $link_select = $LinkModel->GetLinkSelect();
        $this->assign("web_config_info",$web_config_info);
        $this->assign("nav_select",$nav_select);
        $this->assign("article_cate_select",$article_cate_select);
        $this->assign("hot_label_select",$hot_label_select);
        $this->assign("hot_article",$hot_article);
        $this->assign("hot_article_keyword_arr",$hot_article_keyword_arr);
        $this->assign("push_article",$push_article);
         $this->assign("fine_article",$fine_article);
        $this->assign("link_select",$link_select);
        if (session("user_id")) {
            $UserModel = new UserModel();
            $UserInfo = $UserModel->GetUserInfoById(session("user_id"));
            $this->assign("UserInfo",$UserInfo);
        }
    }

    //操作
    public function GetAction($db,$value,$type,$note,$true=false){
        $post = $value;
        $data=[
                'db'=>$db,
                'title'=>$note,
                'note'=>$note,
                'post'=>$post,
                'action'=>$type,
        ];
        $Func = new Func();
        $message= $Func->GetAction($data,$true);
        return $message;
    }
}