<?php

namespace app\index\controller;

use think\Controller;

/**
 * 竞赛规程
 * Class CompetitionRules
 * @package app\index\controller
 */
class CompetitionRules extends Controller{
    public function index(){
        $this->assign('action_page', 4);
        return $this->fetch();
    }
}