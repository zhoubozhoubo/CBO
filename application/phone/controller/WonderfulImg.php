<?php

namespace app\phone\controller;

use app\phone\model\CboWebsiteBottomConf;
use app\phone\model\CboWonderfulImg;
use think\Controller;

/**
 * 精彩图集 Controller
 * Class WonderfulImg
 * @package app\phone\controller
 */
class WonderfulImg extends Base
{
    public function index()
    {
        $websiteBottomConf = CboWebsiteBottomConf::where(['is_delete'=>0])->field('gmt_create,gmt_modified,is_delete', true)->select();
        $page = $this->request->get('page', 1);
        $size = 8;
        $where = [
            'is_delete' => 0
        ];
        $wonderfulImgList = CboWonderfulImg::where($where)->field('gmt_create,gmt_modified,is_delete', true)->order('gmt_create desc, id desc')->page($page, $size)->select();
        $wonderfulImgListCount = CboWonderfulImg::where($where)->count();
        $wonderfulImgListPages = ceil($wonderfulImgListCount / $size);

        $this->assign('banner', $this->banner);
        $this->assign('action_page', -1);
        $this->assign('wonderful_img_list', $wonderfulImgList);
        $this->assign('wonderful_img_pages', $wonderfulImgListPages);
        $this->assign('website_bottom_conf', $websiteBottomConf);

        if ($page !== 1) {
            return json($wonderfulImgList);
        }
        return $this->fetch();
    }

    public function date()
    {
        $websiteBottomConf = CboWebsiteBottomConf::where(['is_delete'=>0])->field('gmt_create,gmt_modified,is_delete', true)->select();
        $date = $this->request->get('date');
        $page = $this->request->get('page', 1);
        $where = [
            'date'=>$date
        ];
        $wonderfulImg = CboWonderfulImg::where($where)->field('gmt_create,gmt_modified,is_delete', true)->page($page,1)->select();
        $wonderfulImgCount = CboWonderfulImg::where($where)->count();

        $this->assign('banner', $this->banner);
        $this->assign('action_page', 7);
        $this->assign('date', $date);
        $this->assign('wonderful_img', $wonderfulImg);
        $this->assign('wonderful_img_pages', $wonderfulImgCount);
        $this->assign('website_bottom_conf', $websiteBottomConf);

        if ($page !== 1) {
            return json($wonderfulImg);
        }
        return $this->fetch();
    }
}