<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use service\FileService;
use think\Db;

/**
 * 把返回的数据集转换成Tree
 * @param $list
 * @param string $pk
 * @param string $pid
 * @param string $child
 * @param string $root
 * @return array
 */
function listToTree($list, $pk = 'id', $pid = 'fid', $child = '_child', $root = '0')
{
    $tree = array();
    if (is_array($list)) {
        $refer = array();
        foreach ($list as $key => $data) {
            $refer[$data[$pk]] = &$list[$key];
        }
        foreach ($list as $key => $data) {
            $parentId = $data[$pid];
            if ($root == $parentId) {
                $tree[] = &$list[$key];
            } else {
                if (isset($refer[$parentId])) {
                    $parent = &$refer[$parentId];
                    $parent[$child][] = &$list[$key];
                }
            }
        }
    }
    return $tree;
}

function formatTree($list, $lv = 0, $title = 'name')
{
    $formatTree = array();
    foreach ($list as $key => $val) {
        $title_prefix = '';
        for ($i = 0; $i < $lv; $i++) {
            $title_prefix .= "|---";
        }
        $val['lv'] = $lv;
        $val['namePrefix'] = $lv == 0 ? '' : $title_prefix;
        $val['showName'] = $lv == 0 ? $val[$title] : $title_prefix . $val[$title];
        if (!array_key_exists('_child', $val)) {
            array_push($formatTree, $val);
        } else {
            $child = $val['_child'];
            unset($val['_child']);
            array_push($formatTree, $val);
            $middle = formatTree($child, $lv + 1, $title); //进行下一层递归
            $formatTree = array_merge($formatTree, $middle);
        }
    }
    return $formatTree;
}

/**
 * 手机号验证
 * @param string $str
 * @return false|int
 */
function isPhone($str = '')
{
    $isMatched = preg_match("/^0?1[3|4|5|6|7|8][0-9]\d{8}$/", $str, $matches);
    return $isMatched;
}

/**
 * 下载远程文件到本地
 * @param string $url 远程图片地址
 * @return string
 */
function local_image($url)
{
    return FileService::download($url)['url'];
}


/**
 * 截取日期
 * @param string $datetime 输入日期
 * @param string $format 输出格式
 * @return false|string
 */
function ymd($datetime, $format = 'Y年m月d日')
{
    $time = date($format, strtotime($datetime));
    return $time;
}

/**
 * curlPOST请求
 * @param $url
 * @param string $requestString
 * @param int $timeout
 * @return bool|string
 */
function doCurlPostRequest($url, $requestString = '', $timeout = 5)
{
    if ($url == '' || $timeout <= 0) {
        return false;
    }
    $headers = ["Content-type: application/json;charset='utf-8'", "Accept: application/json", "Cache-Control: no-cache", "Pragma: no-cache"];
    $con = curl_init((string)$url);
    curl_setopt($con, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($con, CURLOPT_HEADER, false);
    curl_setopt($con, CURLOPT_POSTFIELDS, $requestString);
    curl_setopt($con, CURLOPT_POST, true);
    curl_setopt($con, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($con, CURLOPT_TIMEOUT, (int)$timeout);
    $output = curl_exec($con);
    if ($output === FALSE) {
        return [];
    }
    curl_close($con);
    return $output;
}

/**
 * 获取赛季
 * @return bool|string
 */
function getCompetitionSeason()
{
    $playerScoreListCurl = "https://minidev.woaolanqiu.cn/cbo/data/competition/season";
    return doCurlPostRequest($playerScoreListCurl);
}

/**
 * 获取赛季阶段
 * @param $requestArray
 * @return bool|string
 */
function getSeasonStage($requestArray)
{
    $seasonStageCurl = "https://minidev.woaolanqiu.cn/cbo/data/competition/season/stage";
    $requestString = json_encode($requestArray);
    return doCurlPostRequest($seasonStageCurl, $requestString);
}

/**
 * 获取赛区
 * @param $requestArray
 * @return bool|string
 */
function getArea($requestArray)
{
    $areaCurl = "https://minidev.woaolanqiu.cn/cbo/data/area";
    $requestString = json_encode($requestArray);
    return doCurlPostRequest($areaCurl, $requestString);
}

/**
 * 获取球员积分排行
 * @param $requestArray
 * @return bool|string
 */
function getPlayerScoreRanking($requestArray)
{
    $playerScoreRankingStageCurl = "https://minidev.woaolanqiu.cn/cbo/data/player/score/ranking";
    $requestString = json_encode($requestArray);
    return doCurlPostRequest($playerScoreRankingStageCurl, $requestString);
}

/**
 * 获取球员在球队中统计信息
 * @param $requestArray
 * @return bool|string
 */
function getPlayerTeamStatistics($requestArray)
{
    $playerTeamStatisticsCurl = "https://minidev.woaolanqiu.cn/cbo/data/player/team/statistics";
    $requestString = json_encode($requestArray);
    return doCurlPostRequest($playerTeamStatisticsCurl, $requestString);
}

/**
 * 获取球员统计信息-通过不同类型
 * @param $requestArray
 * @return bool|string
 */
function getPlayerTypeStatistics($requestArray)
{
    $playerTypeStatisticsCurl = "https://minidev.woaolanqiu.cn/cbo/data/player/type/statistics";
    $requestString = json_encode($requestArray);
    return doCurlPostRequest($playerTypeStatisticsCurl, $requestString);
}


function testCurlData()
{
//    $res = getCompetitionSeason();
//        $res = getSeasonStage(["seasonId"=>"75"]);
//        $res = getArea(["seasonId"=>"75"]);
//        $res = getPlayerScoreRanking(["pageNum"=>0,"pageSize"=>10,"seasonId"=>"75","stageId"=>"17"]);
//        $res = getPlayerTeamStatistics(["pageNum"=>0,"pageSize"=>10,"seasonId"=>"75","stageId"=>"17","userId"=>"2137317"]);
        $res = getPlayerTypeStatistics(["pageNum"=>0,"pageSize"=>10,"seasonId"=>"75","stageId"=>"18","provinceId"=>"","cityId"=>"","type"=>"score"]);
    print_r($res);
}




















