<?php

namespace app\phone\controller;

use app\phone\model\CboNews;
use app\phone\model\CboWebsiteBottomConf;
use think\Controller;

/**
 * 新闻 Controller
 * Class News
 * @package app\phone\controller
 */
class News extends Base
{
    public function index()
    {
        $websiteBottomConf = CboWebsiteBottomConf::where(['is_delete'=>0])->field('gmt_create,gmt_modified,is_delete', true)->select();
        $page = $this->request->get('page', 1);
        $size = 8;
        $where = [
            'is_delete' => 0
        ];
        $newsListBig = CboNews::where($where)->field('gmt_modified,is_delete', true)->order('date desc, id desc')->limit(3)->select();
        $newsList = CboNews::where($where)->field('gmt_modified,is_delete', true)->order('date desc, id desc')->page($page, $size)->select();
        $newsCount = CboNews::where($where)->count();
        $newsPages = ceil($newsCount / $size);

        $this->assign('banner', $this->banner);
        $this->assign('action_page', 3);
        $this->assign('news_list_big', $newsListBig);
        $this->assign('news_list', $newsList);
        $this->assign('news_pages', $newsPages);
        $this->assign('website_bottom_conf', $websiteBottomConf);

        if ($page !== 1) {
            return json($newsList);
        }
        return $this->fetch();
    }

    public function details()
    {
        $websiteBottomConf = CboWebsiteBottomConf::where(['is_delete'=>0])->field('gmt_create,gmt_modified,is_delete', true)->select();
        $getData = $this->request->get();
        $id = $getData['id'];

        $where = [
            'is_delete' => 0
        ];
        $news = CboNews::where($where)->field('gmt_modified,is_delete', true)->find($id);

        $this->assign('banner', $this->banner);
        $this->assign('action_page', 3);
        $this->assign('news', $news);
        $this->assign('website_bottom_conf', $websiteBottomConf);
        return $this->fetch();
    }
}