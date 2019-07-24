<?php

namespace app\admin\controller;

use app\util\BaseController;
use think\Db;
use think\Exception;
use think\exception\DbException;

/**
 * EligibilityController
 * Class Eligibility
 * @package app\admin\controller
 */
class Eligibility extends BaseController
{

    public $table = 'CboEligibility';

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
                    if($key === 'type'){
                        $where[$key] = $val;
                    }
                }
            }
        }
        $db = $db->where($where)->order('type asc, sort asc, id desc');
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