<?php

namespace app\index\controller;

use app\index\model\CboNews;
use think\Controller;

/**
 * 新闻 Controller
 * Class News
 * @package app\index\controller
 */
class News extends Controller
{
    public function index()
    {
        $page = $this->request->get('page', 1);
        $size = 8;
        $where = [
            'is_delete' => 0
        ];
        $newsListBig = CboNews::where($where)->field('gmt_modified,is_delete', true)->order('date desc, id desc')->limit(3)->select();
        $newsList = CboNews::where($where)->field('gmt_modified,is_delete', true)->order('date desc, id desc')->page($page, $size)->select();
        $newsCount = CboNews::where($where)->count();
        $newsPages = ceil($newsCount/$size);

        $this->assign('action_page', 3);
        $this->assign('news_list_big', $newsListBig);
        $this->assign('news_list', $newsList);
        $this->assign('news_pages', $newsPages);

        if($page !== 1){
            return json($newsList);
        }
        return $this->fetch();
    }

    public function details()
    {
        $getData = $this->request->get();
        $id = $getData['id'];

        $where = [
            'is_delete' => 0
        ];
        $news = CboNews::where($where)->field('gmt_modified,is_delete', true)->find($id);

        $this->assign('action_page', 3);
        $this->assign('news', $news);
        return $this->fetch();
    }
}