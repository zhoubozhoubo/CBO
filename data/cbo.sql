/*
 Navicat MySQL Data Transfer

 Source Server         : localhost
 Source Server Type    : MariaDB
 Source Server Version : 100140
 Source Host           : localhost:3306
 Source Schema         : cbo

 Target Server Type    : MariaDB
 Target Server Version : 100140
 File Encoding         : 65001

 Date: 20/07/2019 18:22:28
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin_app
-- ----------------------------
DROP TABLE IF EXISTS `admin_app`;
CREATE TABLE `admin_app`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `app_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '应用id',
  `app_secret` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '应用密码',
  `app_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '应用名称',
  `app_status` tinyint(2) NOT NULL DEFAULT 1 COMMENT '应用状态：0表示禁用，1表示启用',
  `app_info` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '应用说明',
  `app_api` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '当前应用允许请求的全部API接口',
  `app_group` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'default' COMMENT '当前应用所属的应用组唯一标识',
  `app_addTime` int(11) NOT NULL DEFAULT 0 COMMENT '应用创建时间',
  `app_api_show` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '前台样式显示所需数据格式',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `app_id`(`app_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = 'appId和appSecret表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for admin_app_group
-- ----------------------------
DROP TABLE IF EXISTS `admin_app_group`;
CREATE TABLE `admin_app_group`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '组名称',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '组说明',
  `status` tinyint(2) NOT NULL DEFAULT 1 COMMENT '组状态',
  `hash` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '组标识',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '应用组，目前只做管理使用，没有实际权限控制' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for admin_auth_group
-- ----------------------------
DROP TABLE IF EXISTS `admin_auth_group`;
CREATE TABLE `admin_auth_group`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '组名称',
  `description` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '组描述',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '组状态：为1正常，为0禁用',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '权限组' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for admin_auth_group_access
-- ----------------------------
DROP TABLE IF EXISTS `admin_auth_group_access`;
CREATE TABLE `admin_auth_group_access`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid` mediumint(8) UNSIGNED NOT NULL,
  `groupId` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `uid`(`uid`) USING BTREE,
  INDEX `groupId`(`groupId`(191)) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户和组的对应关系' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for admin_auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `admin_auth_rule`;
CREATE TABLE `admin_auth_rule`  (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `url` char(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '规则唯一标识',
  `groupId` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '权限所属组的ID',
  `auth` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '权限数值',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：为1正常，为0禁用',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '权限细节' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for admin_fields
-- ----------------------------
DROP TABLE IF EXISTS `admin_fields`;
CREATE TABLE `admin_fields`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fieldName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '字段名称',
  `hash` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '对应接口的唯一标识',
  `dataType` tinyint(2) NOT NULL DEFAULT 0 COMMENT '数据类型，来源于DataType类库',
  `default` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '默认值',
  `isMust` tinyint(2) NOT NULL DEFAULT 0 COMMENT '是否必须 0为不必须，1为必须',
  `range` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '范围，Json字符串，根据数据类型有不一样的含义',
  `info` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '字段说明',
  `type` tinyint(2) NOT NULL DEFAULT 0 COMMENT '字段用处：0为request，1为response',
  `showName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'wiki显示用字段',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `hash`(`hash`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用于保存各个API的字段规则' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for admin_group
-- ----------------------------
DROP TABLE IF EXISTS `admin_group`;
CREATE TABLE `admin_group`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '组名称',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '组说明',
  `status` tinyint(2) NOT NULL DEFAULT 1 COMMENT '组状态',
  `hash` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '组唯一标识',
  `addTime` int(11) NOT NULL DEFAULT 0 COMMENT '创建时间',
  `updateTime` int(11) NOT NULL DEFAULT 0 COMMENT '修改时间',
  `image` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '分组封面图',
  `hot` int(11) NOT NULL DEFAULT 0 COMMENT '分组热度',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '接口组管理' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_group
-- ----------------------------
INSERT INTO `admin_group` VALUES (1, '默认分组', '默认分组', 1, 'default', 0, 0, '', 0);

-- ----------------------------
-- Table structure for admin_list
-- ----------------------------
DROP TABLE IF EXISTS `admin_list`;
CREATE TABLE `admin_list`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `apiClass` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'api索引，保存了类和方法',
  `hash` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT 'api唯一标识',
  `accessToken` tinyint(2) NOT NULL DEFAULT 1 COMMENT '是否需要认证AccessToken 1：需要，0：不需要',
  `needLogin` tinyint(2) NOT NULL DEFAULT 1 COMMENT '是否需要认证用户token  1：需要 0：不需要',
  `status` tinyint(2) NOT NULL DEFAULT 1 COMMENT 'API状态：0表示禁用，1表示启用',
  `method` tinyint(2) NOT NULL DEFAULT 2 COMMENT '请求方式0：不限1：Post，2：Get',
  `info` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT 'api中文说明',
  `isTest` tinyint(2) NOT NULL DEFAULT 0 COMMENT '是否是测试模式：0:生产模式，1：测试模式',
  `returnStr` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '返回数据示例',
  `groupHash` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'default' COMMENT '当前接口所属的接口分组',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `hash`(`hash`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用于维护接口信息' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for admin_menu
-- ----------------------------
DROP TABLE IF EXISTS `admin_menu`;
CREATE TABLE `admin_menu`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '菜单名',
  `fid` int(11) NOT NULL COMMENT '父级菜单ID',
  `url` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '链接',
  `auth` tinyint(2) NOT NULL DEFAULT 0 COMMENT '访客权限',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序',
  `hide` tinyint(2) NOT NULL DEFAULT 0 COMMENT '是否显示',
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '菜单图标',
  `level` tinyint(2) NOT NULL DEFAULT 0 COMMENT '菜单认证等级',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 79 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '目录信息' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_menu
-- ----------------------------
INSERT INTO `admin_menu` VALUES (1, '用户登录', 0, 'admin/Login/index', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (2, '用户登出', 0, 'admin/Login/logout', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (3, '系统管理', 0, '', 0, 1, 0, '', 0);
INSERT INTO `admin_menu` VALUES (4, '菜单维护', 3, '', 0, 1, 0, '', 0);
INSERT INTO `admin_menu` VALUES (5, '菜单状态修改', 4, 'admin/Menu/changeStatus', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (6, '新增菜单', 4, 'admin/Menu/add', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (7, '编辑菜单', 4, 'admin/Menu/edit', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (8, '菜单删除', 4, 'admin/Menu/del', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (9, '用户管理', 3, '', 0, 2, 0, '', 0);
INSERT INTO `admin_menu` VALUES (10, '获取当前组的全部用户', 9, 'admin/User/getUsers', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (11, '用户状态修改', 9, 'admin/User/changeStatus', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (12, '新增用户', 9, 'admin/User/add', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (13, '用户编辑', 9, 'admin/User/edit', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (14, '用户删除', 9, 'admin/User/del', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (15, '权限管理', 3, '', 0, 3, 0, '', 0);
INSERT INTO `admin_menu` VALUES (16, '权限组状态编辑', 15, 'admin/Auth/changeStatus', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (17, '从指定组中删除指定用户', 15, 'admin/Auth/delMember', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (18, '新增权限组', 15, 'admin/Auth/add', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (19, '权限组编辑', 15, 'admin/Auth/edit', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (20, '删除权限组', 15, 'admin/Auth/del', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (21, '获取全部已开放的可选组', 15, 'admin/Auth/getGroups', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (22, '获取组所有的权限列表', 15, 'admin/Auth/getRuleList', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (23, '应用接入', 0, '', 0, 2, 0, '', 0);
INSERT INTO `admin_menu` VALUES (24, '应用管理', 23, '', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (25, '应用状态编辑', 24, 'admin/App/changeStatus', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (26, '获取AppId,AppSecret,接口列表,应用接口权限细节', 24, 'admin/App/getAppInfo', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (27, '新增应用', 24, 'admin/App/add', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (28, '编辑应用', 24, 'admin/App/edit', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (29, '删除应用', 24, 'admin/App/del', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (30, '接口管理', 0, '', 0, 3, 0, '', 0);
INSERT INTO `admin_menu` VALUES (31, '接口维护', 30, '', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (32, '接口状态编辑', 31, 'admin/InterfaceList/changeStatus', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (33, '获取接口唯一标识', 31, 'admin/InterfaceList/getHash', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (34, '添加接口', 31, 'admin/InterfaceList/add', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (35, '编辑接口', 31, 'admin/InterfaceList/edit', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (36, '删除接口', 31, 'admin/InterfaceList/del', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (37, '获取接口请求字段', 31, 'admin/Fields/request', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (38, '获取接口返回字段', 31, 'admin/Fields/response', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (39, '添加接口字段', 31, 'admin/Fields/add', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (40, '上传接口返回字段', 31, 'admin/Fields/upload', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (41, '编辑接口字段', 31, 'admin/Fields/edit', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (42, '删除接口字段', 31, 'admin/Fields/del', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (43, '接口分组', 30, '', 0, 1, 0, '', 0);
INSERT INTO `admin_menu` VALUES (44, '添加接口组', 43, 'admin/InterfaceGroup/add', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (45, '编辑接口组', 43, 'admin/InterfaceGroup/edit', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (46, '删除接口组', 43, 'admin/InterfaceGroup/del', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (47, '获取全部有效的接口组', 43, 'admin/InterfaceGroup/getAll', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (48, '接口组状态维护', 43, 'admin/InterfaceGroup/changeStatus', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (49, '应用分组', 23, '', 0, 1, 0, '', 0);
INSERT INTO `admin_menu` VALUES (50, '添加应用组', 49, 'admin/AppGroup/add', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (51, '编辑应用组', 49, 'admin/AppGroup/edit', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (52, '删除应用组', 49, 'admin/AppGroup/del', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (53, '获取全部可用应用组', 49, 'admin/AppGroup/getAll', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (54, '应用组状态编辑', 49, 'admin/AppGroup/changeStatus', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (55, '菜单列表', 4, 'admin/Menu/index', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (56, '用户列表', 9, 'admin/User/index', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (57, '权限列表', 15, 'admin/Auth/index', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (58, '应用列表', 24, 'admin/App/index', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (59, '应用分组列表', 49, 'admin/AppGroup/index', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (60, '接口列表', 31, 'admin/InterfaceList/index', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (61, '接口分组列表', 43, 'admin/InterfaceGroup/index', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (62, '日志管理', 3, '', 0, 4, 0, '', 0);
INSERT INTO `admin_menu` VALUES (63, '获取操作日志列表', 62, 'admin/Log/index', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (64, '删除单条日志记录', 62, 'admin/Log/del', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (65, '刷新路由', 31, 'admin/InterfaceList/refresh', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (66, '文件上传', 0, 'admin/Index/upload', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (67, '刷新AppSecret', 24, 'admin/App/refreshAppSecret', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (68, '更新个人信息', 9, 'admin/User/own', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (69, '数据库管理', 0, 'admin/DbTable/getTableList', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (70, '数据表管理', 107, 'admin/DbTable/getTableFullFields', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (71, '一键生成', 107, 'admin/DbTable/index', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (72, '内容管理', 0, '', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (73, '新闻管理', 72, 'admin/News/getList', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (74, '新增/编辑新闻', 73, 'admin/News/coruData', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (75, '官方消息管理', 72, 'admin/Message/getList', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (76, '新增/编辑官方消息', 75, 'admin/Message/coruData', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (77, '精彩图集管理', 72, 'admin/WonderfulImg/getList', 0, 0, 0, '', 0);
INSERT INTO `admin_menu` VALUES (78, '新增/编辑精彩图集', 77, 'admin/WonderfulImg/coruData', 0, 0, 0, '', 0);

-- ----------------------------
-- Table structure for admin_user
-- ----------------------------
DROP TABLE IF EXISTS `admin_user`;
CREATE TABLE `admin_user`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户昵称',
  `password` char(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户密码',
  `regTime` int(10) NOT NULL DEFAULT 0 COMMENT '注册时间',
  `regIp` bigint(11) NOT NULL COMMENT '注册IP',
  `updateTime` int(10) NOT NULL DEFAULT 0 COMMENT '更新时间',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '账号状态 0封号 1正常',
  `openId` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '三方登录唯一ID',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `regTime`(`regTime`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员认证信息' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_user
-- ----------------------------
INSERT INTO `admin_user` VALUES (1, 'root', 'root', '912601e4ad1b308c9ae41877cf6ca754', 1519453594, 3663623043, 1561476999, 1, NULL);

-- ----------------------------
-- Table structure for admin_user_action
-- ----------------------------
DROP TABLE IF EXISTS `admin_user_action`;
CREATE TABLE `admin_user_action`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `actionName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '行为名称',
  `uid` int(11) NOT NULL DEFAULT 0 COMMENT '操作用户ID',
  `nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户昵称',
  `addTime` int(11) NOT NULL DEFAULT 0 COMMENT '操作时间',
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '用户提交的数据',
  `url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '操作URL',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 90 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户操作日志' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_user_action
-- ----------------------------
INSERT INTO `admin_user_action` VALUES (1, '数据库管理', 1, 'root', 1563605029, '{\"\\/admin\\/DbTable\\/getTableList\":\"\"}', 'admin/DbTable/getTableList');
INSERT INTO `admin_user_action` VALUES (2, '数据库管理', 1, 'root', 1563605648, '{\"\\/admin\\/DbTable\\/getTableList\":\"\"}', 'admin/DbTable/getTableList');
INSERT INTO `admin_user_action` VALUES (3, '数据表管理', 1, 'root', 1563605654, '{\"\\/admin\\/DbTable\\/getTableFullFields\":\"\",\"tableName\":\"cbo_news\"}', 'admin/DbTable/getTableFullFields');
INSERT INTO `admin_user_action` VALUES (4, '数据库管理', 1, 'root', 1563606411, '{\"\\/admin\\/DbTable\\/getTableList\":\"\"}', 'admin/DbTable/getTableList');
INSERT INTO `admin_user_action` VALUES (5, '数据表管理', 1, 'root', 1563606413, '{\"\\/admin\\/DbTable\\/getTableFullFields\":\"\",\"tableName\":\"cbo_news\"}', 'admin/DbTable/getTableFullFields');
INSERT INTO `admin_user_action` VALUES (6, '一键生成', 1, 'root', 1563606642, '{\"\\/admin\\/DbTable\\/index\":\"\",\"tableName\":\"cbo_news\",\"baseConfig\":{\"pk\":\"id\",\"add\":true,\"edit\":true,\"delete\":true,\"check\":false,\"checkField\":[],\"backControllerName\":\"News\",\"backLogicName\":\"NewsLogic\",\"backModelName\":\"CboNews\",\"frontModelName\":\"content\",\"frontGroupName\":\"cbo_news\",\"frontVueName\":\"list\",\"autoMenu\":true,\"menuAttribute\":[{\"name\":\"\\u5185\\u5bb9\\u7ba1\\u7406\",\"url\":\"\"},{\"name\":\"\\u65b0\\u95fb\\u7ba1\\u7406\",\"url\":\"admin\\/News\\/getList\"},{\"name\":\"\\u65b0\\u589e\\/\\u7f16\\u8f91\\u65b0\\u95fb\",\"url\":\"admin\\/News\\/coruData\"}],\"menuName\":\"\",\"menuUrl\":\"\"},\"searchData\":[{\"key\":\"title\",\"name\":\"\\u65b0\\u95fb\\u6807\\u9898\"},{\"key\":\"date\",\"name\":\"\\u65b0\\u95fb\\u65e5\\u671f\"}],\"searchComponentsData\":[{\"key\":\"title\",\"name\":\"Default\"},{\"key\":\"date\",\"name\":\"DateRangePicker\"}],\"columnsData\":[{\"key\":\"id\",\"name\":\"\\u65b0\\u95fbid\"},{\"key\":\"title\",\"name\":\"\\u65b0\\u95fb\\u6807\\u9898\"},{\"key\":\"img\",\"name\":\"\\u65b0\\u95fb\\u5c01\\u9762\"},{\"key\":\"content\",\"name\":\"\\u65b0\\u95fb\\u5185\\u5bb9\"},{\"key\":\"date\",\"name\":\"\\u65b0\\u95fb\\u65e5\\u671f\"}],\"columnsComponentsData\":[{\"key\":\"id\",\"name\":\"Default\"},{\"key\":\"title\",\"name\":\"Default\"},{\"key\":\"img\",\"name\":\"Img\"},{\"key\":\"content\",\"name\":\"Default\"},{\"key\":\"date\",\"name\":\"Default\"}],\"itemData\":[{\"key\":\"id\",\"name\":\"\\u65b0\\u95fbid\"},{\"key\":\"title\",\"name\":\"\\u65b0\\u95fb\\u6807\\u9898\"},{\"key\":\"img\",\"name\":\"\\u65b0\\u95fb\\u5c01\\u9762\"},{\"key\":\"content\",\"name\":\"\\u65b0\\u95fb\\u5185\\u5bb9\"},{\"key\":\"date\",\"name\":\"\\u65b0\\u95fb\\u65e5\\u671f\"}],\"itemShowData\":[{\"key\":\"id\",\"show\":false},{\"key\":\"title\",\"show\":true},{\"key\":\"img\",\"show\":true},{\"key\":\"content\",\"show\":true},{\"key\":\"date\",\"show\":true},{\"key\":\"gmt_create\",\"show\":false},{\"key\":\"gmt_modified\",\"show\":false},{\"key\":\"is_delete\",\"show\":false}],\"itemComponentsData\":[{\"key\":\"\",\"name\":\"\"},{\"key\":\"title\",\"name\":\"Default\"},{\"key\":\"img\",\"name\":\"UploadImg\"},{\"key\":\"content\",\"name\":\"Editor\"},{\"key\":\"date\",\"name\":\"DatePicker\"},{\"key\":\"\",\"name\":\"\"},{\"key\":\"\",\"name\":\"\"},{\"key\":\"\",\"name\":\"\"}],\"fileList\":[]}', 'admin/DbTable/index');
INSERT INTO `admin_user_action` VALUES (7, '菜单列表', 1, 'root', 1563606646, '{\"\\/admin\\/Menu\\/index\":\"\"}', 'admin/Menu/index');
INSERT INTO `admin_user_action` VALUES (8, '菜单列表', 1, 'root', 1563606987, '{\"\\/admin\\/Menu\\/index\":\"\"}', 'admin/Menu/index');
INSERT INTO `admin_user_action` VALUES (9, '编辑菜单', 1, 'root', 1563607011, '{\"\\/admin\\/Menu\\/edit\":\"\",\"name\":\"\\u65b0\\u95fb\\u7ba1\\u7406\",\"fid\":72,\"url\":\"News\\/getList\",\"sort\":0,\"id\":73}', 'admin/Menu/edit');
INSERT INTO `admin_user_action` VALUES (10, '菜单列表', 1, 'root', 1563607012, '{\"\\/admin\\/Menu\\/index\":\"\"}', 'admin/Menu/index');
INSERT INTO `admin_user_action` VALUES (11, '编辑菜单', 1, 'root', 1563607019, '{\"\\/admin\\/Menu\\/edit\":\"\",\"name\":\"\\u65b0\\u589e\\/\\u7f16\\u8f91\\u65b0\\u95fb\",\"fid\":73,\"url\":\"News\\/coruData\",\"sort\":0,\"id\":74}', 'admin/Menu/edit');
INSERT INTO `admin_user_action` VALUES (12, '菜单列表', 1, 'root', 1563607020, '{\"\\/admin\\/Menu\\/index\":\"\"}', 'admin/Menu/index');
INSERT INTO `admin_user_action` VALUES (13, '菜单列表', 1, 'root', 1563607022, '{\"\\/admin\\/Menu\\/index\":\"\"}', 'admin/Menu/index');
INSERT INTO `admin_user_action` VALUES (14, '菜单列表', 1, 'root', 1563607264, '{\"\\/admin\\/Menu\\/index\":\"\"}', 'admin/Menu/index');
INSERT INTO `admin_user_action` VALUES (15, '菜单列表', 1, 'root', 1563607270, '{\"\\/admin\\/Menu\\/index\":\"\"}', 'admin/Menu/index');
INSERT INTO `admin_user_action` VALUES (16, '菜单列表', 1, 'root', 1563607285, '{\"\\/admin\\/Menu\\/index\":\"\"}', 'admin/Menu/index');
INSERT INTO `admin_user_action` VALUES (17, '新闻管理', 1, 'root', 1563607288, '{\"\\/admin\\/News\\/getList\":\"\",\"page\":\"1\",\"size\":\"10\",\"searchConf\":\"{\\\"title\\\":\\\"\\\",\\\"date\\\":\\\"\\\"}\"}', 'admin/News/getList');
INSERT INTO `admin_user_action` VALUES (18, '文件上传', 1, 'root', 1563607325, '{\"\\/admin\\/Index\\/upload\":\"\"}', 'admin/Index/upload');
INSERT INTO `admin_user_action` VALUES (19, '新增/编辑新闻', 1, 'root', 1563607337, '{\"\\/admin\\/News\\/coruData\":\"\",\"id\":0,\"title\":\"\\u65b0\\u95fb1\",\"img\":\"http:\\/\\/localhost:8003\\/upload\\/20190720\\/3e1efdc0b9dc4dd635b75e7bd5f7d341.png\",\"content\":\"<p>\\u65b0\\u95fb1\\u65b0\\u95fb1<\\/p>\",\"date\":\"2019-07-20\"}', 'admin/News/coruData');
INSERT INTO `admin_user_action` VALUES (20, '新闻管理', 1, 'root', 1563607337, '{\"\\/admin\\/News\\/getList\":\"\",\"page\":\"1\",\"size\":\"10\",\"searchConf\":\"{\\\"title\\\":\\\"\\\",\\\"date\\\":\\\"\\\"}\"}', 'admin/News/getList');
INSERT INTO `admin_user_action` VALUES (21, '新增/编辑新闻', 1, 'root', 1563607344, '{\"\\/admin\\/News\\/coruData\":\"\",\"id\":1,\"title\":\"\\u65b0\\u95fb1\",\"img\":\"http:\\/\\/localhost:8003\\/upload\\/20190720\\/3e1efdc0b9dc4dd635b75e7bd5f7d341.png\",\"content\":\"<p>\\u65b0\\u95fb1\\u65b0\\u95fb1<\\/p>\",\"date\":\"2019-07-19\"}', 'admin/News/coruData');
INSERT INTO `admin_user_action` VALUES (22, '新闻管理', 1, 'root', 1563607344, '{\"\\/admin\\/News\\/getList\":\"\",\"page\":\"1\",\"size\":\"10\",\"searchConf\":\"{\\\"title\\\":\\\"\\\",\\\"date\\\":\\\"\\\"}\"}', 'admin/News/getList');
INSERT INTO `admin_user_action` VALUES (23, '新闻管理', 1, 'root', 1563607357, '{\"\\/admin\\/News\\/getList\":\"\",\"page\":\"1\",\"size\":\"10\",\"searchConf\":\"{\\\"title\\\":\\\"12\\\",\\\"date\\\":\\\"\\\"}\"}', 'admin/News/getList');
INSERT INTO `admin_user_action` VALUES (24, '新闻管理', 1, 'root', 1563607361, '{\"\\/admin\\/News\\/getList\":\"\",\"page\":\"1\",\"size\":\"10\",\"searchConf\":\"{\\\"title\\\":\\\"1\\\",\\\"date\\\":\\\"\\\"}\"}', 'admin/News/getList');
INSERT INTO `admin_user_action` VALUES (25, '新闻管理', 1, 'root', 1563607400, '{\"\\/admin\\/News\\/getList\":\"\",\"page\":\"1\",\"size\":\"10\",\"searchConf\":\"{\\\"title\\\":\\\"\\\",\\\"date\\\":[\\\"2019-07-17\\\",\\\"2019-07-18\\\"]}\"}', 'admin/News/getList');
INSERT INTO `admin_user_action` VALUES (26, '新闻管理', 1, 'root', 1563607403, '{\"\\/admin\\/News\\/getList\":\"\",\"page\":\"1\",\"size\":\"10\",\"searchConf\":\"{\\\"title\\\":\\\"\\\",\\\"date\\\":[\\\"2019-07-17\\\",\\\"2019-07-19\\\"]}\"}', 'admin/News/getList');
INSERT INTO `admin_user_action` VALUES (27, '新闻管理', 1, 'root', 1563607407, '{\"\\/admin\\/News\\/getList\":\"\",\"page\":\"1\",\"size\":\"10\",\"searchConf\":\"{\\\"title\\\":\\\"\\\",\\\"date\\\":[\\\"2019-07-19\\\",\\\"2019-07-20\\\"]}\"}', 'admin/News/getList');
INSERT INTO `admin_user_action` VALUES (28, '新闻管理', 1, 'root', 1563607409, '{\"\\/admin\\/News\\/getList\":\"\",\"page\":\"1\",\"size\":\"10\",\"searchConf\":\"{\\\"title\\\":\\\"\\\",\\\"date\\\":[\\\"2019-07-20\\\",\\\"2019-07-20\\\"]}\"}', 'admin/News/getList');
INSERT INTO `admin_user_action` VALUES (29, '新闻管理', 1, 'root', 1563607411, '{\"\\/admin\\/News\\/getList\":\"\",\"page\":\"1\",\"size\":\"10\",\"searchConf\":\"{\\\"title\\\":\\\"\\\",\\\"date\\\":[\\\"\\\",\\\"\\\"]}\"}', 'admin/News/getList');
INSERT INTO `admin_user_action` VALUES (30, '新闻管理', 1, 'root', 1563607412, '{\"\\/admin\\/News\\/getList\":\"\",\"page\":\"1\",\"size\":\"10\",\"searchConf\":\"{\\\"title\\\":\\\"\\\",\\\"date\\\":[\\\"\\\",\\\"\\\"]}\"}', 'admin/News/getList');
INSERT INTO `admin_user_action` VALUES (31, '新闻管理', 1, 'root', 1563607615, '{\"\\/admin\\/News\\/getList\":\"\",\"page\":\"1\",\"size\":\"10\",\"searchConf\":\"{\\\"title\\\":\\\"\\\",\\\"date\\\":[\\\"\\\",\\\"\\\"]}\"}', 'admin/News/getList');
INSERT INTO `admin_user_action` VALUES (32, '文件上传', 1, 'root', 1563608911, '{\"\\/admin\\/Index\\/upload\":\"\"}', 'admin/Index/upload');
INSERT INTO `admin_user_action` VALUES (33, '新增/编辑新闻', 1, 'root', 1563608913, '{\"\\/admin\\/News\\/coruData\":\"\",\"id\":1,\"title\":\"\\u65b0\\u95fb1\",\"img\":\"http:\\/\\/localhost:8003\\/upload\\/20190720\\/3e1efdc0b9dc4dd635b75e7bd5f7d341.png\",\"content\":\"<p>\\u65b0\\u95fb1\\u65b0\\u95fb1<img src=\\\"http:\\/\\/localhost:8003\\/upload\\/20190720\\/d23ecd8ca09b79cfa0659849c1b9910b.png\\\"><\\/p>\",\"date\":\"2019-07-19\"}', 'admin/News/coruData');
INSERT INTO `admin_user_action` VALUES (34, '新闻管理', 1, 'root', 1563608913, '{\"\\/admin\\/News\\/getList\":\"\",\"page\":\"1\",\"size\":\"10\",\"searchConf\":\"{\\\"title\\\":\\\"\\\",\\\"date\\\":[\\\"\\\",\\\"\\\"]}\"}', 'admin/News/getList');
INSERT INTO `admin_user_action` VALUES (35, '数据库管理', 1, 'root', 1563611688, '{\"\\/admin\\/DbTable\\/getTableList\":\"\"}', 'admin/DbTable/getTableList');
INSERT INTO `admin_user_action` VALUES (36, '数据表管理', 1, 'root', 1563611704, '{\"\\/admin\\/DbTable\\/getTableFullFields\":\"\",\"tableName\":\"cbo_message\"}', 'admin/DbTable/getTableFullFields');
INSERT INTO `admin_user_action` VALUES (37, '数据库管理', 1, 'root', 1563613188, '{\"\\/admin\\/DbTable\\/getTableList\":\"\"}', 'admin/DbTable/getTableList');
INSERT INTO `admin_user_action` VALUES (38, '数据表管理', 1, 'root', 1563613191, '{\"\\/admin\\/DbTable\\/getTableFullFields\":\"\",\"tableName\":\"cbo_message\"}', 'admin/DbTable/getTableFullFields');
INSERT INTO `admin_user_action` VALUES (39, '数据库管理', 1, 'root', 1563613294, '{\"\\/admin\\/DbTable\\/getTableList\":\"\"}', 'admin/DbTable/getTableList');
INSERT INTO `admin_user_action` VALUES (40, '数据表管理', 1, 'root', 1563613296, '{\"\\/admin\\/DbTable\\/getTableFullFields\":\"\",\"tableName\":\"cbo_message\"}', 'admin/DbTable/getTableFullFields');
INSERT INTO `admin_user_action` VALUES (41, '一键生成', 1, 'root', 1563613396, '{\"\\/admin\\/DbTable\\/index\":\"\",\"tableName\":\"cbo_message\",\"baseConfig\":{\"pk\":\"id\",\"add\":true,\"edit\":true,\"delete\":true,\"check\":false,\"checkField\":[],\"backControllerName\":\"Message\",\"backLogicName\":\"MessageLogic\",\"backModelName\":\"CboMessage\",\"frontModelName\":\"content\",\"frontGroupName\":\"cbo_message\",\"frontVueName\":\"list\",\"autoMenu\":true,\"menuAttribute\":[{\"name\":\"\\u5b98\\u65b9\\u6d88\\u606f\\u7ba1\\u7406\",\"url\":\"admin\\/Message\\/getList\"},{\"name\":\"\\u65b0\\u589e\\/\\u7f16\\u8f91\\u5b98\\u65b9\\u6d88\\u606f\",\"url\":\"admin\\/Message\\/coruData\"}],\"menuName\":\"\",\"menuUrl\":\"\"},\"searchData\":[{\"key\":\"title\",\"name\":\"\\u6d88\\u606f\\u6807\\u9898\"},{\"key\":\"date\",\"name\":\"\\u6d88\\u606f\\u65e5\\u671f\"}],\"searchComponentsData\":[{\"key\":\"title\",\"name\":\"Default\"},{\"key\":\"date\",\"name\":\"DateRangePicker\"}],\"columnsData\":[{\"key\":\"id\",\"name\":\"\\u6d88\\u606fid\"},{\"key\":\"title\",\"name\":\"\\u6d88\\u606f\\u6807\\u9898\"},{\"key\":\"content\",\"name\":\"\\u6d88\\u606f\\u5185\\u5bb9\"},{\"key\":\"date\",\"name\":\"\\u6d88\\u606f\\u65e5\\u671f\"}],\"columnsComponentsData\":[{\"key\":\"id\",\"name\":\"Default\"},{\"key\":\"title\",\"name\":\"Default\"},{\"key\":\"content\",\"name\":\"Default\"},{\"key\":\"date\",\"name\":\"Default\"}],\"itemData\":[{\"key\":\"id\",\"name\":\"\\u6d88\\u606fid\"},{\"key\":\"title\",\"name\":\"\\u6d88\\u606f\\u6807\\u9898\"},{\"key\":\"content\",\"name\":\"\\u6d88\\u606f\\u5185\\u5bb9\"},{\"key\":\"date\",\"name\":\"\\u6d88\\u606f\\u65e5\\u671f\"}],\"itemShowData\":[{\"key\":\"id\",\"show\":false},{\"key\":\"title\",\"show\":true},{\"key\":\"content\",\"show\":true},{\"key\":\"date\",\"show\":true},{\"key\":\"gmt_create\",\"show\":false},{\"key\":\"gmt_modified\",\"show\":false},{\"key\":\"is_delete\",\"show\":false}],\"itemComponentsData\":[{\"key\":\"\",\"name\":\"\"},{\"key\":\"title\",\"name\":\"Default\"},{\"key\":\"content\",\"name\":\"Editor\"},{\"key\":\"date\",\"name\":\"DatePicker\"},{\"key\":\"\",\"name\":\"\"},{\"key\":\"\",\"name\":\"\"},{\"key\":\"\",\"name\":\"\"}],\"fileList\":[]}', 'admin/DbTable/index');
INSERT INTO `admin_user_action` VALUES (42, '数据表管理', 1, 'root', 1563613484, '{\"\\/admin\\/DbTable\\/getTableFullFields\":\"\",\"tableName\":\"cbo_message\"}', 'admin/DbTable/getTableFullFields');
INSERT INTO `admin_user_action` VALUES (43, '数据表管理', 1, 'root', 1563613647, '{\"\\/admin\\/DbTable\\/getTableFullFields\":\"\",\"tableName\":\"cbo_message\"}', 'admin/DbTable/getTableFullFields');
INSERT INTO `admin_user_action` VALUES (44, '用户登出', 1, 'root', 1563613651, '{\"\\/admin\\/Login\\/logout\":\"\"}', 'admin/Login/logout');
INSERT INTO `admin_user_action` VALUES (45, '权限列表', 1, 'root', 1563613699, '{\"\\/admin\\/Auth\\/index\":\"\",\"page\":\"1\",\"size\":\"10\",\"keywords\":\"\",\"status\":\"\"}', 'admin/Auth/index');
INSERT INTO `admin_user_action` VALUES (46, '用户列表', 1, 'root', 1563613702, '{\"\\/admin\\/User\\/index\":\"\",\"page\":\"1\",\"size\":\"10\",\"type\":\"\",\"keywords\":\"\",\"status\":\"\"}', 'admin/User/index');
INSERT INTO `admin_user_action` VALUES (47, '菜单列表', 1, 'root', 1563613704, '{\"\\/admin\\/Menu\\/index\":\"\"}', 'admin/Menu/index');
INSERT INTO `admin_user_action` VALUES (48, '编辑菜单', 1, 'root', 1563613715, '{\"\\/admin\\/Menu\\/edit\":\"\",\"name\":\"\\u5b98\\u65b9\\u6d88\\u606f\\u7ba1\\u7406\",\"fid\":72,\"url\":\"Message\\/getList\",\"sort\":0,\"id\":75}', 'admin/Menu/edit');
INSERT INTO `admin_user_action` VALUES (49, '菜单列表', 1, 'root', 1563613715, '{\"\\/admin\\/Menu\\/index\":\"\"}', 'admin/Menu/index');
INSERT INTO `admin_user_action` VALUES (50, '编辑菜单', 1, 'root', 1563613720, '{\"\\/admin\\/Menu\\/edit\":\"\",\"name\":\"\\u65b0\\u589e\\/\\u7f16\\u8f91\\u5b98\\u65b9\\u6d88\\u606f\",\"fid\":75,\"url\":\"Message\\/coruData\",\"sort\":0,\"id\":76}', 'admin/Menu/edit');
INSERT INTO `admin_user_action` VALUES (51, '菜单列表', 1, 'root', 1563613720, '{\"\\/admin\\/Menu\\/index\":\"\"}', 'admin/Menu/index');
INSERT INTO `admin_user_action` VALUES (52, '菜单列表', 1, 'root', 1563613724, '{\"\\/admin\\/Menu\\/index\":\"\"}', 'admin/Menu/index');
INSERT INTO `admin_user_action` VALUES (53, '菜单列表', 1, 'root', 1563613757, '{\"\\/admin\\/Menu\\/index\":\"\"}', 'admin/Menu/index');
INSERT INTO `admin_user_action` VALUES (54, '新闻管理', 1, 'root', 1563613763, '{\"\\/admin\\/News\\/getList\":\"\",\"page\":\"1\",\"size\":\"10\",\"searchConf\":\"{\\\"title\\\":\\\"\\\",\\\"date\\\":\\\"\\\"}\"}', 'admin/News/getList');
INSERT INTO `admin_user_action` VALUES (55, '官方消息管理', 1, 'root', 1563613765, '{\"\\/admin\\/Message\\/getList\":\"\",\"page\":\"1\",\"size\":\"10\",\"searchConf\":\"{\\\"title\\\":\\\"\\\",\\\"date\\\":\\\"\\\"}\"}', 'admin/Message/getList');
INSERT INTO `admin_user_action` VALUES (56, '新增/编辑官方消息', 1, 'root', 1563613778, '{\"\\/admin\\/Message\\/coruData\":\"\",\"id\":0,\"title\":\"\\u6d88\\u606f1\",\"content\":\"<p>\\u6d88\\u606f1\\u6d88\\u606f1\\u6d88\\u606f1<\\/p>\",\"date\":\"2019-07-18\"}', 'admin/Message/coruData');
INSERT INTO `admin_user_action` VALUES (57, '官方消息管理', 1, 'root', 1563613778, '{\"\\/admin\\/Message\\/getList\":\"\",\"page\":\"1\",\"size\":\"10\",\"searchConf\":\"{\\\"title\\\":\\\"\\\",\\\"date\\\":\\\"\\\"}\"}', 'admin/Message/getList');
INSERT INTO `admin_user_action` VALUES (58, '新增/编辑官方消息', 1, 'root', 1563613784, '{\"\\/admin\\/Message\\/coruData\":\"\",\"id\":1,\"title\":\"\\u6d88\\u606f1\",\"content\":\"<p>\\u6d88\\u606f1\\u6d88\\u606f1\\u6d88\\u606f1<\\/p>\",\"date\":\"2019-07-19\"}', 'admin/Message/coruData');
INSERT INTO `admin_user_action` VALUES (59, '官方消息管理', 1, 'root', 1563613784, '{\"\\/admin\\/Message\\/getList\":\"\",\"page\":\"1\",\"size\":\"10\",\"searchConf\":\"{\\\"title\\\":\\\"\\\",\\\"date\\\":\\\"\\\"}\"}', 'admin/Message/getList');
INSERT INTO `admin_user_action` VALUES (60, '新闻管理', 1, 'root', 1563613785, '{\"\\/admin\\/News\\/getList\":\"\",\"page\":\"1\",\"size\":\"10\",\"searchConf\":\"{\\\"title\\\":\\\"\\\",\\\"date\\\":\\\"\\\"}\"}', 'admin/News/getList');
INSERT INTO `admin_user_action` VALUES (61, '数据库管理', 1, 'root', 1563613986, '{\"\\/admin\\/DbTable\\/getTableList\":\"\"}', 'admin/DbTable/getTableList');
INSERT INTO `admin_user_action` VALUES (62, '数据表管理', 1, 'root', 1563613989, '{\"\\/admin\\/DbTable\\/getTableFullFields\":\"\",\"tableName\":\"cbo_wonderful_img\"}', 'admin/DbTable/getTableFullFields');
INSERT INTO `admin_user_action` VALUES (63, '数据库管理', 1, 'root', 1563614088, '{\"\\/admin\\/DbTable\\/getTableList\":\"\"}', 'admin/DbTable/getTableList');
INSERT INTO `admin_user_action` VALUES (64, '数据表管理', 1, 'root', 1563614182, '{\"\\/admin\\/DbTable\\/getTableFullFields\":\"\",\"tableName\":\"cbo_wonderful_img\"}', 'admin/DbTable/getTableFullFields');
INSERT INTO `admin_user_action` VALUES (65, '数据库管理', 1, 'root', 1563614259, '{\"\\/admin\\/DbTable\\/getTableList\":\"\"}', 'admin/DbTable/getTableList');
INSERT INTO `admin_user_action` VALUES (66, '数据表管理', 1, 'root', 1563614260, '{\"\\/admin\\/DbTable\\/getTableFullFields\":\"\",\"tableName\":\"cbo_wonderful_img\"}', 'admin/DbTable/getTableFullFields');
INSERT INTO `admin_user_action` VALUES (67, '一键生成', 1, 'root', 1563614305, '{\"\\/admin\\/DbTable\\/index\":\"\",\"tableName\":\"cbo_wonderful_img\",\"baseConfig\":{\"pk\":\"id\",\"add\":true,\"edit\":true,\"delete\":true,\"check\":false,\"checkField\":[],\"backControllerName\":\"WonderfulImg\",\"backLogicName\":\"WonderfulImgLogic\",\"backModelName\":\"CboWonderfulImg\",\"frontModelName\":\"content\",\"frontGroupName\":\"cbo_wonderful_img\",\"frontVueName\":\"list\",\"autoMenu\":true,\"menuAttribute\":[{\"name\":\"\\u7cbe\\u5f69\\u56fe\\u96c6\\u7ba1\\u7406\",\"url\":\"admin\\/WonderfulImg\\/getList\"},{\"name\":\"\\u65b0\\u589e\\/\\u7f16\\u8f91\\u7cbe\\u5f69\\u56fe\\u96c6\",\"url\":\"admin\\/WonderfulImg\\/coruData\"}],\"menuName\":\"\",\"menuUrl\":\"\"},\"searchData\":[{\"key\":\"title\",\"name\":\"\\u56fe\\u96c6\\u6807\\u9898\"}],\"searchComponentsData\":[{\"key\":\"title\",\"name\":\"Default\"}],\"columnsData\":[{\"key\":\"id\",\"name\":\"\\u56fe\\u96c6id\"},{\"key\":\"title\",\"name\":\"\\u56fe\\u96c6\\u6807\\u9898\"},{\"key\":\"img\",\"name\":\"\\u56fe\\u96c6\\u56fe\\u7247\"}],\"columnsComponentsData\":[{\"key\":\"id\",\"name\":\"Default\"},{\"key\":\"title\",\"name\":\"Default\"},{\"key\":\"img\",\"name\":\"Img\"}],\"itemData\":[{\"key\":\"id\",\"name\":\"\\u56fe\\u96c6id\"},{\"key\":\"title\",\"name\":\"\\u56fe\\u96c6\\u6807\\u9898\"},{\"key\":\"img\",\"name\":\"\\u56fe\\u96c6\\u56fe\\u7247\"}],\"itemShowData\":[{\"key\":\"id\",\"show\":false},{\"key\":\"title\",\"show\":true},{\"key\":\"img\",\"show\":true},{\"key\":\"gmt_create\",\"show\":false},{\"key\":\"gmt_modified\",\"show\":false},{\"key\":\"is_delete\",\"show\":false}],\"itemComponentsData\":[{\"key\":\"\",\"name\":\"\"},{\"key\":\"title\",\"name\":\"Default\"},{\"key\":\"img\",\"name\":\"UploadImg\"},{\"key\":\"\",\"name\":\"\"},{\"key\":\"\",\"name\":\"\"},{\"key\":\"\",\"name\":\"\"}],\"fileList\":[]}', 'admin/DbTable/index');
INSERT INTO `admin_user_action` VALUES (68, '数据表管理', 1, 'root', 1563614368, '{\"\\/admin\\/DbTable\\/getTableFullFields\":\"\",\"tableName\":\"cbo_wonderful_img\"}', 'admin/DbTable/getTableFullFields');
INSERT INTO `admin_user_action` VALUES (69, '菜单列表', 1, 'root', 1563614374, '{\"\\/admin\\/Menu\\/index\":\"\"}', 'admin/Menu/index');
INSERT INTO `admin_user_action` VALUES (70, '编辑菜单', 1, 'root', 1563614380, '{\"\\/admin\\/Menu\\/edit\":\"\",\"name\":\"\\u7cbe\\u5f69\\u56fe\\u96c6\\u7ba1\\u7406\",\"fid\":72,\"url\":\"WonderfulImg\\/getList\",\"sort\":0,\"id\":77}', 'admin/Menu/edit');
INSERT INTO `admin_user_action` VALUES (71, '菜单列表', 1, 'root', 1563614380, '{\"\\/admin\\/Menu\\/index\":\"\"}', 'admin/Menu/index');
INSERT INTO `admin_user_action` VALUES (72, '编辑菜单', 1, 'root', 1563614384, '{\"\\/admin\\/Menu\\/edit\":\"\",\"name\":\"\\u65b0\\u589e\\/\\u7f16\\u8f91\\u7cbe\\u5f69\\u56fe\\u96c6\",\"fid\":77,\"url\":\"WonderfulImg\\/coruData\",\"sort\":0,\"id\":78}', 'admin/Menu/edit');
INSERT INTO `admin_user_action` VALUES (73, '菜单列表', 1, 'root', 1563614384, '{\"\\/admin\\/Menu\\/index\":\"\"}', 'admin/Menu/index');
INSERT INTO `admin_user_action` VALUES (74, '菜单列表', 1, 'root', 1563614388, '{\"\\/admin\\/Menu\\/index\":\"\"}', 'admin/Menu/index');
INSERT INTO `admin_user_action` VALUES (75, '用户登出', 1, 'root', 1563614407, '{\"\\/admin\\/Login\\/logout\":\"\"}', 'admin/Login/logout');
INSERT INTO `admin_user_action` VALUES (76, '精彩图集管理', 1, 'root', 1563614416, '{\"\\/admin\\/WonderfulImg\\/getList\":\"\",\"page\":\"1\",\"size\":\"10\",\"searchConf\":\"{\\\"title\\\":\\\"\\\"}\"}', 'admin/WonderfulImg/getList');
INSERT INTO `admin_user_action` VALUES (77, '文件上传', 1, 'root', 1563614432, '{\"\\/admin\\/Index\\/upload\":\"\"}', 'admin/Index/upload');
INSERT INTO `admin_user_action` VALUES (78, '新增/编辑精彩图集', 1, 'root', 1563614434, '{\"\\/admin\\/WonderfulImg\\/coruData\":\"\",\"id\":0,\"title\":\"\\u56fe\\u96c61\",\"img\":\"http:\\/\\/localhost:8003\\/upload\\/20190720\\/891456ab11200e843f4c51c599d4eee6.png\"}', 'admin/WonderfulImg/coruData');
INSERT INTO `admin_user_action` VALUES (79, '精彩图集管理', 1, 'root', 1563614434, '{\"\\/admin\\/WonderfulImg\\/getList\":\"\",\"page\":\"1\",\"size\":\"10\",\"searchConf\":\"{\\\"title\\\":\\\"\\\"}\"}', 'admin/WonderfulImg/getList');
INSERT INTO `admin_user_action` VALUES (80, '数据库管理', 1, 'root', 1563614494, '{\"\\/admin\\/DbTable\\/getTableList\":\"\"}', 'admin/DbTable/getTableList');
INSERT INTO `admin_user_action` VALUES (81, '数据表管理', 1, 'root', 1563614496, '{\"\\/admin\\/DbTable\\/getTableFullFields\":\"\",\"tableName\":\"cbo_wonderful_img\"}', 'admin/DbTable/getTableFullFields');
INSERT INTO `admin_user_action` VALUES (82, '新闻管理', 1, 'root', 1563614522, '{\"\\/admin\\/News\\/getList\":\"\",\"page\":\"1\",\"size\":\"10\",\"searchConf\":\"{\\\"title\\\":\\\"\\\",\\\"date\\\":\\\"\\\"}\"}', 'admin/News/getList');
INSERT INTO `admin_user_action` VALUES (83, '官方消息管理', 1, 'root', 1563614523, '{\"\\/admin\\/Message\\/getList\":\"\",\"page\":\"1\",\"size\":\"10\",\"searchConf\":\"{\\\"title\\\":\\\"\\\",\\\"date\\\":\\\"\\\"}\"}', 'admin/Message/getList');
INSERT INTO `admin_user_action` VALUES (84, '精彩图集管理', 1, 'root', 1563614523, '{\"\\/admin\\/WonderfulImg\\/getList\":\"\",\"page\":\"1\",\"size\":\"10\",\"searchConf\":\"{\\\"title\\\":\\\"\\\"}\"}', 'admin/WonderfulImg/getList');
INSERT INTO `admin_user_action` VALUES (85, '新闻管理', 1, 'root', 1563615763, '{\"\\/admin\\/News\\/getList\":\"\",\"page\":\"1\",\"size\":\"10\",\"searchConf\":\"{\\\"title\\\":\\\"\\\",\\\"date\\\":\\\"\\\"}\"}', 'admin/News/getList');
INSERT INTO `admin_user_action` VALUES (86, '文件上传', 1, 'root', 1563615769, '{\"\\/admin\\/Index\\/upload\":\"\"}', 'admin/Index/upload');
INSERT INTO `admin_user_action` VALUES (87, '新增/编辑新闻', 1, 'root', 1563615795, '{\"\\/admin\\/News\\/coruData\":\"\",\"id\":1,\"title\":\"\\u65b0\\u95fb1\",\"img\":\"http:\\/\\/localhost:8003\\/upload\\/20190720\\/3e1efdc0b9dc4dd635b75e7bd5f7d341.png\",\"content\":\"<p>\\u65b0\\u95fb1\\u65b0\\u95fb1<img src=\\\"http:\\/\\/localhost:8003\\/upload\\/20190720\\/d23ecd8ca09b79cfa0659849c1b9910b.png\\\"><\\/p>\",\"date\":\"2019-07-19\",\"file\":\"http:\\/\\/localhost:8003\\/upload\\/20190720\\/d3b97236f1dca8777dbb3806fd61bd3f.pdf\"}', 'admin/News/coruData');
INSERT INTO `admin_user_action` VALUES (88, '文件上传', 1, 'root', 1563615869, '{\"\\/admin\\/Index\\/upload\":\"\"}', 'admin/Index/upload');
INSERT INTO `admin_user_action` VALUES (89, '新增/编辑新闻', 1, 'root', 1563615894, '{\"\\/admin\\/News\\/coruData\":\"\",\"id\":1,\"title\":\"\\u65b0\\u95fb1\",\"img\":\"http:\\/\\/localhost:8003\\/upload\\/20190720\\/3e1efdc0b9dc4dd635b75e7bd5f7d341.png\",\"content\":\"<p>\\u65b0\\u95fb1\\u65b0\\u95fb1<img src=\\\"http:\\/\\/localhost:8003\\/upload\\/20190720\\/d23ecd8ca09b79cfa0659849c1b9910b.png\\\"><\\/p>\",\"date\":\"2019-07-19\",\"file\":\"\"}', 'admin/News/coruData');

-- ----------------------------
-- Table structure for admin_user_data
-- ----------------------------
DROP TABLE IF EXISTS `admin_user_data`;
CREATE TABLE `admin_user_data`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `loginTimes` int(11) NOT NULL DEFAULT 0 COMMENT '账号登录次数',
  `lastLoginIp` bigint(11) NOT NULL DEFAULT 0 COMMENT '最后登录IP',
  `lastLoginTime` int(11) NOT NULL DEFAULT 0 COMMENT '最后登录时间',
  `uid` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户ID',
  `headImg` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '用户头像',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员数据表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_user_data
-- ----------------------------
INSERT INTO `admin_user_data` VALUES (1, 4, 0, 1563614413, '1', '');

-- ----------------------------
-- Table structure for cbo_message
-- ----------------------------
DROP TABLE IF EXISTS `cbo_message`;
CREATE TABLE `cbo_message`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '消息id',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '消息标题',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '消息内容',
  `date` date NOT NULL COMMENT '消息日期',
  `gmt_create` datetime(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建日期',
  `gmt_modified` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日期',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除(0未删除1已删除)',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '官方消息表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cbo_message
-- ----------------------------
INSERT INTO `cbo_message` VALUES (1, '消息1', '<p>消息1消息1消息1</p>', '2019-07-19', '2019-07-20 17:09:38', '2019-07-20 17:09:44', 0);

-- ----------------------------
-- Table structure for cbo_news
-- ----------------------------
DROP TABLE IF EXISTS `cbo_news`;
CREATE TABLE `cbo_news`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '新闻id',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '新闻标题',
  `img` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '新闻封面',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '新闻内容',
  `date` date NOT NULL COMMENT '新闻日期',
  `gmt_create` datetime(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建日期',
  `gmt_modified` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日期',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除(0未删除1已删除)',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '新闻表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cbo_news
-- ----------------------------
INSERT INTO `cbo_news` VALUES (1, '新闻1', 'http://localhost:8003/upload/20190720/3e1efdc0b9dc4dd635b75e7bd5f7d341.png', '<p>新闻1新闻1<img src=\"http://localhost:8003/upload/20190720/d23ecd8ca09b79cfa0659849c1b9910b.png\"></p>', '2019-07-19', '2019-07-20 15:22:17', '2019-07-20 15:48:33', 0);

-- ----------------------------
-- Table structure for cbo_wonderful_img
-- ----------------------------
DROP TABLE IF EXISTS `cbo_wonderful_img`;
CREATE TABLE `cbo_wonderful_img`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '图集id',
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '图集标题',
  `img` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '图集图片',
  `gmt_create` datetime(0) NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建日期',
  `gmt_modified` datetime(0) NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新日期',
  `is_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否删除(0未删除1已删除)',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '精彩图集表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cbo_wonderful_img
-- ----------------------------
INSERT INTO `cbo_wonderful_img` VALUES (1, '图集1', 'http://localhost:8003/upload/20190720/891456ab11200e843f4c51c599d4eee6.png', '2019-07-20 17:20:34', '2019-07-20 17:20:34', 0);

SET FOREIGN_KEY_CHECKS = 1;
