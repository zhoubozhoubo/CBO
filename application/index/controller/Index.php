<?php

namespace app\index\controller;

use app\index\model\CboBasicConf;
use app\index\model\CboMessage;
use app\index\model\CboNews;
use app\index\model\CboWonderfulImg;
use think\Controller;

/**
 * 首页 Controller
 * Class Index
 * @package app\index\controller
 */
class Index extends Controller{
    public function index(){
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
        // TODO 数据统计
        //球员积分排名
        $page = 1;
        $size = 4;
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
        }else{
            $playerScoreRankingList=[];
        }

        $this->assign('action_page', 0);
        $this->assign('index_introduce', $indexIntroduce);
        $this->assign('news_list', $newsList);
        $this->assign('message_list', $messageList);
        $this->assign('wonderful_img_list', $wonderfulImgList);
        $this->assign('player_score_ranking_list', $playerScoreRankingList);
        return $this->fetch();
    }
}