<?php

namespace app\index\controller;

use app\index\model\CboEligibility;
use app\index\model\CboMatchTimeAddress;
use app\index\model\CboMatchWay;
use app\index\model\CboWebsiteBottomConf;
use think\Controller;

/**
 * 竞赛规程
 * Class CompetitionRules
 * @package app\index\controller
 */
class CompetitionRules extends Base {
    public function index(){
        $websiteBottomConf = CboWebsiteBottomConf::where(['is_delete'=>0])->field('gmt_create,gmt_modified,is_delete', true)->select();
        //二级bar选中状态
        $actionTwo = $this->request->get('actionTwo', 0);

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
        //比赛办法
        $where = [
            'is_delete' => 0
        ];
        $matchWay = CboMatchWay::where($where)->field('gmt_create,gmt_modified,is_delete', true)->order('id desc')->select();

        //字母列表
        $letter = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        foreach ($eligibilityTypeOne as $key=>&$val){
            $val['letter'] = $letter[$key];
        }
        foreach ($eligibilityTypeTwo as $key=>&$val){
            $val['letter'] = $letter[$key];
        }

        $this->assign('banner', $this->banner);
        $this->assign('action_page', 4);
        $this->assign('action_two', $actionTwo);
        $this->assign('match_time_address', $matchTimeAddress);
        $this->assign('eligibility_type_one', $eligibilityTypeOne);
        $this->assign('eligibility_type_two', $eligibilityTypeTwo);
        $this->assign('match_way', $matchWay);
        $this->assign('website_bottom_conf', $websiteBottomConf);
        return $this->fetch();
    }
}