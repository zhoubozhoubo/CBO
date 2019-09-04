<?php
/**
 * Created by PhpStorm.
 * User: zb
 * Date: 19-8-27
 * Time: 上午10:17
 */

namespace app\phone\controller;

use app\phone\model\CboBasicConf;
use think\Controller;

class Base extends Controller{
    public $banner;

    public function _initialize()
    {
        $where = [
            'name'=>'banner',
            'is_delete'=>0
        ];
        $banner = CboBasicConf::where($where)->field('gmt_create,gmt_modified,is_delete', true)->value('value');
        $this->banner = $banner;
    }
}