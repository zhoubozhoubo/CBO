<?php

namespace app\admin\controller;

use app\admin\model\CboFiles;
use app\util\BaseController;
use app\util\ReturnCode;
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
            $fileUrl = explode('/', $item['file_url']);
            $item['file_url'] =$fileUrl[count($fileUrl)-1];
        }
    }


    /**
     * 新增/更新数据
     * @return array
     */
    public function coruData()
    {
        $postData = $this->request->post();
        $postData['file_url'] = $postData['file']['url'];
        unset($postData['file']);
        return $this->coruBase($postData);
    }

    public function download(){
        $getData = $this->request->get();
        $id = $getData['id'];
        $file = CboFiles::get($id);
        $fileUrl = explode('/', $file['file_url']);
        $fileUrl = $_SERVER['DOCUMENT_ROOT'].'/upload/'.$fileUrl[count($fileUrl)-2].'/'.$fileUrl[count($fileUrl)-1];
        if(!isset($fileUrl)||trim($fileUrl)==''){
            return $this->buildFailed(ReturnCode::FILE_NOT_FOUND,'文件不存在1','');
        }
        if(!file_exists($fileUrl)){ //检查文件是否存在
            return $this->buildFailed(ReturnCode::FILE_NOT_FOUND,'文件不存在2','');
        }
        $file_name=basename($fileUrl);
//        $file_type=explode('.',$file['file_url']);
//        $file_type=$file_type[count($file_type)-1];
        $file_name=trim($file['file_name']=='')?$file_name:urlencode($file['file_name']);
        $file=fopen($fileUrl,'r'); //打开文件
        //输入文件标签
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header("Accept-Length: ".filesize($file['file_url']));
        header("Content-Disposition: attachment; filename=".$file_name);
        //输出文件内容
        return fread($file,filesize($fileUrl));
        fclose($file);
    }
}