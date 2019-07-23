<template>
    <div>
        <Card>
            <p slot="title">
                <Icon type="md-person"></Icon>
                赛事简介管理
            </p>
            <div>
                <Form ref="myForm" :rules="ruleValidate" :model="formItem" :label-width="100">
                    <FormItem label="配置名称" prop="name">
                        <Input style="width: 300px;" v-model="formItem.name" disabled></Input>
                    </FormItem>
                    <FormItem label="配置备注" prop="remarks">
                        <Input style="width: 300px;" v-model="formItem.remarks" placeholder="配置备注"></Input>
                    </FormItem>
                    <FormItem label="配置内容" prop="value">
                        <div style="width: 800px;">
                            <Upload
                                    id="iviewUp"
                                    ref="upload"
                                    :show-upload-list="false"
                                    :on-success="handleSingleSuccess"
                                    :format="['jpg','jpeg','png']"
                                    :max-size="5120"
                                    :on-format-error="handleFormatError"
                                    :before-upload="handleBeforeUpload()"
                                    type="drag"
                                    :action="uploadUrl"
                                    :headers="uploadHeader"
                                    style="display: none;width:0">
                                <div style="width: 0">
                                    <Icon type="ios-camera" size="50"></Icon>
                                </div>
                            </Upload>
                            <quill-editor
                                    v-model="formItem.value"
                                    ref="myQuillEditor"
                                    :options="editorOption"
                                    @blur="onEditorBlur($event)"
                                    @focus="onEditorFocus($event)"
                                    @change="onEditorChange($event)">
                            </quill-editor>
                        </div>

                    </FormItem>
                    <div>
                        <Button type="primary" style="width: 100px; margin-left: 100px;" :loading="loading" @click="submit">保存</Button>
                    </div>
                </Form>
            </div>
        </Card>
    </div>
</template>

<script>
    import config from '../../../../build/config';
    import {getDataDetails, coruData} from '@/api/cbo_basic_conf'
    import {quillEditor} from 'vue-quill-editor';

    export default {
        name: 'match_synopsis',
        data () {
            return {
                loading: false,
                formItem: {
                    id: '',
                    name: '',
                    remarks: '',
                    value: ''
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
                ruleValidate: {}
            };
        },
        mounted () {
            this.getDataDetails()
            this.uploadUrl = config.baseUrl + 'Index/upload';
            this.uploadHeader = {'ApiAuth': sessionStorage.getItem('apiAuth')};
        },
        methods: {
            // 提交
            submit() {
                this.$refs['myForm'].validate((valid) => {
                    if (valid) {
                        this.loading = true
                        coruData(this.formItem).then(res => {
                            if (res.data.code === 1) {
                                this.getDataDetails()
                                this.$Message.success(res.data.msg)
                            } else {
                                this.$Message.error(res.data.msg)
                            }
                            this.loading = false
                        })
                    }
                })
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
            // 获取数据
            getDataDetails() {
                getDataDetails('match_synopsis').then(res => {
                    console.log(res)
                    this.formItem = res.data.data
                })
            }
        }
    };
</script>
<style>
    .ql-editor {
        height: 300px;
    }
</style>
