<?php

namespace app\admin\controller;

use app\admin\model\CboBasicConf;
use app\util\BaseController;
use think\Db;
use think\Exception;
use think\exception\DbException;

/**
 * BasicConfController
 * Class BasicConf
 * @package app\admin\controller
 */
class BasicConf extends BaseController
{

    public $table = 'CboBasicConf';

    /**
     * 获取列表
     *
     */
    public function getDetails()
    {
        $getData = $this->request->get();
        $where = [
            'name'=>$getData['name'],
            'is_delete'=>0
        ];
        $basicConf = CboBasicConf::where($where)->field('gmt_create,gmt_modified,is_delete', true)->find();
        return $this->buildSuccess($basicConf);
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