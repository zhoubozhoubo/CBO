webpackJsonp([18],{239:function(e,t,a){"use strict";function r(e){c||a(846)}Object.defineProperty(t,"__esModule",{value:!0});var n=a(473),o=a.n(n);for(var i in n)"default"!==i&&function(e){a.d(t,e,function(){return n[e]})}(i);var s=a(848),l=(a.n(s),a(4)),c=!1,d=r,m=Object(l.a)(o.a,s.render,s.staticRenderFns,!1,d,"data-v-5a501d5e",null);m.options.__file="src/views/system/auth.vue",t.default=m.exports},473:function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var r=a(34),n=function(e){return e&&e.__esModule?e:{default:e}}(r),o=function(e,t,a,r){return t("Button",{props:{type:"primary"},style:{margin:"0 5px"},on:{click:function(){e.formItem.id=a.id,e.formItem.name=a.name,e.formItem.description=a.description,n.default.get("Auth/getRuleList",{params:{groupId:a.id}}).then(function(t){var a=t.data;1===a.code?e.ruleList=a.data.list:-14===a.code?(e.$store.commit("logout",e),e.$router.push({name:"login"})):e.$Message.error(a.msg)}),e.modalSetting.show=!0,e.modalSetting.index=r}}},"编辑")},i=function(e,t,a,r){return t("Poptip",{props:{confirm:!0,title:"您确定要删除这条数据吗? ",transfer:!0},on:{"on-ok":function(){n.default.get("Auth/del",{params:{id:a.id}}).then(function(t){a.loading=!1,1===t.data.code?(e.tableData.splice(r,1),e.$Message.success(t.data.msg)):e.$Message.error(t.data.msg)})}}},[t("Button",{style:{margin:"0 5px"},props:{type:"error",placement:"top",loading:a.isDeleting}},"删除")])},s=function(e,t,a,r){return t("Button",{props:{type:"primary"},style:{margin:"0 5px"},on:{click:function(){e.memberSetting.show=!0,e.memberShow.gid=a.id,e.getMemberList()}}},"组成员")},l=function(e,t,a,r){return t("Poptip",{props:{confirm:!0,title:"您确定要删除这条数据吗? ",transfer:!0},on:{"on-ok":function(){n.default.get("Auth/delMember",{params:{uid:a.id,gid:e.memberShow.gid}}).then(function(t){a.loading=!1,1===t.data.code?(e.memberData.splice(r,1),e.$Message.success(t.data.msg)):e.$Message.error(t.data.msg)})}}},[t("Button",{style:{margin:"0 5px"},props:{type:"error",placement:"top",loading:a.isDeleting}},"删除")])};t.default={name:"system_auth",data:function(){return{ruleList:[],columnsList:[{title:"序号",type:"index",width:65,align:"center"},{title:"权限组",align:"center",key:"name",width:100},{title:"描述",align:"center",key:"description"},{title:"成员授权",align:"center",key:"member",width:116,handle:["member"]},{title:"状态",align:"center",key:"status",width:100},{title:"操作",align:"center",key:"handle",width:175,handle:["edit","delete"]}],memberColumns:[{title:"序号",type:"index",width:65,align:"center"},{title:"用户账号",align:"center",key:"username"},{title:"真实姓名",align:"center",key:"nickname",width:90},{title:"登录次数",align:"center",key:"loginTimes",width:90},{title:"最后登录时间",align:"center",key:"lastLoginTime",width:160},{title:"最后登录IP",align:"center",key:"lastLoginIp",width:160},{title:"状态",align:"center",key:"status",width:100},{title:"操作",align:"center",key:"handle",width:175,handle:["delete"]}],tableData:[],memberData:[],tableShow:{currentPage:1,pageSize:10,listCount:0},memberShow:{currentPage:1,pageSize:10,listCount:0,gid:0},searchConf:{keywords:"",status:""},modalSetting:{show:!1,loading:!1,index:0},memberSetting:{show:!1,loading:!1,index:0},formItem:{name:"",description:"",rules:[],id:0},ruleValidate:{name:[{required:!0,message:"组名称不能为空",trigger:"blur"}]}}},created:function(){this.init(),this.getList()},methods:{init:function(){var e=this;this.columnsList.forEach(function(t){"handle"===t.key&&(t.render=function(t,a){var r=e.tableData[a.index];return t("div",[o(e,t,r,a.index),i(e,t,r,a.index)])}),"member"===t.key&&(t.render=function(t,a){var r=e.tableData[a.index];return t("div",[s(e,t,r,a.index)])}),"status"===t.key&&(t.render=function(t,a){var r=e.tableData[a.index];return t("i-switch",{attrs:{size:"large"},props:{"true-value":1,"false-value":0,value:r.status},on:{"on-change":function(t){n.default.get("Auth/changeStatus",{params:{status:t,id:r.id}}).then(function(t){var a=t.data;1===a.code?e.$Message.success(a.msg):-14===a.code?(e.$store.commit("logout",e),e.$router.push({name:"login"})):(e.$Message.error(a.msg),e.getList()),e.cancel()})}}},[t("span",{slot:"open"},"启用"),t("span",{slot:"close"},"禁用")])})}),this.memberColumns.forEach(function(t){"handle"===t.key&&(t.render=function(t,a){var r=e.memberData[a.index];return t("div",[l(e,t,r,a.index)])}),"status"===t.key&&(t.render=function(t,a){return 1===e.memberData[a.index].status?t("Tag",{props:{color:"green"}},"启用"):t("Tag",{props:{color:"red"}},"禁用")})})},alertAdd:function(){var e=this;n.default.get("Auth/getRuleList").then(function(t){var a=t.data;1===a.code?e.ruleList=a.data.list:-14===a.code?(e.$store.commit("logout",e),e.$router.push({name:"login"})):e.$Message.error(a.msg)}),this.modalSetting.show=!0},submit:function(){var e=this;this.formItem.rules=[];var t=this.$refs.formTree.getCheckedNodes(),a=t.length;if(a)for(var r=0;r<=a-1;r++)this.formItem.rules.push(t[r].key);var o=this;this.$refs.myForm.validate(function(t){if(t){o.modalSetting.loading=!0;var a="";a=0===e.formItem.id?"Auth/add":"Auth/edit",n.default.post(a,e.formItem).then(function(e){1===e.data.code?o.$Message.success(e.data.msg):o.$Message.error(e.data.msg),o.getList(),o.cancel()})}})},cancel:function(){this.modalSetting.show=!1},doCancel:function(e){e||(this.formItem.id=0,this.$refs.myForm.resetFields(),this.modalSetting.loading=!1,this.modalSetting.index=0)},changePage:function(e){this.tableShow.currentPage=e,this.getList()},changeSize:function(e){this.tableShow.pageSize=e,this.getList()},changeMemberPage:function(e){this.memberShow.currentPage=e,this.getMemberList()},changeMemberSize:function(e){this.memberShow.pageSize=e,this.getMemberList()},search:function(){this.tableShow.currentPage=1,this.getList()},getList:function(){var e=this;n.default.get("Auth/index",{params:{page:e.tableShow.currentPage,size:e.tableShow.pageSize,keywords:e.searchConf.keywords,status:e.searchConf.status}}).then(function(t){var a=t.data;1===a.code?(e.tableData=a.data.list,e.tableShow.listCount=a.data.count):-14===a.code?(e.$store.commit("logout",e),e.$router.push({name:"login"})):e.$Message.error(a.msg)})},getMemberList:function(){var e=this;n.default.get("User/getUsers",{params:{page:e.memberShow.currentPage,size:e.memberShow.pageSize,gid:e.memberShow.gid}}).then(function(t){var a=t.data;1===a.code?(e.memberData=a.data.list,e.memberShow.listCount=a.data.count):-14===a.code?(e.$store.commit("logout",e),e.$router.push({name:"login"})):e.$Message.error(a.msg)})}}}},846:function(e,t,a){var r=a(847);"string"==typeof r&&(r=[[e.i,r,""]]),r.locals&&(e.exports=r.locals);var n=a(16).default;n("1ce44ccd",r,!1,{})},847:function(e,t,a){t=e.exports=a(15)(!1),t.push([e.i,"\n.rule-list[data-v-5a501d5e] {\n  height: 300px;\n  border: 1px solid #dddee1;\n  border-radius: 5px;\n  padding: 10px;\n  overflow: auto;\n}\n",""])},848:function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var r=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("Row",[a("Col",{attrs:{span:"24"}},[a("Card",{staticStyle:{"margin-bottom":"10px"}},[a("Form",{attrs:{inline:""}},[a("FormItem",{staticStyle:{"margin-bottom":"0"}},[a("Select",{staticStyle:{width:"100px"},attrs:{clearable:"",placeholder:"请选择状态"},model:{value:e.searchConf.status,callback:function(t){e.$set(e.searchConf,"status",t)},expression:"searchConf.status"}},[a("Option",{attrs:{value:1}},[e._v("启用")]),e._v(" "),a("Option",{attrs:{value:0}},[e._v("禁用")])],1)],1),e._v(" "),a("FormItem",{staticStyle:{"margin-bottom":"0"}},[a("Input",{attrs:{placeholder:"请输入组名称"},model:{value:e.searchConf.keywords,callback:function(t){e.$set(e.searchConf,"keywords",t)},expression:"searchConf.keywords"}})],1),e._v(" "),a("FormItem",{staticStyle:{"margin-bottom":"0"}},[a("Button",{attrs:{type:"primary"},on:{click:e.search}},[e._v("查询/刷新")])],1)],1)],1)],1)],1),e._v(" "),a("Row",[a("Col",{attrs:{span:"24"}},[a("Card",[a("p",{staticStyle:{height:"32px"},attrs:{slot:"title"},slot:"title"},[a("Button",{attrs:{type:"primary",icon:"md-add"},on:{click:e.alertAdd}},[e._v("新增")])],1),e._v(" "),a("div",[a("Table",{attrs:{columns:e.columnsList,data:e.tableData,border:"","disabled-hover":""}})],1),e._v(" "),a("div",{staticClass:"margin-top-15",staticStyle:{"text-align":"center"}},[a("Page",{attrs:{total:e.tableShow.listCount,current:e.tableShow.currentPage,"page-size":e.tableShow.pageSize,"show-elevator":"","show-sizer":"","show-total":""},on:{"on-change":e.changePage,"on-page-size-change":e.changeSize}})],1)])],1)],1),e._v(" "),a("Modal",{attrs:{width:"668",styles:{top:"30px"}},on:{"on-visible-change":e.doCancel},model:{value:e.modalSetting.show,callback:function(t){e.$set(e.modalSetting,"show",t)},expression:"modalSetting.show"}},[a("p",{staticStyle:{color:"#2d8cf0"},attrs:{slot:"header"},slot:"header"},[a("Icon",{attrs:{type:"md-information-circle"}}),e._v(" "),a("span",[e._v(e._s(e.formItem.id?"编辑":"新增")+"权限组")])],1),e._v(" "),a("Form",{ref:"myForm",attrs:{rules:e.ruleValidate,model:e.formItem,"label-width":80}},[a("FormItem",{attrs:{label:"组名称",prop:"name"}},[a("Input",{attrs:{placeholder:"请输入权限组名称"},model:{value:e.formItem.name,callback:function(t){e.$set(e.formItem,"name",t)},expression:"formItem.name"}})],1),e._v(" "),a("FormItem",{attrs:{label:"组描述",prop:"description"}},[a("Input",{attrs:{type:"textarea",placeholder:"请输入权限组描述"},model:{value:e.formItem.description,callback:function(t){e.$set(e.formItem,"description",t)},expression:"formItem.description"}})],1),e._v(" "),a("FormItem",{attrs:{label:"组授权",prop:"rules"}},[a("div",{staticClass:"rule-list"},[a("Tree",{ref:"formTree",attrs:{data:e.ruleList,"show-checkbox":"",multiple:""}})],1)])],1),e._v(" "),a("div",{attrs:{slot:"footer"},slot:"footer"},[a("Button",{staticStyle:{"margin-right":"8px"},attrs:{type:"text"},on:{click:e.cancel}},[e._v("取消")]),e._v(" "),a("Button",{attrs:{type:"primary",loading:e.modalSetting.loading},on:{click:e.submit}},[e._v("确定")])],1)],1),e._v(" "),a("Modal",{attrs:{width:"998",styles:{top:"30px"}},model:{value:e.memberSetting.show,callback:function(t){e.$set(e.memberSetting,"show",t)},expression:"memberSetting.show"}},[a("p",{staticStyle:{color:"#2d8cf0"},attrs:{slot:"header"},slot:"header"},[a("Icon",{attrs:{type:"md-information-circle"}}),e._v(" "),a("span",[e._v("组成员列表")])],1),e._v(" "),a("div",[a("Table",{attrs:{columns:e.memberColumns,data:e.memberData,border:"","disabled-hover":""}})],1),e._v(" "),a("div",{staticClass:"margin-top-15",staticStyle:{"text-align":"center"}},[a("Page",{attrs:{total:e.memberShow.listCount,current:e.memberShow.currentPage,"page-size":e.memberShow.pageSize,"show-elevator":"","show-sizer":"","show-total":""},on:{"on-change":e.changeMemberPage,"on-page-size-change":e.changeMemberSize}})],1),e._v(" "),a("p",{attrs:{slot:"footer"},slot:"footer"})])],1)},n=[];r._withStripped=!0,t.render=r,t.staticRenderFns=n}});