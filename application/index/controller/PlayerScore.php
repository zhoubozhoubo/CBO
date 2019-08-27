<?php

namespace app\index\controller;

use app\index\model\CboWebsiteBottomConf;
use think\Controller;

/**
 * 球员积分 Controller
 * Class PlayerScore
 * @package app\index\controller
 */
class PlayerScore extends Base
{
    public function index()
    {
        $websiteBottomConf = CboWebsiteBottomConf::where(['is_delete'=>0])->field('gmt_create,gmt_modified,is_delete', true)->select();
        $page = $this->request->get('page', 1);
        $size = 20;
        $playerScoreRankingArray=[
            "pageNum"=>$page,
            "pageSize"=>$size,
            "seasonId"=>"75",
            "stageId"=>"17"
        ];
        $playerScoreRanking = getPlayerScoreRanking($playerScoreRankingArray);
        if($playerScoreRanking){
            $playerScoreRanking = json_decode($playerScoreRanking, true);
        }
        if($playerScoreRanking && $playerScoreRanking['code'] === 200){
            $playerScoreRankingList = $playerScoreRanking['data']['list'];
            $playerScoreRankingCount = $playerScoreRanking['data']['total'];
            $playerScoreRankingPages = ceil($playerScoreRankingCount / $size);
        }else{
            $playerScoreRankingList=[];
            $playerScoreRankingPages = 0;
        }

        $this->assign('banner', $this->banner);
        $this->assign('action_page', 5);
        $this->assign('player_score_ranking_list', $playerScoreRankingList);
        $this->assign('player_score_ranking_pages', $playerScoreRankingPages);
        $this->assign('website_bottom_conf', $websiteBottomConf);

        if ($page !== 1) {
            return $playerScoreRankingList;
        }
        return $this->fetch();
    }

    public function details()
    {
        $websiteBottomConf = CboWebsiteBottomConf::where(['is_delete'=>0])->field('gmt_create,gmt_modified,is_delete', true)->select();
        $userId = $this->request->get('userId');
        $page = $this->request->get('page', 1);
        $size = 10;
        $playerScoreRankingArray=[
            "pageNum"=>$page,
            "pageSize"=>$size,
            "seasonId"=>"75",
            "stageId"=>"17",
            "userId"=>$userId
        ];
        $playerTeamStatistics = getPlayerTeamStatistics($playerScoreRankingArray);
        $playerTeamStatistics = json_decode($playerTeamStatistics, true);
        if($playerTeamStatistics && $playerTeamStatistics['code'] === 200){
            $playerTeamStatisticsList = $playerTeamStatistics['data']['list'];
            $playerTeamStatisticsCount = $playerTeamStatistics['data']['total'];
            $playerTeamStatisticsPages = ceil($playerTeamStatisticsCount / $size);
        }else{
            $playerTeamStatisticsList=[];
            $playerTeamStatisticsCount = 0;
            $playerTeamStatisticsPages = 0;
        }

        $this->assign('banner', $this->banner);
        $this->assign('action_page', 5);
        $this->assign('user_id', $userId);
        $this->assign('player_team_statistics_list', $playerTeamStatisticsList);
        $this->assign('player_team_statistics_count', $playerTeamStatisticsCount);
        $this->assign('player_team_statistics_pages', $playerTeamStatisticsPages);
        $this->assign('website_bottom_conf', $websiteBottomConf);

        if ($page !== 1) {
            return $playerTeamStatisticsList;
        }
        return $this->fetch();
    }
}