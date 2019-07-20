<?php

namespace app\admin\controller;

use app\util\BaseController;
use think\Db;
use think\Exception;
use think\exception\DbException;

/**
 * FilesController
 * Class Files
 * @package app\admin\controller
 */
class Files extends BaseController
{

    public $table = 'CboFiles';

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
                    if(in_array($key, ["date"])){
                        if($val[0]&&$val[1]){
                            $db->whereTime($key,'between', ["{$val[0]} 00:00:00", "{$val[1]} 23:59:59"]);
                        }
                    }else if($key === 'status'){
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

    public function _getList_data_filter(&$data){
        foreach ($data as &$item){
            $item['file'] = [['name' => '', 'url' => $item['file_url']]];
        }
    }


    /**
     * 新增/更新数据
     * @return array
     */
    public function coruData()
    {
        $postData = $this->request->post();
        $postData['file_url'] = $postData['file'][0]['url'];
        unset($postData['file']);
        print_r($postData);exit;
        return $this->coruBase($postData);
    }
}