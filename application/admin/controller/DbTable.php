<?php

namespace app\admin\controller;

use app\model\AdminMenu;
use app\util\BaseController;
use think\Db;
use think\exception\DbException;

class DbTable extends BaseController
{
    //前端文件路径
    public $webPath = '/CBOadminWeb/src/';
    //是否强制写入vue文件
    public $forceWriteVue = false;
    //是否强制写入js文件
    public $forceWriteJs = false;
    //是否强制写入controller文件
    public $forceWriteController = false;
    //是否强制写入logicr文件
    public $forceWriteLogic = false;
    //是否强制写入model文件
    public $forceWriteModel = false;

    /**
     * 一键生成
     * @return array
     */
    public function index()
    {
        $this->requestType('POST');
        $postData = $this->request->post();

        //是否强制重写
        if (isset($postData['fileList']) && $postData['fileList'] !== []) {
            $fileList = $postData['fileList'];
            foreach ($fileList as $key => &$val) {
                if ($val['forceWrite']) {
                    $val['forceWrite'] = 1;
                } else {
                    $val['forceWrite'] = 0;
                }
            }
            if ($fileList[0]['forceWrite'] === 0 && $fileList[1]['forceWrite'] === 0 && $fileList[2]['forceWrite'] === 0 && $fileList[3]['forceWrite'] === 0 && $fileList[4]['forceWrite'] === 0) {
                return $this->buildFailed(-2, '请勾选要重写生成的文件');
            }
            //强制写入vue文件
            if ($fileList[0]['forceWrite'] === 1) {
                $this->forceWriteVue = true;
            }
            //强制写入js文件
            if ($fileList[1]['forceWrite'] === 1) {
                $this->forceWriteJs = true;
            }
            //强制写入controller文件
            if ($fileList[2]['forceWrite'] === 1) {
                $this->forceWriteController = true;
            }
            //强制写入logic文件
            if ($fileList[3]['forceWrite'] === 1) {
                $this->forceWriteLogic = true;
            }
            //强制写入model文件
            if ($fileList[3]['forceWrite'] === 1) {
                $this->forceWriteModel = true;
            }
        }

        //表名称
        $tableName = $postData['tableName'];
        //表所有字段及属性
        $fullFields = Db::query("SHOW FULL FIELDS FROM {$tableName}");

        //基本配置
        $baseConfig = $postData['baseConfig'];

        //可搜索列数据
        $searchData = $postData['searchData'];
//        foreach ($searchData as $key => &$val) {
//            if ($val['key'] === '' || $val['name'] === '') {
//                unset($searchData[$key]);
//            }
//        }
//        $searchData = array_values($searchData);

        //搜索组件数据
        $searchComponentsData = $postData['searchComponentsData'];
        $datePicker = [];
        $dateRangePicker = [];
        foreach ($searchComponentsData as &$val) {
            if ($val['name'] === 'DatePicker') {
                $datePicker[] = $val['key'];
            } else if ($val['name'] === 'DateRangePicker') {
                $dateRangePicker[] = $val['key'];
            }
        }
//        $searchComponentsData = array_values($searchComponentsData);

        //表格列数据
        $columnsData = $postData['columnsData'];
//        foreach ($columnsData as $key => &$val) {
//            if ($val['key'] === '' || $val['name'] === '') {
//                unset($columnsData[$key]);
//            }
//        }
//        $columnsData = array_values($columnsData);

        //表格组件数据
        $columnsComponentsData = $postData['columnsComponentsData'];
        $switch = [];
//        $SwitchComment = [];
        foreach ($columnsComponentsData as &$val) {
            if ($val['name'] === 'Switch') {
                $switch[] = $val['key'];
//                foreach ($fullFields as $item) {
//                    if ($item['Field'] === $val['key']) {
//                        $SwitchComment[$key]['name'] = $val['key'];
//                        $SwitchComment[$key]['value'] = json_decode($item['Comment'],true);
//                    }
//                }
            }
        }
//        $tableComponentsData = array_values($tableComponentsData);

        //表单元素数据
        $itemData = $postData['itemData'];
//        foreach ($formData as $key => &$val) {
//            if ($val['key'] === '' || $val['name'] === '') {
//                unset($formData[$key]);
//            }
//        }
//        $formData = array_values($formData);

        //表单显示数据
        $itemShowData = $postData['itemShowData'];
        foreach ($itemShowData as &$val) {
            if (!$val['show']) {
                $val['show'] = 0;
            }
        }
//        $showData = array_values($showData);

        //表单组件数据
        $itemComponentsData = $postData['itemComponentsData'];
//        foreach ($componentsData as $key => &$val) {
//            if ($val['key'] === '' || $val['name'] === '') {
//                unset($componentsData[$key]);
//            }
//        }
//        $componentsData = array_values($componentsData);

        //必填数据
//        $requiredRuleData = $postData['requiredRuleData'];
//        foreach ($requiredRuleData as $key => &$val) {
//            if ($val['key'] === '') {
//                unset($requiredRuleData[$key]);
//            } else {
//                if (!$val['required']) {
//                    $val['required'] = 0;
//                }
//            }
//        }
//        $requiredRuleData = array_values($requiredRuleData);

        if (isset($postData['fileList']) && $postData['fileList'] !== []) {
            //生成vue文件
            if ($this->forceWriteVue === true) {
                $vueRes = $this->createVue($baseConfig, $searchData, $searchComponentsData, $columnsData, $columnsComponentsData, $itemData, $itemShowData, $itemComponentsData);
            } else {
                $vueRes = 1;
            }

            //生成js文件
            if ($this->forceWriteJs === true) {
                $jsRes = $this->createJs($baseConfig['backControllerName'], $baseConfig['frontGroupName'], $baseConfig['frontVueName']);
            } else {
                $jsRes = 1;
            }

            //生成Controller文件
            if ($this->forceWriteController === true) {
                $controllerRes = $this->createController($baseConfig, $datePicker, $dateRangePicker);
            } else {
                $controllerRes = 1;
            }

            //生成Logic文件
            if ($this->forceWriteLogic === true) {
                $logicRes = $this->createLogic($baseConfig);
            } else {
                $logicRes = 1;
            }

            //生成Model文件
            if ($this->forceWriteModel === true) {
                $modelRes = $this->createModel($baseConfig['backModelName']);
            } else {
                $modelRes = 1;
            }
        } else {
            //生成vue文件
            $vueRes = $this->createVue($baseConfig, $searchData, $searchComponentsData, $columnsData, $columnsComponentsData, $itemData, $itemShowData, $itemComponentsData);

            //生成js文件
            $jsRes = $this->createJs($baseConfig['backControllerName'], $baseConfig['frontGroupName'], $baseConfig['frontVueName']);

            //生成Controller文件
            $controllerRes = $this->createController($baseConfig, $datePicker, $dateRangePicker);

            //生成Logic文件
            $logicRes = $this->createLogic($baseConfig);

            //生成Model文件
            $modelRes = $this->createModel($baseConfig['backModelName']);
        }

        //自动生成菜单
        if($postData['baseConfig']['autoMenu']){
            $menuAttribute = $postData['baseConfig']['menuAttribute'];
            if($menuAttribute){
                foreach ($menuAttribute as $item){
                    if($item['url']){
                        $menu = AdminMenu::where('url',$item['url'])->count();
                    }else{
                        $menu = 0;
                    }

                    if(!$menu){
                        AdminMenu::create($item);
                    }
                }
//                $adminMenu = new AdminMenu();
//                $adminMenu->saveAll($menuAttribute);
            }
        }

        if ($vueRes === 1 && $jsRes === 1 && $controllerRes === 1 && $logicRes === 1 && $modelRes === 1) {
            //生成成功
            return $this->buildSuccess([]);
        } else {
            //存在文件生成失败或文件存在
            $data = [
                ['name' => 'vueFile', 'create' => $vueRes, 'forceWrite' => $this->forceWriteVue],
                ['name' => 'jsFile', 'create' => $jsRes, 'forceWrite' => $this->forceWriteJs],
                ['name' => 'controllerFile', 'create' => $controllerRes, 'forceWrite' => $this->forceWriteController],
                ['name' => 'logicFile', 'create' => $logicRes, 'forceWrite' => $this->forceWriteLogic],
                ['name' => 'modelFile', 'create' => $modelRes, 'forceWrite' => $this->forceWriteModel]
            ];
            return $this->buildFailed(0, '存在部分文件生成失败或部分文件已经存在，是否重新生成！', $data);
        }
    }

    public function createVue($baseConfig, $searchData, $searchComponentsData, $columnsData, $columnsComponentsData, $itemData, $itemShowData, $itemComponentsData)
    {
        //模块名
        $modelName = $baseConfig['frontModelName'];
        //组名称
        $groupName = $baseConfig['frontGroupName'];
        //文件名称
        $vueName = $baseConfig['frontVueName'];

        //表主键
        $pk = $baseConfig['pk'];

        //新增操作
        $add = $baseConfig['add'];

        //编辑操作
        $edit = $baseConfig['edit'];

        //删除操作
        $delete = $baseConfig['delete'];

        //搜索表单
//        $searchConf = $searchData;
        $searchConfJson = [];
        if ($searchData) {
            foreach ($searchData as $key => &$val) {
                $val['components'] = $searchComponentsData[$key]['name'];
                //搜索字段json
                $searchConfJson["-" . $val['key'] . "-"] = '';
            }
        }

        //显示表格列json
        $columnsListJson = [];
        if ($columnsData) {
            foreach ($columnsData as $key => &$val) {
                $val['components'] = $columnsComponentsData[$key]['name'];
                $columnsListJson[$key]["-title-"] = $val['name'];
                $columnsListJson[$key]["-key-"] = $val['key'];
                $columnsListJson[$key]["-align-"] = 'center';
            }
        }

        //操作列
        if ($edit || $delete) {
            $key = count($columnsListJson);
            $columnsListJson[$key]['-title-'] = '操作';
            $columnsListJson[$key]['-key-'] = 'handle';
            $columnsListJson[$key]['-align-'] = 'center';
            if (!$edit && $delete) {
                $handle = ['delete'];
            } else if ($edit && !$delete) {
                $handle = ['edit'];
            } else {
                $handle = ['edit', 'delete'];
            }
            $columnsListJson[$key]['-handle-'] = $handle;
        }

        //表单元素
//        $formItem = $itemData;
        $formItemJson = [];
//        $imgComponents = 0;
//        $textAreaComponents = 0;
        if ($itemData) {
            foreach ($itemData as $key => &$val) {
                //表单元素显示
//                $val['show'] = $itemShowData[$key]['show'];
//                if ($val['show']) {
//                    $val['components'] = $itemComponentsData[$key]['name'];
//                }


                foreach ($itemShowData as $k => $v) {
                    if ($val['key'] === $v['key']) {
                        //表单元素显示
                        $val['show'] = $v['show'];
                        if ($v['show']) {

                            $val['components'] = $itemComponentsData[$k]['name'];
                        }
                    }
                }
                //表单元素组件
//                if ($itemShowData[$key]['show']) {
//                    foreach ($itemComponentsData as $k => $v) {
//                        if ($itemComponentsData[$key]['name'] === 'UploadImg') {
//                            $img[] = $itemComponentsData[$key]['key'];
////                            $imgComponents = 1;
//                        }
//                        if ($itemComponentsData[$key]['name'] === 'TextArea') {
//                            $textArea[] = $itemComponentsData[$key]['key'];
////                            $textAreaComponents = 1;
//                        }
////                        if ($val['key'] === $itemComponentsData[$key]['key']) {
//                            //表单元素组件类型
////                            $itemData[$key]['components'] = $itemComponentsData[$key]['name'];
////                        }
//                    $itemData[$key]['components'] = $itemComponentsData[$key]['name'];
//                    }
//                }
                //表单字段json
                $formItemJson["-" . $val['key'] . "-"] = '';
            }
        }
        $img = [];
        $file = [];
        $editor = [];
        if ($itemComponentsData) {
            foreach ($itemComponentsData as $k => &$val) {
                if ($val['name'] === 'UploadImg') {
                    $img[] = $val['key'];
                }
                if ($val['name'] === 'UploadFile') {
                    $file[] = $val['key'];
                }
                if ($val['name'] === 'Editor') {
                    $editor[] = $val['key'];
                }
            }
        }

        //表单字段过滤规则
//        $ruleValidate = [];
//        if ($requiredRuleData) {
//            foreach ($requiredRuleData as $val) {
//                $ruleValidate['-' . $val['key'] . '-'] = [["-required-" => $val['required'], "-message-" => $val['msg'], "-trigger-" => 'blur']];
//            }
//        }

        //主键
        $this->assign('pk', $pk);
        //文件信息
        $this->assign('modelName', $modelName);
        $this->assign('groupName', $groupName);
        $this->assign('vueName', $vueName);
        //搜索参数
        $this->assign('searchData', $searchData);
        $this->assign('searchConfJson', $this->jsonReplace($searchConfJson));
        //表格数据
        $this->assign('columnsData', $columnsData);
        $this->assign('columnsListJson', $this->jsonReplace($columnsListJson));
//        $this->assign('columnsComponentsData', $columnsComponentsData);
        //表单参数
        $this->assign('itemData', $itemData);
        $this->assign('formItemJson', $this->jsonReplace($formItemJson));
        //操作
        $this->assign('add', $add);
        $this->assign('edit', $edit);
        $this->assign('delete', $delete);
        //其他
        $this->assign('img', $img);
        $this->assign('file', $file);
        $this->assign('editor', $editor);

//        $this->assign('imgComponentsKey', $imgComponentsKey);
//        $this->assign('imgComponents', $imgComponents);
//        $this->assign('textAreaComponentsKey', $textAreaComponentsKey);
//        $this->assign('textAreaComponents', $textAreaComponents);
        //表单验证
//        if($ruleValidate){
//            $this->assign('ruleValidate', $this->jsonReplace($ruleValidate));
//        }else{
//            $this->assign('ruleValidate', '');
//        }

//        $this->assign('switch', $switch);


        //原始vue文件内容
        $vueTxt = $this->fetch('vue');

        $path = $this->webPath .'views/' . $modelName . '/' . $groupName . '/';

        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $path)) {
            mkdir($_SERVER['DOCUMENT_ROOT'] . $path, 0777, true);
        }
        $pathFile = $_SERVER['DOCUMENT_ROOT'] . $path . $vueName . ".vue";
        if (file_exists($pathFile) && !$this->forceWriteVue) {
//            return $this->buildSuccess('文件已经存在，是否重新生成！', ReturnCode::DB_SAVE_ERROR);
            return -1;
        }
        if (file_exists($pathFile)) {
            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/generateBack/')) {
                mkdir($_SERVER['DOCUMENT_ROOT'] . '/generateBack/', 0777, true);
            }
            copy($pathFile, $_SERVER['DOCUMENT_ROOT'] . '/generateBack/' . $vueName . date('Ymdhms', time()) . ".vue");
        }

        //打开文件
        $myFile = fopen($pathFile, "w");

        // 写入文件
        if (fwrite($myFile, $vueTxt)) {
            fclose($myFile);
//            return $this->buildSuccess('生成成功');
            return 1;
        } else {
//            return $this->buildFailed();
            return 0;
        }
    }

    public function createJs($backControllerName, $frontGroupName, $frontVueName)
    {
        $this->assign('backControllerName', $backControllerName);
        $jsTxt = $this->fetch('js');
        $path = $this->webPath .'api/';
        if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $path)) {
            mkdir($_SERVER['DOCUMENT_ROOT'] . $path, 0777, true);
        }
        $pathFile = $_SERVER['DOCUMENT_ROOT'] . $path . $frontGroupName .'_' . $frontVueName . ".js";
        if (file_exists($pathFile) && !$this->forceWriteJs) {
//            return $this->buildSuccess('文件已经存在，是否重新生成！', ReturnCode::DB_SAVE_ERROR);
            return -1;
        }
        if (file_exists($pathFile)) {
            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/generateBack/')) {
                mkdir($_SERVER['DOCUMENT_ROOT'] . '/generateBack/', 0777, true);
            }
            copy($pathFile, $_SERVER['DOCUMENT_ROOT'] . '/generateBack/' . $frontGroupName .'_'. $frontVueName . date('Ymdhms', time()) . ".js");
        }
        $myFile = fopen($pathFile, "w");

        if (fwrite($myFile, $jsTxt)) {
            fclose($myFile);
//            return $this->buildSuccess('生成成功');
            return 1;
        } else {
//            return $this->buildFailed();
            return 0;
        }
    }

    public function createController($baseConfig, $datePicker, $dateRangePicker)
    {
//        print_r($datePicker);
//        print_r(implode(',',$datePicker));
//        exit;
        $datePicker = '"' . implode('","', $datePicker) . '"';
        $dateRangePicker = '"' . implode('","', $dateRangePicker) . '"';
        //下划线转驼峰命名
        //文件名称及类名称
        $backControllerName = $this->convertUnderline($baseConfig['backControllerName']);
        //Model名称
        $backModelName = $this->convertUnderline($baseConfig['backModelName']);
        //表主键
        $pk = $baseConfig['pk'];
        //查重
        $check = $baseConfig['check'];
        //查重字段
        $checkField = $baseConfig['checkField'];

        $isset=[];
        foreach ($checkField as $item) {
            $isset[$item]="isset($-postData['$item'])";
        }
        $isset = implode(' && ', $isset);

        //返回原始文件内容
        $firstTxt = "<?php

namespace app\admin\controller;

use app\util\BaseController;
use think\Db;
use think\Exception;
use think\\exception\DbException;

/**
 * {$backControllerName}Controller
 * Class {$backControllerName}
 * @package app\admin\controller
 */
class {$backControllerName} extends BaseController
{

    public $-table = '{$backModelName}';

    /**
     * 获取列表
     * @return array|string
     * @throws DbException
     * @throws Exception
     */
    public function getList()
    {
        $-searchConf = json_decode($-this->request->get('searchConf', ''),true);
        $-db = Db::name($-this->table);
        $-where = [];
        if($-searchConf){
            foreach ($-searchConf as $-key=>$-val){
                if($-val !== ''){
                    ";
        $datePickerTxt = "if(in_array($-key, [{$datePicker}])){
                        if($-val){
                            $-db->whereTime($-key,'between', [" . '"{$-val} 00:00:00"' . ", " . '"{$val} 23:59:59"' . "]);
                        }
                    }else ";
        $dateRangePickerTxt = "if(in_array($-key, [{$dateRangePicker}])){
                        if($-val[0]&&$-val[1]){
                            $-db->whereTime($-key,'between', [" . '"{$val[0]} 00:00:00"' . ", " . '"{$val[1]} 23:59:59"' . "]);
                        }
                    }else ";
        $lastTxt = "if($-key === 'status'){
                        $-where[$-key] = $-val;
                    }else {
                        $-where[$-key] = ['like', '%'.$-val.'%'];
                    }
                }
            }
        }
        $-db = $-db->where($-where)->order('{$pk} desc');
        return $-this->_list($-db);
    }


    /**
     * 新增/更新数据
     * @return array
     */
    public function coruData()
    {
        $-postData = $-this->request->post();";
        $checkTxt = "
        if($isset){
            $-this->check = true;
        }";
        $finalTxt = "
        return $-this->coruBase($-postData);
    }
}";
//        print_r($datePicker === '""' && $dateRangePicker === '""');
//        exit;
        if ($datePicker === '""' && $dateRangePicker === '""') {
            $controllerTxt = $firstTxt . $lastTxt;
        } else if ($datePicker !== '""' && $dateRangePicker === '""') {
            $controllerTxt = $firstTxt . $datePickerTxt . $lastTxt;
        } else if ($datePicker === '""' && $dateRangePicker !== '""' ) {
            $controllerTxt = $firstTxt . $dateRangePickerTxt . $lastTxt;
        } else {
            $controllerTxt = $firstTxt . $datePickerTxt . $dateRangePickerTxt . $lastTxt;
        }

        if(!$check){
            $controllerTxt = $controllerTxt.$finalTxt;
        }else{
            $controllerTxt = $controllerTxt.$checkTxt.$finalTxt;
        }

        $path = '/admin/controller/';
        if (!file_exists(APP_PATH . $path)) {
            mkdir(APP_PATH . $path, 0777, true);
        }
        $pathFile = APP_PATH . $path . $backControllerName . ".php";

        if (file_exists($pathFile) && !$this->forceWriteController) {
//            return $this->buildSuccess('文件已经存在，是否重新生成！', ReturnCode::DB_SAVE_ERROR);
            return -1;
        }
        if (file_exists($pathFile)) {
            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/generateBack/')) {
                mkdir($_SERVER['DOCUMENT_ROOT'] . '/generateBack/', 0777, true);
            }
            copy($pathFile, $_SERVER['DOCUMENT_ROOT'] . '/generateBack/' . $backControllerName . date('Ymdhms', time()) . ".php");
        }
        $txt = str_replace('$-', '$', $controllerTxt);
        $myFile = fopen($pathFile, "w");

        if (fwrite($myFile, $txt)) {
            fclose($myFile);
//            return $this->buildSuccess('生成成功');
            return 1;
        } else {
//            return $this->buildFailed();
            return 0;
        }
    }

    public function createLogic($baseConfig)
    {
        //下划线转驼峰命名
        //Logic名称
        $backLogicName = $this->convertUnderline($baseConfig['backLogicName']);
        //Model名称
        $backModelName = $this->convertUnderline($baseConfig['backModelName']);
        //表主键
        $pk = $baseConfig['pk'];
        //查重
        $check = $baseConfig['check'];
        //查重字段
        $checkField = $baseConfig['checkField'];
        
        $whereOr=[];
        foreach ($checkField as $item) {
            $whereOr[$item]="'$item'=>$-param['$item']";
        }
        $whereOr = implode(',', $whereOr);



        //返回原始文件内容
        $firstTxt = "<?php

namespace app\admin\logic;

use app\admin\model\\$backModelName;
use app\util\BaseLogic;
use app\util\ReturnCode;

/**
 * {$backLogicName}
 * Class {$backLogicName}
 * @package app\admin\logic
 */
class {$backLogicName} extends BaseLogic
{";
        $checkTxt = "
    /**
     * 参数检测
     * @param $-param
     * @return array
     */
    public function check($-param)
    {
        //查重
        $-whereBase = [
            'is_delete' => ['=', 0]
        ];
        $-wherePk = [
            '{$pk}' => ['<>', $-param['{$pk}']]
        ];
        $-whereOr = [{$whereOr}];
        if (!$-param['{$pk}']) {
            $-count = ContentNews::where(function ($-query) use ($-whereOr, $-whereBase) {
                $-query->whereOr($-whereOr);
            })->where($-whereBase)->count();
        } else {
            $-count = ContentNews::where(function ($-query) use ($-whereOr, $-whereBase, $-wherePk) {
                $-query->whereOr($-whereOr);
            })->where($-whereBase)->where($-wherePk)->count();
        }
        if ($-count > 0) {
            return $-this->resultFailed(ReturnCode::DATA_REPEAT, '数据重复');
        }
        return $-this->resultSuccess();
    }
    ";
        $lastTxt = "
    /**
     * 创建OR更新
     * @param $-param
     * @return array
     */
    public function coru($-param){
        //实力化操作模型
        $-model = new {$backModelName}();
        //判断创建OR更新
        if (!$-param['{$pk}']) {
            return $-res = $-this->createBase($-model,$-param);
        } else {
            return $-res = $-this->updateBase($-model,$-param);
        }
    }
}
";
        if($check && $whereOr){
            $logicTxt = $firstTxt . $checkTxt . $lastTxt;
        }else{
            $logicTxt = $firstTxt . $lastTxt;
        }
        $path = '/admin/logic/';
        if (!file_exists(APP_PATH . $path)) {
            mkdir(APP_PATH . $path, 0777, true);
        }
        $pathFile = APP_PATH . $path . $backLogicName . ".php";
        if (file_exists($pathFile) && !$this->forceWriteModel) {
//            return $this->buildSuccess('文件已经存在，是否重新生成！', ReturnCode::DB_SAVE_ERROR);
            return -1;
        }
        if (file_exists($pathFile)) {
            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/generateBack/')) {
                mkdir($_SERVER['DOCUMENT_ROOT'] . '/generateBack/', 0777, true);
            }
            copy($pathFile, $_SERVER['DOCUMENT_ROOT'] . '/generateBack/' . $backLogicName . date('Ymdhms', time()) . ".php");
        }
        $logicTxt = str_replace('$-', '$', $logicTxt);
        $myFile = fopen($pathFile, "w");

        if (fwrite($myFile, $logicTxt)) {
            fclose($myFile);
//            return $this->buildSuccess('生成成功');
            return 1;
        } else {
//            return $this->buildFailed();
            return 0;
        }

    }

    public function createModel($backModelName)
    {
        //返回原始文件内容
        $modelTxt = "<?php

namespace app\admin\model;

use think\Model;

class {$backModelName} extends Model
{

}";
        $path = '/admin/model/';
        if (!file_exists(APP_PATH . $path)) {
            mkdir(APP_PATH . $path, 0777, true);
        }
        $pathFile = APP_PATH . $path . $backModelName . ".php";
        if (file_exists($pathFile) && !$this->forceWriteModel) {
//            return $this->buildSuccess('文件已经存在，是否重新生成！', ReturnCode::DB_SAVE_ERROR);
            return -1;
        }
        if (file_exists($pathFile)) {
            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . '/generateBack/')) {
                mkdir($_SERVER['DOCUMENT_ROOT'] . '/generateBack/', 0777, true);
            }
            copy($pathFile, $_SERVER['DOCUMENT_ROOT'] . '/generateBack/' . $backModelName . date('Ymdhms', time()) . ".php");
        }
//        $modelTxt = str_replace('$-', '$', $modelTxt);
        $myFile = fopen($pathFile, "w");

        if (fwrite($myFile, $modelTxt)) {
            fclose($myFile);
//            return $this->buildSuccess('生成成功');
            return 1;
        } else {
//            return $this->buildFailed();
            return 0;
        }

    }

    /**
     * 获取数据表
     * @return array
     */
    public function getTableList()
    {
        try {
            $lists = Db::query("SHOW TABLE STATUS");
            return $this->buildSuccess($lists);
        } catch (DbException $exception) {
            return $this->buildFailed();
        }
    }

    /**
     * 获取表所有列
     * @return array
     */
    public function getTableFullColumns()
    {
        $tableName = $this->request->get('tableName');
        try {
            $lists = Db::query("SHOW FULL COLUMNS FROM {$tableName}");
            return $this->buildSuccess($lists);
        } catch (DbException $exception) {
            return $this->buildFailed();
        }
    }

    /**
     * 获取表所有字段
     * @return array
     */
    public function getTableFullFields()
    {
        $tableName = $this->request->get('tableName');
        try {
            $lists = Db::query("SHOW FULL FIELDS FROM {$tableName}");
            return $this->buildSuccess($lists);
        } catch (DbException $exception) {
            return $this->buildFailed();
        }
    }

    /**
     * json字符串处理
     * @param $str
     * @return mixed
     */
    public function jsonReplace($str)
    {
        if ($str) {
            return str_replace('-"', '', str_replace('"-', '', json_encode($str, JSON_UNESCAPED_UNICODE)));
        }
        return '';
    }

    /**
     * 下划线转驼峰命名
     * @param $str
     * @return string
     */
    public function convertUnderline($str)
    {
        $str = ucwords(str_replace('_', ' ', $str));
        $str = str_replace(' ', '', lcfirst($str));
        return ucfirst($str);
    }


}