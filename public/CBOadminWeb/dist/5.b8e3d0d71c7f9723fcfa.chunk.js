webpackJsonp([5],{243:function(n,t,e){"use strict";function a(n){d||(e(867),e(869))}Object.defineProperty(t,"__esModule",{value:!0});var i=e(474),o=e.n(i);for(var r in i)"default"!==r&&function(n){e.d(t,n,function(){return i[n]})}(r);var s=e(872),l=(e.n(s),e(4)),d=!1,c=a,m=Object(l.a)(o.a,s.render,s.staticRenderFns,!1,c,"data-v-382e24c6",null);m.options.__file="src/views/content/cbo_wonderful_img/list.vue",t.default=m.exports},474:function(n,t,e){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var a=e(55),i=function(n){return n&&n.__esModule?n:{default:n}}(a),o=e(871),r=function(n,t,e,a){return t("Button",{props:{type:"primary"},style:{margin:"0 5px"},on:{click:function(){n.formItem.id=e.id,n.formItem.title=e.title,n.formItem.img=e.img,n.modalSetting.show=!0,n.modalSetting.index=a}}},"编辑")},s=function(n,t,e,a){return t("Poptip",{props:{confirm:!0,title:"您确定要删除这条数据吗? ",transfer:!0},on:{"on-ok":function(){(0,o.coruData)({id:e.id,is_delete:1}).then(function(t){1===t.data.code?(n.tableData.splice(a,1),n.$Message.success(t.data.msg)):n.$Message.error(t.data.msg)},function(t){n.$Message.error(t.data.msg)})}}},[t("Button",{style:{margin:"0 5px"},props:{type:"error",placement:"top"}},"删除")])};t.default={name:"list",components:{},data:function(){return{columnsList:[{title:"序号",type:"index",align:"center",width:"60"},{title:"图集标题",key:"title",align:"center"},{title:"图集图片",key:"img",align:"center",width:"100"},{title:"操作",key:"handle",align:"center",width:"200",handle:["edit","delete"]}],tableData:[],tableShow:{loading:!0,currentPage:1,pageSize:10,listCount:0},searchConf:{title:""},formItem:{id:"",title:"",img:""},modalSetting:{show:!1,loading:!1,index:0},modalSeeingImg:{img:"",show:!1},uploadUrl:"",uploadHeader:{},ruleValidate:{title:[{required:!0,message:"请输入标题",trigger:"blur"}],img:[{required:!0,message:"请上传封面",trigger:"change"}]}}},created:function(){this.init(),this.getList(),this.uploadUrl=i.default.baseUrl+"Index/upload",this.uploadHeader={ApiAuth:sessionStorage.getItem("apiAuth")}},methods:{init:function(){var n=this;this.columnsList.forEach(function(t){"handle"===t.key&&(t.render=function(t,e){var a=n.tableData[e.index];return t("div",[r(n,t,a,e.index),s(n,t,a,e.index)])}),"img"===t.key&&(t.render=function(t,e){var a=n.tableData[e.index];return a.img?t("a",{attrs:{href:a.img,target:"_black"}},"查看图片"):t("Tag",{},"暂无图片")})})},alertAdd:function(){this.formItem.id=0,this.modalSetting.show=!0},handleView:function(){this.modalSeeingImg.show=!0,this.modalSeeingImg.img=this.formItem.img},handleImgRemove:function(){this.formItem.img=""},handleImgFormatError:function(n){this.$Notice.warning({title:"文件类型不合法",desc:n.name+"的文件类型不正确，请上传jpg或者png图片。"})},handleImgMaxSize:function(n){this.$Notice.warning({title:"文件大小不合法",desc:n.name+"太大啦请上传小于5M的文件。"})},handleImgSuccess:function(n){1===n.code?(this.$Message.success(n.msg),this.formItem.img=n.data.fileUrl):this.$Message.error(n.msg)},submit:function(){var n=this;this.$refs.myForm.validate(function(t){t&&(n.modalSetting.loading=!0,(0,o.coruData)(n.formItem).then(function(t){1===t.data.code?(n.$Message.success(t.data.msg),n.getList(),n.cancel()):n.$Message.error(t.data.msg),n.modalSetting.loading=!1}))})},cancel:function(){this.modalSetting.show=!1},doCancel:function(n){n||(this.formItem.id=0,this.$refs.myForm.resetFields(),this.modalSetting.loading=!1,this.modalSetting.index=0)},changePage:function(n){this.tableShow.currentPage=n,this.getList()},changeSize:function(n){this.tableShow.pageSize=n,this.getList()},search:function(){this.tableShow.currentPage=1,this.getList()},getList:function(){var n=this;this.tableShow.loading=!0,(0,o.getDataList)(this.tableShow,this.searchConf).then(function(t){n.tableData=t.data.data.list,n.tableShow.listCount=t.data.data.count,n.tableShow.loading=!1})}}}},867:function(n,t,e){var a=e(868);"string"==typeof a&&(a=[[n.i,a,""]]),a.locals&&(n.exports=a.locals);var i=e(16).default;i("841043dc",a,!1,{})},868:function(n,t,e){t=n.exports=e(15)(!1),t.push([n.i,"\n.demo-upload-list[data-v-382e24c6] {\n    display: inline-block;\n    width: 60px;\n    height: 60px;\n    text-align: center;\n    line-height: 60px;\n    border: 1px solid transparent;\n    border-radius: 4px;\n    overflow: hidden;\n    background: #fff;\n    position: relative;\n    box-shadow: 0 1px 1px rgba(0, 0, 0, .2);\n    margin-right: 4px;\n}\n.demo-upload-list img[data-v-382e24c6] {\n    width: 100%;\n    height: 100%;\n}\n.demo-upload-list-cover[data-v-382e24c6] {\n    display: none;\n    position: absolute;\n    top: 0;\n    bottom: 0;\n    left: 0;\n    right: 0;\n    background: rgba(0, 0, 0, .6);\n}\n.demo-upload-list:hover .demo-upload-list-cover[data-v-382e24c6] {\n    display: block;\n}\n.demo-upload-list-cover i[data-v-382e24c6] {\n    color: #fff;\n    font-size: 20px;\n    cursor: pointer;\n    margin: 0 2px;\n}\n",""])},869:function(n,t,e){var a=e(870);"string"==typeof a&&(a=[[n.i,a,""]]),a.locals&&(n.exports=a.locals);var i=e(16).default;i("5fed11a2",a,!1,{})},870:function(n,t,e){t=n.exports=e(15)(!1),t.push([n.i,"\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n\n",""])},871:function(n,t,e){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.coruData=t.getDataList=void 0;var a=e(34),i=function(n){return n&&n.__esModule?n:{default:n}}(a);t.getDataList=function(n,t){return i.default.get("WonderfulImg/getList",{params:{page:n.currentPage,size:n.pageSize,searchConf:t}})},t.coruData=function(n){return i.default.post("WonderfulImg/coruData",n)}},872:function(n,t,e){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var a=function(){var n=this,t=n.$createElement,e=n._self._c||t;return e("div",[e("Row",[e("Col",{attrs:{span:"24"}},[e("Card",{staticStyle:{"margin-bottom":"10px"}},[e("Form",{attrs:{inline:""}},[e("FormItem",{staticStyle:{"margin-bottom":"0"}},[e("Input",{attrs:{clearable:"",placeholder:"图集标题"},model:{value:n.searchConf.title,callback:function(t){n.$set(n.searchConf,"title",t)},expression:"searchConf.title"}})],1),n._v(" "),e("FormItem",{staticStyle:{"margin-bottom":"0"}},[e("Button",{attrs:{type:"primary",shape:"circle",icon:"ios-search"},on:{click:n.search}},[n._v("查询/刷新")])],1)],1)],1)],1)],1),n._v(" "),e("Row",[e("Col",{attrs:{span:"24"}},[e("Card",[e("p",{staticStyle:{height:"40px"},attrs:{slot:"title"},slot:"title"},[e("Button",{attrs:{type:"primary",icon:"md-add"},on:{click:n.alertAdd}},[n._v("新增")])],1),n._v(" "),e("div",[e("Table",{attrs:{loading:n.tableShow.loading,columns:n.columnsList,data:n.tableData,border:"","disabled-hover":""}})],1),n._v(" "),e("div",{staticStyle:{"text-align":"center","margin-top":"15px"}},[e("Page",{attrs:{total:n.tableShow.listCount,current:n.tableShow.currentPage,"page-size":n.tableShow.pageSize,"show-elevator":"","show-sizer":"","show-total":""},on:{"on-change":n.changePage,"on-page-size-change":n.changeSize}})],1)])],1)],1),n._v(" "),e("Modal",{attrs:{width:"700",styles:{top:"30px"}},on:{"on-visible-change":n.doCancel},model:{value:n.modalSetting.show,callback:function(t){n.$set(n.modalSetting,"show",t)},expression:"modalSetting.show"}},[e("p",{staticStyle:{color:"#2d8cf0"},attrs:{slot:"header"},slot:"header"},[e("Icon",{attrs:{type:"md-information-circle"}}),n._v(" "),e("span",[n._v(n._s(n.formItem.id?"编辑":"新增"))])],1),n._v(" "),e("Form",{ref:"myForm",attrs:{rules:n.ruleValidate,model:n.formItem,"label-width":100}},[e("FormItem",{attrs:{label:"图集标题",prop:"title"}},[e("Input",{attrs:{placeholder:"图集标题"},model:{value:n.formItem.title,callback:function(t){n.$set(n.formItem,"title",t)},expression:"formItem.title"}})],1),n._v(" "),e("FormItem",{attrs:{label:"图集图片",prop:"img"}},[n.formItem.img?e("div",{staticClass:"demo-upload-list"},[e("img",{attrs:{src:n.formItem.img}}),n._v(" "),e("div",{staticClass:"demo-upload-list-cover"},[e("Icon",{attrs:{type:"ios-eye-outline"},nativeOn:{click:function(t){return n.handleView()}}}),n._v(" "),e("Icon",{attrs:{type:"ios-trash-outline"},nativeOn:{click:function(t){return n.handleImgRemove()}}})],1)]):n._e(),n._v(" "),n.formItem.img?e("input",{directives:[{name:"model",rawName:"v-model",value:n.formItem.img,expression:"formItem.img"}],attrs:{type:"hidden",name:"image"},domProps:{value:n.formItem.img},on:{input:function(t){t.target.composing||n.$set(n.formItem,"img",t.target.value)}}}):n._e(),n._v(" "),n.formItem.img?n._e():e("Upload",{staticStyle:{display:"inline-block",width:"58px"},attrs:{type:"drag",action:n.uploadUrl,headers:n.uploadHeader,format:["jpg","jpeg","png"],"max-size":5120,"on-success":n.handleImgSuccess,"on-format-error":n.handleImgFormatError,"on-exceeded-size":n.handleImgMaxSize}},[e("div",{staticStyle:{width:"58px",height:"58px","line-height":"58px"}},[e("Icon",{attrs:{type:"ios-camera",size:"20"}})],1)])],1)],1),n._v(" "),e("div",{attrs:{slot:"footer"},slot:"footer"},[e("Button",{staticStyle:{"margin-right":"8px"},attrs:{type:"text"},on:{click:n.cancel}},[n._v("取消")]),n._v(" "),e("Button",{attrs:{type:"primary",loading:n.modalSetting.loading},on:{click:n.submit}},[n._v("确定")])],1)],1),n._v(" "),e("Modal",{attrs:{"footer-hide":"","class-name":"fl-image-modal"},model:{value:n.modalSeeingImg.show,callback:function(t){n.$set(n.modalSeeingImg,"show",t)},expression:"modalSeeingImg.show"}},[e("img",{staticStyle:{width:"100%"},attrs:{src:n.modalSeeingImg.img}})])],1)},i=[];a._withStripped=!0,t.render=a,t.staticRenderFns=i}});