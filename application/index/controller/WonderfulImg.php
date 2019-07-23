<?php

namespace app\index\controller;

use app\index\model\CboWonderfulImg;
use think\Controller;

/**
 * 精彩图集 Controller
 * Class WonderfulImg
 * @package app\index\controller
 */
class WonderfulImg extends Controller
{
    public function index()
    {
        $page = $this->request->get('page', 1);
        $size = 8;
        $where = [
            'is_delete' => 0
        ];
        $wonderfulImgList = CboWonderfulImg::where($where)->field('gmt_create,gmt_modified,is_delete', true)->order('gmt_create desc, id desc')->page($page, $size)->select();
        $wonderfulImgListCount = CboWonderfulImg::where($where)->count();
        $wonderfulImgListPages = ceil($wonderfulImgListCount / $size);

        $this->assign('action_page', -1);
        $this->assign('wonderful_img_list', $wonderfulImgList);
        $this->assign('wonderful_img_pages', $wonderfulImgListPages);

        if ($page !== 1) {
            return json($wonderfulImgList);
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
        $news = CboWonderfulImg::where($where)->field('gmt_modified,is_delete', true)->find($id);

        $this->assign('action_page', 3);
        $this->assign('news', $news);
        return $this->fetch();
    }
}