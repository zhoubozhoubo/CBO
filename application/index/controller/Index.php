<?php

namespace app\index\controller;

use app\index\model\CboBasicConf;
use app\index\model\CboMessage;
use app\index\model\CboNews;
use app\index\model\CboTeamSituationImg;
use app\index\model\CboWebsiteBottomConf;
use app\index\model\CboWonderfulImg;
use think\Controller;

/**
 * 首页 Controller
 * Class Index
 * @package app\index\controller
 */
class Index extends Base {
    public function index(){
        $websiteBottomConf = CboWebsiteBottomConf::where(['is_delete'=>0])->field('gmt_create,gmt_modified,is_delete', true)->select();
        //首页介绍
        $where = [
            'name'=>'index_introduce',
            'is_delete'=>0
        ];
        $indexIntroduce = CboBasicConf::where($where)->field('gmt_create,gmt_modified,is_delete', true)->find();

        $where = [
            'is_delete'=>0
        ];
        //新闻动态
        $newsList = CboNews::where($where)->field('id,title,img')->order('date desc, id desc')->limit(4)->select();
        //官方消息
        $messageList = CboMessage::where($where)->field('id,title')->order('date desc, id desc')->limit(4)->select();
        //精彩图集
        $wonderfulImgList = CboWonderfulImg::where($where)->field('gmt_modified,is_delete', true)->order('gmt_create desc')->limit(5)->select();
        // 数据统计
        $page = 1;
//        $type = $this->request->get('type', 'score');
        $type = "score";
        $size = 9;
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
        if($playerTypeStatistics){
            $playerTypeStatistics = json_decode($playerTypeStatistics, true);
        }

        if ($playerTypeStatistics && $playerTypeStatistics['code'] === 200) {
            $playerTypeStatisticsList = $playerTypeStatistics['data']['list'];
        } else {
            $playerTypeStatisticsList = [];
        }
        //球员积分排名
        $page = 1;
        $size = 5;
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
        }else{
            $playerScoreRankingList=[];
        }

        $this->assign('banner', $this->banner);
        $this->assign('action_page', 0);
        $this->assign('index_introduce', $indexIntroduce);
        $this->assign('news_list', $newsList);
        $this->assign('message_list', $messageList);
        $this->assign('wonderful_img_list', $wonderfulImgList);
        $this->assign('player_score_ranking_list', $playerScoreRankingList);
        $this->assign('player_type_statistics_list', $playerTypeStatisticsList);
        $this->assign('website_bottom_conf', $websiteBottomConf);
        return $this->fetch();
    }

    /**
     * 获取赛季
     * @return array
     */
    public function getCompetitionSeason(){
        $competitionSeason = getCompetitionSeason();
        if($competitionSeason){
            $competitionSeason = json_decode($competitionSeason, true);
        }
        if ($competitionSeason && $competitionSeason['code'] === 200) {
            $competitionSeasonList = $competitionSeason['data'];
        }else{
            $competitionSeasonList = [];
        }
        return $competitionSeasonList;
    }

    /**
     * 获取赛季阶段
     * @return array
     */
    public function getSeasonStage(){
        $seasonId = $this->request->get('seasonId');
        $seasonStageArray = [
            "seasonId"=> $seasonId
        ];
        $seasonStage = getSeasonStage($seasonStageArray);
        if($seasonStage){
            $seasonStage = json_decode($seasonStage, true);
        }
        if ($seasonStage && $seasonStage['code'] === 200) {
            $seasonStageList = $seasonStage['data'];
        }else{
            $seasonStageList = [];
        }
        return $seasonStageList;
    }

    /**
     * 获取赛区
     * @return array
     */
    public function getArea(){
        $seasonId = $this->request->get('seasonId');
        $stageId = $this->request->get('stageId');
        $areaArray = [
            "seasonId"=> $seasonId,
            "stageId"=>$stageId
        ];
        $area = getArea($areaArray);
        if($area){
            $area = json_decode($area, true);
        }
        if ($area && $area['code'] === 200) {
            $areaList = $area['data'];
        }else{
            $areaList = [];
        }
        return $areaList;
    }

    public function getTeamStatisticsList(){
        $seasonId = $this->request->get('seasonId', '');
        $stageId = $this->request->get('stageId', '');
        $provinceId = $this->request->get('provinceId', '');
        $cityId = $this->request->get('cityId', '');

        if($seasonId && $stageId && $provinceId && $cityId){
            $where = [
                "seasonId" => $seasonId,
                "stageId" => $stageId,
                "provinceId" => $provinceId,
                "cityId" => $cityId
            ];
        }else{
            $where=[];
        }

        $teamSituationList = CboTeamSituationImg::where($where)->field('id,img')->select();
        return $teamSituationList;
    }
}