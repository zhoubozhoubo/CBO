<?php

namespace app\index\controller;

use app\index\model\CboFiles;
use think\Controller;

/**
 * 文件 Controller
 * Class Files
 * @package app\index\controller
 */
class Files extends Controller{
    public function index(){
        $file_name = $this->request->get('search_value');
        $where = [
            'is_delete' => 0
        ];
        if($file_name){
            $where['file_name'] = ['like',"%{$file_name}%"];
        }
        $filesList = CboFiles::where($where)->field('gmt_modified,is_delete', true)->order('date desc, id desc')->select();
//        print_r($filesList);exit;
        $this->assign('action_page', 6);
        $this->assign('files_list', $filesList);

        if($file_name){
            if($filesList){
                return json($filesList);
            }else{
                return 0;
            }
        }
        return $this->fetch();
    }
}