<?php

namespace app\admin\controller;

use app\util\BaseController;
use think\Db;
use think\Exception;
use think\exception\DbException;

/**
 * TeamSituationImgController
 * Class TeamSituationImg
 * @package app\admin\controller
 */
class TeamSituationImg extends BaseController
{

    public $table = 'CboTeamSituationImg';

    /**
     * 获取列表
     * @return array|string
     * @throws DbException
     * @throws Exception
     */
    public function getList()
    {
        $searchConf = json_decode($this->request->get('searchConf', ''),true);
        $db = Db::name($this->table);
        $where = [];
        if($searchConf){
            foreach ($searchConf as $key=>$val){
//                if($val !== ''){
//                    if($key === 'status'){
                        $where[$key] = $val;
//                    }
//                }
            }
        }
        $db = $db->where($where)->order('id desc');
        return $this->_list($db);
    }

    /**
     * 新增/更新数据
     * @return array
     */
    public function coruData()
    {
        $postData = $this->request->post();
        return $this->coruBase($postData);
    }

    /**
     * 获取赛季
     * @return array
     */
    public function getCompetitionSeason(){
        $competitionSeason = getCompetitionSeason();
        $competitionSeason = json_decode($competitionSeason, true);
        if ($competitionSeason && $competitionSeason['code'] === 200) {
            $competitionSeasonList = $competitionSeason['data'];
        }else{
            $competitionSeasonList = [];
        }
        return $this->buildSuccess($competitionSeasonList);
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
        $seasonStage = json_decode($seasonStage, true);
        if ($seasonStage && $seasonStage['code'] === 200) {
            $seasonStageList = $seasonStage['data'];
        }else{
            $seasonStageList = [];
        }
        return $this->buildSuccess($seasonStageList);
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
        $area = json_decode($area, true);
        if ($area && $area['code'] === 200) {
            $areaList = $area['data'];
        }else{
            $areaList = [];
        }
        return $this->buildSuccess($areaList);
    }
}