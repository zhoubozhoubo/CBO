<?php

namespace app\index\controller;

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
        $page = $this->request->get('page', 1);
        $type = $this->request->get('type', 'score');
        $size = 20;
        $playerScoreRankingArray = [
            "pageNum" => $page,
            "pageSize" => $size,
            "seasonId" => "75",
            "stageId" => "18",
            "provinceId" => "",
            "cityId" => "",
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

        $this->assign('action_page', -1);
        $this->assign('player_type_statistics_list', $playerTypeStatisticsList);
        $this->assign('player_type_statistics_pages', $playerTypeStatisticsPages);

        if ($page !== 1) {
            return $playerTypeStatisticsList;
        }
        return $this->fetch();
    }
}