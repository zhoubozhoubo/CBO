<template>
    <div>
        <Row>
            <Col span="24">
                <Card style="margin-bottom: 10px; height: 80px;">
                    <!--搜索表单-->
                    <Form inline  ref="searchForm" :rules="searchRuleValidate" :model="searchConf">
                        <FormItem style="margin-bottom: 0" prop="season_id">
                            <Select v-model="searchConf.season_id" clearable placeholder='赛季' @on-change="changeCompetitionSeason" @on-clear="clearCompetitionSeason" style="width:100px">
                                <Option v-for="(item, index) in competitionSeasonList" :value="item.seasonId">{{item.seasonName}}</Option>
                            </Select>
                        </FormItem>
                        <FormItem style="margin-bottom: 0" prop="stage_id">
                            <Select v-model="searchConf.stage_id" clearable :disabled="searchConf.season_id == ''" placeholder='赛季阶段' @on-change="changeSeasonStage" @on-clear="clearSeasonStage" style="width:100px">
                                <Option v-for="(item, index) in seasonStageList" :value="item.stageId">{{item.stageName}}</Option>
                            </Select>
                        </FormItem>
                        <FormItem style="margin-bottom: 0" prop="province_id">
                            <Select v-model="searchConf.province_id" clearable :disabled="searchConf.stage_id == ''" placeholder='省' @on-change="changeArea" @on-clear="clearArea" style="width:100px">
                                <Option v-for="(item, index) in areaList" :value="item.areaId">{{item.areaName}}</Option>
                            </Select>
                        </FormItem>
                        <FormItem style="margin-bottom: 0" prop="city_id">
                            <Select v-model="searchConf.city_id" clearable :disabled="searchConf.province_id == ''" placeholder='市' @on-change="changeCity" style="width:100px">
                                <Option v-for="(item, index) in cityList" :value="item.areaId">{{item.areaName}}</Option>
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
                <!--<FormItem label="赛季" prop="season_id">
                    <Select v-model="formItem.season_id" disabled style="width:200px">
                        <Option v-for="(item, index) in competitionSeasonList" :value="item.seasonId">{{item.seasonName}}</Option>
                    </Select>
                </FormItem>
                <FormItem label="赛季阶段" prop="stage_id">
                    <Select v-model="formItem.stage_id" disabled style="width:200px">
                        <Option v-for="(item, index) in seasonStageList" :value="item.stageId">{{item.stageName}}</Option>
                    </Select>
                </FormItem>
                <FormItem label="省" prop="province_id">
                    <Select v-model="formItem.province_id" disabled style="width:200px">
                        <Option v-for="(item, index) in areaList" :value="item.areaId">{{item.areaName}}</Option>
                    </Select>
                </FormItem>
                <FormItem label="市" prop="city_id">
                    <Select v-model="formItem.city_id" disabled style="width:200px">
                        <Option v-for="(item, index) in cityList" :value="item.areaId">{{item.areaName}}</Option>
                    </Select>
                </FormItem>-->
                <FormItem label="球队战况图片" prop="img">
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
    import {getDataList, coruData, getCompetitionSeason, getSeasonStage, getArea} from '@/api/cbo_team_situation_img_list'

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
                    vm.formItem.season_id = currentRow.season_id;
                    vm.formItem.stage_id = currentRow.stage_id;
                    vm.formItem.province_id = currentRow.province_id;
                    vm.formItem.city_id = currentRow.city_id;
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
        components: {},
        data() {
            return {
                // 初始化表格列
                columnsList: [{title: "序号", type: "index", align: "center", width: "60"},
                //     {
                //     title: "赛季id",
                //     key: "season_id",
                //     align: "center"
                // }, {title: "赛季阶段id", key: "stage_id", align: "center"}, {
                //     title: "省id",
                //     key: "province_id",
                //     align: "center"
                // }, {title: "市id", key: "city_id", align: "center"},
                    {
                    title: "球队战况图片",
                    key: "img",
                    align: "center"
                },
                    {title: "添加时间", key: "gmt_create", align: "center"},
                    {title: "最后更新时间", key: "gmt_modified", align: "center"},
                    {title: "操作", key: "handle", align: "center", width: "200", handle: ["edit", "delete"]}],
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
                searchConf: {season_id: "", stage_id: "", province_id: "", city_id: ""},
                // 表单属性
                formItem: {id: "", season_id: "", stage_id: "", province_id: "", city_id: "", img: ""},
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
                ruleValidate: {
                    img: [{ required: true, message: "请上传封面", trigger: "change" }],
                },
                // 搜索表单验证
                searchRuleValidate: {
                    season_id: [{required: true, message: "请选择赛季", trigger: "change"}],
                    stage_id: [{required: true, message: "请选择赛季阶段", trigger: "change"}],
                    province_id: [{required: true, message: "请选择省", trigger: "change"}],
                    city_id: [{required: true, message: "请选择市", trigger: "change"}]
                },
                // 赛季数据
                competitionSeasonList: {},
                // 赛季阶段数据
                seasonStageList: {},
                // 赛区数据
                areaList: {},
                // 赛区市数据
                cityList: {},
                seasonId: '',
                stageId: '',
                areaId: '',
                cityId: ''
            }
        },
        created() {
            this.init()
            this.getList()
            this.getCompetitionSeason()
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
                                return h('a', {
                                    attrs: {
                                        href: currentRowData.img,
                                        target: '_black'
                                    }
                                }, '查看图片')
                            } else {
                                return h('Tag', {}, '暂无图片');
                            }
                        };
                    }
                })
            },
            // 新增
            alertAdd() {
                this.$refs['searchForm'].validate((valid) => {
                    if (valid) {
                        this.formItem.id = 0
                        this.modalSetting.show = true
                    }
                })
            },
            // 图片上传一系列
            handleView() {
                this.modalSeeingImg.show = true;
                this.modalSeeingImg.img = this.formItem.img;
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
                // if (!data) {
                //     this.formItem.id = 0
                //     this.$refs['myForm'].resetFields()
                //     this.modalSetting.loading = false
                //     this.modalSetting.index = 0
                // }
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
                this.$refs['searchForm'].validate((valid) => {
                    if (valid) {
                        this.tableShow.currentPage = 1
                        this.getList()
                    }
                })
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
            // 获取赛季
            getCompetitionSeason() {
                getCompetitionSeason().then(res => {
                    this.competitionSeasonList = res.data.data
                })
            },
            // 改变赛季
            changeCompetitionSeason(seasonId){
                this.seasonId = seasonId
                this.searchConf.season_id = seasonId
                this.formItem.season_id = seasonId
                this.getSeasonStage(seasonId)
            },
            // 清空赛季
            clearCompetitionSeason(){
                this.seasonId = ''
                this.searchConf.season_id = ''
                this.formItem.season_id = ''
                this.clearSeasonStage()
            },
            // 获取赛季阶段
            getSeasonStage(seasonId) {
                getSeasonStage(seasonId).then(res => {
                    this.seasonStageList = res.data.data
                })
            },
            // 改变赛季阶段
            changeSeasonStage(stageId){
                this.stageId = stageId
                this.searchConf.stage_id = stageId
                this.formItem.stage_id = stageId
                this.getArea(stageId)
            },
            // 清空赛季阶段
            clearSeasonStage(){
                this.stageId = ''
                this.searchConf.stage_id = ''
                this.formItem.stage_id = ''
                this.clearArea()
            },
            // 获取赛区
            getArea(stageId) {
                getArea(this.seasonId, stageId).then(res => {
                    this.areaList = res.data.data
                })
            },
            // 改变赛区
            changeArea(areaId){
                this.areaId = areaId
                this.searchConf.province_id = areaId
                this.formItem.province_id = areaId
                this.areaList.map((item) => {
                    if(item.areaId === areaId){
                        this.cityList = item.childList
                    }
                })
            },
            // 清空赛区
            clearArea(){
                this.areaId = ''
                this.searchConf.province_id = ''
                this.formItem.province_id = ''
                this.clearCity()
            },
            // 改变赛区市
            changeCity(cityId){
                this.cityId = cityId
                this.searchConf.city_id = cityId
                this.formItem.city_id = cityId
            },
            // 清空赛区市
            clearCity(){
                this.cityId = ''
                this.searchConf.city_id = ''
                this.formItem.city_id = ''
            },
        }
    }
</script>

<style scoped>
    .demo-upload-list {
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
        box-shadow: 0 1px 1px rgba(0, 0, 0, .2);
        margin-right: 4px;
    }

    .demo-upload-list img {
        width: 100%;
        height: 100%;
    }

    .demo-upload-list-cover {
        display: none;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, .6);
    }

    .demo-upload-list:hover .demo-upload-list-cover {
        display: block;
    }

    .demo-upload-list-cover i {
        color: #fff;
        font-size: 20px;
        cursor: pointer;
        margin: 0 2px;
    }
</style>
<style>
</style>
