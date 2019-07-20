<template>
    <div>
        <Row>
            <Col span="24">
            <Card style="margin-bottom: 10px">
                <!--搜索表单-->
                <Form inline>
                                        <FormItem style="margin-bottom: 0">
                        <Input v-model="searchConf.title" clearable placeholder="图集标题"></Input>
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
                                <FormItem label="图集标题" prop="title">
                                        <Input v-model="formItem.title" placeholder="图集标题"/>
                                    </FormItem>
                                <FormItem label="图集图片" prop="img">
                                        <div class="demo-upload-list" v-if="formItem.img">
                        <img :src="formItem.img">
                        <div class="demo-upload-list-cover">
                            <Icon type="ios-eye-outline" @click.native="handleView()"></Icon>
                            <Icon type="ios-trash-outline" @click.native="handleImgRemove()"></Icon>
                        </div>
                    </div>
                    <input v-if="formItem.img" v-model="formItem.img" type="hidden" name="image">
                    <Upload type="drag"
                            v-if="!formItem.img"
                            :action="uploadUrl"
                            :headers="uploadHeader"
                            :format="['jpg','jpeg','png']"
                            :max-size="5120"
                            :on-success="handleImgSuccess"
                            :on-format-error="handleImgFormatError"
                            :on-exceeded-size="handleImgMaxSize"
                            style="display: inline-block;width:58px;">
                        <div style="width: 58px;height:58px;line-height: 58px;">
                            <Icon type="ios-camera" size="20"></Icon>
                        </div>
                    </Upload>
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
    import {getDataList,coruData} from '@/api/cbo_wonderful_img_list'
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
                                        vm.formItem.title = currentRow.title;
                                        vm.formItem.img = currentRow.img;
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
                columnsList:[{title:"图集id",key:"id",align:"center"},{title:"图集标题",key:"title",align:"center"},{title:"图集图片",key:"img",align:"center"},{title:"操作",key:"handle",align:"center",handle:["edit","delete"]}],
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
                                searchConf:{title:""},
                                // 表单属性
                                formItem:{id:"",title:"",img:""},
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
                                            if (item.key === 'img') {
                            item.render = (h, param) => {
                                let currentRowData = vm.tableData[param.index];
                                if (currentRowData.img) {
                                    return h('img', {
                                        style: {
                                            width: '40px',
                                            height: '40px',
                                            cursor: 'pointer',
                                            margin: '5px 0'
                                        },
                                        attrs: {
                                            src: currentRowData.img,
                                            shape: 'square',
                                            size: 'large'
                                        },
                                        on: {
                                            click: (e) => {
                                                vm.modalSeeingImg.img = currentRowData.img;
                                                vm.modalSeeingImg.show = true;
                                            }
                                        }
                                    });
                                } else {
                                    return h('Tag', {}, '暂无图片');
                                }
                            };
                        }
                                    })
            },
                        // 新增
            alertAdd() {
                this.formItem.id = 0
                this.modalSetting.show = true
            },
                        // 图片上传一系列
            handleView() {
                this.visible = true;
            },
            handleImgRemove() {
                this.formItem.img = '';
            },
            handleImgFormatError(file) {
                this.$Notice.warning({
                    title: '文件类型不合法',
                    desc: file.name + '的文件类型不正确，请上传jpg或者png图片。'
                });
            },
            handleImgMaxSize(file) {
                this.$Notice.warning({
                    title: '文件大小不合法',
                    desc: file.name + '太大啦请上传小于5M的文件。'
                });
            },
            handleImgSuccess(response) {
                if (response.code === 1) {
                    this.$Message.success(response.msg);
                    this.formItem.img = response.data.fileUrl;
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
.demo-upload-list{
    display: inline-block;
    width: 60px;
    height: 60px;
    text-align: center;
    line-height: 60px;
    border: 1px solid transparent;
    border-radius: 4px;
    overflow: hidden;
    background: #fff;
    position: relative;
    box-shadow: 0 1px 1px rgba(0,0,0,.2);
    margin-right: 4px;
}
.demo-upload-list img{
    width: 100%;
    height: 100%;
}
.demo-upload-list-cover{
    display: none;
    position: absolute;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(0,0,0,.6);
}
.demo-upload-list:hover .demo-upload-list-cover{
    display: block;
}
.demo-upload-list-cover i{
    color: #fff;
    font-size: 20px;
    cursor: pointer;
    margin: 0 2px;
}
</style>
<style>
</style>
