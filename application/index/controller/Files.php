<?php

namespace app\index\controller;

use app\index\model\CboFiles;
use app\index\model\CboWebsiteBottomConf;
use think\Controller;

/**
 * 文件 Controller
 * Class Files
 * @package app\index\controller
 */
class Files extends Base {
    public function index(){
        $websiteBottomConf = CboWebsiteBottomConf::where(['is_delete'=>0])->field('gmt_create,gmt_modified,is_delete', true)->select();
        $file_name = $this->request->get('search_value');
        $where = [
            'is_delete' => 0
        ];
        if($file_name){
            $where['file_name'] = ['like',"%{$file_name}%"];
        }
        $filesList = CboFiles::where($where)->field('gmt_modified,is_delete', true)->order('date desc, id desc')->select();

        $this->assign('banner', $this->banner);
        $this->assign('action_page', 6);
        $this->assign('files_list', $filesList);
        $this->assign('website_bottom_conf', $websiteBottomConf);

        if($file_name||$file_name===''){
            if($filesList){
                return json($filesList);
            }else{
                return 0;
            }
        }
        return $this->fetch();
    }
}