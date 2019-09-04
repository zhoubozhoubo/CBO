<?php

namespace app\phone\controller;

use app\phone\model\CboBasicConf;
use app\phone\model\CboWebsiteBottomConf;
use think\Controller;

/**
 * 赛事简介 Controller
 * Class MatchSynopsis
 * @package app\phone\controller
 */
class MatchSynopsis extends Base{
    public function index(){
        $websiteBottomConf = CboWebsiteBottomConf::where(['is_delete'=>0])->field('gmt_create,gmt_modified,is_delete', true)->select();
        $where = [
            'name'=>'match_synopsis',
            'is_delete'=>0
        ];
        $matchSynopsis = CboBasicConf::where($where)->field('gmt_create,gmt_modified,is_delete', true)->find();

        $this->assign('banner', $this->banner);
        $this->assign('action_page', 1);
        $this->assign('match_synopsis', $matchSynopsis);
        $this->assign('website_bottom_conf', $websiteBottomConf);
        return $this->fetch();
    }
}