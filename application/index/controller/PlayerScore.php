<?php

namespace app\index\controller;

use think\Controller;

/**
 * 球员积分 Controller
 * Class PlayerScore
 * @package app\index\controller
 */
class PlayerScore extends Controller{
    public function index(){
        $this->assign('action_page', 5);
        return $this->fetch();
    }
    public function details(){
        return $this->fetch();
    }
}