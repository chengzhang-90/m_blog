<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class ArticleCate extends Model
{

    
    public function GetArticleCateInfoBy($article_cate_id){
        $article_cate_info=db('article_cate')->find($article_cate_id);
        return $article_cate_info;
    }
    public function GetArticleCateSelect()
    {
        $article_cate_select=db('article_cate')
        ->where("article_cate_delete_time",null)
        ->order('article_cate_sort desc')->select();
        return $article_cate_select;
    }
    //分类
    public function GetArticleCate(){
        $article_cate=db("article_cate")
        ->where('article_cate_delete_time',NULL)
        ->order('article_cate_sort desc')->select();
        $article_catelist=[];
        $child_three=[];
        $child_four=[];
        foreach ($article_cate as $k => $v) {
            if ($v['article_cate_level']=='1') {
                foreach ($article_cate as $ks => $vs) {
                    if ($v['article_cate_id']==$vs['article_cate_pid']&&$vs['article_cate_level']=='2') {
                        foreach ($article_cate as $kss => $vss) {
                            if ($vs['article_cate_id']==$vss['article_cate_pid']) {
                                foreach ($article_cate as $ksss => $vsss) {
                                    if ($vss['article_cate_id']==$vsss['article_cate_pid']&&$vsss['article_cate_level']=='4') {
                                        $vss["child_four"][]=$vsss;
                                    }
                                }
                                $vs['child_three'][]= $vss;
                            }
                        }
                        $v['child'][]=$vs;
                    }
                }
                $article_catelist[]=$v;
            }
        }

        return $article_catelist;
    }
    public function GetChildrenId($article_cate_list,$article_cate_pid=0,$article_cate_level=1){
        static $arr=array();
        foreach ($article_cate_list as $key => $value) {
            if ($value['article_cate_pid']==$article_cate_pid) {
                $value['article_cate_level']=$article_cate_level;
                $value['str']=str_repeat('———————', $value['article_cate_level']-1);
                $arr[]=$value;
                $this->GetChildrenId($article_cate_list,$value['article_cate_id'],$article_cate_level+1);

            }
        }
        return $arr;
    }
}