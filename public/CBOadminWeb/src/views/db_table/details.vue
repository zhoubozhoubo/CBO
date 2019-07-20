<template>
    <div>
        <!--表格-->
        <Row>
            <Col span="24">
                <Card>
                    <p slot="title" style="height: 32px">
                        <Button type="info" @click="goBack" icon="md-arrow-round-back">返回</Button>
                        <Button type="success" @click="oneTouch" icon="md-create" style="margin-left: 16px;">一键生成
                        </Button>
                    </p>

                    <div>
                        <Form ref="myForm" :model="baseConfig" :label-width="110">
                            <FormItem label="表名称：">
                                {{tableName}}
                            </FormItem>
                            <FormItem label="表主键：" prop="pk">
                                <Select v-model="baseConfig.pk" placeholder='请选择表主键' style="width: 150px;">
                                    <Option v-for="(item, index) in tableData" :key="index" :value="item.Field">
                                        {{item.Field}}
                                    </Option>
                                </Select>
                            </FormItem>
                            <FormItem label="操作：">
                                <i-switch style="margin-right: 15px;" size="large" v-model="baseConfig.add">
                                    <span slot="open">新增</span>
                                    <span slot="close">新增</span>
                                </i-switch>
                                <i-switch style="margin-right: 15px;" size="large" v-model="baseConfig.edit">
                                    <span slot="open">编辑</span>
                                    <span slot="close">编辑</span>
                                </i-switch>
                                <i-switch style="margin-right: 15px;" size="large" v-model="baseConfig.delete">
                                    <span slot="open">删除</span>
                                    <span slot="close">删除</span>
                                </i-switch>
                            </FormItem>
                            <FormItem label="数据查重：" v-if="baseConfig.add || baseConfig.edit">
                                <i-switch style="margin-right: 15px;" size="large" v-model="baseConfig.check">
                                    <span slot="open">开启</span>
                                    <span slot="close">关闭</span>
                                </i-switch>
                            </FormItem>
                            <FormItem label="查重字段：" v-if="(baseConfig.add || baseConfig.edit) && baseConfig.check">
                                <Select v-model="baseConfig.checkField" multiple placeholder='请选择需查重字段'
                                        style="width: 457px;">
                                    <Option v-for="(item, index) in tableData" :key="index" :value="item.Field">
                                        {{item.Field}}
                                    </Option>
                                </Select>
                                <div>
                                    <p style="display: inline-block; width: 150px; font-size: 12px; color: #ed4014;">
                                        <span v-if="baseConfig.checkField.length > 0" style="color: #515a6e;"><Icon
                                                type="ios-alert-outline" size="12"/> 需要检测数据重复的字段</span>
                                        <span v-else style="color: #ed4014;"><Icon type="ios-alert-outline" size="12"/> 需要检测数据重复的字段</span>
                                    </p>
                                </div>
                            </FormItem>
                            <FormItem label="后端文件配置：">
                                <Input style="width: 150px;" v-model="baseConfig.backControllerName" clearable
                                       placeholder="请输入后端控制器名称"></Input>
                                <Input style="width: 150px;" v-model="baseConfig.backLogicName" clearable
                                       placeholder="请输入后端逻辑名称"></Input>
                                <Input style="width: 150px;" v-model="baseConfig.backModelName" clearable
                                       placeholder="请输入后端模型名称"></Input>
                                <div>
                                    <p style="display: inline-block; width: 150px; font-size: 12px; color: #ed4014;">
                                        <span v-if="baseConfig.backControllerName.trim()" style="color: #515a6e;"><Icon
                                                type="ios-alert-outline" size="12"/> 后端控制器名称</span>
                                        <span v-else style="color: #ed4014;"><Icon type="ios-alert-outline" size="12"/> 后端控制器名称</span>
                                    </p>
                                    <p style="display: inline-block; width: 150px; font-size: 12px; color: #ed4014;">
                                        <span v-if="baseConfig.backLogicName.trim()" style="color: #515a6e;"><Icon
                                                type="ios-alert-outline" size="12"/> 后端逻辑名称</span>
                                        <span v-else style="color: #ed4014;"><Icon type="ios-alert-outline" size="12"/> 后端逻辑名称</span>
                                    </p>
                                    <p style="display: inline-block; width: 150px; font-size: 12px; color: #ed4014;">
                                        <span v-if="baseConfig.backModelName.trim()" style="color: #515a6e;"><Icon
                                                type="ios-alert-outline" size="12"/> 后端模型名称</span>
                                        <span v-else style="color: #ed4014;"><Icon type="ios-alert-outline" size="12"/> 后端模型名称</span>
                                    </p>
                                </div>
                            </FormItem>
                            <FormItem label="前端文件配置：">
                                <Input style="width: 150px;" v-model="baseConfig.frontModelName" clearable
                                       placeholder="请输入前端文件模块名称"></Input>
                                <Input style="width: 150px;" v-model="baseConfig.frontGroupName" clearable
                                       placeholder="请输入前端文件组名称"></Input>
                                <Input style="width: 150px;" v-model="baseConfig.frontVueName" clearable
                                       placeholder="请输入前端vue文件名称"></Input>
                                <div>
                                    <p style="display: inline-block; width: 150px; font-size: 12px; color: #ed4014;">
                                        <span v-if="baseConfig.frontModelName.trim()" style="color: #515a6e;"><Icon
                                                type="ios-alert-outline" size="12"/> 前端文件模块名称</span>
                                        <span v-else style="color: #ed4014;"><Icon type="ios-alert-outline" size="12"/> 前端文件模块名称</span>
                                    </p>
                                    <p style="display: inline-block; width: 150px; font-size: 12px; color: #ed4014;">
                                        <span v-if="baseConfig.frontGroupName.trim()" style="color: #515a6e;"><Icon
                                                type="ios-alert-outline" size="12"/> 前端文件组名称</span>
                                        <span v-else style="color: #ed4014;"><Icon type="ios-alert-outline" size="12"/> 前端文件组名称</span>
                                    </p>
                                    <p style="display: inline-block; width: 150px; font-size: 12px; color: #ed4014;">
                                        <span v-if="baseConfig.frontVueName.trim()" style="color: #515a6e;"><Icon
                                                type="ios-alert-outline" size="12"/> 前端vue文件名称</span>
                                        <span v-else style="color: #ed4014;"><Icon type="ios-alert-outline" size="12"/> 前端vue文件名称</span>
                                    </p>
                                </div>
                            </FormItem>
                            <FormItem label="自动生成菜单：">
                                <i-switch style="margin-right: 15px;" size="large" v-model="baseConfig.autoMenu">
                                    <span slot="open">是</span>
                                    <span slot="close">否</span>
                                </i-switch>
                            </FormItem>
                            <FormItem label="菜单配置：" v-if="baseConfig.autoMenu">
                                <div v-for="(menu, index) in baseConfig.menuAttribute">
                                    <Input style="width: 150px;" v-model="menu.name" clearable
                                           placeholder="请输入菜单名称"></Input>
                                    <Input style="width: 250px;" v-model="menu.url" clearable
                                           placeholder="请输入菜单URL"></Input>
                                    <Icon type="md-remove-circle" size="20" color="#ed4014" style="margin-left: 10px" v-if="index > 0" @click="delMenu(index)"/>
                                    <div>
                                        <p style="display: inline-block; width: 150px; font-size: 12px; color: #ed4014;">
                                        <span v-if="menu.name.trim()" style="color: #515a6e;"><Icon
                                                type="ios-alert-outline" size="12"/> 后台菜单名称</span>
                                            <span v-else style="color: #ed4014;"><Icon type="ios-alert-outline" size="12"/> 后台菜单名称</span>
                                        </p>
                                        <p style="display: inline-block; width: 150px; font-size: 12px; color: #ed4014;">
                                        <span style="color: #515a6e;"><Icon
                                                type="ios-alert-outline" size="12"/> 后台菜单URL</span>
                                        </p>
                                    </div>
                                </div>
                                <Button type="success" ghost @click="addMenu" icon="md-add">添加菜单</Button>
                            </FormItem>
                        </Form>

                        <Table :loading="tableLoading" :columns="columnsList" :data="tableData" border
                               disabled-hover></Table>
                    </div>
                </Card>
            </Col>
        </Row>
        <!--重新生成文件弹窗-->
        <Modal :closable="false" :mask-closable="false" v-model="modalSetting.show" width="668" :styles="{top: '30px'}">
            <p slot="header" style="color:#2d8cf0;">
                <Icon type="md-information-circle"></Icon>
                <span>文件生成结果</span>
            </p>
            <Row>
                <Col span="24">
                    <p style="margin-bottom: 10px;">请勾选要重新生成的文件（<span style="color: #19be6b;">绿色</span>表示<span
                            style="color: #19be6b;">生成成功</span>；<span style="color: #ed4014;">红色</span>表示<span
                            style="color: #ed4014;">生成失败</span>或<span style="color: #ed4014;">文件已存在</span>）</p>
                </Col>
                <Col span="6" v-for="(file, fileIndex) in fileList" :key="fileIndex">
                    <Checkbox v-if="file.create === 1" disabled><span style="color: #19be6b;">{{file.name}}</span>
                    </Checkbox>
                    <Checkbox v-else v-model="file.forceWrite"><span style="color: #ed4014;">{{file.name}}</span>
                    </Checkbox>
                </Col>
            </Row>
            <div slot="footer">
                <Button type="text" @click="cancel" style="margin-right: 8px">取消</Button>
                <Button type="primary" @click="submit" :loading="modalSetting.loading">确定</Button>
            </div>
        </Modal>
    </div>
</template>
<script>
    import {getTableFullFields, oneTouch} from '@/api/db_table'

    // 复选框
    const checkbox = (vm, h, currentRow, index, data, dataOther = '') => {
        if(data === vm.itemShowData){
            if (!vm.itemData[index].key) {
                return h('Checkbox', {
                    props: {
                        value: vm.itemShowData[index].show,
                        size: 'large',
                        disabled: true
                    },
                    style: {
                        margin: '5px 0'
                    }
                })
            }
        }
        return h('Checkbox', {
            props: {
                value: false,
                size: 'large'
            },
            style: {
                margin: '5px 0'
            },
            on: {
                'on-change': (res) => {
                    if (res === true) {
                        let comment = vm.tableData[index].Comment
                        let start = comment.indexOf('(')
                        let end = comment.indexOf(')')
                        if(start !== -1 && end !== -1){
                            comment = comment.substr(0,start)
                        }
                        vm.setData(data, index, currentRow.Field, comment, dataOther)
                    } else {
                        vm.resetData(data, index, dataOther)
                    }
                }
            }
        })
    }

    // 输入框
    const input = (vm, h, currentRow, index, data) => {
        if (!data[index].key) {
            return h('Input', {
                props: {
                    value: '',
                    size: 'default',
                    disabled: true
                }
            })
        } else {
            return h('Input', {
                props: {
                    value: data[index].name,
                    size: 'default',
                    clearable: true,
                    disabled: false
                },
                on: {
                    input: (val) => {
                        data[index].name = val
                    }
                }
            })
        }
    }

    // 搜索组件类型
    const searchComponentsTypeSelect = (vm, h, currentRow, index) => {
        if (!vm.searchData[index].key) {
            return h('Select', {
                props: {
                    value: ' ',
                    size: 'large',
                    disabled: true
                }
            })
        } else {
            return h('Select', {
                props: {
                    value: vm.searchComponentsData[index].name,
                    size: 'large'
                },
                on: {
                    'on-change': (val) => {
                        if (val) {
                            vm.setData(vm.searchComponentsData, index, currentRow.Field, val)
                        }
                    }
                }
            }, vm.searchSelectList.map((item) => {
                return h('Option', {
                    props: {
                        value: item.value,
                        label: item.name
                    }
                })
            }))
        }
    }

    // 表格列组件类型
    const columnsComponentsTypeSelect = (vm, h, currentRow, index) => {
        if (!vm.columnsData[index].key) {
            return h('Select', {
                props: {
                    value: ' ',
                    size: 'large',
                    disabled: true
                }
            })
        } else {
            return h('Select', {
                props: {
                    value: vm.columnsComponentsData[index].name,
                    size: 'large'
                },
                on: {
                    'on-change': (val) => {
                        if (val) {
                            vm.setData(vm.columnsComponentsData, index, currentRow.Field, val)
                        }
                    }
                }
            }, vm.columnsSelectList.map((item) => {
                return h('Option', {
                    props: {
                        value: item.value,
                        label: item.name
                    }
                })
            }))
        }
    }

    // 表格元素组件类型
    const itemComponentsTypeSelect = (vm, h, currentRow, index) => {
        if (!vm.itemShowData[index].show) {
            return h('Select', {
                props: {
                    value: ' ',
                    size: 'large',
                    disabled: true
                }
            })
        } else {
            return h('Select', {
                props: {
                    value: vm.itemComponentsData[index].name,
                    size: 'large'
                },
                on: {
                    'on-change': (val) => {
                        if (val) {
                            vm.setData(vm.itemComponentsData, index, currentRow.Field, val)
                        }
                    }
                }
            }, vm.itemSelectList.map((item) => {
                return h('Option', {
                    props: {
                        value: item.value,
                        label: item.name
                    }
                })
            }))
        }
    }

    export default {
        name: "table_details",
        components: {},
        data() {
            return {
                // 表名称
                tableName: '',
                // 表格加载状态
                tableLoading: false,
                // 表格数据
                tableData: [],
                // 初始化表格列
                columnsList: [
                    {
                        title: '序号',
                        align: 'center',
                        type: 'index',
                        fixed: 'left',
                        width: 65
                    },
                    {
                        title: '字段名称',
                        align: 'left',
                        key: 'Field',
                        fixed: 'left',
                        width: 150
                    },
                    {
                        title: '字段类型',
                        align: 'left',
                        key: 'Type',
                        fixed: 'left',
                        width: 150
                    },
                    {
                        title: '注释',
                        align: 'left',
                        key: 'Comment',
                        tooltip: true,
                        fixed: 'left',
                        width: 150
                    },
                    {
                        title: '作为搜索',
                        align: 'center',
                        key: 'Search',
                        width: 100
                    },
                    {
                        title: '搜索名称',
                        align: 'left',
                        key: 'SearchName',
                        width: 150
                    },
                    {
                        title: '搜索组件类型',
                        align: 'center',
                        key: 'SearchComponentsType',
                        width: 200
                    },
                    {
                        title: '作为表格列',
                        align: 'center',
                        key: 'Columns',
                        width: 100
                    },
                    {
                        title: '表格列名称',
                        align: 'left',
                        key: 'ColumnsName',
                        width: 150
                    },
                    {
                        title: '表格列组件类型',
                        align: 'center',
                        key: 'ColumnsComponentsType',
                        width: 200
                    },
                    {
                        title: '表单元素',
                        align: 'center',
                        key: 'Item',
                        width: 100
                    },
                    {
                        title: '表单元素名称',
                        align: 'left',
                        key: 'ItemName',
                        width: 150
                    },
                    {
                        title: '表单中显示',
                        align: 'center',
                        key: 'ItemShow',
                        width: 100
                    },
                    {
                        title: '表单组件类型',
                        align: 'center',
                        key: 'ItemComponentsType',
                        width: 200
                    },
                    // {
                    //     title: '验证规则',
                    //     align: 'left',
                    //     key: 'RuleValidate',
                    //     width: 200
                    // }
                ],
                // 基本配置
                baseConfig: {
                    // 表主键
                    pk: '',
                    // 新增操作
                    add: true,
                    // 编辑操作
                    edit: true,
                    // 删除操作
                    delete: true,
                    // 数据查重
                    check: false,
                    // 查重字段
                    checkField: [],
                    // 后端控制器名称
                    backControllerName: '',
                    // 后端逻辑名称
                    backLogicName: '',
                    // 后端模型名称
                    backModelName: '',
                    // 前端文件模块名称
                    frontModelName: '',
                    // 前端文件组名称
                    frontGroupName: '',
                    // 前端vue文件名称
                    frontVueName: '',
                    // 自动生成菜单
                    autoMenu: true,
                    // 菜单属性
                    menuAttribute: [
                        {
                            name: '',
                            url: ''
                        }
                    ],
                    menuName: '',
                    menuUrl: '',
                },
                ruleValidate: {
                    pk: [{message: '请选择表主键', trigger: 'blur'}]
                },
                // 搜索组件类型下拉列表
                searchSelectList: [
                    {
                        value: 'Default',
                        name: 'Input 输入框'
                    },
                    {
                        value: 'Select',
                        name: 'Select 选择器'
                    },
                    {
                        value: 'DatePicker',
                        name: 'DatePicker 日期选择器'
                    },
                    {
                        value: 'DateRangePicker',
                        name: 'DatePicker 日期范围选择器'
                    }
                ],
                // 表格列组件类型下拉列表
                columnsSelectList: [
                    {
                        value: 'Default',
                        name: 'Default 默认'
                    },
                    {
                        value: 'Img',
                        name: 'Img 图片'
                    },
                    {
                        value: 'Tag',
                        name: 'Tag 标签'
                    },
                    {
                        value: 'Switch',
                        name: 'Switch 开关'
                    }
                ],
                // 表单元素组件类型下拉列表
                itemSelectList: [
                    {
                        value: 'Default',
                        name: 'Input 输入框'
                    },
                    {
                        value: 'TextArea',
                        name: 'TextArea 文本域'
                    },
                    {
                        value: 'Editor',
                        name: 'Editor 富文本'
                    },
                    {
                        value: 'UploadImg',
                        name: 'UploadImg 上传图片'
                    },
                    {
                        value: 'UploadFile',
                        name: 'UploadFile 上传文件'
                    },
                    {
                        value: 'Radio',
                        name: 'Radio 单选框'
                    },
                    {
                        value: 'Checkbox',
                        name: 'Checkbox 多选框'
                    },
                    {
                        value: 'Select',
                        name: 'Select 选择器'
                    },
                    {
                        value: 'Switch',
                        name: 'Switch 开关'
                    },
                    {
                        value: 'DatePicker',
                        name: 'DatePicker 日期选择器'
                    },
                    {
                        value: 'DateRangePicker',
                        name: 'DatePicker 日期范围选择器'
                    },
                    {
                        value: 'TimePicker',
                        name: 'TimePicker 时间选择器'
                    },
                    {
                        value: 'InputNumber',
                        name: 'InputNumber 数字输入框'
                    },
                    {
                        value: 'Slider',
                        name: 'Slider 滑块'
                    }
                ],
                // 搜索列数据
                searchData: [],
                // 搜索组件数据
                searchComponentsData: [],
                // 表格列数据
                columnsData: [],
                // 表格列组件数据
                columnsComponentsData: [],
                // 表单元素列数据
                itemData: [],
                // 表单元素列显示数据
                itemShowData: [],
                // 表单元素列组件数据
                itemComponentsData: [],
                // 初始化重新生成文件弹出框
                modalSetting: {
                    show: false,
                    loading: false
                },
                fileList: [],
            }
        },
        created() {
            this.tableName = this.$route.params.tableName
            this.init()
            this.getList()
        },
        methods: {
            // 初始化页面
            init() {
                let vm = this

                // 初始化默认文件配置
                if (this.tableName.indexOf('_')) {
                    this.baseConfig.backControllerName = this.camelCase(this.tableName.slice(this.tableName.indexOf('_')))
                } else {
                    this.baseConfig.backControllerName = this.camelCase(this.tableName)
                }
                this.baseConfig.backLogicName = this.baseConfig.backControllerName + 'Logic'
                this.baseConfig.backModelName = this.camelCase(this.tableName)
                this.baseConfig.frontModelName = this.tableName
                this.baseConfig.frontGroupName = this.tableName
                this.baseConfig.frontVueName = this.tableName

                // 初始化表格显示
                this.columnsList.forEach(item => {
                    // 作为搜索列
                    if (item.key === 'Search') {
                        item.render = (h, param) => {
                            let currentRowData = vm.tableData[param.index]
                            return h('div', [
                                checkbox(vm, h, currentRowData, param.index, this.searchData, this.searchComponentsData)
                            ])
                        }
                    }
                    // 作为搜索列
                    if (item.key === 'Search') {
                        item.render = (h, param) => {
                            let currentRowData = vm.tableData[param.index]
                            return h('div', [
                                checkbox(vm, h, currentRowData, param.index, this.searchData, this.searchComponentsData)
                            ])
                        }
                    }
                    // 搜索名称列
                    if (item.key === 'SearchName') {
                        item.render = (h, param) => {
                            let currentRowData = vm.tableData[param.index]
                            return h('div', [
                                input(vm, h, currentRowData, param.index, this.searchData)
                            ])
                        }
                    }
                    // 搜索组件类型列
                    if (item.key === 'SearchComponentsType') {
                        item.render = (h, param) => {
                            let currentRowData = vm.tableData[param.index]
                            return h('div', [
                                searchComponentsTypeSelect(vm, h, currentRowData, param.index)
                            ])
                        }
                    }
                    // 作为表格列
                    if (item.key === 'Columns') {
                        item.render = (h, param) => {
                            let currentRowData = vm.tableData[param.index]
                            return h('div', [
                                checkbox(vm, h, currentRowData, param.index, this.columnsData, this.columnsComponentsData)
                            ])
                        }
                    }
                    // 表格列名称列
                    if (item.key === 'ColumnsName') {
                        item.render = (h, param) => {
                            let currentRowData = vm.tableData[param.index]
                            return h('div', [
                                input(vm, h, currentRowData, param.index, this.columnsData)
                            ])
                        }
                    }
                    // 表格列组件类型列
                    if (item.key === 'ColumnsComponentsType') {
                        item.render = (h, param) => {
                            let currentRowData = vm.tableData[param.index]
                            return h('div', [
                                columnsComponentsTypeSelect(vm, h, currentRowData, param.index)
                            ])
                        }
                    }
                    // 表单元素列
                    if (item.key === 'Item') {
                        item.render = (h, param) => {
                            let currentRowData = vm.tableData[param.index]
                            return h('div', [
                                checkbox(vm, h, currentRowData, param.index, this.itemData)
                            ])
                        }
                    }
                    // 表单元素列名称列
                    if (item.key === 'ItemName') {
                        item.render = (h, param) => {
                            let currentRowData = vm.tableData[param.index]
                            return h('div', [
                                input(vm, h, currentRowData, param.index, this.itemData)
                            ])
                        }
                    }
                    // 表单元素显示列
                    if (item.key === 'ItemShow') {
                        item.render = (h, param) => {
                            let currentRowData = vm.tableData[param.index]
                            return h('div', [
                                checkbox(vm, h, currentRowData, param.index, this.itemShowData, this.itemComponentsData)
                            ])
                        }
                    }
                    // 表单元素列组件类型列
                    if (item.key === 'ItemComponentsType') {
                        item.render = (h, param) => {
                            let currentRowData = vm.tableData[param.index]
                            return h('div', [
                                itemComponentsTypeSelect(vm, h, currentRowData, param.index)
                            ])
                        }
                    }
                })
            },
            // 返回上一页
            goBack() {
                this.$router.go(-1)
            },
            // 添加菜单
            addMenu() {
                this.baseConfig.menuAttribute.push({
                    name: '',
                    url: ''
                })
            },
            // 删除菜单
            delMenu(index) {
                this.baseConfig.menuAttribute.splice(index, 1)
            },
            // 获取数据列表
            getList() {
                this.tableLoading = true
                getTableFullFields(this.tableName).then(res => {
                    this.tableLoading = false
                    this.tableData = res.data.data

                    this.tableData.map((item, index) => {
                        // 初始化表主键
                        if (item['Key'] === "PRI") {
                            this.baseConfig.pk = item['Field']
                        }
                        // 初始化搜索数据
                        this.searchData[index] = {
                            key: '',
                            name: ''
                        }
                        // 初始化搜索组件数据
                        this.searchComponentsData[index] = {
                            key: '',
                            name: ''
                        }
                        // 初始化表格列数据
                        this.columnsData[index] = {
                            key: '',
                            name: ''
                        }
                        // 初始化表格组件数据
                        this.columnsComponentsData[index] = {
                            key: '',
                            name: ''
                        }
                        // 初始化表单元素列数据
                        this.itemData[index] = {
                            key: '',
                            name: ''
                        }
                        // 初始化表单显示列数据
                        this.itemShowData[index] = {
                            key: item['Field'],
                            show: false
                        }
                        // 初始化表单列组件数据
                        this.itemComponentsData[index] = {
                            key: '',
                            name: ''
                        }
                    })
                })
            },
            // 设置数据
            setData(data, index, key, name, dataOther = '') {
                this.$set(data, index, {
                    key: key,
                    name: name
                })
                if (data === this.itemShowData) {
                    this.$set(data, index, {
                        key: key,
                        show: true
                    })
                }
                if (dataOther) {
                    this.$set(dataOther, index, {
                        key: key,
                        name: 'Default'
                    })
                }
            },
            // 重置数据
            resetData(data, index, dataOther = '') {
                this.$set(data, index, {
                    key: '',
                    name: ''
                })
                if (data === this.itemShowData) {
                    this.$set(data, index, {
                        key: '',
                        show: false
                    })
                }
                if(data === this.itemData){
                  this.$set(this.itemShowData, index, {
                    key: '',
                    show: false
                  })
                }
                if (dataOther) {
                    this.$set(dataOther, index, {
                        key: '',
                        name: ''
                    })
                }
            },
            // 数据检测
            checkData() {
                if (!this.baseConfig['pk'].trim() ||
                    !this.baseConfig['backControllerName'].trim() ||
                    !this.baseConfig['backLogicName'].trim() ||
                    !this.baseConfig['backModelName'].trim() ||
                    !this.baseConfig['frontModelName'].trim() ||
                    !this.baseConfig['frontGroupName'].trim() ||
                    !this.baseConfig['frontVueName'].trim()
                ) {
                    this.$Message.error('请检查配置信息')
                    return 0
                }
                return 1
            },
            // 数据过滤
            filterData() {
                this.searchData.map((item, index) => {
                    if (item['key'] === '') {
                        this.searchData.splice(index, 1)
                    }
                })
            },
            // 一键生成
            oneTouch() {
                // 生成前数据检测
                if (!this.checkData()) {
                    return
                }
                //一键生成
                oneTouch(
                    this.tableName,
                    this.baseConfig,
                    this.searchData,
                    this.searchComponentsData,
                    this.columnsData,
                    this.columnsComponentsData,
                    this.itemData,
                    this.itemShowData,
                    this.itemComponentsData,
                    this.fileList).then(res => {
                    if (res.data.code === 1) {
                        // 生成成功
                        this.$Message.success('生成成功')
                    } else if (res.data.code === 0) {
                        // 生成失败
                        this.$Message.error(res.data.msg)
                        this.modalSetting.show = true
                        this.fileList = res.data.data
                    }
                })
            },
            // 提交表单
            submit() {
                oneTouch(
                    this.tableName,
                    this.baseConfig,
                    this.searchData,
                    this.searchComponentsData,
                    this.columnsData,
                    this.columnsComponentsData,
                    this.itemData,
                    this.itemShowData,
                    this.itemComponentsData,
                    this.fileList).then(res => {
                    if (res.data.code === 1) {
                        // 生成成功
                        this.$Message.success('生成成功')
                        this.modalSetting.show = false
                        this.fileList = []
                    } else if (res.data.code === 0) {
                        // 生成失败
                        this.$Message.error(res.data.msg)
                        this.fileList = res.data.data
                    } else if (res.data.code === -2) {
                        this.$Message.error(res.data.msg)
                    }
                })
            },
            // 表单取消
            cancel() {
                this.modalSetting.show = false
                this.fileList = []
            },
            // 下划线转驼峰
            camelCase(string) {
                string = string.replace(/_([a-z])/g, function (all, letter) {
                    return letter.toUpperCase()
                })
                return string.charAt(0).toUpperCase() + string.slice(1)
            }
        }
    }
</script>

<style scoped>

</style>
