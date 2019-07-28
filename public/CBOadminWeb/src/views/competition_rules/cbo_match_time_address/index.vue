<template>
    <div>
        <Row>
            <Col span="24">
                <Card style="margin-bottom: 10px">
                    <!--搜索表单-->
                    <Form inline>
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
                <FormItem label="比赛类型" prop="match_type">
                    <Input v-model="formItem.match_type" disabled placeholder="比赛类型"/>
                </FormItem>
                <FormItem label="比赛时间" prop="match_time">
                    <Input type="textarea" :rows="4" v-model="formItem.match_time" placeholder="比赛时间"/>
                </FormItem>
                <FormItem label="比赛地点" prop="match_address">
                    <Select v-model="formItem.match_address" multiple>
                        <Option v-for="(province, index) in provinceList" :value="province.name">{{province.name}}</Option>
                    </Select>
                </FormItem>
            </Form>
            <div slot="footer">
                <Button type="text" @click="cancel" style="margin-right: 8px">取消</Button>
                <Button type="primary" @click="submit" :loading="modalSetting.loading">确定</Button>
            </div>
        </Modal>
    </div>
</template>

<script>
    import {getDataList, getProvinceList, coruData} from '@/api/cbo_match_time_address_index'

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
                    vm.formItem.match_type = currentRow.match_type;
                    vm.formItem.match_time = currentRow.match_time;
                    if(currentRow.match_address){
                        vm.formItem.match_address = currentRow.match_address.split(',');
                    }
                    vm.modalSetting.show = true
                    vm.modalSetting.index = index
                }
            }
        }, '编辑')
    }

    export default {
        name: 'index',
        components: {},
        data() {
            return {
                // 初始化表格列
                columnsList: [{title: "序号", type: "index", align: "center", width: "60"}, {
                    title: "比赛类型",
                    key: "match_type",
                    align: "center", width: "150"
                }, {title: "比赛时间", key: "match_time", align: "center"}, {
                    title: "比赛地点",
                    key: "match_address",
                    align: "center"
                }, {title: "操作", key: "handle", align: "center", width: "200", handle: ["edit"]}],
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
                searchConf: {},
                // 表单属性
                formItem: {id: "", match_type: "", match_time: "", match_address: []},
                // modal属性
                modalSetting: {
                    show: false,
                    loading: false,
                    index: 0
                },
                // 表单验证
                ruleValidate: {},
                // 省数据列表
                provinceList: {}
            }
        },
        created() {
            this.init()
            this.getList()
            this.getProvinceList()
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
                                editButton(vm, h, currentRowData, param.index)
                            ])
                        }
                    }
                    if (item.key === 'match_type') {
                        item.render = (h, param) => {
                            let currentRowData = vm.tableData[param.index];
                            return h('Tag', {
                                attrs: {
                                    color: 'blue'
                                }
                            }, currentRowData.match_type);
                        };
                    }
                })
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
            },
            // 获取省数据列表
            getProvinceList() {
                getProvinceList().then(res => {
                    this.provinceList = res.data.data
                })
            }
        }
    }
</script>

<style scoped>
</style>
<style>
</style>
