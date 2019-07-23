<?php

namespace app\index\controller;

use think\Controller;

/**
 * 数据统计
 * Class DataStatistics
 * @package app\index\controller
 */
class DataStatistics extends Controller{
    public function index(){
        $this->assign('action_page', -1);
        return $this->fetch();
    }
}