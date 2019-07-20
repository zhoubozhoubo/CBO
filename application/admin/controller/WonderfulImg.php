<?php

namespace app\admin\controller;

use app\util\BaseController;
use think\Db;
use think\Exception;
use think\exception\DbException;

/**
 * WonderfulImgController
 * Class WonderfulImg
 * @package app\admin\controller
 */
class WonderfulImg extends BaseController
{

    public $table = 'CboWonderfulImg';

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
                if($val !== ''){
                    if($key === 'status'){
                        $where[$key] = $val;
                    }else {
                        $where[$key] = ['like', '%'.$val.'%'];
                    }
                }
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
}