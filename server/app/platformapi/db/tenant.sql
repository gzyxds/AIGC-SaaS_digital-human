/*

 Target Server Type    : MySQL
 Target Server Version : 50729 (5.7.29)
 File Encoding         : 65001

 Date: 01/10/2024 14:18:29
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for la_tenant_admin
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_admin_{tenantSn}`;
CREATE TABLE `la_tenant_admin_{tenantSn}`  (
                                    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                                    `tenant_id` int(10) NOT NULL COMMENT '租户ID',
                                    `root` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否超级管理员 0-否 1-是',
                                    `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '名称',
                                    `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户头像',
                                    `account` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '账号',
                                    `password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '密码',
                                    `login_time` int(10) NULL DEFAULT NULL COMMENT '最后登录时间',
                                    `login_ip` varchar(39) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '最后登录ip',
                                    `multipoint_login` tinyint(1) UNSIGNED NULL DEFAULT 1 COMMENT '是否支持多处登录：1-是；0-否；',
                                    `disable` tinyint(1) UNSIGNED NULL DEFAULT 0 COMMENT '是否禁用：0-否；1-是；',
                                    `create_time` int(10) NOT NULL COMMENT '创建时间',
                                    `update_time` int(10) NULL DEFAULT NULL COMMENT '修改时间',
                                    `delete_time` int(10) NULL DEFAULT NULL COMMENT '删除时间',
                                    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '租户管理员表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for la_tenant_admin_dept
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_admin_dept_{tenantSn}`;
CREATE TABLE `la_tenant_admin_dept_{tenantSn}`  (
                                         `admin_id` int(10) NOT NULL DEFAULT 0 COMMENT '管理员id',
                                         `dept_id` int(10) NOT NULL DEFAULT 0 COMMENT '部门id',
                                         PRIMARY KEY (`admin_id`, `dept_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '部门关联表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for la_tenant_admin_jobs
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_admin_jobs_{tenantSn}`;
CREATE TABLE `la_tenant_admin_jobs_{tenantSn}`  (
                                         `admin_id` int(10) NOT NULL COMMENT '管理员id',
                                         `jobs_id` int(10) NOT NULL COMMENT '岗位id',
                                         PRIMARY KEY (`admin_id`, `jobs_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '岗位关联表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for la_tenant_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_admin_role_{tenantSn}`;
CREATE TABLE `la_tenant_admin_role_{tenantSn}`  (
                                         `admin_id` int(10) NOT NULL COMMENT '管理员id',
                                         `role_id` int(10) NOT NULL COMMENT '角色id',
                                         PRIMARY KEY (`admin_id`, `role_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '角色关联表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for la_tenant_admin_session
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_admin_session_{tenantSn}`;
CREATE TABLE `la_tenant_admin_session_{tenantSn}`  (
                                            `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                                            `admin_id` int(11) UNSIGNED NOT NULL COMMENT '租户id',
                                            `terminal` tinyint(1) NOT NULL DEFAULT 1 COMMENT '客户端类型：1-pc管理后台 2-mobile手机管理后台',
                                            `token` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '令牌',
                                            `update_time` int(10) NULL DEFAULT NULL COMMENT '更新时间',
                                            `expire_time` int(10) NOT NULL COMMENT '到期时间',
                                            PRIMARY KEY (`id`) USING BTREE,
                                            UNIQUE INDEX `admin_id_client`(`admin_id`, `terminal`) USING BTREE COMMENT '一个用户在一个终端只有一个token',
                                            UNIQUE INDEX `token`(`token`) USING BTREE COMMENT 'token是唯一的'
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '管理员会话表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for la_tenant_config
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_config_{tenantSn}`;
CREATE TABLE `la_tenant_config_{tenantSn}`  (
                                     `id` int(11) NOT NULL AUTO_INCREMENT,
                                     `tenant_id` int(11) NOT NULL COMMENT '租户ID',
                                     `type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '类型',
                                     `name` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '名称',
                                     `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '值',
                                     `create_time` int(10) NULL DEFAULT NULL COMMENT '创建时间',
                                     `update_time` int(10) NULL DEFAULT NULL COMMENT '更新时间',
                                     PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '配置表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for la_tenant_dept
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_dept_{tenantSn}`;
CREATE TABLE `la_tenant_dept_{tenantSn}`  (
                                   `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
                                   `tenant_id` int(11) NOT NULL COMMENT '租户ID',
                                   `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '部门名称',
                                   `pid` bigint(20) NOT NULL DEFAULT 0 COMMENT '上级部门id',
                                   `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序',
                                   `leader` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '负责人',
                                   `mobile` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '联系电话',
                                   `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '部门状态（0停用 1正常）',
                                   `create_time` int(10) NOT NULL COMMENT '创建时间',
                                   `update_time` int(10) NULL DEFAULT NULL COMMENT '修改时间',
                                   `delete_time` int(10) NULL DEFAULT NULL COMMENT '删除时间',
                                   PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '租户部门表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for la_tenant_file
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_file_{tenantSn}`;
CREATE TABLE `la_tenant_file_{tenantSn}`  (
                                   `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
                                   `tenant_id` int(11) NOT NULL COMMENT '租户ID',
                                   `cid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '类目ID',
                                   `source_id` int(11) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上传者id',
                                   `source` tinyint(1) NOT NULL DEFAULT 0 COMMENT '来源类型[0-后台,1-用户]',
                                   `type` tinyint(2) UNSIGNED NOT NULL DEFAULT 10 COMMENT '类型[10=图片, 20=视频]',
                                   `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '文件名称',
                                   `uri` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '文件路径',
                                   `create_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '创建时间',
                                   `update_time` int(10) NULL DEFAULT NULL COMMENT '更新时间',
                                   `delete_time` int(10) NULL DEFAULT NULL COMMENT '删除时间',
                                   PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '文件表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for la_tenant_file_cate
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_file_cate_{tenantSn}`;
CREATE TABLE `la_tenant_file_cate_{tenantSn}`  (
                                        `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
                                        `tenant_id` int(11) NOT NULL COMMENT '租户ID',
                                        `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级ID',
                                        `type` tinyint(2) UNSIGNED NOT NULL DEFAULT 10 COMMENT '类型[10=图片，20=视频，30=文件]',
                                        `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '分类名称',
                                        `create_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '创建时间',
                                        `update_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '更新时间',
                                        `delete_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '删除时间',
                                        PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '文件分类表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for la_tenant_jobs
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_jobs_{tenantSn}`;
CREATE TABLE `la_tenant_jobs_{tenantSn}`  (
                                   `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
                                   `tenant_id` int(11) NOT NULL COMMENT '租户ID',
                                   `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '岗位名称',
                                   `code` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '岗位编码',
                                   `sort` int(11) NULL DEFAULT 0 COMMENT '显示顺序',
                                   `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态（0停用 1正常）',
                                   `remark` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '备注',
                                   `create_time` int(10) NOT NULL COMMENT '创建时间',
                                   `update_time` int(10) NULL DEFAULT NULL COMMENT '修改时间',
                                   `delete_time` int(10) NULL DEFAULT NULL COMMENT '删除时间',
                                   PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '岗位表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for la_tenant_notice_record
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_notice_record_{tenantSn}`;
CREATE TABLE `la_tenant_notice_record_{tenantSn}`  (
                                            `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
                                            `tenant_id` int(11) NOT NULL COMMENT '租户ID',
                                            `user_id` int(10) UNSIGNED NOT NULL COMMENT '用户id',
                                            `title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标题',
                                            `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '内容',
                                            `scene_id` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '场景',
                                            `read` tinyint(1) NULL DEFAULT 0 COMMENT '已读状态;0-未读,1-已读',
                                            `recipient` tinyint(1) NULL DEFAULT 0 COMMENT '通知接收对象类型;1-会员;2-商家;3-平台;4-游客(未注册用户)',
                                            `send_type` tinyint(1) NULL DEFAULT 0 COMMENT '通知发送类型 1-系统通知 2-短信通知 3-微信模板 4-微信小程序',
                                            `notice_type` tinyint(1) NULL DEFAULT NULL COMMENT '通知类型 1-业务通知 2-验证码',
                                            `extra` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '其他',
                                            `create_time` int(10) NULL DEFAULT NULL COMMENT '创建时间',
                                            `update_time` int(10) NULL DEFAULT NULL COMMENT '更新时间',
                                            `delete_time` int(10) NULL DEFAULT NULL COMMENT '删除时间',
                                            PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '通知记录表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for la_tenant_notice_setting
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_notice_setting_{tenantSn}`;
CREATE TABLE `la_tenant_notice_setting_{tenantSn}`  (
                                             `id` int(11) NOT NULL AUTO_INCREMENT,
                                             `tenant_id` int(11) NOT NULL COMMENT '租户ID',
                                             `scene_id` int(10) NOT NULL COMMENT '场景id',
                                             `scene_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '场景名称',
                                             `scene_desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '场景描述',
                                             `recipient` tinyint(1) NOT NULL DEFAULT 1 COMMENT '接收者 1-用户 2-平台',
                                             `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '通知类型: 1-业务通知 2-验证码',
                                             `system_notice` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '系统通知设置',
                                             `sms_notice` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '短信通知设置',
                                             `oa_notice` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '公众号通知设置',
                                             `mnp_notice` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '小程序通知设置',
                                             `support` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '支持的发送类型 1-系统通知 2-短信通知 3-微信模板消息 4-小程序提醒',
                                             `update_time` int(10) NULL DEFAULT NULL COMMENT '更新时间',
                                             PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '通知设置表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for la_tenant_pay_config
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_pay_config_{tenantSn}`;
CREATE TABLE `la_tenant_pay_config_{tenantSn}`  (
                                         `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                                         `tenant_id` int(11) NOT NULL COMMENT '租户ID',
                                         `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '模版名称',
                                         `pay_way` tinyint(1) NOT NULL COMMENT '支付方式:1-余额支付;2-微信支付;3-支付宝支付;',
                                         `config` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '对应支付配置(json字符串)',
                                         `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '图标',
                                         `sort` int(5) NULL DEFAULT NULL COMMENT '排序',
                                         `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '备注',
                                         PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '支付配置表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for la_tenant_pay_way
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_pay_way_{tenantSn}`;
CREATE TABLE `la_tenant_pay_way_{tenantSn}`  (
                                      `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                                      `tenant_id` int(11) NOT NULL COMMENT '租户ID',
                                      `pay_config_id` int(11) NOT NULL COMMENT '支付配置ID',
                                      `scene` tinyint(1) NOT NULL COMMENT '场景:1-微信小程序;2-微信公众号;3-H5;4-PC;5-APP;',
                                      `is_default` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否默认支付:0-否;1-是;',
                                      `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态:0-关闭;1-开启;',
                                      PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '支付方式表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for la_tenant_system_menu
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_system_menu_{tenantSn}`;
CREATE TABLE `la_tenant_system_menu_{tenantSn}`  (
                                          `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
                                          `tenant_id` int(11) NOT NULL COMMENT '租户ID',
                                          `pid` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上级菜单',
                                          `type` char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '权限类型: M=目录，C=菜单，A=按钮',
                                          `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '菜单名称',
                                          `icon` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '菜单图标',
                                          `sort` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '菜单排序',
                                          `perms` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '权限标识',
                                          `paths` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '路由地址',
                                          `component` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '前端组件',
                                          `selected` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '选中路径',
                                          `params` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '路由参数',
                                          `is_cache` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否缓存: 0=否, 1=是',
                                          `is_show` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否显示: 0=否, 1=是',
                                          `is_disable` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否禁用: 0=否, 1=是',
                                          `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
                                          `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
                                          PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 301 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '系统菜单表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for la_tenant_system_role
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_system_role_{tenantSn}`;
CREATE TABLE `la_tenant_system_role_{tenantSn}`  (
                                          `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                                          `tenant_id` int(11) NOT NULL COMMENT '租户ID',
                                          `name` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '名称',
                                          `desc` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '描述',
                                          `sort` int(11) NULL DEFAULT 0 COMMENT '排序',
                                          `create_time` int(10) NULL DEFAULT NULL COMMENT '创建时间',
                                          `update_time` int(10) NULL DEFAULT NULL COMMENT '更新时间',
                                          `delete_time` int(10) NULL DEFAULT NULL COMMENT '删除时间',
                                          PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '角色表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for la_tenant_system_role_menu
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_system_role_menu_{tenantSn}`;
CREATE TABLE `la_tenant_system_role_menu_{tenantSn}`  (
                                               `role_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '角色ID',
                                               `menu_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '菜单ID',
                                               PRIMARY KEY (`role_id`, `menu_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '角色菜单关系表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for la_user
-- ----------------------------
DROP TABLE IF EXISTS `la_user_{tenantSn}`;
CREATE TABLE `la_user_{tenantSn}`  (
                            `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
                            `tenant_id` int(11) NOT NULL COMMENT '租户ID',
                            `sn` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '编号',
                            `avatar` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '头像',
                            `real_name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '真实姓名',
                            `nickname` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户昵称',
                            `account` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户账号',
                            `password` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户密码',
                            `mobile` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户电话',
                            `sex` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户性别: [1=男, 2=女]',
                            `channel` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '注册渠道: [1-微信小程序 2-微信公众号 3-手机H5 4-电脑PC 5-苹果APP 6-安卓APP]',
                            `is_disable` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否禁用: [0=否, 1=是]',
                            `login_ip` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '最后登录IP',
                            `login_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '最后登录时间',
                            `is_new_user` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否是新注册用户: [1-是, 0-否]',
                            `user_money` decimal(10, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '用户余额',
                            `total_recharge_amount` decimal(10, 2) UNSIGNED NULL DEFAULT 0.00 COMMENT '累计充值',
                            `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
                            `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
                            `delete_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '删除时间',
                            PRIMARY KEY (`id`) USING BTREE,
                            UNIQUE INDEX `sn`(`sn`) USING BTREE COMMENT '编号唯一',
                            UNIQUE INDEX `account`(`account`) USING BTREE COMMENT '账号唯一'
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for la_user_account_log
-- ----------------------------
DROP TABLE IF EXISTS `la_user_account_log_{tenantSn}`;
CREATE TABLE `la_user_account_log_{tenantSn}`  (
                                        `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                                        `tenant_id` int(11) NOT NULL COMMENT '租户ID',
                                        `sn` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '流水号',
                                        `user_id` int(11) NOT NULL COMMENT '用户id',
                                        `change_object` tinyint(1) NOT NULL DEFAULT 0 COMMENT '变动对象',
                                        `change_type` smallint(5) NOT NULL COMMENT '变动类型',
                                        `action` tinyint(1) NOT NULL DEFAULT 0 COMMENT '动作 1-增加 2-减少',
                                        `change_amount` decimal(10, 2) NOT NULL COMMENT '变动数量',
                                        `left_amount` decimal(10, 2) NOT NULL DEFAULT 100.00 COMMENT '变动后数量',
                                        `source_sn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '关联单号',
                                        `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '备注',
                                        `extra` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '预留扩展字段',
                                        `create_time` int(10) NULL DEFAULT NULL COMMENT '创建时间',
                                        `update_time` int(10) NULL DEFAULT NULL COMMENT '更新时间',
                                        `delete_time` int(10) NULL DEFAULT NULL COMMENT '删除时间',
                                        PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户账户变动记录表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for la_user_auth
-- ----------------------------
DROP TABLE IF EXISTS `la_user_auth_{tenantSn}`;
CREATE TABLE `la_user_auth_{tenantSn}`  (
                                 `id` int(11) NOT NULL AUTO_INCREMENT,
                                 `tenant_id` int(11) NOT NULL COMMENT '租户ID',
                                 `user_id` int(11) NOT NULL COMMENT '用户id',
                                 `openid` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '微信openid',
                                 `unionid` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '微信unionid',
                                 `terminal` tinyint(1) NOT NULL DEFAULT 1 COMMENT '客户端类型：1-微信小程序；2-微信公众号；3-手机H5；4-电脑PC；5-苹果APP；6-安卓APP',
                                 `create_time` int(10) NULL DEFAULT NULL COMMENT '创建时间',
                                 `update_time` int(10) NULL DEFAULT NULL COMMENT '更新时间',
                                 PRIMARY KEY (`id`) USING BTREE,
                                 UNIQUE INDEX `openid`(`openid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户授权表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for la_user_session
-- ----------------------------
DROP TABLE IF EXISTS `la_user_session_{tenantSn}`;
CREATE TABLE `la_user_session_{tenantSn}`  (
                                    `id` int(11) NOT NULL AUTO_INCREMENT,
                                    `tenant_id` int(11) NOT NULL COMMENT '租户ID',
                                    `user_id` int(11) NOT NULL COMMENT '用户id',
                                    `terminal` tinyint(1) NOT NULL DEFAULT 1 COMMENT '客户端类型：1-微信小程序；2-微信公众号；3-手机H5；4-电脑PC；5-苹果APP；6-安卓APP',
                                    `token` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '令牌',
                                    `update_time` int(10) NULL DEFAULT NULL COMMENT '更新时间',
                                    `expire_time` int(10) NOT NULL COMMENT '到期时间',
                                    PRIMARY KEY (`id`) USING BTREE,
                                    UNIQUE INDEX `admin_id_client`(`user_id`, `terminal`) USING BTREE COMMENT '一个用户在一个终端只有一个token',
                                    UNIQUE INDEX `token`(`token`) USING BTREE COMMENT 'token是唯一的'
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '用户会话表' ROW_FORMAT = DYNAMIC;


-- ----------------------------
-- Table structure for la_article
-- ----------------------------
DROP TABLE IF EXISTS `la_article_{tenantSn}`;
CREATE TABLE `la_article_{tenantSn}`  (
                               `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '文章id',
                               `tenant_id` int(11) NOT NULL COMMENT '租户ID',
                               `cid` int(11) NOT NULL COMMENT '文章分类',
                               `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '文章标题',
                               `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '简介',
                               `abstract` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '文章摘要',
                               `image` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文章图片',
                               `author` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '' COMMENT '作者',
                               `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '文章内容',
                               `click_virtual` int(10) NULL DEFAULT 0 COMMENT '虚拟浏览量',
                               `click_actual` int(11) NULL DEFAULT 0 COMMENT '实际浏览量',
                               `is_show` tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否显示:1-是.0-否',
                               `sort` int(5) NULL DEFAULT 0 COMMENT '排序',
                               `create_time` int(11) NULL DEFAULT NULL COMMENT '创建时间',
                               `update_time` int(11) NULL DEFAULT NULL COMMENT '更新时间',
                               `delete_time` int(11) NULL DEFAULT NULL COMMENT '删除时间',
                               PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '文章表' ROW_FORMAT = DYNAMIC;


-- ----------------------------
-- Table structure for la_article_cate
-- ----------------------------
DROP TABLE IF EXISTS `la_article_cate_{tenantSn}`;
CREATE TABLE `la_article_cate_{tenantSn}`  (
                                    `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '文章分类id',
                                    `tenant_id` int(11) NOT NULL COMMENT '租户ID',
                                    `name` varchar(90) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '分类名称',
                                    `sort` int(11) NULL DEFAULT 0 COMMENT '排序',
                                    `is_show` tinyint(1) NULL DEFAULT 1 COMMENT '是否显示:1-是;0-否',
                                    `create_time` int(10) NULL DEFAULT NULL COMMENT '创建时间',
                                    `update_time` int(10) NULL DEFAULT NULL COMMENT '更新时间',
                                    `delete_time` int(10) NULL DEFAULT NULL COMMENT '删除时间',
                                    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '文章分类表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for la_decorate_page_copy1
-- ----------------------------
DROP TABLE IF EXISTS `la_decorate_page_{tenantSn}`;
CREATE TABLE `la_decorate_page_{tenantSn}`  (
                                           `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
                                           `tenant_id` int(10) NOT NULL COMMENT '租户ID',
                                           `type` tinyint(2) UNSIGNED NOT NULL DEFAULT 10 COMMENT '页面类型 1=商城首页, 2=个人中心, 3=客服设置 4-PC首页',
                                           `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '页面名称',
                                           `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '页面数据',
                                           `meta` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL COMMENT '页面设置',
                                           `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
                                           `update_time` int(10) UNSIGNED NOT NULL COMMENT '更新时间',
                                           PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 46 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '装修页面配置表' ROW_FORMAT = DYNAMIC;


-- ----------------------------
-- Table structure for la_decorate_tabbar
-- ----------------------------
DROP TABLE IF EXISTS `la_decorate_tabbar_{tenantSn}`;
CREATE TABLE `la_decorate_tabbar_{tenantSn}`  (
                                       `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键',
                                       `tenant_id` int(10) NOT NULL COMMENT '租户ID',
                                       `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '导航名称',
                                       `selected` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '未选图标',
                                       `unselected` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '已选图标',
                                       `link` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '链接地址',
                                       `is_show` tinyint(255) UNSIGNED NOT NULL DEFAULT 1 COMMENT '显示状态',
                                       `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
                                       `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '更新时间',
                                       PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 28 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '装修底部导航表' ROW_FORMAT = DYNAMIC;

SET FOREIGN_KEY_CHECKS = 1;