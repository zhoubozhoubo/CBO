<?php

namespace app\admin\logic;

use app\admin\model\CboMatchTimeAddress;
use app\util\BaseLogic;
use app\util\ReturnCode;

/**
 * MatchTimeAddressLogic
 * Class MatchTimeAddressLogic
 * @package app\admin\logic
 */
class MatchTimeAddressLogic extends BaseLogic
{
    /**
     * 创建OR更新
     * @param $param
     * @return array
     */
    public function coru($param){
        //实力化操作模型
        $model = new CboMatchTimeAddress();
        //判断创建OR更新
        if (!$param['id']) {
            return $res = $this->createBase($model,$param);
        } else {
            return $res = $this->updateBase($model,$param);
        }
    }
}
