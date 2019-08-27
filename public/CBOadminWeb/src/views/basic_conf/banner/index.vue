<style lang="less">
    @import 'index.less';
</style>

<template>
    <div>
        <Card>
            <p slot="title">
                <Icon type="md-person"></Icon>
                banner图配置
            </p>
            <div>
                <Form ref="myForm" :rules="ruleValidate" :model="formItem" :label-width="80">
                    <FormItem label="banner图" prop="value">
                        <div class="demo-upload-list" v-if="formItem.value">
                            <img :src="formItem.value">
                            <div class="demo-upload-list-cover">
                                <Icon type="ios-trash-outline" @click.native="handleImgRemove()"></Icon>
                            </div>
                        </div>
                        <input v-if="formItem.value" v-model="formItem.value" type="hidden" name="image">
                        <Upload type="drag"
                                :action="uploadUrl"
                                :headers="uploadHeader"
                                v-if="!formItem.value"
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
                    <div>
                        <Button type="primary" style="width: 100px;" :loading="loading" @click="submit">保存</Button>
                    </div>
                </Form>
            </div>
        </Card>
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
    import {getDataDetails, coruData} from '@/api/cbo_basic_conf'

    export default {
        name: 'banner',
        data () {
            return {
                loading: false,
                formItem: {
                    id: '',
                    name: '',
                    remarks: '',
                    value: ''
                },
                // 图片modal
                modalSeeingImg: {
                    img: '',
                    show: false
                },
                uploadUrl: '',
                uploadHeader: {},
                ruleValidate: {
                    value: [{ required: true, message: "请上传图片", trigger: "change" }]
                },
            };
        },
        mounted () {
            this.getDataDetails()
            this.uploadUrl = config.baseUrl + 'Index/upload';
            this.uploadHeader = {'ApiAuth': sessionStorage.getItem('apiAuth')};
        },
        methods: {
            // 获取数据
            getDataDetails() {
                getDataDetails('banner').then(res => {
                    console.log(res)
                    this.formItem = res.data.data
                })
            },
            // 图片上传一系列
            handleView() {
                this.modalSeeingImg.show = true;
                this.modalSeeingImg.img = this.formItem.value;
            },
            handleImgSuccess(response) {
                if (response.code === 1) {
                    this.$Message.success(response.msg);
                    this.formItem.value = response.data.fileUrl;
                } else {
                    this.$Message.error(response.msg);
                }
            },
            handleImgRemove() {
                this.formItem.value = '';
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
        }
    };
</script>
