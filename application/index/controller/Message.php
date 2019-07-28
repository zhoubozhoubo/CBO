<?php

namespace app\index\controller;

use app\index\model\CboMessage;
use app\index\model\CboWebsiteBottomConf;
use think\Controller;

/**
 * 官方消息 Controller
 * Class Message
 * @package app\index\controller
 */
class Message extends Controller{
    public function index(){
        $websiteBottomConf = CboWebsiteBottomConf::where(['is_delete'=>0])->field('gmt_create,gmt_modified,is_delete', true)->select();
        $page = $this->request->get('page', 1);
        $size = 8;
        $where = [
            'is_delete' => 0
        ];
        $messageList = CboMessage::where($where)->field('gmt_modified,is_delete', true)->order('date desc, id desc')->page($page, $size)->select();
        $messageCount = CboMessage::where($where)->count();
        $messagePages = ceil($messageCount/$size);

        $this->assign('action_page', 2);
        $this->assign('message_list', $messageList);
        $this->assign('message_pages', $messagePages);
        $this->assign('website_bottom_conf', $websiteBottomConf);

        if($page !== 1){
            return json($messageList);
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
        $message = CboMessage::where($where)->field('gmt_modified,is_delete', true)->find($id);

        $this->assign('action_page', 2);
        $this->assign('news', $message);
        $this->assign('website_bottom_conf', $websiteBottomConf);
        return $this->fetch();
    }
}