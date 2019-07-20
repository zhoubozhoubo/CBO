<?php

namespace app\util;

/**
 * 逻辑基类
 * Class BaseLogic
 * @package app\common\base
 */
class BaseLogic
{
    public function resultSuccess($data = '', $msg='成功', $code = 1)
    {
        return ['code' => $code, 'msg' => $msg, 'data' => $data];
    }
    public function resultFailed($code = 0, $msg='失败', $data = '')
    {
        return ['code' => $code, 'msg' => $msg, 'data' => $data];
    }

    /**
     * 创建Base
     * @param $model
     * @param $param
     * @return array
     */
    public function createBase($model, $param){
        $res = $model->create($param);
        if(!$res){
            return $this->resultFailed(ReturnCode::ADD_FAILED,'新增数据失败！');
        }
        return $this->resultSuccess($res);
    }

    /**
     * 更新Base
     * @param $model
     * @param $param
     * @return array
     */
    public function updateBase($model, $param){
        $res = $model->update($param);
        if(!$res){
            return $this->resultFailed(ReturnCode::UPDATE_FAILED,'更新数据失败！');
        }
        return $this->resultSuccess($res);
    }

}