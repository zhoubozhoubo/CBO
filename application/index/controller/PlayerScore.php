<?php

namespace app\index\controller;

use think\Controller;

/**
 * 球员积分 Controller
 * Class PlayerScore
 * @package app\index\controller
 */
class PlayerScore extends Controller
{
    public function index()
    {
        $page = $this->request->get('page', 1);
        $size = 20;
        $playerScoreRankingArray=[
            "pageNum"=>$page,
            "pageSize"=>$size,
            "seasonId"=>"75",
            "stageId"=>"17"
        ];
        $playerScoreRanking = getPlayerScoreRanking($playerScoreRankingArray);
        $playerScoreRanking = json_decode($playerScoreRanking, true);
        if($playerScoreRanking && $playerScoreRanking['code'] === 200){
            $playerScoreRankingList = $playerScoreRanking['data']['list'];
            $playerScoreRankingCount = $playerScoreRanking['data']['total'];
            $playerScoreRankingPages = ceil($playerScoreRankingCount / $size);
        }else{
            $playerScoreRankingList=[];
            $playerScoreRankingPages = 0;
        }

        $this->assign('action_page', 5);
        $this->assign('player_score_ranking_list', $playerScoreRankingList);
        $this->assign('player_score_ranking_pages', $playerScoreRankingPages);

        if ($page !== 1) {
            return $playerScoreRankingList;
        }
        return $this->fetch();
    }

    public function details()
    {
        $this->assign('action_page', 5);
        return $this->fetch();
    }
}