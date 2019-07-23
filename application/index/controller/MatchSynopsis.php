<?php

namespace app\index\controller;

use app\index\model\CboBasicConf;
use think\Controller;

/**
 * 赛事简介 Controller
 * Class MatchSynopsis
 * @package app\index\controller
 */
class MatchSynopsis extends Controller{
    public function index(){
        $where = [
            'name'=>'match_synopsis',
            'is_delete'=>0
        ];
        $matchSynopsis = CboBasicConf::where($where)->field('gmt_create,gmt_modified,is_delete', true)->find();

        $this->assign('action_page', 1);
        $this->assign('match_synopsis', $matchSynopsis);
        return $this->fetch();
    }
}