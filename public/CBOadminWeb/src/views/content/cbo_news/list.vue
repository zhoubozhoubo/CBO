<template>
    <div>
        <Row>
            <Col span="24">
                <Card style="margin-bottom: 10px">
                    <!--搜索表单-->
                    <Form inline>
                        <FormItem style="margin-bottom: 0">
                            <Input v-model="searchConf.title" clearable placeholder="新闻标题"></Input>
                        </FormItem>
                        <FormItem style="margin-bottom: 0">
                            <DatePicker type="daterange" @on-change="searchConf.date=$event" placeholder="选择日期范围"
                                        style="width: 200px"></DatePicker>
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
        <Modal fullscreen v-model="modalSetting.show" width="700" style="z-index: 999" @on-visible-change="doCancel">
            <p slot="header" style="color:#2d8cf0;">
                <Icon type="md-information-circle"></Icon>
                <span>{{formItem.id ? '编辑' : '新增'}}</span>
            </p>
            <Form ref="myForm" :rules="ruleValidate" :model="formItem" :label-width="100">
                <FormItem label="新闻标题" prop="title">
                    <Input v-model="formItem.title" placeholder="新闻标题"/>
                </FormItem>
                <FormItem label="新闻封面" prop="img">
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
                <!--<FormItem label="新闻内容" prop="content">-->
                    <!--<Upload-->
                            <!--id="iviewUp"-->
                            <!--ref="upload"-->
                            <!--:show-upload-list="false"-->
                            <!--:on-success="handleSingleSuccess"-->
                            <!--:format="['jpg','jpeg','png']"-->
                            <!--:max-size="5120"-->
                            <!--:on-format-error="handleFormatError"-->
                            <!--:before-upload="handleBeforeUpload()"-->
                            <!--type="drag"-->
                            <!--:action="uploadUrl"-->
                            <!--:headers="uploadHeader"-->
                            <!--style="display: none;width:0">-->
                        <!--<div style="width: 0">-->
                            <!--<Icon type="ios-camera" size="50"></Icon>-->
                        <!--</div>-->
                    <!--</Upload>-->
                    <!--<quill-editor-->
                            <!--v-model="formItem.content"-->
                            <!--ref="myQuillEditor"-->
                            <!--:options="editorOption"-->
                            <!--@blur="onEditorBlur($event)"-->
                            <!--@focus="onEditorFocus($event)"-->
                            <!--@change="onEditorChange($event)">-->
                    <!--</quill-editor>-->
                <!--</FormItem>-->
                <FormItem label="新闻内容" prop="content">
                    <VueUEditor ueditorPath="/static/Ue/" :ueditorConfig="editorConfig" @ready="onEditorReady"></VueUEditor>
                </FormItem>
                <FormItem label="新闻作者" prop="author">
                    <Input v-model="formItem.author" placeholder="新闻作者"/>
                </FormItem>
                <FormItem label="新闻日期" prop="date">
                    <DatePicker type="date" @on-change="formItem.date=$event" :value="formItem.date" placeholder="选择日期"
                                style="width: 200px"></DatePicker>
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
        <!--查看详情-->
        <Modal v-model="modalSeeingCon.show"
               :title="modalSeeingCon.title"
               footer-hide
               width="1200"
               :styles="{top: '30px'}">
            <div class="ql-container ql-snow">
                <div class="ql-editor ql-editor_big">
                    <div v-html="modalSeeingCon.content"></div>
                </div>
            </div>
        </Modal>
    </div>
</template>

<script>
    import config from '../../../../build/config';
    import {getDataList, coruData} from '@/api/cbo_news_list'
    import {quillEditor} from 'vue-quill-editor';
    import VueUEditor from 'vue-ueditor';

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
                    vm.formItem.content = currentRow.content;
                    vm.editor.setContent(vm.formItem.content);
                    vm.formItem.author = currentRow.author;
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
    const detailsButton = (vm, h, currentRow, index) => {
        return h('Button', {
            props: {
                type: 'success',
                size: 'small'
            },
            style: {
                margin: '0 5px'
            },
            on: {
                'click': () => {
                    vm.modalSeeingCon.title = currentRow.title;
                    vm.modalSeeingCon.content = currentRow.content;
                    vm.modalSeeingCon.show = true;
                }
            }
        }, '查看');
    };

    export default {
        name: 'list',
        components: {
            VueUEditor
        },
        data() {
            return {
                editor: '',
                editorConfig: {
                    // 编辑器不自动被内容撑高
                    autoHeightEnabled: false,
                    // 初始容器高度
                    initialFrameHeight: 400,
                    // 初始容器宽度
                    initialFrameWidth: '100%',
                    // 上传文件接口（这个地址是我为了方便各位体验文件上传功能搭建的临时接口，请勿在生产环境使用！！！）
                    serverUrl: 'http://localhost:8003/controller.php',
                    // UEditor 资源文件的存放路径，如果你使用的是 vue-cli 生成的项目，通常不需要设置该选项，vue-ueditor-wrap 会自动处理常见的情况，如果需要特殊配置，参考下方的常见问题2
                    UEDITOR_HOME_URL: '/static/Ue/',
                    zIndex: 9999
                },
                // 初始化表格列
                columnsList: [{title: "序号", type: "index", align: "center", width: "60"}, {
                    title: "新闻标题",
                    key: "title",
                    align: "center"
                }, {title: "新闻封面", key: "img", align: "center", width: "100"}, {
                    title: "新闻内容",
                    key: "content",
                    align: "center", width: "100"
                }, {title: "新闻作者", key: "author", align: "center", width: "150"}, {title: "新闻日期", key: "date", align: "center", width: "150"}, {
                    title: "操作",
                    key: "handle",
                    align: "center", width: "200",
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
                searchConf: {title: "", date: ""},
                // 表单属性
                formItem: {id: "", title: "", img: "", content: "", author: "", date: ""},
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
                // 详情modal
                modalSeeingCon: {
                    title: '',
                    content: '',
                    show: false
                },
                uploadUrl: '',
                uploadHeader: {},
                editorOption: {
                    modules: {
                        toolbar: {
                            container: [
                                [{'size': ['small', false, 'large', 'huge']}],
                                [{'header': [1, 2, 3, 4, 5, 6, false]}],
                                ['bold', 'italic', 'underline', 'strike', 'blockquote', 'clean'],
                                [{'header': 1}, {'header': 2}],
                                [{'list': 'ordered'}, {'list': 'bullet'}],
                                [{'script': 'sub'}, {'script': 'super'}],
                                [{'align': []}],
                                [{'color': []}, {'background': []}],
                                ['image']
                            ],
                            handlers: {
                                'image': function (value) {
                                    if (value) {
                                        document.querySelector('#iviewUp input').click();
                                    } else {
                                        this.quill.format('image', false);
                                    }
                                }
                            }
                        }
                    }
                },
                // 表单验证
                ruleValidate: {
                    title: [{ required: true, message: "请输入标题", trigger: "blur" }],
                    img: [{ required: true, message: "请上传封面", trigger: "change" }],
                    author: [{ required: true, message: "请输入作者", trigger: "blur" }],
                    date: [{ required: true, message: "请输入日期", trigger: "blur" }],
                },
            }
        },
        created() {
            this.init()
            this.getList()
            this.uploadUrl = config.baseUrl + 'Index/upload';
            this.uploadHeader = {'ApiAuth': sessionStorage.getItem('apiAuth')};
        },
        mounted() {
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
                    if (item.key === 'content') {
                        item.render = (h, param) => {
                            let currentRowData = vm.tableData[param.index];
                            return h('div', [
                                detailsButton(vm, h, currentRowData, param.index)
                            ]);
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
                this.modalSeeingImg.show = true;
                this.modalSeeingImg.img = this.formItem.img;
            },
            handleImgSuccess(response) {
                if (response.code === 1) {
                    this.$Message.success(response.msg);
                    this.formItem.img = response.data.fileUrl;
                } else {
                    this.$Message.error(response.msg);
                }
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
            // 富文本编辑器一系列
            handleSingleSuccess(res, file) {
                // 获取富文本组件实例
                let vm = this
                let quill = this.$refs.myQuillEditor.quill
                // 如果上传成功
                if (res.code === 1) {
                    // 获取光标所在位置
                    let length = quill.getSelection().index;
                    // 插入图片  res.info为服务器返回的图片地址
                    quill.insertEmbed(length, 'image', res.data.fileUrl)
                    // 调整光标到最后
                    quill.setSelection(length + 1);
                } else {
                    vm.$Message.error('图片插入失败');
                }
            },
            handleFormatError() {
            },
            handleBeforeUpload() {
            },
            onEditorBlur() {
            },
            onEditorFocus() {
            },
            onEditorChange() {
            },
            // 提交
            submit() {
                this.formItem.content = this.editor.getContent()
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
            onEditorReady: function(editor) {
                this.editor = editor;
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
    .ql-editor {
        height: 300px;
    }

    .ql-editor_big {
        height: 600px;
    }
</style>
