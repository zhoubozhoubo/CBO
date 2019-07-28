<?php

namespace app\index\controller;

use app\index\model\CboWebsiteBottomConf;
use think\Controller;

/**
 * 数据统计
 * Class DataStatistics
 * @package app\index\controller
 */
class DataStatistics extends Controller
{
    public function index()
    {
        $websiteBottomConf = CboWebsiteBottomConf::where(['is_delete'=>0])->field('gmt_create,gmt_modified,is_delete', true)->select();
        $page = $this->request->get('page', 1);
        $seasonId = $this->request->get('seasonId', '75');
        $stageId = $this->request->get('stageId', '18');
        $provinceId = $this->request->get('provinceId', '');
        $cityId = $this->request->get('cityId', '');
        $type = $this->request->get('type', 'score');
        if($page == 0){
            $size = 9;
        }else{
            $size = 20;
        }

        $playerScoreRankingArray = [
            "pageNum" => $page,
            "pageSize" => $size,
            "seasonId" => $seasonId,
            "stageId" => $stageId,
            "provinceId" => $provinceId,
            "cityId" => $cityId,
            "type" => $type
        ];
        $playerTypeStatistics = getPlayerTypeStatistics($playerScoreRankingArray);
        $playerTypeStatistics = json_decode($playerTypeStatistics, true);
        if ($playerTypeStatistics && $playerTypeStatistics['code'] === 200) {
            $playerTypeStatisticsList = $playerTypeStatistics['data']['list'];
            $playerTypeStatisticsCount = $playerTypeStatistics['data']['total'];
            $playerTypeStatisticsPages = ceil($playerTypeStatisticsCount / $size);
        } else {
            $playerTypeStatisticsList = [];
            $playerTypeStatisticsPages = 0;
        }

        $this->assign('action_page', 7);
        $this->assign('player_type_statistics_list', $playerTypeStatisticsList);
        $this->assign('player_type_statistics_pages', $playerTypeStatisticsPages);
        $this->assign('website_bottom_conf', $websiteBottomConf);

        if ($page !== 1) {
            return ['list'=>$playerTypeStatisticsList];
        }
        return $this->fetch();
    }
}