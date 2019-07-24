<?php

namespace app\index\controller;

use app\index\model\CboEligibility;
use app\index\model\CboMatchTimeAddress;
use think\Controller;

/**
 * 竞赛规程
 * Class CompetitionRules
 * @package app\index\controller
 */
class CompetitionRules extends Controller{
    public function index(){
        //比赛时间及地点
        $where = [
            'is_delete' => 0
        ];
        $matchTimeAddress = CboMatchTimeAddress::where($where)->field('gmt_create,gmt_modified,is_delete', true)->select();
        //参赛资格
        $where = [
            'type'=>1,
            'is_delete' => 0
        ];
        $eligibilityTypeOne = CboEligibility::where($where)->field('gmt_create,gmt_modified,is_delete', true)->order('sort asc')->select();
        $where = [
            'type'=>2,
            'is_delete' => 0
        ];
        $eligibilityTypeTwo = CboEligibility::where($where)->field('gmt_create,gmt_modified,is_delete', true)->order('sort asc')->select();
        //字母列表
        $letter = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        foreach ($eligibilityTypeOne as $key=>&$val){
            $val['letter'] = $letter[$key];
        }
        foreach ($eligibilityTypeTwo as $key=>&$val){
            $val['letter'] = $letter[$key];
        }

        $this->assign('action_page', 4);
        $this->assign('match_time_address', $matchTimeAddress);
        $this->assign('eligibility_type_one', $eligibilityTypeOne);
        $this->assign('eligibility_type_two', $eligibilityTypeTwo);
        return $this->fetch();
    }
}