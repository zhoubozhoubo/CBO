<template>
    <div>
        <Row>
            <Col span="24">
            <Card style="margin-bottom: 10px">
                <!--搜索表单-->
                <Form inline>
                                        <FormItem style="margin-bottom: 0">
                        <Input v-model="searchConf.file_name" clearable placeholder="文件名称"></Input>
                    </FormItem>
                                        <FormItem style="margin-bottom: 0">
                    <DatePicker type="daterange" @on-change="searchConf.date=$event" placeholder="选择日期范围" style="width: 200px"></DatePicker>
                    </FormItem>
                                        <FormItem style="margin-bottom: 0">
                        <Button type="primary" shape="circle" icon="ios-search" @click="search">查询/刷新</Button>
                    </FormItem>
                </Form>
            </Card>
            </Col>
        </Row>
        <Row>
            <Col span="24">
            <Card>
                                <p slot="title" style="height: 40px">
                    <Button type="primary" @click="alertAdd" icon="md-add">新增</Button>
                </p>
                                <div>
                    <Table :loading="tableShow.loading" :columns="columnsList" :data="tableData" border disabled-hover></Table>
                </div>
                <div style="text-align: center;margin-top: 15px">
                    <Page :total="tableShow.listCount" :current="tableShow.currentPage"
                          :page-size="tableShow.pageSize" @on-change="changePage"
                          @on-page-size-change="changeSize" show-elevator show-sizer show-total></Page>
                </div>
            </Card>
            </Col>
        </Row>
        <!--新增、编辑Modal-->
        <Modal v-model="modalSetting.show" width="700" :styles="{top: '30px'}" @on-visible-change="doCancel">
            <p slot="header" style="color:#2d8cf0;">
                <Icon type="md-information-circle"></Icon>
                <span>{{formItem.id ? '编辑' : '新增'}}</span>
            </p>
            <Form ref="myForm" :rules="ruleValidate" :model="formItem" :label-width="100">
                                <FormItem label="文件名称" prop="file_name">
                                        <Input v-model="formItem.file_name" placeholder="文件名称"/>
                                    </FormItem>
                                <FormItem label="上传文件" prop="file">
                                    <input v-if="formItem.file" v-model="formItem.file" type="hidden" name="file">
                                        <Upload :action="uploadUrl"
                            :headers="uploadHeader"
                            :max-size="5120"
                            :default-file-list="formItem.file"
                            :on-success="handleFileSuccess"
                            :on-remove="handleFileRemove"
                            :on-exceeded-size="handleFileMaxSize">
                        <Button icon="ios-cloud-upload-outline">上传文件</Button>
                    </Upload>
                                    </FormItem>
                                <FormItem label="文件日期" prop="date">
                                        <DatePicker type="date" @on-change="formItem.date=$event" placeholder="选择日期" style="width: 200px"></DatePicker>
                                    </FormItem>
                            </Form>
            <div slot="footer">
                <Button type="text" @click="cancel" style="margin-right: 8px">取消</Button>
                <Button type="primary" @click="submit" :loading="modalSetting.loading">确定</Button>
            </div>
        </Modal>
        <!--查看大图-->
        <Modal v-model="modalSeeingImg.show"
               footer-hide
               class-name="fl-image-modal">
            <img :src="modalSeeingImg.img" style="width: 100%">
        </Modal>
    </div>
</template>

<script>
    import config from '../../../../build/config';
    import {getDataList,coruData} from '@/api/cbo_files_list'
        const editButton = (vm, h, currentRow, index) => {
        return h('Button', {
            props: {
                type: 'primary'
            },
            style: {
                margin: '0 5px'
            },
            on: {
                'click': () => {
                                        vm.formItem.id = currentRow.id;
                                        vm.formItem.file_name = currentRow.file_name;
                                        vm.formItem.file_url = currentRow.file_url;
                                        vm.formItem.date = currentRow.date;
                                        vm.modalSetting.show = true
                    vm.modalSetting.index = index
                }
            }
        }, '编辑')
    }
        const deleteButton = (vm, h, currentRow, index) => {
        return h('Poptip', {
            props: {
                confirm: true,
                title: '您确定要删除这条数据吗? ',
                transfer: true
            },
            on: {
                'on-ok': () => {
                    coruData({id: currentRow.id, is_delete: 1}).then(res => {
                        if (res.data.code === 1) {
                            vm.tableData.splice(index, 1)
                            vm.$Message.success(res.data.msg)
                        } else {
                            vm.$Message.error(res.data.msg)
                        }
                    }, err => {
                        vm.$Message.error(err.data.msg)
                    })
                }
            }
        }, [
            h('Button', {
                style: {
                    margin: '0 5px'
                },
                props: {
                    type: 'error',
                    placement: 'top',
                }
            }, '删除')
        ])
    }
    
    export default {
        name: 'list',
        components: {
        },
        data() {
            return {
                // 初始化表格列
                columnsList:[{title:"文件id",key:"id",align:"center"},{title:"文件名称",key:"file_name",align:"center"},{title:"文件地址",key:"file_url",align:"center"},{title:"文件日期",key:"date",align:"center"},{title:"操作",key:"handle",align:"center",handle:["edit","delete"]}],
                // 表格数据
                tableData: [],
                // 表格显示分页属性
                tableShow: {
                    loading: true,
                    currentPage: 1,
                    pageSize: 10,
                    listCount: 0
                },
                // 搜索配置
                                searchConf:{file_name:"",date:""},
                                // 表单属性
                                formItem:{id:"",file: [],file_name:"",file_url:"",date:""},
                                // modal属性
                modalSetting: {
                    show: false,
                    loading: false,
                    index: 0
                },
                // 图片modal
                modalSeeingImg: {
                    img: '',
                    show: false
                },
                uploadUrl: '',
                uploadHeader: {},
                                // 表单验证
                ruleValidate:{}
            }
        },
        created() {
            this.init()
            this.getList()
            this.uploadUrl = config.baseUrl + 'Index/upload';
            this.uploadHeader = {'ApiAuth': sessionStorage.getItem('apiAuth')};
                    },
        methods: {
            // 页面初始化
            init() {
                let vm = this
                this.columnsList.forEach(item => {
                    if (item.key === 'handle') {
                        item.render = (h, param) => {
                            let currentRowData = vm.tableData[param.index]
                                                        return h('div', [
                                editButton(vm, h, currentRowData, param.index),
                                deleteButton(vm, h, currentRowData, param.index)
                            ])
                                                    }
                    }
                                    })
            },
                        // 新增
            alertAdd() {
                this.formItem.id = 0
                this.modalSetting.show = true
            },
                        // 文件上传一系列
            handleFileRemove() {
                this.formItem.file = [];
            },
            handleFileMaxSize(file) {
                this.$Notice.warning({
                    title: '文件大小不合法',
                    desc: file.name + '太大啦请上传小于5M的文件。'
                });
            },
            handleFileSuccess(response) {
                if (response.code === 1) {
                    this.$Message.success(response.msg);
                    let file = { 'name': '', 'url': response.data.fileUrl }
                    this.formItem.file.push(file)
                } else {
                    this.$Message.error(response.msg);
                }
            },
                        // 提交
            submit() {
                this.$refs['myForm'].validate((valid) => {
                    if (valid) {
                        this.modalSetting.loading = true
                        coruData(this.formItem).then(res => {
                            if (res.data.code === 1) {
                                this.$Message.success(res.data.msg)
                                this.getList()
                                this.cancel()
                            } else {
                                this.$Message.error(res.data.msg)
                            }
                            this.modalSetting.loading = false
                        })
                    }
                })
            },
            // 取消表单显示
            cancel() {
                this.modalSetting.show = false
            },
            // 取消表单数据
            doCancel(data) {
                if (!data) {
                    this.formItem.id = 0
                    this.$refs['myForm'].resetFields()
                    this.modalSetting.loading = false
                    this.modalSetting.index = 0
                }
            },
            // 数据分页一系列
            changePage(page) {
                this.tableShow.currentPage = page
                this.getList()
            },
            changeSize(size) {
                this.tableShow.pageSize = size
                this.getList()
            },
            // 搜索
            search() {
                this.tableShow.currentPage = 1
                this.getList()
            },
            // 获取数据列表
            getList() {
                this.tableShow.loading = true;
                getDataList(this.tableShow, this.searchConf).then(res => {
                    this.tableData = res.data.data.list
                    this.tableShow.listCount = res.data.data.count
                    this.tableShow.loading = false;
                })
            }
        }
    }
</script>

<style scoped>
</style>
<style>
</style>
