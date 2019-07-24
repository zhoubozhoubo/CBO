<template>
    <div>
        <Row>
            <Col span="24">
                <Card style="margin-bottom: 10px">
                    <!--搜索表单-->
                    <Form inline>
                        <FormItem style="margin-bottom: 0">
                            <Select v-model="searchConf.type" clearable placeholder='资格类型' style="width:100px">
                                <Option value="1">城市赛</Option>
                                <Option value="2">女子冠军赛</Option>
                            </Select>
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
                        <Table :loading="tableShow.loading" :columns="columnsList" :data="tableData" border
                               disabled-hover></Table>
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
                <FormItem label="资格类型" prop="type">
                    <Select v-model="formItem.type" style="width:200px">
                        <Option value="1">城市赛</Option>
                        <Option value="2">女子冠军赛</Option>
                    </Select>
                </FormItem>
                <FormItem label="资格内容" prop="content">
                    <Input type="textarea" :rows="4" v-model="formItem.content" placeholder="资格内容"/>
                </FormItem>
                <FormItem label="排序" prop="sort">
                    <InputNumber :min="0" v-model="formItem.sort"></InputNumber>
                    <Tag color="error" style="margin-left:5px">数字越小越靠前</Tag>
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
    import {getDataList, coruData} from '@/api/cbo_eligibility_index'

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
                    vm.formItem.type = String(currentRow.type);
                    vm.formItem.content = currentRow.content;
                    vm.formItem.sort = currentRow.sort;
                    vm.modalSetting.show = true
                    vm.modalSetting.index = index
                    console.log(vm.formItem)
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
        name: 'idnex',
        components: {},
        data() {
            return {
                // 初始化表格列
                columnsList: [{title: "数据id", key: "id", align: "center"}, {
                    title: "资格类型",
                    key: "type",
                    align: "center"
                }, {title: "资格内容", key: "content", align: "center"}, {title: "排序", key: "sort", align: "center"}, {
                    title: "操作",
                    key: "handle",
                    align: "center",
                    handle: ["edit", "delete"]
                }],
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
                searchConf: {type: ""},
                // 表单属性
                formItem: {id: "", type: "", content: "", sort: 0},
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
                // 表单验证
                ruleValidate: {}
            }
        },
        created() {
            this.init()
            this.getList()
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
                    if (item.key === 'type') {
                        item.render = (h, param) => {
                            let currentRowData = vm.tableData[param.index];
                            switch (currentRowData.type) {
                                case 1:
                                    return h('Tag', {
                                        attrs: {
                                            color: 'blue'
                                        }
                                    }, '城市赛');
                                    break
                                case 2:
                                    return h('Tag', {
                                        attrs: {
                                            color: 'red'
                                        }
                                    }, '女子冠军赛');
                                    break
                            }
                        }
                    }
                    if (item.key === 'sort') {
                        item.render = (h, param) => {
                            let currentRowData = vm.tableData[param.index];
                            return h('Tag', {
                                attrs: {
                                    color: 'success'
                                }
                            }, currentRowData.sort);
                        }
                    }
                })
            },
            // 新增
            alertAdd() {
                this.formItem.id = 0
                this.modalSetting.show = true
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
