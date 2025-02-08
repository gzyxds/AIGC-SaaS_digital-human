SET NAMES utf8mb4;
SET
FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for la_admin
-- ----------------------------
DROP TABLE IF EXISTS `la_admin`;
CREATE TABLE `la_admin`
(
    `id`               int(11) UNSIGNED                                              NOT NULL AUTO_INCREMENT,
    `root`             tinyint(1) UNSIGNED                                           NOT NULL DEFAULT 0 COMMENT '是否超级管理员 0-否 1-是',
    `name`             varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  NOT NULL DEFAULT '' COMMENT '名称',
    `avatar`           varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户头像',
    `account`          varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  NOT NULL DEFAULT '' COMMENT '账号',
    `password`         varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  NOT NULL COMMENT '密码',
    `login_time`       int(10)                                                       NULL     DEFAULT NULL COMMENT '最后登录时间',
    `login_ip`         varchar(39) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT '' COMMENT '最后登录ip',
    `multipoint_login` tinyint(1) UNSIGNED                                           NULL     DEFAULT 1 COMMENT '是否支持多处登录：1-是；0-否；',
    `disable`          tinyint(1) UNSIGNED                                           NULL     DEFAULT 0 COMMENT '是否禁用：0-否；1-是；',
    `create_time`      int(10)                                                       NOT NULL COMMENT '创建时间',
    `update_time`      int(10)                                                       NULL     DEFAULT NULL COMMENT '修改时间',
    `delete_time`      int(10)                                                       NULL     DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '管理员表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_admin_dept
-- ----------------------------
DROP TABLE IF EXISTS `la_admin_dept`;
CREATE TABLE `la_admin_dept`
(
    `admin_id` int(10) NOT NULL DEFAULT 0 COMMENT '管理员id',
    `dept_id`  int(10) NOT NULL DEFAULT 0 COMMENT '部门id',
    PRIMARY KEY (`admin_id`, `dept_id`) USING BTREE
) ENGINE = InnoDB
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '部门关联表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_admin_jobs
-- ----------------------------
DROP TABLE IF EXISTS `la_admin_jobs`;
CREATE TABLE `la_admin_jobs`
(
    `admin_id` int(10) NOT NULL COMMENT '管理员id',
    `jobs_id`  int(10) NOT NULL COMMENT '岗位id',
    PRIMARY KEY (`admin_id`, `jobs_id`) USING BTREE
) ENGINE = InnoDB
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '岗位关联表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `la_admin_role`;
CREATE TABLE `la_admin_role`
(
    `admin_id` int(10) NOT NULL COMMENT '管理员id',
    `role_id`  int(10) NOT NULL COMMENT '角色id',
    PRIMARY KEY (`admin_id`, `role_id`) USING BTREE
) ENGINE = InnoDB
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '角色关联表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_admin_session
-- ----------------------------
DROP TABLE IF EXISTS `la_admin_session`;
CREATE TABLE `la_admin_session`
(
    `id`          int(11) UNSIGNED                                             NOT NULL AUTO_INCREMENT,
    `admin_id`    int(11) UNSIGNED                                             NOT NULL COMMENT '用户id',
    `terminal`    tinyint(1)                                                   NOT NULL DEFAULT 1 COMMENT '客户端类型：1-pc管理后台 2-mobile手机管理后台',
    `token`       varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '令牌',
    `update_time` int(10)                                                      NULL     DEFAULT NULL COMMENT '更新时间',
    `expire_time` int(10)                                                      NOT NULL COMMENT '到期时间',
    PRIMARY KEY (`id`) USING BTREE,
    UNIQUE INDEX `admin_id_client` (`admin_id`, `terminal`) USING BTREE COMMENT '一个用户在一个终端只有一个token',
    UNIQUE INDEX `token` (`token`) USING BTREE COMMENT 'token是唯一的'
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '管理员会话表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_article
-- ----------------------------
DROP TABLE IF EXISTS `la_article`;
CREATE TABLE `la_article`
(
    `id`            int(11)                                                       NOT NULL AUTO_INCREMENT COMMENT '文章id',
    `tenant_id`     int(11)                                                       NOT NULL COMMENT '租户ID',
    `cid`           int(11)                                                       NOT NULL COMMENT '文章分类',
    `title`         varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '文章标题',
    `desc`          varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT '' COMMENT '简介',
    `abstract`      text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci         NULL COMMENT '文章摘要',
    `image`         varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT NULL COMMENT '文章图片',
    `author`        varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT '' COMMENT '作者',
    `content`       text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci         NULL COMMENT '文章内容',
    `click_virtual` int(10)                                                       NULL     DEFAULT 0 COMMENT '虚拟浏览量',
    `click_actual`  int(11)                                                       NULL     DEFAULT 0 COMMENT '实际浏览量',
    `is_show`       tinyint(1)                                                    NOT NULL DEFAULT 1 COMMENT '是否显示:1-是.0-否',
    `sort`          int(5)                                                        NULL     DEFAULT 0 COMMENT '排序',
    `create_time`   int(11)                                                       NULL     DEFAULT NULL COMMENT '创建时间',
    `update_time`   int(11)                                                       NULL     DEFAULT NULL COMMENT '更新时间',
    `delete_time`   int(11)                                                       NULL     DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 4
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '文章表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of la_article
-- ----------------------------
BEGIN;
INSERT INTO `la_article`
VALUES (1, 0, 3, 'AI数字人-介绍',
        '数字人克隆市场的发展前景非常广阔，全球AI数字人市场正处于快速增长阶段，预计未来几年，中国虚拟数字人核心市场规模将达到数百亿元人民币。',
        '', '', '',
        '<p>数字人克隆市场的发展前景非常广阔，全球AI数字人市场正处于快速增长阶段，预计未来几年，中国虚拟数字人核心市场规模将达到数百亿元人民币。</p><p>视频平台上数字人的身影越来越多，企业不再需要雇佣专人亲自去拍摄每一条视频，有效地控制了成本，而且数字人效果好，出品质量稳定有保证！</p><p>数字人的应用场景非常多，可以作为企业会展迎宾、前台、打造商业IP、客服、产品讲解、影视表演等等，如下图所示，各行各业都将和数字人紧密联系！使用数字人来提高效率是大势所趋！</p><p><img src=\"resource/image/tenantapi/default/article_001.png\" alt=\"\" data-href=\"\" style=\"\"/></p><p>随着AIGC技术的发展，现在数字人克隆技术进步非常大！生成一个数字人的成本大大降低，使得普罗大众也有机会用上数字人来发展业务！而码多多基于SaaS多开模式的架构设计，可以构建多站点多渠道的推广路径，通过码多多AI数字人系统，打造AIGC数字人生成平台，正是站在时代风口卖铲子！</p><p>面对蓬勃的市场，永远没有早晚之分，最好的进场时机就是当下！<br></p><h2>数字人优点</h2><ol><li>无需真人出镜</li><li>无需背台词</li><li>口音纯正</li><li>可以随时反复修改台词和语音</li><li>标准化批量产出视频</li><li>说话自然逼真</li><li>用途广阔</li><li>无需额外雇佣主播</li><li>永远保持热情</li></ol>',
        0, 5, 1, 0, 1732866424, 1733188848, NULL);
INSERT INTO `la_article`
VALUES (2, 0, 2, 'AI数字人-功能介绍', '本篇文章将为您讲解AI数字人系统的各项功能。', '', '', '',
        '<p>本篇文章将为您讲解AI数字人系统的各项功能。</p><h3>【数据看板】</h3><p>可以查看生成数字人的进度，包含「合成中」、「已完成」两种状态。</p><h3>【声音克隆】</h3><p>添加音色：可通过手机「扫描二维码」或「跳转前往」录制声音并上传。</p><p>我的音色：包含所有已录制上传的音色，可试听或直接使用；通过「搜索」功能可以查找包含对应关键字标题的音色；点击「教程」可查看录制声音的具体要求和操作顺序。</p><p>系统音色：包含系统提供的公共音色，可根据「场景」和「类型」筛选。</p><h3>【声音合成】</h3><p>声音合成：可以根据选择的「克隆音色」，结合输入的文案来合成语音；「语速调节」可以修改合成语音的速度，最小为0.1倍，最大为2倍。在右侧「合成记录」面板可查看合成的语音记录，包含「全部」「完成」「合成中」「失败」4种状态。点击合成语音可在线播放。</p><h3>【数字分身】</h3><p>数字分身：「我的形象」可查看当前已有的克隆形象，使用「新建」可以通过上传实拍视频来合成数字分身（克隆形象）；点击形象可预览上传的视频效果，点击「使用」，选择「克隆音色」和「通道」，点击「开始合成」即可生成数字人；点击「批量合成」可以批量选择多个视频合成。「我的作品」面板可以查看合成的数字人视频作品，点击视频可以在线预览或者下载。</p><h3>【帮助】</h3><p>可以查看相关文章。</p><h3>【个人中心】</h3><p>个人信息：可查看与编辑「用户头像」「昵称」「手机号码」，可以查看当前「数字形象」「数字分身」「定制音色」「克隆声音」的数量。</p><p>充值中心：可以查看用户UID以及当前剩余算力。用户可以选择合适的充值套餐并支付来完成算力充值。</p><p>充值记录：可查看每笔充值记录的具体金额和日期。</p><p>余额明细：可查看每笔算力的具体用途和使用时间。</p>',
        0, 5, 1, 0, 1732866517, 1733188842, NULL);
INSERT INTO `la_article`
VALUES (3, 0, 1, 'AI数字人-创作流程', '用户可根据以下步骤来完成数字人视频的创作。', '', '', '',
        '<p>用户可根据以下步骤来完成数字人视频的创作：</p><p>第1步：单击【声音克隆】-【添加音色】，通过手机端或电脑端录制并上传声音文件。录制的时候应选择周边安静的环境，录音时发音清晰，情绪饱满，停顿恰当，可最大程度让克隆出来的声音更加真实自然。<br></p><p>第2步：试听音色确认无误后，点击【使用】后系统会自动跳转到【声音合成】面板，确保【选择音色】所选无误，填写任意标题，输入需要合成的文本，根据需要调整语速（一般情况下默认即可），点击【开始合成】，等待片刻，即可在右侧面板预览声音合成后的效果。<br></p><p>第3步：打开【数字分身】面板，点击【我的形象】-【新建】，按规范拍摄或上传需要克隆的人物视频，点击【开始创建】，等待片刻，即可在右侧面板预览视频效果。需要注意的是，该视频为用户上传的视频预览，并非系统合成后的效果预览。<br></p><p>第4步：点击视频右上角的【使用】按钮，选择需要合成的音色（即步骤2合成的声音），点击开始合成，等待片刻，即可在【我的作品】面板查看合成后的数字人视频效果。</p>',
        0, 7, 1, 0, 1732866561, 1733188841, NULL);
COMMIT;

-- ----------------------------
-- Table structure for la_article_cate
-- ----------------------------
DROP TABLE IF EXISTS `la_article_cate`;
CREATE TABLE `la_article_cate`
(
    `id`          int(11)                                                      NOT NULL AUTO_INCREMENT COMMENT '文章分类id',
    `tenant_id`   int(11)                                                      NOT NULL COMMENT '租户ID',
    `name`        varchar(90) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '分类名称',
    `sort`        int(11)                                                      NULL DEFAULT 0 COMMENT '排序',
    `is_show`     tinyint(1)                                                   NULL DEFAULT 1 COMMENT '是否显示:1-是;0-否',
    `create_time` int(10)                                                      NULL DEFAULT NULL COMMENT '创建时间',
    `update_time` int(10)                                                      NULL DEFAULT NULL COMMENT '更新时间',
    `delete_time` int(10)                                                      NULL DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 3
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '文章分类表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of la_article_cate
-- ----------------------------
BEGIN;
INSERT INTO `la_article_cate`
VALUES (1, 0, '声音克隆教程', 0, 1, 1663317280, 1663317280, NULL),
       (2, 0, '数字人克隆教程', 0, 1, 1663317280, 1663321464, NULL),
       (3, 0, '声音合成教程', 0, 1, 1727070858, 1727070858, NULL);
COMMIT;

-- ----------------------------
-- Table structure for la_article_collect
-- ----------------------------
DROP TABLE IF EXISTS `la_article_collect`;
CREATE TABLE `la_article_collect`
(
    `id`          int(10) UNSIGNED    NOT NULL AUTO_INCREMENT COMMENT '主键',
    `tenant_id`   int(11)             NOT NULL COMMENT '租户ID',
    `user_id`     int(10) UNSIGNED    NOT NULL DEFAULT 0 COMMENT '用户ID',
    `article_id`  int(10) UNSIGNED    NOT NULL DEFAULT 0 COMMENT '文章ID',
    `status`      tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '收藏状态 0-未收藏 1-已收藏',
    `create_time` int(10) UNSIGNED    NOT NULL DEFAULT 0 COMMENT '创建时间',
    `update_time` int(10) UNSIGNED    NOT NULL DEFAULT 0 COMMENT '更新时间',
    `delete_time` int(10)             NULL     DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '文章收藏表'
  ROW_FORMAT = Dynamic;
-- ----------------------------
-- Table structure for la_config
-- ----------------------------
DROP TABLE IF EXISTS `la_config`;
CREATE TABLE `la_config`
(
    `id`          int(11)                                                      NOT NULL AUTO_INCREMENT,
    `type`        varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT NULL COMMENT '类型',
    `name`        varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '名称',
    `value`       text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci        NULL COMMENT '值',
    `create_time` int(10)                                                      NULL     DEFAULT NULL COMMENT '创建时间',
    `update_time` int(10)                                                      NULL     DEFAULT NULL COMMENT '更新时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '配置表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_decorate_page
-- ----------------------------
DROP TABLE IF EXISTS `la_decorate_page`;
CREATE TABLE `la_decorate_page`
(
    `id`          int(10) UNSIGNED                                              NOT NULL AUTO_INCREMENT COMMENT '主键',
    `tenant_id`   int(10)                                                       NOT NULL COMMENT '租户ID',
    `type`        tinyint(2) UNSIGNED                                           NOT NULL DEFAULT 10 COMMENT '页面类型 1=商城首页, 2=个人中心, 3=客服设置 4-PC首页',
    `name`        varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '页面名称',
    `data`        text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci         NULL COMMENT '页面数据',
    `meta`        text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci         NULL COMMENT '页面设置',
    `create_time` int(10) UNSIGNED                                              NOT NULL DEFAULT 0 COMMENT '创建时间',
    `update_time` int(10) UNSIGNED                                              NOT NULL COMMENT '更新时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 6
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '装修页面配置表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of la_decorate_page
-- ----------------------------
BEGIN;
INSERT INTO `la_decorate_page`
VALUES (1, 0, 1, '商城首页',
        '[{"title":"首页轮播图","name":"banner","content":{"enabled":1,"data":[{"image":"/resource/image/mobile/banner01.png","is_show":"1"},{"is_show":"1","image":"/resource/image/mobile/banner02.png"}]},"styles":{}}]',
        '[{"title":"页面设置","name":"page-meta","content":{"title":"首页","bg_type":"2","bg_color":"#2F80ED","bg_image":"/resource/image/tenantapi/default/page_meta_bg01.png","text_color":"2","title_type":"2","title_img":"/resource/image/tenantapi/default/page_mate_title.png"},"styles":{}}]',
        1661757188, 1710989700);
INSERT INTO `la_decorate_page`
VALUES (2, 0, 2, '个人中心',
        '[{\"title\":\"用户信息\",\"name\":\"user-info\",\"disabled\":1,\"content\":{},\"styles\":{}},{\"title\":\"我的服务\",\"name\":\"my-service\",\"content\":{\"style\":1,\"title\":\"我的服务\",\"data\":[{\"image\":\"/resource/image/tenantapi/default/user_collect.png\",\"name\":\"我的收藏\",\"link\":{\"path\":\"/pages/collection/collection\",\"name\":\"我的收藏\",\"type\":\"shop\"},\"is_show\":\"1\"},{\"image\":\"/resource/image/tenantapi/default/user_setting.png\",\"name\":\"个人设置\",\"link\":{\"path\":\"/pages/user_set/user_set\",\"name\":\"个人设置\",\"type\":\"shop\"},\"is_show\":\"1\"},{\"image\":\"/resource/image/tenantapi/default/user_kefu.png\",\"name\":\"联系客服\",\"link\":{\"path\":\"/pages/customer_service/customer_service\",\"name\":\"联系客服\",\"type\":\"shop\"},\"is_show\":\"1\"},{\"image\":\"/resource/image/tenantapi/default/wallet.png\",\"name\":\"我的钱包\",\"link\":{\"path\":\"/packages/pages/user_wallet/user_wallet\",\"name\":\"我的钱包\",\"type\":\"shop\"},\"is_show\":\"1\"}],\"enabled\":1},\"styles\":{}},{\"title\":\"个人中心广告图\",\"name\":\"user-banner\",\"content\":{\"enabled\":1,\"data\":[{\"image\":\"/resource/image/tenantapi/default/user_ad01.png\",\"name\":\"\",\"link\":{\"path\":\"/pages/customer_service/customer_service\",\"name\":\"联系客服\",\"type\":\"shop\"},\"is_show\":\"1\"},{\"image\":\"/resource/image/tenantapi/default/user_ad02.png\",\"name\":\"\",\"link\":{\"path\":\"/pages/customer_service/customer_service\",\"name\":\"联系客服\",\"type\":\"shop\"},\"is_show\":\"1\"}]},\"styles\":{}}]',
        '[{\"title\":\"页面设置\",\"name\":\"page-meta\",\"content\":{\"title\":\"个人中心\",\"bg_type\":\"1\",\"bg_color\":\"#2F80ED\",\"bg_image\":\"\",\"text_color\":\"1\",\"title_type\":\"2\",\"title_img\":\"/resource/image/tenantapi/default/page_mate_title.png\"},\"styles\":{}}]',
        1661757188, 1710933097);
INSERT INTO `la_decorate_page`
VALUES (3, 0, 3, '客服设置',
        '[{\"title\":\"客服设置\",\"name\":\"customer-service\",\"content\":{\"title\":\"添加客服二维码\",\"time\":\"早上 9:30 - 19:00\",\"mobile\":\"18578768757\",\"qrcode\":\"/resource/image/common/kefu01.png\",\"remark\":\"长按添加客服或拨打客服热线\"},\"styles\":{}}]',
        '', 1661757188, 1710929953);
INSERT INTO `la_decorate_page`
VALUES (4, 0, 4, 'PC设置',
        '[{\"id\":\"m3rf384r8ocsy\",\"title\":\"标题设置\",\"name\":\"title\",\"isShow\":true,\"prop\":{\"title\":\"一站式AI数字人视频制作平台\",\"subtitle\":\"立即制作您的专属数字人视频\"}},{\"id\":\"m3rf384rojmy9\",\"title\":\"图片设置\",\"name\":\"banner\",\"isShow\":true,\"prop\":{\"data\":[{\"image\":\"resource/image/tenantapi/default/banner001.png\"},{\"image\":\"resource/image/tenantapi/default/banner002.png\"}]}}]',
        '', 1661757188, 1710990175);
INSERT INTO `la_decorate_page`
VALUES (5, 0, 5, '系统风格',
        '{\"themeColorId\":3,\"topTextColor\":\"white\",\"navigationBarColor\":\"#A74BFD\",\"themeColor1\":\"#A74BFD\",\"themeColor2\":\"#CB60FF\",\"buttonColor\":\"white\"}',
        '', 1710410915, 1710990415);
COMMIT;

-- ----------------------------
-- Table structure for la_decorate_tabbar
-- ----------------------------
DROP TABLE IF EXISTS `la_decorate_tabbar`;
CREATE TABLE `la_decorate_tabbar`
(
    `id`          int(10) UNSIGNED                                              NOT NULL AUTO_INCREMENT COMMENT '主键',
    `tenant_id`   int(10)                                                       NOT NULL COMMENT '租户ID',
    `name`        varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  NOT NULL DEFAULT '' COMMENT '导航名称',
    `selected`    varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '未选图标',
    `unselected`  varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '已选图标',
    `link`        varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT NULL COMMENT '链接地址',
    `is_show`     tinyint(255) UNSIGNED                                         NOT NULL DEFAULT 1 COMMENT '显示状态',
    `create_time` int(10) UNSIGNED                                              NOT NULL DEFAULT 0 COMMENT '创建时间',
    `update_time` int(10) UNSIGNED                                              NOT NULL DEFAULT 0 COMMENT '更新时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 4
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '装修底部导航表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of la_decorate_tabbar
-- ----------------------------
BEGIN;
INSERT INTO `la_decorate_tabbar`
VALUES (1, 0, '首页', 'resource/image/tenantapi/default/tabbar_home_sel.png',
        'resource/image/tenantapi/default/tabbar_home.png',
        '{\"path\":\"/pages/index/index\",\"name\":\"商城首页\",\"type\":\"shop\"}', 1, 1662688157, 1662688157);
INSERT INTO `la_decorate_tabbar`
VALUES (2, 0, '资讯', 'resource/image/tenantapi/default/tabbar_text_sel.png',
        'resource/image/tenantapi/default/tabbar_text.png',
        '{\"path\":\"/pages/news/news\",\"name\":\"帮助中心\",\"type\":\"shop\",\"canTab\":\"1\"}', 1, 1662688157,
        1662688157);
INSERT INTO `la_decorate_tabbar`
VALUES (3, 0, '我的', 'resource/image/tenantapi/default/tabbar_me_sel.png',
        'resource/image/tenantapi/default/tabbar_me.png',
        '{\"path\":\"/pages/user/user\",\"name\":\"个人中心\",\"type\":\"shop\",\"canTab\":\"1\"}', 1, 1662688157,
        1662688157);
COMMIT;

-- ----------------------------
-- Table structure for la_dept
-- ----------------------------
DROP TABLE IF EXISTS `la_dept`;
CREATE TABLE `la_dept`
(
    `id`          int(11)                                                      NOT NULL AUTO_INCREMENT COMMENT 'id',
    `name`        varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '部门名称',
    `pid`         bigint(20)                                                   NOT NULL DEFAULT 0 COMMENT '上级部门id',
    `sort`        int(11)                                                      NOT NULL DEFAULT 0 COMMENT '排序',
    `leader`      varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT NULL COMMENT '负责人',
    `mobile`      varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT NULL COMMENT '联系电话',
    `status`      tinyint(1)                                                   NOT NULL DEFAULT 0 COMMENT '部门状态（0停用 1正常）',
    `create_time` int(10)                                                      NOT NULL COMMENT '创建时间',
    `update_time` int(10)                                                      NULL     DEFAULT NULL COMMENT '修改时间',
    `delete_time` int(10)                                                      NULL     DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 2
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '部门表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of la_dept
-- ----------------------------
BEGIN;
INSERT INTO `la_dept`
VALUES (1, '公司', 0, 0, 'boss', '12345698745', 1, 1650592684, 1653640368, NULL);
COMMIT;

-- ----------------------------
-- Table structure for la_dev_crontab
-- ----------------------------
DROP TABLE IF EXISTS `la_dev_crontab`;
CREATE TABLE `la_dev_crontab`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT,
    `name`        varchar(32) NOT NULL COMMENT '定时任务名称',
    `type`        tinyint(1) NOT NULL COMMENT '类型 1-定时任务',
    `system`      tinyint(4) DEFAULT '0' COMMENT '是否系统任务 0-否 1-是',
    `remark`      varchar(255) DEFAULT '' COMMENT '备注',
    `command`     varchar(64) NOT NULL COMMENT '命令内容',
    `params`      varchar(64)  DEFAULT '' COMMENT '参数',
    `status`      tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 1-运行 2-停止 3-错误',
    `expression`  varchar(64) NOT NULL COMMENT '运行规则',
    `error`       varchar(256) DEFAULT NULL COMMENT '运行失败原因',
    `last_time`   int(11) DEFAULT NULL COMMENT '最后执行时间',
    `time`        varchar(64)  DEFAULT '0' COMMENT '实时执行时长',
    `max_time`    varchar(64)  DEFAULT '0' COMMENT '最大执行时长',
    `create_time` int(10) DEFAULT NULL COMMENT '创建时间',
    `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
    `delete_time` int(10) DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COMMENT='计划任务表';

-- ----------------------------
-- Records of la_dev_crontab
-- ----------------------------
BEGIN;
INSERT INTO `la_dev_crontab`
VALUES (1, '任务失败处理', 1, 0, '', 'task_success', '', 1, '* * * * *', '', 1733199567, '0.01', '0.02', 1733192776,
        1733192776, NULL);
INSERT INTO `la_dev_crontab`
VALUES (2, '退款查询处理', 1, 0, '', 'query_refund', '', 1, '* * * * *', '', 1733199567, '0.02', '0.07', 1733192813,
        1733192813, NULL);
INSERT INTO `la_dev_crontab`
VALUES (3, '全流程任务', 1, 0, '', 'complete_flow', '', 1, '* * * * *', '', 1733199567, '0.02', '0.00', 1735737173,
        1735737495, NULL);
COMMIT;

-- ----------------------------
-- Table structure for la_dict_data
-- ----------------------------
DROP TABLE IF EXISTS `la_dict_data`;
CREATE TABLE `la_dict_data`
(
    `id`          int(11)                                                       NOT NULL AUTO_INCREMENT COMMENT 'id',
    `name`        varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '数据名称',
    `value`       varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '数据值',
    `type_id`     int(11)                                                       NOT NULL COMMENT '字典类型id',
    `type_value`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '字典类型',
    `sort`        int(10)                                                       NULL     DEFAULT 0 COMMENT '排序值',
    `status`      tinyint(1)                                                    NOT NULL DEFAULT 0 COMMENT '状态 0-停用 1-正常',
    `remark`      varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT '' COMMENT '备注',
    `create_time` int(10)                                                       NOT NULL COMMENT '创建时间',
    `update_time` int(10)                                                       NULL     DEFAULT NULL COMMENT '修改时间',
    `delete_time` int(10)                                                       NULL     DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 14
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '字典数据表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of la_dict_data
-- ----------------------------
BEGIN;
INSERT INTO `la_dict_data`
VALUES (1, '隐藏', '0', 1, 'show_status', 0, 1, '', 1656381543, 1656381543, NULL);
INSERT INTO `la_dict_data`
VALUES (2, '显示', '1', 1, 'show_status', 0, 1, '', 1656381550, 1656381550, NULL);
INSERT INTO `la_dict_data`
VALUES (3, '进行中', '0', 2, 'business_status', 0, 1, '', 1656381410, 1656381410, NULL);
INSERT INTO `la_dict_data`
VALUES (4, '成功', '1', 2, 'business_status', 0, 1, '', 1656381437, 1656381437, NULL);
INSERT INTO `la_dict_data`
VALUES (5, '失败', '2', 2, 'business_status', 0, 1, '', 1656381449, 1656381449, NULL);
INSERT INTO `la_dict_data`
VALUES (6, '待处理', '0', 3, 'event_status', 0, 1, '', 1656381212, 1656381212, NULL);
INSERT INTO `la_dict_data`
VALUES (7, '已处理', '1', 3, 'event_status', 0, 1, '', 1656381315, 1656381315, NULL);
INSERT INTO `la_dict_data`
VALUES (8, '拒绝处理', '2', 3, 'event_status', 0, 1, '', 1656381331, 1656381331, NULL);
INSERT INTO `la_dict_data`
VALUES (9, '禁用', '1', 4, 'system_disable', 0, 1, '', 1656312030, 1656312030, NULL);
INSERT INTO `la_dict_data`
VALUES (10, '正常', '0', 4, 'system_disable', 0, 1, '', 1656312040, 1656312040, NULL);
INSERT INTO `la_dict_data`
VALUES (11, '未知', '0', 5, 'sex', 0, 1, '', 1656062988, 1656062988, NULL);
INSERT INTO `la_dict_data`
VALUES (12, '男', '1', 5, 'sex', 0, 1, '', 1656062999, 1656062999, NULL);
INSERT INTO `la_dict_data`
VALUES (13, '女', '2', 5, 'sex', 0, 1, '', 1656063009, 1656063009, NULL);
COMMIT;

-- ----------------------------
-- Table structure for la_dict_type
-- ----------------------------
DROP TABLE IF EXISTS `la_dict_type`;
CREATE TABLE `la_dict_type`
(
    `id`          int(11)                                                       NOT NULL AUTO_INCREMENT COMMENT 'id',
    `name`        varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '字典名称',
    `type`        varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '字典类型名称',
    `status`      tinyint(1)                                                    NOT NULL DEFAULT 0 COMMENT '状态 0-停用 1-正常',
    `remark`      varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT '' COMMENT '备注',
    `create_time` int(10)                                                       NOT NULL COMMENT '创建时间',
    `update_time` int(10)                                                       NULL     DEFAULT NULL COMMENT '修改时间',
    `delete_time` int(10)                                                       NULL     DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 6
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '字典类型表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of la_dict_type
-- ----------------------------
BEGIN;
INSERT INTO `la_dict_type`
VALUES (1, '显示状态', 'show_status', 1, '', 1656381520, 1656381520, NULL);
INSERT INTO `la_dict_type`
VALUES (2, '业务状态', 'business_status', 1, '', 1656381393, 1656381393, NULL);
INSERT INTO `la_dict_type`
VALUES (3, '事件状态', 'event_status', 1, '', 1656381075, 1656381075, NULL);
INSERT INTO `la_dict_type`
VALUES (4, '禁用状态', 'system_disable', 1, '', 1656311838, 1656311838, NULL);
INSERT INTO `la_dict_type`
VALUES (5, '用户性别', 'sex', 1, '', 1656062946, 1656380925, NULL);
COMMIT;

-- ----------------------------
-- Table structure for la_file
-- ----------------------------
DROP TABLE IF EXISTS `la_file`;
CREATE TABLE `la_file`
(
    `id`          int(10) UNSIGNED                                              NOT NULL AUTO_INCREMENT COMMENT '主键ID',
    `cid`         int(10) UNSIGNED                                              NOT NULL DEFAULT 0 COMMENT '类目ID',
    `source_id`   int(11) UNSIGNED                                              NOT NULL DEFAULT 0 COMMENT '上传者id',
    `source`      tinyint(1)                                                    NOT NULL DEFAULT 0 COMMENT '来源类型[0-后台,1-用户]',
    `type`        tinyint(2) UNSIGNED                                           NOT NULL DEFAULT 10 COMMENT '类型[10=图片, 20=视频]',
    `name`        varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '文件名称',
    `uri`         varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '文件路径',
    `create_time` int(10) UNSIGNED                                              NULL     DEFAULT NULL COMMENT '创建时间',
    `update_time` int(10)                                                       NULL     DEFAULT NULL COMMENT '更新时间',
    `delete_time` int(10)                                                       NULL     DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '文件表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_file_cate
-- ----------------------------
DROP TABLE IF EXISTS `la_file_cate`;
CREATE TABLE `la_file_cate`
(
    `id`          int(10) UNSIGNED                                             NOT NULL AUTO_INCREMENT COMMENT '主键ID',
    `pid`         int(10) UNSIGNED                                             NOT NULL DEFAULT 0 COMMENT '父级ID',
    `type`        tinyint(2) UNSIGNED                                          NOT NULL DEFAULT 10 COMMENT '类型[10=图片，20=视频，30=文件]',
    `name`        varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '分类名称',
    `create_time` int(10) UNSIGNED                                             NULL     DEFAULT NULL COMMENT '创建时间',
    `update_time` int(10) UNSIGNED                                             NULL     DEFAULT NULL COMMENT '更新时间',
    `delete_time` int(10) UNSIGNED                                             NULL     DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '文件分类表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_generate_column
-- ----------------------------
DROP TABLE IF EXISTS `la_generate_column`;
CREATE TABLE `la_generate_column`
(
    `id`             int(11)                                                       NOT NULL AUTO_INCREMENT COMMENT 'id',
    `table_id`       int(11)                                                       NOT NULL DEFAULT 0 COMMENT '表id',
    `column_name`    varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '字段名称',
    `column_comment` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '字段描述',
    `column_type`    varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '字段类型',
    `is_required`    tinyint(1)                                                    NULL     DEFAULT 0 COMMENT '是否必填 0-非必填 1-必填',
    `is_pk`          tinyint(1)                                                    NULL     DEFAULT 0 COMMENT '是否为主键 0-不是 1-是',
    `is_insert`      tinyint(1)                                                    NULL     DEFAULT 0 COMMENT '是否为插入字段 0-不是 1-是',
    `is_update`      tinyint(1)                                                    NULL     DEFAULT 0 COMMENT '是否为更新字段 0-不是 1-是',
    `is_lists`       tinyint(1)                                                    NULL     DEFAULT 0 COMMENT '是否为列表字段 0-不是 1-是',
    `is_query`       tinyint(1)                                                    NULL     DEFAULT 0 COMMENT '是否为查询字段 0-不是 1-是',
    `query_type`     varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT '=' COMMENT '查询类型',
    `view_type`      varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT 'input' COMMENT '显示类型',
    `dict_type`      varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT '' COMMENT '字典类型',
    `create_time`    int(10)                                                       NOT NULL COMMENT '创建时间',
    `update_time`    int(10)                                                       NULL     DEFAULT NULL COMMENT '修改时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '代码生成表字段信息表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_generate_table
-- ----------------------------
DROP TABLE IF EXISTS `la_generate_table`;
CREATE TABLE `la_generate_table`
(
    `id`            int(11)                                                       NOT NULL AUTO_INCREMENT COMMENT 'id',
    `table_name`    varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '表名称',
    `table_comment` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '表描述',
    `template_type` tinyint(1)                                                    NOT NULL DEFAULT 0 COMMENT '模板类型 0-单表(curd) 1-树表(curd)',
    `author`        varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT '' COMMENT '作者',
    `remark`        varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT '' COMMENT '备注',
    `generate_type` tinyint(1)                                                    NOT NULL DEFAULT 0 COMMENT '生成方式  0-压缩包下载 1-生成到模块',
    `module_name`   varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT '' COMMENT '模块名',
    `class_dir`     varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT '' COMMENT '类目录名',
    `class_comment` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT '' COMMENT '类描述',
    `admin_id`      int(11)                                                       NULL     DEFAULT 0 COMMENT '管理员id',
    `menu`          text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci         NULL COMMENT '菜单配置',
    `delete`        text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci         NULL COMMENT '删除配置',
    `tree`          text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci         NULL COMMENT '树表配置',
    `relations`     text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci         NULL COMMENT '关联配置',
    `create_time`   int(10)                                                       NOT NULL COMMENT '创建时间',
    `update_time`   int(10)                                                       NULL     DEFAULT NULL COMMENT '修改时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '代码生成表信息表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_hot_search
-- ----------------------------
DROP TABLE IF EXISTS `la_hot_search`;
CREATE TABLE `la_hot_search`
(
    `id`          int(10) UNSIGNED                                              NOT NULL AUTO_INCREMENT COMMENT '主键',
    `tenant_id`   int(11)                                                       NOT NULL COMMENT '租户ID',
    `name`        varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '关键词',
    `sort`        smallint(5) UNSIGNED                                          NOT NULL DEFAULT 0 COMMENT '排序号',
    `create_time` int(10)                                                       NULL     DEFAULT NULL COMMENT '创建时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '热门搜索表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_jobs
-- ----------------------------
DROP TABLE IF EXISTS `la_jobs`;
CREATE TABLE `la_jobs`
(
    `id`          int(11)                                                       NOT NULL AUTO_INCREMENT COMMENT 'id',
    `name`        varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '岗位名称',
    `code`        varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '岗位编码',
    `sort`        int(11)                                                       NULL     DEFAULT 0 COMMENT '显示顺序',
    `status`      tinyint(1)                                                    NOT NULL DEFAULT 0 COMMENT '状态（0停用 1正常）',
    `remark`      varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT NULL COMMENT '备注',
    `create_time` int(10)                                                       NOT NULL COMMENT '创建时间',
    `update_time` int(10)                                                       NULL     DEFAULT NULL COMMENT '修改时间',
    `delete_time` int(10)                                                       NULL     DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '岗位表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_notice_record
-- ----------------------------
DROP TABLE IF EXISTS `la_notice_record`;
CREATE TABLE `la_notice_record`
(
    `id`          int(10) UNSIGNED                                              NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `user_id`     int(10) UNSIGNED                                              NOT NULL COMMENT '用户id',
    `title`       varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标题',
    `content`     text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci         NOT NULL COMMENT '内容',
    `scene_id`    int(10) UNSIGNED                                              NULL     DEFAULT 0 COMMENT '场景',
    `read`        tinyint(1)                                                    NULL     DEFAULT 0 COMMENT '已读状态;0-未读,1-已读',
    `recipient`   tinyint(1)                                                    NULL     DEFAULT 0 COMMENT '通知接收对象类型;1-会员;2-商家;3-平台;4-游客(未注册用户)',
    `send_type`   tinyint(1)                                                    NULL     DEFAULT 0 COMMENT '通知发送类型 1-系统通知 2-短信通知 3-微信模板 4-微信小程序',
    `notice_type` tinyint(1)                                                    NULL     DEFAULT NULL COMMENT '通知类型 1-业务通知 2-验证码',
    `extra`       varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT '' COMMENT '其他',
    `create_time` int(10)                                                       NULL     DEFAULT NULL COMMENT '创建时间',
    `update_time` int(10)                                                       NULL     DEFAULT NULL COMMENT '更新时间',
    `delete_time` int(10)                                                       NULL     DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '通知记录表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_notice_setting
-- ----------------------------
DROP TABLE IF EXISTS `la_notice_setting`;
CREATE TABLE `la_notice_setting`
(
    `id`            int(11)                                                       NOT NULL AUTO_INCREMENT,
    `scene_id`      int(10)                                                       NOT NULL COMMENT '场景id',
    `scene_name`    varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '场景名称',
    `scene_desc`    varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '场景描述',
    `recipient`     tinyint(1)                                                    NOT NULL DEFAULT 1 COMMENT '接收者 1-用户 2-平台',
    `type`          tinyint(1)                                                    NOT NULL DEFAULT 1 COMMENT '通知类型: 1-业务通知 2-验证码',
    `system_notice` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci         NULL COMMENT '系统通知设置',
    `sms_notice`    text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci         NULL COMMENT '短信通知设置',
    `oa_notice`     text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci         NULL COMMENT '公众号通知设置',
    `mnp_notice`    text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci         NULL COMMENT '小程序通知设置',
    `support`       char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci     NOT NULL DEFAULT '' COMMENT '支持的发送类型 1-系统通知 2-短信通知 3-微信模板消息 4-小程序提醒',
    `update_time`   int(10)                                                       NULL     DEFAULT NULL COMMENT '更新时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 5
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '通知设置表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of la_notice_setting
-- ----------------------------
BEGIN;
INSERT INTO `la_notice_setting`
VALUES (1, 101, '登录验证码', '用户手机号码登录时发送', 1, 2,
        '{\"type\":\"system\",\"title\":\"\",\"content\":\"\",\"status\":\"0\",\"is_show\":\"\",\"tips\":[\"可选变量 验证码:code\"]}',
        '{\"type\":\"sms\",\"template_id\":\"SMS_123456\",\"content\":\"您正在登录，验证码${code}，切勿将验证码泄露于他人，本条验证码有效期5分钟。\",\"status\":\"1\",\"is_show\":\"1\"}',
        '{\"type\":\"oa\",\"template_id\":\"\",\"template_sn\":\"\",\"name\":\"\",\"first\":\"\",\"remark\":\"\",\"tpl\":[],\"status\":\"0\",\"is_show\":\"\",\"tips\":[\"可选变量 验证码:code\",\"配置路径：小程序后台 > 功能 > 订阅消息\"]}',
        '{\"type\":\"mnp\",\"template_id\":\"\",\"template_sn\":\"\",\"name\":\"\",\"tpl\":[],\"status\":\"0\",\"is_show\":\"\",\"tips\":[\"可选变量 验证码:code\",\"配置路径：小程序后台 > 功能 > 订阅消息\"]}',
        '2', NULL);
INSERT INTO `la_notice_setting`
VALUES (2, 102, '绑定手机验证码', '用户绑定手机号码时发送', 1, 2,
        '{\"type\":\"system\",\"title\":\"\",\"content\":\"\",\"status\":\"0\",\"is_show\":\"\"}',
        '{\"type\":\"sms\",\"template_id\":\"SMS_123456\",\"content\":\"您正在绑定手机号，验证码${code}，切勿将验证码泄露于他人，本条验证码有效期5分钟。\",\"status\":\"1\",\"is_show\":\"1\"}',
        '{\"type\":\"oa\",\"template_id\":\"\",\"template_sn\":\"\",\"name\":\"\",\"first\":\"\",\"remark\":\"\",\"tpl\":[],\"status\":\"0\",\"is_show\":\"\"}',
        '{\"type\":\"mnp\",\"template_id\":\"\",\"template_sn\":\"\",\"name\":\"\",\"tpl\":[],\"status\":\"0\",\"is_show\":\"\"}',
        '2', NULL);
INSERT INTO `la_notice_setting`
VALUES (3, 103, '变更手机验证码', '用户变更手机号码时发送', 1, 2,
        '{\"type\":\"system\",\"title\":\"\",\"content\":\"\",\"status\":\"0\",\"is_show\":\"\",\"tips\":[\"可选变量 验证码:code\"]}',
        '{\"type\":\"sms\",\"template_id\":\"SMS_123456\",\"content\":\"您正在变更手机号，验证码${code}，切勿将验证码泄露于他人，本条验证码有效期5分钟。\",\"status\":\"1\",\"is_show\":\"1\"}',
        '{\"type\":\"oa\",\"template_id\":\"\",\"template_sn\":\"\",\"name\":\"\",\"first\":\"\",\"remark\":\"\",\"tpl\":[],\"status\":\"0\",\"is_show\":\"\",\"tips\":[\"可选变量 验证码:code\",\"配置路径：小程序后台 > 功能 > 订阅消息\"]}',
        '{\"type\":\"mnp\",\"template_id\":\"\",\"template_sn\":\"\",\"name\":\"\",\"tpl\":[],\"status\":\"0\",\"is_show\":\"\",\"tips\":[\"可选变量 验证码:code\",\"配置路径：小程序后台 > 功能 > 订阅消息\"]}',
        '2', NULL);
INSERT INTO `la_notice_setting`
VALUES (4, 104, '找回登录密码验证码', '用户找回登录密码号码时发送', 1, 2,
        '{\"type\":\"system\",\"title\":\"\",\"content\":\"\",\"status\":\"0\",\"is_show\":\"\",\"tips\":[\"可选变量 验证码:code\"]}',
        '{\"type\":\"sms\",\"template_id\":\"SMS_123456\",\"content\":\"您正在找回登录密码，验证码${code}，切勿将验证码泄露于他人，本条验证码有效期5分钟。\",\"status\":\"0\",\"is_show\":\"1\",\"tips\":[\"可选变量 验证码:code\",\"示例：您正在找回登录密码，验证码${code}，切勿将验证码泄露于他人，本条验证码有效期5分钟。\",\"生效条件：1、管理后台完成短信设置。 2、第三方短信平台申请模板。\"]}',
        '{\"type\":\"oa\",\"template_id\":\"\",\"template_sn\":\"\",\"name\":\"\",\"first\":\"\",\"remark\":\"\",\"tpl\":[],\"status\":\"0\",\"is_show\":\"\",\"tips\":[\"可选变量 验证码:code\",\"配置路径：小程序后台 > 功能 > 订阅消息\"]}',
        '{\"type\":\"mnp\",\"template_id\":\"\",\"template_sn\":\"\",\"name\":\"\",\"tpl\":[],\"status\":\"0\",\"is_show\":\"\",\"tips\":[\"可选变量 验证码:code\",\"配置路径：小程序后台 > 功能 > 订阅消息\"]}',
        '2', NULL);
COMMIT;

-- ----------------------------
-- Table structure for la_official_account_reply
-- ----------------------------
DROP TABLE IF EXISTS `la_official_account_reply`;
CREATE TABLE `la_official_account_reply`
(
    `id`            int(11) UNSIGNED                                             NOT NULL AUTO_INCREMENT,
    `tenant_id`     int(11)                                                      NOT NULL COMMENT '租户ID',
    `name`          varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '规则名称',
    `keyword`       varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '关键词',
    `reply_type`    tinyint(1)                                                   NOT NULL COMMENT '回复类型 1-关注回复 2-关键字回复 3-默认回复',
    `matching_type` tinyint(1) UNSIGNED                                          NOT NULL DEFAULT 1 COMMENT '匹配方式：1-全匹配；2-模糊匹配',
    `content_type`  tinyint(1) UNSIGNED                                          NOT NULL DEFAULT 1 COMMENT '内容类型：1-文本',
    `content`       text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci        NOT NULL COMMENT '回复内容',
    `status`        tinyint(1) UNSIGNED                                          NOT NULL DEFAULT 0 COMMENT '启动状态：1-启动；0-关闭',
    `sort`          int(11) UNSIGNED                                             NOT NULL DEFAULT 50 COMMENT '排序',
    `create_time`   int(10)                                                      NULL     DEFAULT NULL COMMENT '创建时间',
    `update_time`   int(10)                                                      NULL     DEFAULT NULL COMMENT '更新时间',
    `delete_time`   int(10)                                                      NULL     DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '公众号消息回调表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_operation_log
-- ----------------------------
DROP TABLE IF EXISTS `la_operation_log`;
CREATE TABLE `la_operation_log`
(
    `id`          int(11)                                                       NOT NULL AUTO_INCREMENT,
    `admin_id`    int(11)                                                       NOT NULL COMMENT '管理员ID',
    `admin_name`  varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  NOT NULL DEFAULT '' COMMENT '管理员名称',
    `account`     varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  NOT NULL DEFAULT '' COMMENT '管理员账号',
    `action`      varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT '' COMMENT '操作名称',
    `type`        varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci   NOT NULL COMMENT '请求方式',
    `url`         varchar(600) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '访问链接',
    `params`      text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci         NULL COMMENT '请求数据',
    `result`      text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci         NULL COMMENT '请求结果',
    `ip`          varchar(39) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  NOT NULL DEFAULT '' COMMENT 'ip地址',
    `create_time` int(10)                                                       NULL     DEFAULT NULL COMMENT '创建时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '系统日志表'
  ROW_FORMAT = Dynamic;



-- ----------------------------
-- Table structure for la_pay_config
-- ----------------------------
DROP TABLE IF EXISTS `la_pay_config`;
CREATE TABLE `la_pay_config`
(
    `id`      int(11) UNSIGNED                                              NOT NULL AUTO_INCREMENT,
    `name`    varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '模版名称',
    `pay_way` tinyint(1)                                                    NOT NULL COMMENT '支付方式:1-余额支付;2-微信支付;3-支付宝支付;',
    `config`  text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci         NULL COMMENT '对应支付配置(json字符串)',
    `icon`    varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT NULL COMMENT '图标',
    `sort`    int(5)                                                        NULL     DEFAULT NULL COMMENT '排序',
    `remark`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT NULL COMMENT '备注',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 4
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '支付配置表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of la_pay_config
-- ----------------------------
BEGIN;
INSERT INTO `la_pay_config`
VALUES (1, '余额支付', 1, '', 'resource/image/common/balance_pay.png', 128, '余额支付备注');
INSERT INTO `la_pay_config`
VALUES (2, '微信支付', 2,
        '{\"interface_version\":\"v3\",\"merchant_type\":\"ordinary_merchant\",\"mch_id\":\"\",\"pay_sign_key\":\"\",\"apiclient_cert\":\"\",\"apiclient_key\":\"\"}',
        '/resource/image/common/wechat_pay.png', 123, '微信支付备注');
INSERT INTO `la_pay_config`
VALUES (3, '支付宝支付', 3,
        '{\"mode\":\"normal_mode\",\"merchant_type\":\"ordinary_merchant\",\"app_id\":\"\",\"private_key\":\"\",\"ali_public_key\":\"\"}',
        '/resource/image/common/ali_pay.png', 123, '支付宝支付');
COMMIT;

-- ----------------------------
-- Table structure for la_pay_way
-- ----------------------------
DROP TABLE IF EXISTS `la_pay_way`;
CREATE TABLE `la_pay_way`
(
    `id`            int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `pay_config_id` int(11)          NOT NULL COMMENT '支付配置ID',
    `scene`         tinyint(1)       NOT NULL COMMENT '场景:1-微信小程序;2-微信公众号;3-H5;4-PC;5-APP;',
    `is_default`    tinyint(1)       NOT NULL DEFAULT 0 COMMENT '是否默认支付:0-否;1-是;',
    `status`        tinyint(1)       NOT NULL DEFAULT 1 COMMENT '状态:0-关闭;1-开启;',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 8
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '支付方式表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of la_pay_way
-- ----------------------------
BEGIN;
INSERT INTO `la_pay_way`
VALUES (1, 1, 1, 0, 1);
INSERT INTO `la_pay_way`
VALUES (2, 2, 1, 1, 1);
INSERT INTO `la_pay_way`
VALUES (3, 1, 2, 0, 1);
INSERT INTO `la_pay_way`
VALUES (4, 2, 2, 1, 1);
INSERT INTO `la_pay_way`
VALUES (5, 1, 3, 0, 1);
INSERT INTO `la_pay_way`
VALUES (6, 2, 3, 1, 1);
INSERT INTO `la_pay_way`
VALUES (7, 3, 3, 0, 1);
COMMIT;

-- ----------------------------
-- Table structure for la_recharge_order
-- ----------------------------
DROP TABLE IF EXISTS `la_recharge_order`;
CREATE TABLE `la_recharge_order`
(
    `id`                    int(11)                                                       NOT NULL AUTO_INCREMENT COMMENT 'id',
    `tenant_id`             int(11)                                                       NOT NULL COMMENT '租户ID',
    `sn`                    varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '订单编号',
    `user_id`               int(11)                                                       NOT NULL COMMENT '用户id',
    `pay_sn`                varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT '' COMMENT '支付编号-冗余字段，针对微信同一主体不同客户端支付需用不同订单号预留。',
    `pay_way`               tinyint(2)                                                    NOT NULL DEFAULT 2 COMMENT '支付方式 2-微信支付 3-支付宝支付',
    `pay_status`            tinyint(1)                                                    NOT NULL DEFAULT 0 COMMENT '支付状态：0-待支付；1-已支付',
    `pay_time`              int(10)                                                       NULL     DEFAULT NULL COMMENT '支付时间',
    `order_amount`          decimal(10, 2)                                               NOT NULL COMMENT '消费金额',
    `power`                 varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT NULL COMMENT '充值算力',
    `order_terminal`        tinyint(1)                                                    NULL     DEFAULT 1 COMMENT '终端',
    `transaction_id`        varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT NULL COMMENT '第三方平台交易流水号',
    `refund_status`         tinyint(1)                                                    NULL     DEFAULT 0 COMMENT '退款状态 0-未退款 1-已退款',
    `refund_transaction_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT NULL COMMENT '退款交易流水号',
    `create_time`           int(10)                                                       NULL     DEFAULT NULL COMMENT '创建时间',
    `update_time`           int(10)                                                       NULL     DEFAULT NULL COMMENT '更新时间',
    `delete_time`           int(10)                                                       NULL     DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '充值订单表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_refund_log
-- ----------------------------
DROP TABLE IF EXISTS `la_refund_log`;
CREATE TABLE `la_refund_log`
(
    `id`            int(11)                                                      NOT NULL AUTO_INCREMENT COMMENT 'id',
    `tenant_id`     int(11)                                                      NOT NULL COMMENT '租户ID',
    `sn`            varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT NULL COMMENT '编号',
    `record_id`     int(11)                                                      NOT NULL COMMENT '退款记录id',
    `user_id`       int(11)                                                      NOT NULL DEFAULT 0 COMMENT '关联用户',
    `handle_id`     int(11)                                                      NOT NULL DEFAULT 0 COMMENT '处理人id（管理员id）',
    `order_amount`  decimal(10, 2) UNSIGNED                                      NOT NULL DEFAULT 0.00 COMMENT '订单总的应付款金额，冗余字段',
    `refund_amount` decimal(10, 2) UNSIGNED                                      NOT NULL DEFAULT 0.00 COMMENT '本次退款金额',
    `refund_status` tinyint(1) UNSIGNED                                          NOT NULL DEFAULT 0 COMMENT '退款状态，0退款中，1退款成功，2退款失败',
    `refund_msg`    text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci        NULL COMMENT '退款信息',
    `create_time`   int(10) UNSIGNED                                             NULL     DEFAULT 0 COMMENT '创建时间',
    `update_time`   int(10)                                                      NULL     DEFAULT NULL COMMENT '更新时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '退款日志'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_refund_record
-- ----------------------------
DROP TABLE IF EXISTS `la_refund_record`;
CREATE TABLE `la_refund_record`
(
    `id`             int(11)                                                       NOT NULL AUTO_INCREMENT COMMENT 'id',
    `tenant_id`      int(11)                                                       NOT NULL COMMENT '租户ID',
    `sn`             varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '退款编号',
    `user_id`        int(11)                                                       NOT NULL DEFAULT 0 COMMENT '关联用户',
    `order_id`       int(11)                                                       NOT NULL DEFAULT 0 COMMENT '来源订单id',
    `order_sn`       varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '来源单号',
    `order_type`     varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT 'order' COMMENT '订单来源 order-商品订单 recharge-充值订单',
    `order_amount`   decimal(10, 2) UNSIGNED                                       NOT NULL DEFAULT 0.00 COMMENT '订单总的应付款金额，冗余字段',
    `refund_amount`  decimal(10, 2) UNSIGNED                                       NOT NULL DEFAULT 0.00 COMMENT '本次退款金额',
    `transaction_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT NULL COMMENT '第三方平台交易流水号',
    `refund_way`     tinyint(1)                                                    NOT NULL DEFAULT 1 COMMENT '退款方式 1-线上退款 2-线下退款',
    `refund_type`    tinyint(1)                                                    NOT NULL DEFAULT 1 COMMENT '退款类型 1-后台退款',
    `refund_status`  tinyint(1) UNSIGNED                                           NOT NULL DEFAULT 0 COMMENT '退款状态，0退款中，1退款成功，2退款失败',
    `create_time`    int(10) UNSIGNED                                              NULL     DEFAULT 0 COMMENT '创建时间',
    `update_time`    int(10)                                                       NULL     DEFAULT NULL COMMENT '更新时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '退款记录'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_sms_log
-- ----------------------------
DROP TABLE IF EXISTS `la_sms_log`;
CREATE TABLE `la_sms_log`
(
    `id`          int(11)                                                       NOT NULL AUTO_INCREMENT COMMENT 'id',
    `tenant_id`   int(11)                                                       NOT NULL COMMENT '租户ID',
    `scene_id`    int(11)                                                       NOT NULL COMMENT '场景id',
    `mobile`      varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  NOT NULL COMMENT '手机号码',
    `content`     varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '发送内容',
    `code`        varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '发送关键字（注册、找回密码）',
    `is_verify`   tinyint(1)                                                    NULL DEFAULT 0 COMMENT '是否已验证；0-否；1-是',
    `check_num`   int(5)                                                        NULL DEFAULT 0 COMMENT '验证次数',
    `send_status` tinyint(1)                                                    NOT NULL COMMENT '发送状态：0-发送中；1-发送成功；2-发送失败',
    `send_time`   int(10)                                                       NOT NULL COMMENT '发送时间',
    `results`     text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci         NULL COMMENT '短信结果',
    `create_time` int(10)                                                       NULL DEFAULT NULL COMMENT '创建时间',
    `update_time` int(10)                                                       NULL DEFAULT NULL COMMENT '更新时间',
    `delete_time` int(10)                                                       NULL DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '短信记录表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_system_menu
-- ----------------------------
DROP TABLE IF EXISTS `la_system_menu`;
CREATE TABLE `la_system_menu`
(
    `id`          int(10) UNSIGNED                                              NOT NULL AUTO_INCREMENT COMMENT '主键',
    `pid`         int(10) UNSIGNED                                              NOT NULL DEFAULT 0 COMMENT '上级菜单',
    `type`        char(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci      NOT NULL DEFAULT '' COMMENT '权限类型: M=目录，C=菜单，A=按钮',
    `name`        varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '菜单名称',
    `icon`        varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '菜单图标',
    `sort`        smallint(5) UNSIGNED                                          NOT NULL DEFAULT 0 COMMENT '菜单排序',
    `perms`       varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '权限标识',
    `paths`       varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '路由地址',
    `component`   varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '前端组件',
    `selected`    varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '选中路径',
    `params`      varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '路由参数',
    `is_cache`    tinyint(1) UNSIGNED                                           NOT NULL DEFAULT 0 COMMENT '是否缓存: 0=否, 1=是',
    `is_show`     tinyint(1) UNSIGNED                                           NOT NULL DEFAULT 1 COMMENT '是否显示: 0=否, 1=是',
    `is_disable`  tinyint(1) UNSIGNED                                           NOT NULL DEFAULT 0 COMMENT '是否禁用: 0=否, 1=是',
    `create_time` int(10) UNSIGNED                                              NOT NULL DEFAULT 0 COMMENT '创建时间',
    `update_time` int(10) UNSIGNED                                              NOT NULL DEFAULT 0 COMMENT '更新时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 166
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '系统菜单表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of la_system_menu
-- ----------------------------
BEGIN;
INSERT INTO `la_system_menu`
VALUES (4, 0, 'M', '权限管理', 'el-icon-Lock', 300, '', 'permission', '', '', '', 0, 1, 0, 1656664556, 1710472802);
INSERT INTO `la_system_menu`
VALUES (5, 0, 'C', '工作台', 'el-icon-Monitor', 1000, 'workbench/index', 'workbench', 'workbench/index', '', '', 0, 1,
        0, 1656664793, 1664354981);
INSERT INTO `la_system_menu`
VALUES (6, 4, 'C', '菜单', 'el-icon-Operation', 100, 'auth.menu/lists', 'menu', 'permission/menu/index', '', '', 1, 1,
        0, 1656664960, 1710472994);
INSERT INTO `la_system_menu`
VALUES (7, 4, 'C', '管理员', 'local-icon-shouyiren', 80, 'auth.admin/lists', 'admin', 'permission/admin/index', '', '',
        0, 1, 0, 1656901567, 1710473013);
INSERT INTO `la_system_menu`
VALUES (8, 4, 'C', '角色', 'el-icon-Female', 90, 'auth.role/lists', 'role', 'permission/role/index', '', '', 0, 1, 0,
        1656901660, 1710473000);
INSERT INTO `la_system_menu`
VALUES (12, 8, 'A', '新增', '', 1, 'auth.role/add', '', '', '', '', 0, 1, 0, 1657001790, 1663750625);
INSERT INTO `la_system_menu`
VALUES (14, 8, 'A', '编辑', '', 1, 'auth.role/edit', '', '', '', '', 0, 1, 0, 1657001924, 1663750631);
INSERT INTO `la_system_menu`
VALUES (15, 8, 'A', '删除', '', 1, 'auth.role/delete', '', '', '', '', 0, 1, 0, 1657001982, 1663750637);
INSERT INTO `la_system_menu`
VALUES (16, 6, 'A', '新增', '', 1, 'auth.menu/add', '', '', '', '', 0, 1, 0, 1657072523, 1663750565);
INSERT INTO `la_system_menu`
VALUES (17, 6, 'A', '编辑', '', 1, 'auth.menu/edit', '', '', '', '', 0, 1, 0, 1657073955, 1663750570);
INSERT INTO `la_system_menu`
VALUES (18, 6, 'A', '删除', '', 1, 'auth.menu/delete', '', '', '', '', 0, 1, 0, 1657073987, 1663750578);
INSERT INTO `la_system_menu`
VALUES (19, 7, 'A', '新增', '', 1, 'auth.admin/add', '', '', '', '', 0, 1, 0, 1657074035, 1663750596);
INSERT INTO `la_system_menu`
VALUES (20, 7, 'A', '编辑', '', 1, 'auth.admin/edit', '', '', '', '', 0, 1, 0, 1657074071, 1663750603);
INSERT INTO `la_system_menu`
VALUES (21, 7, 'A', '删除', '', 1, 'auth.admin/delete', '', '', '', '', 0, 1, 0, 1657074108, 1663750609);
INSERT INTO `la_system_menu`
VALUES (23, 28, 'M', '开发工具', 'el-icon-EditPen', 40, '', 'dev_tools', '', '', '', 0, 1, 0, 1657097744, 1710473127);
INSERT INTO `la_system_menu`
VALUES (24, 23, 'C', '代码生成器', 'el-icon-DocumentAdd', 1, 'tools.generator/generateTable', 'code',
        'dev_tools/code/index', '', '', 0, 1, 0, 1657098110, 1658989423);
INSERT INTO `la_system_menu`
VALUES (25, 0, 'M', '组织管理', 'el-icon-OfficeBuilding', 400, '', 'organization', '', '', '', 0, 1, 0, 1657099914,
        1710472797);
INSERT INTO `la_system_menu`
VALUES (26, 25, 'C', '部门管理', 'el-icon-Coordinate', 100, 'dept.dept/lists', 'department',
        'organization/department/index', '', '', 1, 1, 0, 1657099989, 1710472962);
INSERT INTO `la_system_menu`
VALUES (27, 25, 'C', '岗位管理', 'el-icon-PriceTag', 90, 'dept.jobs/lists', 'post', 'organization/post/index', '', '',
        0, 1, 0, 1657100044, 1710472967);
INSERT INTO `la_system_menu`
VALUES (28, 0, 'M', '系统设置', 'el-icon-Setting', 200, '', 'setting', '', '', '', 0, 1, 0, 1657100164, 1710472807);
INSERT INTO `la_system_menu`
VALUES (29, 28, 'M', '网站设置', 'el-icon-Basketball', 100, '', 'website', '', '', '', 0, 1, 0, 1657100230, 1710473049);
INSERT INTO `la_system_menu`
VALUES (30, 29, 'C', '网站信息', '', 1, 'setting.web.web_setting/getWebsite', 'information',
        'setting/website/information', '', '', 0, 1, 0, 1657100306, 1657164412);
INSERT INTO `la_system_menu`
VALUES (31, 29, 'C', '网站备案', '', 1, 'setting.web.web_setting/getCopyright', 'filing', 'setting/website/filing', '',
        '', 0, 1, 1, 1657100434, 1657164723);
INSERT INTO `la_system_menu`
VALUES (32, 29, 'C', '政策协议', '', 1, 'setting.web.web_setting/getAgreement', 'protocol', 'setting/website/protocol',
        '', '', 0, 1, 1, 1657100571, 1657164770);
INSERT INTO `la_system_menu`
VALUES (33, 28, 'C', '存储设置', 'el-icon-FolderOpened', 70, 'setting.storage/lists', 'storage',
        'setting/storage/index', '', '', 0, 1, 0, 1657160959, 1710473095);
INSERT INTO `la_system_menu`
VALUES (34, 23, 'C', '字典管理', 'el-icon-Box', 1, 'setting.dict.dict_type/lists', 'dict', 'setting/dict/type/index',
        '', '', 0, 1, 0, 1657161211, 1663225935);
INSERT INTO `la_system_menu`
VALUES (35, 28, 'M', '系统维护', 'el-icon-SetUp', 50, '', 'system', '', '', '', 0, 1, 0, 1657161569, 1710473122);
INSERT INTO `la_system_menu`
VALUES (36, 35, 'C', '系统日志', '', 90, 'setting.system.log/lists', 'journal', 'setting/system/journal', '', '', 0, 1,
        0, 1657161696, 1710473253);
INSERT INTO `la_system_menu`
VALUES (37, 35, 'C', '系统缓存', '', 80, '', 'cache', 'setting/system/cache', '', '', 0, 1, 0, 1657161896, 1710473258);
INSERT INTO `la_system_menu`
VALUES (38, 35, 'C', '系统环境', '', 70, 'setting.system.system/info', 'environment', 'setting/system/environment', '',
        '', 0, 1, 0, 1657162000, 1710473265);
INSERT INTO `la_system_menu`
VALUES (39, 24, 'A', '导入数据表', '', 1, 'tools.generator/selectTable', '', '', '', '', 0, 1, 0, 1657162736,
        1657162736);
INSERT INTO `la_system_menu`
VALUES (40, 24, 'A', '代码生成', '', 1, 'tools.generator/generate', '', '', '', '', 0, 1, 0, 1657162806, 1657162806);
INSERT INTO `la_system_menu`
VALUES (41, 23, 'C', '编辑数据表', '', 1, 'tools.generator/edit', 'code/edit', 'dev_tools/code/edit', '/dev_tools/code',
        '', 1, 0, 0, 1657162866, 1663748668);
INSERT INTO `la_system_menu`
VALUES (42, 24, 'A', '同步表结构', '', 1, 'tools.generator/syncColumn', '', '', '', '', 0, 1, 0, 1657162934,
        1657162934);
INSERT INTO `la_system_menu`
VALUES (43, 24, 'A', '删除数据表', '', 1, 'tools.generator/delete', '', '', '', '', 0, 1, 0, 1657163015, 1657163015);
INSERT INTO `la_system_menu`
VALUES (44, 24, 'A', '预览代码', '', 1, 'tools.generator/preview', '', '', '', '', 0, 1, 0, 1657163263, 1657163263);
INSERT INTO `la_system_menu`
VALUES (51, 30, 'A', '保存', '', 1, 'setting.web.web_setting/setWebsite', '', '', '', '', 0, 1, 0, 1657164469,
        1663750649);
INSERT INTO `la_system_menu`
VALUES (52, 31, 'A', '保存', '', 1, 'setting.web.web_setting/setCopyright', '', '', '', '', 0, 1, 0, 1657164692,
        1663750657);
INSERT INTO `la_system_menu`
VALUES (53, 32, 'A', '保存', '', 1, 'setting.web.web_setting/setAgreement', '', '', '', '', 0, 1, 0, 1657164824,
        1663750665);
INSERT INTO `la_system_menu`
VALUES (54, 33, 'A', '设置', '', 1, 'setting.storage/setup', '', '', '', '', 0, 1, 0, 1657165303, 1663750673);
INSERT INTO `la_system_menu`
VALUES (55, 34, 'A', '新增', '', 1, 'setting.dict.dict_type/add', '', '', '', '', 0, 1, 0, 1657166966, 1663750783);
INSERT INTO `la_system_menu`
VALUES (56, 34, 'A', '编辑', '', 1, 'setting.dict.dict_type/edit', '', '', '', '', 0, 1, 0, 1657166997, 1663750789);
INSERT INTO `la_system_menu`
VALUES (57, 34, 'A', '删除', '', 1, 'setting.dict.dict_type/delete', '', '', '', '', 0, 1, 0, 1657167038, 1663750796);
INSERT INTO `la_system_menu`
VALUES (58, 62, 'A', '新增', '', 1, 'setting.dict.dict_data/add', '', '', '', '', 0, 1, 0, 1657167317, 1663750758);
INSERT INTO `la_system_menu`
VALUES (59, 62, 'A', '编辑', '', 1, 'setting.dict.dict_data/edit', '', '', '', '', 0, 1, 0, 1657167371, 1663750751);
INSERT INTO `la_system_menu`
VALUES (60, 62, 'A', '删除', '', 1, 'setting.dict.dict_data/delete', '', '', '', '', 0, 1, 0, 1657167397, 1663750768);
INSERT INTO `la_system_menu`
VALUES (61, 37, 'A', '清除系统缓存', '', 1, 'setting.system.cache/clear', '', '', '', '', 0, 1, 0, 1657173837,
        1657173939);
INSERT INTO `la_system_menu`
VALUES (62, 23, 'C', '字典数据管理', '', 1, 'setting.dict.dict_data/lists', 'dict/data', 'setting/dict/data/index',
        '/dev_tools/dict', '', 1, 0, 0, 1657174351, 1663745617);
INSERT INTO `la_system_menu`
VALUES (63, 158, 'M', '素材管理', 'el-icon-Picture', 0, '', 'material', '', '', '', 0, 1, 0, 1657507133, 1710472243);
INSERT INTO `la_system_menu`
VALUES (64, 63, 'C', '素材中心', 'el-icon-PictureRounded', 0, '', 'index', 'material/index', '', '', 0, 1, 0,
        1657507296, 1664355653);
INSERT INTO `la_system_menu`
VALUES (68, 6, 'A', '详情', '', 0, 'auth.menu/detail', '', '', '', '', 0, 1, 0, 1663725564, 1663750584);
INSERT INTO `la_system_menu`
VALUES (69, 7, 'A', '详情', '', 0, 'auth.admin/detail', '', '', '', '', 0, 1, 0, 1663725623, 1663750615);
INSERT INTO `la_system_menu`
VALUES (101, 158, 'M', '消息管理', 'el-icon-ChatDotRound', 80, '', 'message', '', '', '', 0, 1, 0, 1663838602,
        1710471874);
INSERT INTO `la_system_menu`
VALUES (102, 101, 'C', '通知设置', '', 0, 'notice.notice/settingLists', 'notice', 'message/notice/index', '', '', 0, 1,
        0, 1663839195, 1663839195);
INSERT INTO `la_system_menu`
VALUES (103, 102, 'A', '详情', '', 0, 'notice.notice/detail', '', '', '', '', 0, 1, 0, 1663839537, 1663839537);
INSERT INTO `la_system_menu`
VALUES (104, 101, 'C', '通知设置编辑', '', 0, 'notice.notice/set', 'notice/edit', 'message/notice/edit',
        '/message/notice', '', 0, 0, 0, 1663839873, 1663898477);
INSERT INTO `la_system_menu`
VALUES (107, 101, 'C', '短信设置', '', 0, 'notice.sms_config/getConfig', 'short_letter', 'message/short_letter/index',
        '', '', 0, 1, 0, 1663898591, 1664355708);
INSERT INTO `la_system_menu`
VALUES (108, 107, 'A', '设置', '', 0, 'notice.sms_config/setConfig', '', '', '', '', 0, 1, 0, 1663898644, 1663898644);
INSERT INTO `la_system_menu`
VALUES (109, 107, 'A', '详情', '', 0, 'notice.sms_config/detail', '', '', '', '', 0, 1, 0, 1663898661, 1663898661);
INSERT INTO `la_system_menu`
VALUES (112, 28, 'M', '用户设置', 'local-icon-keziyuyue', 90, '', 'user', '', '', '', 0, 1, 1, 1663903302, 1710473056);
INSERT INTO `la_system_menu`
VALUES (113, 112, 'C', '用户设置', '', 0, 'setting.user.user/getConfig', 'setup', 'setting/user/setup', '', '', 0, 1, 1,
        1663903506, 1663903506);
INSERT INTO `la_system_menu`
VALUES (114, 113, 'A', '保存', '', 0, 'setting.user.user/setConfig', '', '', '', '', 0, 1, 0, 1663903522, 1663903522);
INSERT INTO `la_system_menu`
VALUES (115, 112, 'C', '登录注册', '', 0, 'setting.user.user/getRegisterConfig', 'login_register',
        'setting/user/login_register', '', '', 0, 1, 0, 1663903832, 1663903832);
INSERT INTO `la_system_menu`
VALUES (116, 115, 'A', '保存', '', 0, 'setting.user.user/setRegisterConfig', '', '', '', '', 0, 1, 0, 1663903852,
        1663903852);
INSERT INTO `la_system_menu`
VALUES (117, 0, 'M', '租户管理', 'local-icon-user_biaoqian', 900, '', 'tenant', '', '', '', 0, 1, 0, 1663904351,
        1724998415);
INSERT INTO `la_system_menu`
VALUES (118, 117, 'C', '租户列表', 'local-icon-user_guanli', 100, 'user.user/lists', 'lists', 'tenant/lists/index',
        '', '', 0, 1, 0, 1663904392, 1724998428);
INSERT INTO `la_system_menu`
VALUES (143, 35, 'C', '定时任务', '', 100, 'crontab.crontab/lists', 'scheduled_task',
        'setting/system/scheduled_task/index', '', '', 0, 1, 0, 1669357509, 1710473246);
INSERT INTO `la_system_menu`
VALUES (144, 35, 'C', '定时任务添加/编辑', '', 0, 'crontab.crontab/add:edit', 'scheduled_task/edit',
        'setting/system/scheduled_task/edit', '/setting/system/scheduled_task', '', 0, 0, 0, 1669357670, 1669357765);
INSERT INTO `la_system_menu`
VALUES (145, 143, 'A', '添加', '', 0, 'crontab.crontab/add', '', '', '', '', 0, 1, 0, 1669358282, 1669358282);
INSERT INTO `la_system_menu`
VALUES (146, 143, 'A', '编辑', '', 0, 'crontab.crontab/edit', '', '', '', '', 0, 1, 0, 1669358303, 1669358303);
INSERT INTO `la_system_menu`
VALUES (147, 143, 'A', '删除', '', 0, 'crontab.crontab/delete', '', '', '', '', 0, 1, 0, 1669358334, 1669358334);
INSERT INTO `la_system_menu`
VALUES (158, 0, 'M', '应用管理', 'el-icon-Postcard', 800, '', 'app', '', '', '', 0, 1, 0, 1677143430, 1710472079);
INSERT INTO `la_system_menu`
VALUES (161, 28, 'M', '支付设置', 'local-icon-set_pay', 80, '', 'pay', '', '', '', 0, 1, 1, 1677148075, 1710473061);
INSERT INTO `la_system_menu`
VALUES (162, 161, 'C', '支付方式', '', 0, 'setting.pay.pay_way/getPayWay', 'method', 'setting/pay/method/index', '', '',
        0, 1, 0, 1677148207, 1677148207);
INSERT INTO `la_system_menu`
VALUES (163, 161, 'C', '支付配置', '', 0, 'setting.pay.pay_config/lists', 'config', 'setting/pay/config/index', '', '',
        0, 1, 0, 1677148260, 1677148374);
INSERT INTO `la_system_menu`
VALUES (164, 162, 'A', '设置支付方式', '', 0, 'setting.pay.pay_way/setPayWay', '', '', '', '', 0, 1, 0, 1677219624,
        1677219624);
INSERT INTO `la_system_menu`
VALUES (165, 163, 'A', '配置', '', 0, 'setting.pay.pay_config/setConfig', '', '', '', '', 0, 1, 0, 1677219655,
        1677219655);
INSERT INTO `la_system_menu`
VALUES (166, 118, 'A', '新增租户', '', 0, 'tenant.tenant/add', '', '', '', '', 1, 1, 0, 1726822307, 1726822435);
INSERT INTO `la_system_menu`
VALUES (167, 118, 'A', '编辑租户', '', 0, 'tenant.tenant/edit', '', '', '', '', 1, 1, 0, 1726822372, 1726822440);
INSERT INTO `la_system_menu`
VALUES (168, 118, 'A', '租户详情', '', 0, 'tenant.tenant/detail', '', '', '', '', 1, 1, 0, 1726822396, 1726822444);
INSERT INTO `la_system_menu`
VALUES (169, 118, 'A', '删除租户', '', 0, 'tenant.tenant/delete', '', '', '', '', 1, 1, 0, 1726822416, 1726822449);
INSERT INTO `la_system_menu`
VALUES (173, 28, 'C', '系统更新', 'el-icon-Upload', 0, 'upgrade.upgrade/lists', 'update', 'setting/system/update/index',
        '', '', 1, 1,
        0, 1731916116, 1731916116);
INSERT INTO `la_system_menu`
VALUES (174, 173, 'A', '下载更新包', '', 0, 'upgrade.upgrade/downloadPkg', '', '', '', '', 1, 1, 0, 1731916155,
        1731916155);
INSERT INTO `la_system_menu`
VALUES (175, 173, 'A', '一健更新', '', 0, 'upgrade.upgrade/upgrade', '', '', '', '', 1, 1, 0, 1731916248, 1731916260);

-- ----------------------------
-- Table structure for la_system_role
-- ----------------------------
DROP TABLE IF EXISTS `la_system_role`;
CREATE TABLE `la_system_role`
(
    `id`          int(11) UNSIGNED                                             NOT NULL AUTO_INCREMENT,
    `name`        varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '名称',
    `desc`        varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci      NOT NULL DEFAULT '' COMMENT '描述',
    `sort`        int(11)                                                      NULL     DEFAULT 0 COMMENT '排序',
    `create_time` int(10)                                                      NULL     DEFAULT NULL COMMENT '创建时间',
    `update_time` int(10)                                                      NULL     DEFAULT NULL COMMENT '更新时间',
    `delete_time` int(10)                                                      NULL     DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '角色表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_system_role_menu
-- ----------------------------
DROP TABLE IF EXISTS `la_system_role_menu`;
CREATE TABLE `la_system_role_menu`
(
    `role_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '角色ID',
    `menu_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '菜单ID',
    PRIMARY KEY (`role_id`, `menu_id`) USING BTREE
) ENGINE = InnoDB
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '角色菜单关系表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_tenant
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant`;
CREATE TABLE `la_tenant`
(
    `id`                  int(11) UNSIGNED                                              NOT NULL AUTO_INCREMENT COMMENT '主键',
    `sn`                  varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  NOT NULL COMMENT '编号',
    `name`                varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  NOT NULL DEFAULT '' COMMENT '名称',
    `avatar`              varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '租户头像',
    `tel`                 varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT NULL COMMENT '联系方式',
    `disable`             tinyint(1) UNSIGNED                                           NULL     DEFAULT 0 COMMENT '是否禁用：0-否；1-是；',
    `tactics`             tinyint(1) UNSIGNED                                           NOT NULL DEFAULT 0 COMMENT '分表策略: [0=否, 1=是]',
    `notes`               varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT NULL COMMENT '租户备注',
    `domain_alias`        varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT NULL COMMENT '域名别名',
    `domain_alias_enable` tinyint(10)                                                   NOT NULL DEFAULT 1 COMMENT '启用域名别名：0-启用；1-禁用',
    `create_time`         int(10)                                                       NOT NULL COMMENT '创建时间',
    `update_time`         int(10)                                                       NULL     DEFAULT NULL COMMENT '修改时间',
    `delete_time`         int(10)                                                       NULL     DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '租户表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_tenant_admin
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_admin`;
CREATE TABLE `la_tenant_admin`
(
    `id`               int(11) UNSIGNED                                              NOT NULL AUTO_INCREMENT,
    `tenant_id`        int(10)                                                       NOT NULL COMMENT '租户ID',
    `root`             tinyint(1) UNSIGNED                                           NOT NULL DEFAULT 0 COMMENT '是否超级管理员 0-否 1-是',
    `name`             varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  NOT NULL DEFAULT '' COMMENT '名称',
    `avatar`           varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '用户头像',
    `account`          varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  NOT NULL DEFAULT '' COMMENT '账号',
    `password`         varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  NOT NULL COMMENT '密码',
    `login_time`       int(10)                                                       NULL     DEFAULT NULL COMMENT '最后登录时间',
    `login_ip`         varchar(39) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT '' COMMENT '最后登录ip',
    `multipoint_login` tinyint(1) UNSIGNED                                           NULL     DEFAULT 1 COMMENT '是否支持多处登录：1-是；0-否；',
    `disable`          tinyint(1) UNSIGNED                                           NULL     DEFAULT 0 COMMENT '是否禁用：0-否；1-是；',
    `create_time`      int(10)                                                       NOT NULL COMMENT '创建时间',
    `update_time`      int(10)                                                       NULL     DEFAULT NULL COMMENT '修改时间',
    `delete_time`      int(10)                                                       NULL     DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '租户管理员表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_tenant_admin_dept
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_admin_dept`;
CREATE TABLE `la_tenant_admin_dept`
(
    `admin_id` int(10) NOT NULL DEFAULT 0 COMMENT '管理员id',
    `dept_id`  int(10) NOT NULL DEFAULT 0 COMMENT '部门id',
    PRIMARY KEY (`admin_id`, `dept_id`) USING BTREE
) ENGINE = InnoDB
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '部门关联表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_tenant_admin_jobs
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_admin_jobs`;
CREATE TABLE `la_tenant_admin_jobs`
(
    `admin_id` int(10) NOT NULL COMMENT '管理员id',
    `jobs_id`  int(10) NOT NULL COMMENT '岗位id',
    PRIMARY KEY (`admin_id`, `jobs_id`) USING BTREE
) ENGINE = InnoDB
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '岗位关联表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_tenant_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_admin_role`;
CREATE TABLE `la_tenant_admin_role`
(
    `admin_id` int(10) NOT NULL COMMENT '管理员id',
    `role_id`  int(10) NOT NULL COMMENT '角色id',
    PRIMARY KEY (`admin_id`, `role_id`) USING BTREE
) ENGINE = InnoDB
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '角色关联表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_tenant_admin_session
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_admin_session`;
CREATE TABLE `la_tenant_admin_session`
(
    `id`          int(11) UNSIGNED                                             NOT NULL AUTO_INCREMENT,
    `admin_id`    int(11) UNSIGNED                                             NOT NULL COMMENT '租户id',
    `terminal`    tinyint(1)                                                   NOT NULL DEFAULT 1 COMMENT '客户端类型：1-pc管理后台 2-mobile手机管理后台',
    `token`       varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '令牌',
    `update_time` int(10)                                                      NULL     DEFAULT NULL COMMENT '更新时间',
    `expire_time` int(10)                                                      NOT NULL COMMENT '到期时间',
    PRIMARY KEY (`id`) USING BTREE,
    UNIQUE INDEX `admin_id_client` (`admin_id`, `terminal`) USING BTREE COMMENT '一个用户在一个终端只有一个token',
    UNIQUE INDEX `token` (`token`) USING BTREE COMMENT 'token是唯一的'
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '管理员会话表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_tenant_config
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_config`;
CREATE TABLE `la_tenant_config`
(
    `id`          int(11)                                                      NOT NULL AUTO_INCREMENT,
    `tenant_id`   int(11)                                                      NOT NULL COMMENT '租户ID',
    `type`        varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT NULL COMMENT '类型',
    `name`        varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '名称',
    `value`       text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci        NULL COMMENT '值',
    `create_time` int(10)                                                      NULL     DEFAULT NULL COMMENT '创建时间',
    `update_time` int(10)                                                      NULL     DEFAULT NULL COMMENT '更新时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 13
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '配置表'
  ROW_FORMAT = Dynamic;

BEGIN;
INSERT INTO `la_tenant_config` (`id`, `tenant_id`, `type`, `name`, `value`, `create_time`, `update_time`)
VALUES (1, 0, 'agreement', 'service_title', '用户协议', 1731424715, 1732525836);
INSERT INTO `la_tenant_config` (`id`, `tenant_id`, `type`, `name`, `value`, `create_time`, `update_time`)
VALUES (2, 0, 'agreement', 'service_content',
        '<p>欢迎您使用本公司出品的“AI数字人”！</p><p>为了更好地为您提供服务，请仔细阅读和理解本《AI数字人用户协议》（以下简称“本协议”）。<strong>在您开始使用“AI数字人 ”之前，请您务必认真阅读并充分理解本协议，特别是涉及免除或者限制责任的条款、权利许可和信息使用的条款、法律适用和争议解决条款等。其中，免除或者限制责任条款等重要内容将以加粗形式提示您注意，请您重点阅读。如您未满18周岁，请您在法定监护人陪同下仔细阅读并充分理解本协议，并征得法定监护人的同意后使用本服务。</strong></p><p><strong>在阅读本协议的过程中，如果您不同意本协议或其中任何条款约定，请立即停止使用并退出。若您使用“AI数字人 ”，则视为您已充分理解本协议并承诺作为本协议的一方当事人接受协本协议的约束。</strong></p><p>由于互联网行业的快速发展，本用户协议并不能完整罗列并覆盖您与本公司的所有权利与义务，现有的约定也不能保证完全符合未来发展的需求。因此，您同意本公司有权不定期对本用户协议作任何修改和补充，并予以公示。通常情况下，对用户协议、隐私政策或规则的修改将在公布时或规定的生效日立即生效。您继续访问和使用“AI数字人”即视为您接受修订后的用户协议。否则，您有权通过停止访问“AI数字人 ”且拒绝使用服务、删除您在“AI数字人 ”上的信息和账户等方式来终止该协议。</p><p>如您代表某个机构而非您个人登录和使用“AI数字人 ”，则您将被认为获得充分授权代表该机构同意本用户协议及其不定期的修改和补充。</p><p><br></p><ol><li style=\"text-align: left;\">适用范围</li><li style=\"text-align: left;\">1.1 本协议是您与本公司（本公司，以下简称“本公司”或“我方”）之间就您登录、使用“AI数字人 PC端、小程序”，并获得“AI数字人 ”提供的相关服务所订立的协议。就本协议项下涉及的某些服务，可能会由本公司的关联公司、控制公司向您提供，您知晓并同意接受上述服务内容，即视为接受双方之间的相关权利义务关系亦受本协议约束。</li><li style=\"text-align: left;\">1.2 “用户”指所有直接或间接登录和使用“AI数字人 ”的使用者，包括自然人、法人和其他组织等。在本协议中称为“用户”或称“您”。</li><li style=\"text-align: left;\">1.3 “AI数字人 ”指由本公司合法拥有并运营的、标注名称为“AI数字人 ”的，致力于为用户提供新颖、轻松的AI工具及其相关的技术服务。</li><li style=\"text-align: left;\">1.4 本协议内容同时包括本公司已经发布及后续可能不断发布的关于“AI数字人 ”的相关协议、规则等内容。前述内容一经正式发布，并以适当的方式送达用户（系统通知等），即为本协议不可分割的组成部分，您应同样遵守。</li><li style=\"text-align: left;\"></li><li style=\"text-align: left;\">使用“AI数字人”</li><li style=\"text-align: left;\">2.1 您使用“AI数字人 ”，您可以通过手机号验证注册登录，或通过第三方平台账号（微信账号）的方式登录进入“AI数字人 ”。</li><li style=\"text-align: left;\">2.2 为更好的提升用户体验及服务，我方将不定期提供更新或改变（包括但不限于软件的修改、升级、功能强化、开发新服务、软件替换等）。为保证“AI数字人 ”安全、提升用户服务，本更新或部分服务内容更新后，在可能的情况下，我方将以包括但不限于系统提示、公告、站内信等方式提示用户，用户有权选择接受更新版本或服务，如用户不接受，部分功能将受到限制或不能继续使用。</li><li style=\"text-align: left;\"><strong>2.3 除非得到本公司事先书面授权，您不得以任何形式对“AI数字人”进行如下操作，包括但不限于改编、复制、垂直搜索、镜像或交易等未经授权的访问或使用。</strong></li><li style=\"text-align: left;\">2.6 您理解，您使用“AI数字人 ”需自行准备与有关的终端设备（如手机、电脑等），一旦您在其终端设备中打开“AI数字人 ”，即视为您使用“AI数字人 ”。为充分实现“AI数字人 ”的全部功能，您可能需要将其终端设备联网，您理解由您承担所需要的费用（如流量费、上网费等）。</li><li style=\"text-align: left;\"><strong>2.7 本公司许可您个人的、不可转让的、不可分许可的、非独占地合法使用“AI数字人”的权利。本协议未明示授权的其他一切权利仍由我方保留，您在行使该些权利时须另行获得本公司的书面许可，同时本公司如未行使前述任何权利，并不构成对该权利的放弃。</strong></li><li style=\"text-align: left;\"><strong>2.8充值消耗</strong></li><li style=\"text-align: left;\">您理解并同意，您可以通过充值和积分签到获取算力后，选择AI数字人指定且需消耗算力才能使用的服务；若您的算力余额不足，您将无法选择指定AI数字人服务，但不影响其他功能的正常使用。</li><li style=\"text-align: left;\"><strong>2.9账号安全规范</strong></li><li style=\"text-align: left;\"><strong>（1）您应对您的账户下的所有行为承担全部责任。您应当对您的账户、登录密码、验证码具有妥善保管的义务，若您未妥善保管所造成的损失（包括但不限于您自身、AI数字人、第三方），均由您自行承担。</strong></li><li style=\"text-align: left;\">（2）如您发现您的账号出现泄露或其他危及账户安全的异常情形时，您应立即按照本协议通知条款中联系方式联系我方，要求我方暂时冻结您的账号。<strong>您理解并同意AI数字人对您的请求需要合理时间进行反馈，AI数字人对在合理时间内已经产生的后果不承担任何责任。</strong></li><li style=\"text-align: left;\">（3）<strong> 除依据法律、法规规定或行政司法机关的决定，未经AI数字人同意之外，您的账户、登录密码、验证码不得以任何方式向第三方转让、借用、赠与。否则，由此造成的一切损失（包括但不限于您自身、平台、第三方），均由您自行承担。</strong></li><li style=\"text-align: left;\">用户行为规范</li><li style=\"text-align: left;\">3.1 用户行为要求</li><li style=\"text-align: left;\">您应当对您使用本产品及相关服务的行为负责，除非法律允许或者经本公司事先书面许可，您使用“AI数字人 ”不得具有下列行为：</li><li style=\"text-align: left;\">3.1.1 使用未经本公司授权或许可的任何插件、外挂、系统或第三方工具对“AI数字人 ”的正常运行进行干扰、破坏、修改或施加其他影响。</li><li style=\"text-align: left;\">3.1.2 利用或针对“AI数字人 ”进行任何危害计算机网络安全的行为，包括但不限于：</li><li style=\"text-align: left;\">（1）非法侵入他人网络、干扰他人网络正常功能、窃取网络数据等危害网络安全的活动；</li><li style=\"text-align: left;\">（2）提供专门用于从事侵入网络、干扰网络正常功能及防护措施、窃取网络数据等危害网络安全活动的程序、工具；</li><li style=\"text-align: left;\">（3）明知他人从事危害网络安全的活动的，为其提供技术支持、广告推广、支付结算等帮助；</li><li style=\"text-align: left;\">（4）使用未经许可的数据或进入未经许可的服务器/账号；</li><li style=\"text-align: left;\">（5）未经允许进入公众计算机网络或者他人计算机系统并删除、修改、增加存储信息；</li><li style=\"text-align: left;\">（6）未经许可，企图探查、扫描、测试“AI数字人 ”系统或网络的弱点或其它实施破坏网络安全的行为；</li><li style=\"text-align: left;\">（7）企图干涉、破坏“AI数字人 ”所在系统或网站的正常运行，故意传播恶意程序或病毒以及其他破坏干扰正常网络信息服务的行为；</li><li style=\"text-align: left;\">（8）伪造TCP/IP数据包名称或部分名称。</li><li style=\"text-align: left;\">3.1.3 对“AI数字人 ”进行反向工程、反向汇编、编译或者以其他方式尝试发现本的源代码。</li><li style=\"text-align: left;\">3.1.4 违反法律法规、本协议、本公司的相关规则及侵犯他人合法权益的其他行为。</li><li style=\"text-align: left;\">如果我方发现或收到他人举报或投诉用户违反法律法规、政策及公序良俗、本协议约定或侵犯他人合法权益的，我方有权不经通知随时对举报和投诉的内容，包括但不限于用户资料、聊天记录进行审查、删除，并视情节轻重对违规帐号处以包括但不限于警告、帐号封禁 、设备封禁 、功能封禁的处罚，且通知用户处理结果。</li><li style=\"text-align: left;\">3.1.5 制作色情、诽谤或其他非法材料。宣传酒精饮料、烟草、赌博、武器或爆炸物。</li><li style=\"text-align: left;\"><strong>3.2 用户在登录环节提交的账号昵称、头像等账号信息中不得出现违法和不良信息，否则本公司将不予登录；在登录后，如本公司发现用户以虚假信息骗取账号登录，或其账号昵称、头像等注册信息存在违法和不良信息的，本公司有权不经通知单方采取限期改正、暂停使用、注销账号、收回账号等措施。</strong></li><li style=\"text-align: left;\"><strong>3.3 用户使用“AI数字人”应自觉遵守宪法法律、法规、遵守公共秩序，尊重社会公德、社会主义制度、国家利益、公民合法权益、道德风尚和信息真实性等要求。用户不得使用“AI数字人 ”上传、录制、制作、复制、提交、发布、存储、发送、接受、传播或分享含有下列内容的信息：</strong></li><li style=\"text-align: left;\">（1）反对宪法确定的基本原则的；</li><li style=\"text-align: left;\">（2）危害国家安全，泄露国家秘密的；</li><li style=\"text-align: left;\">（3）颠覆国家政权，推翻社会主义制度、煽动分裂国家、破坏国家统一的；</li><li style=\"text-align: left;\">（4）损害国家荣誉和利益的；</li><li style=\"text-align: left;\">（5）宣扬恐怖主义、极端主义的；</li><li style=\"text-align: left;\">（6）宣扬民族仇恨、民族歧视，破坏民族团结的；</li><li style=\"text-align: left;\">（7）煽动地域歧视、地域仇恨的；</li><li style=\"text-align: left;\">（8）破坏国家宗教政策，宣扬邪教和迷信的；</li><li style=\"text-align: left;\">（9）编造、散布谣言、虚假信息，扰乱经济秩序和社会秩序、破坏社会稳定的；</li><li style=\"text-align: left;\">（10）散布、传播暴力、淫秽、色情、赌博、凶杀、恐怖或者教唆犯罪的；</li><li style=\"text-align: left;\">（11）侵害未成年人合法权益或者损害未成年人身心健康的；</li><li style=\"text-align: left;\">（12）未获他人允许，偷拍、偷录他人，侵害他人合法权利的；</li><li style=\"text-align: left;\">（13）包含恐怖、暴力血腥、高危险性、危害表演者自身或他人身心健康内容的；</li><li style=\"text-align: left;\">（14）危害网络安全、利用网络从事危害国家安全、荣誉和利益的；</li><li style=\"text-align: left;\">（15）侮辱或者诽谤他人，侵害他人合法权益的；</li><li style=\"text-align: left;\">（16）对他人进行暴力恐吓、威胁，实施人肉搜索的；</li><li style=\"text-align: left;\">（17）涉及他人隐私、个人信息或资料的；</li><li style=\"text-align: left;\">（18）散布污言秽语，损害社会公序良俗的；</li><li style=\"text-align: left;\">（19）侵犯他人隐私权、名誉权、肖像权、知识产权等合法权益内容的；</li><li style=\"text-align: left;\">（20）散布商业广告，或类似的商业招揽信息、过度营销信息及垃圾信息；</li><li style=\"text-align: left;\">（21）使用本网站常用语言文字以外的其他语言文字评论的；</li><li style=\"text-align: left;\">（22）其他违反法律法规、政策及公序良俗、干扰“AI数字人 ”正常运营或侵犯其他用户或第三方合法权益内容的其他信息。</li><li style=\"text-align: left;\">3.4 <strong>AI数字人有权对您使用AI数字人的情况进行审查和监督，如果您在使用“AI数字人”时违反任何上述规定，我方或授权代表有权要求您改正或直接采取一切必要的措施（包括但不限于暂停或终止您使用“AI数字人”的权利）以减轻您的不当使用行为造成的影响。</strong></li><li style=\"text-align: left;\">3.5 <strong>如果您违反上述规定并造成严重后果的，我方除暂停或终止对您的服务、解除本协议外，如您的行为给我方造成损失，我方有权通过法律途径向您追索。</strong></li><li style=\"text-align: left;\"></li><li style=\"text-align: left;\"><strong>4、个人信息保护</strong></li><li style=\"text-align: left;\">4.1 我方与您一同致力于您个人信息的保护，保护用户个人信息是本公司的基本原则之一。在使用“AI数字人 ”的过程中，您可能需要提供您的个人信息，以便我方向您提供更好的服务和相应的技术支持。您理解并同意，本公司有权在遵守法律法规、本协议以及《AI数字人隐私政策》的前提下，获取、使用、储存和分享您的个人信息。</li><li style=\"text-align: left;\">4.2 本公司将运用加密技术、匿名化处理等其他与AI数字人相匹配的技术措施及其他安全措施保护您的个人信息，防止您的信息被不当使用或被未经授权的访问、使用或泄漏，并为前述目的建立完善的管理制度。</li><li style=\"text-align: left;\">4.3 如“AI数字人 ”包含链接至第三方提供的信息或其他服务（包括网站），您知悉并理解，运营该等服务的第三方可能会要求您提供个人信息。我方特别提醒您，需要认真阅读该等第三方的用户协议、隐私政策以及其他相关的条款，妥善保护自己的个人信息，仅在必要的情况下向该等第三方提供。本协议（以及其他与“AI数字人 ”相关的协议和规则，包括但不限于《AI数字人隐私政策》）不适用于任何第三方提供的服务，公司对任何因第三方使用由您提供的个人信息所可能引起的后果不承担任何法律责任。</li><li style=\"text-align: left;\">更多关于用户个人信息保护的内容，请参看《AI数字人隐私政策》。</li><li style=\"text-align: left;\"></li><li style=\"text-align: left;\">“AI数字人”数据使用规范</li><li style=\"text-align: left;\">未经公司书面许可，用户不得自行或授权、允许、协助任何第三人对本协议“AI数字人 ”中信息内容进行如下行为：</li><li style=\"text-align: left;\">5.1 复制、读取、采用“AI数字人 ”的信息内容，用于包括但不限于宣传、增加阅读量、浏览量等商业用途；</li><li style=\"text-align: left;\">5.2 擅自编辑、整理、编排“AI数字人 ”的信息内容后在“AI数字人 ”的源页面以外的渠道进行展示；</li><li style=\"text-align: left;\">5.3 采用包括但不限于特殊标识、特殊代码等任何形式的识别方法，自行或协助第三人对“AI数字人 ”的的信息或内容产生流量、阅读量引导、转移、劫持等不利影响；</li><li style=\"text-align: left;\">5.4 其他非法获取“AI数字人 ”的信息内容的行为。</li><li style=\"text-align: left;\"></li><li style=\"text-align: left;\">违约处理</li><li style=\"text-align: left;\">6.1 针对您违反本协议或其他服务条款的行为，公司有权独立判断并视情况采取措施。对涉嫌违反法律法规、涉嫌违法犯罪的行为将保存有关记录，并依法向有关主管部门报告、配合有关主管部门调查。</li><li style=\"text-align: left;\"><strong>6.2因您违反本协议或其他服务条款规定，引起第三方投诉或诉讼索赔的，您应当自行承担全部法律责任。因您的违法或违约行为导致本公司及其关联公司、控制公司向任何第三方赔偿或遭受国家机关处罚的，您还应足额赔偿本公司及其关联公司、控制公司因此遭受的全部损失。</strong></li><li style=\"text-align: left;\"><strong>6.3本公司尊重并保护法人、公民的知识产权、名誉权、姓名权、隐私权等合法权益。您保证，在使用“AI数字人”制作、复制、发布、传播的信息时不侵犯任何第三方的知识产权、名誉权、姓名权、隐私权等权利及合法权益。针对第三方提出的全部权利主张，您应自行承担全部法律责任；如因您的侵权行为导致本公司及其关联公司、控制公司遭受损失的（包括经济、商誉等损失），您还应足额赔偿本公司及其关联公司、控制公司遭受的全部损失。</strong></li><li style=\"text-align: left;\"></li><li style=\"text-align: left;\">服务的变更、中断和终止</li><li style=\"text-align: left;\">7.1 您理解并同意，公司提供的“AI数字人 ”是按照现有技术和条件所能达到的现状提供的。公司会尽最大努力向您提供服务，确保服务的连贯性和安全性。您理解，公司不能随时预见和防范技术以及其他风险，包括但不限于不可抗力、病毒、木马、黑客攻击、系统不稳定、第三方服务瑕疵及其他各种安全问题的侵扰等原因可能导致的服务中断、数据丢失以及其他的损失和风险。</li><li style=\"text-align: left;\">7.2 <strong>您理解并同意，公司为了服务整体运营的需要，有权在公告通知后修改、中断、中止或终止“AI数字人 ”，而无须向用户负责或承担任何赔偿责任。</strong></li><li style=\"text-align: left;\"></li><li style=\"text-align: left;\">广告</li><li style=\"text-align: left;\">8.1 您使用“AI数字人 ”过程中，充分理解并同意：本服务中可能包括公司针对个人用户推出的信息、广告发布或品牌推广服务，您同意公司有权在“AI数字人 ”中展示“AI数字人 ”供应商、合作伙伴的商业广告、推广或信息（包括商业或非商业信息）。</li><li style=\"text-align: left;\">8.2 如您不同意 “AI数字人 ”推送通知服务的，您有权停止使用“AI数字人 ”。</li><li style=\"text-align: left;\">8.3 公司依照法律规定履行广告及推广相关义务，您应当自行判断该广告或推广信息的真实性和可靠性并为自己的判断行为负责。除法律法规明确规定外，您因该广告或推广信息进行的购买、交易或因前述内容遭受的损害或损失，用户应自行承担，公司不予承担责任。</li><li style=\"text-align: left;\"></li><li style=\"text-align: left;\">知识产权</li><li style=\"text-align: left;\">9.1公司在“AI数字人 ”中提供的内容（包括但不限于软件、技术、程序、网页、文字、图片、图像、音频、视频、图表、版面设计、电子文档等）的知识产权属于本公司及其关联公司所有。公司提供本服务时所依托的软件的著作权、专利权及其他知识产权均归本公司及其关联公司所有。未经本公司许可，任何人不得擅自使用（包括但不限于通过任何机器人、蜘蛛等程序或设备监视、复制、传播、展示、镜像、上载、下载“AI数字人 ”中的内容），但相关权利人依照法律规定应享有权利的除外。</li><li style=\"text-align: left;\">9.2 本公司及其关联公司为“AI数字人 ”开发、运营提供技术支持，并对“AI数字人 ”的开发和运营等过程中产生的所有数据和信息等享有全部权利，但相关权利人依照法律规定应享有权利的除外。</li><li style=\"text-align: left;\"><strong>9.3 您理解并承诺，您在使用本服务时的输入不侵犯任何人的知识产权、肖像权、名誉权、荣誉权、姓名权、隐私权、个人信息权益等合法权益，不涉及任何国家秘密、商业秘密、重要数据或其他可能会对国家安全或者公共利益造成不利影响的数据，否则由此产生的侵权风险和责任由您承担。AI数字人由此遭受的全部直接和间接损失（包括但不限于经济、商誉、维权支出、律师费等损失）也同样由您承担。</strong></li><li style=\"text-align: left;\"><strong>9.4 本网站非常重视知识产权的保护，按照法律法规的要求设置知识产权负责人对知识产权进行识别处理。您知悉、理解并同意，如果您按照法律规定对您的输入（包括您使用本服务过程中自行上传、发布的全部内容）和/或输出享有权利的（包括但不限于知识产权、肖像权等），您使用本服务均不会造成前述合法权利的转移或减损，除非我们与您另有约定。与此同时，您理解并同意AI数字人将在法律允许的范围内为实现本服务目的对您上传、发布的内容进行存储及使用（包括但不限于复制、分发、传送、公开展示、编辑等）。</strong></li><li style=\"text-align: left;\"><strong>9.5未经本公司事先书面同意，您不得私自使用本公司的包括但不限于“猿创家”“AI数字人”等在内的任何商标、服务标记、商号、域名、网站名称或其他显著品牌特征等（以下统称为“标识”）。未经本公司事先书面同意，您不得将本条款前述标识以单独或结合任何方式展示、使用或申请注册商标、进行域名注册等，也不得实施向他人明示或暗示有权展示、使用、或其他有权处理该些标识的行为。由于您违反本协议使用本公司上述商标、标识等给本公司或他人造成损失的，由您承担全部法律责任。</strong></li><li style=\"text-align: left;\"></li><li style=\"text-align: left;\">免责声明</li><li style=\"text-align: left;\">10.1 您理解并同意，“AI数字人”可能会受多种因素的影响或干扰，本公司不保证(包括但不限于)：</li><li style=\"text-align: left;\">10.1.1 软件完全适合用户的使用要求；</li><li style=\"text-align: left;\">10.1.2 软件不受干扰，及时、安全、可靠或不出现错误；用户经由本公司取得的任何软件、服务或其他材料符合用户的期望；</li><li style=\"text-align: left;\">10.1.3 软件中任何错误都将能得到更正。</li><li style=\"text-align: left;\">10.2 用户理解并同意，在使用“AI数字人 ”过程中，可能遇到不可抗力等因素（不可抗力是指不能预见、不能克服并不能避免的客观事件），包括但不限于政府行为、自然灾害、网络原因、黑客攻击、战争或任何其它类似事件。<strong>出现不可抗力情况时，本公司将努力在第一时间及时修复，但因不可抗力给用户造成了损失，用户同意本公司不承担责任。</strong></li><li style=\"text-align: left;\">10.3 本公司依据本协议约定获得处理违法违规内容的权利，该权利不构成本公司的义务或承诺，我方不能保证及时发现违法行为或进行相应处理。</li><li style=\"text-align: left;\">10.4 用户阅读、理解并同意：关于本协议服务，我方不提供任何种类的明示或暗示担保或条件，包括但不限于商业适售性、特定用途适用性等。您对本协议的使用行为必须自行承担相应风险。</li><li style=\"text-align: left;\">10.5 <strong>用户阅读、理解并同意，本协议是在保障遵守国家法律法规、维护公序良俗，保护他人合法权益，本公司在能力范围内尽最大的努力按照相关法律法规进行判断，但并不保证我方判断完全与司法机关、行政机关的判断一致，如因此产生的后果用户已经理解并同意自行承担。</strong></li><li style=\"text-align: left;\"></li><li style=\"text-align: left;\">未成年人使用条款</li><li style=\"text-align: left;\">12.1 若用户是未满18周岁的未成年人，应在监护人监护、指导并获得监护人同意情况下阅读本协议和使用“AI数字人 ”。</li><li style=\"text-align: left;\">12.2 <strong>若用户是未满18周岁的未成年人，请不要通过本提交任何个人信息。 我们鼓励家长和法定监护人监控孩子的互联网使用情况，并协助实施本政策，教育孩子不要在未经他们许可的情况下通过网站、服务和/或软件提供个人信息</strong>I 。</li><li style=\"text-align: left;\">12.3 <strong>未成年用户理解如因您违反法律法规、本协议内容，则您及您的监护人应依照法律规定承担因此而导致的一切后果。</strong></li><li style=\"text-align: left;\"></li><li style=\"text-align: left;\">其他</li><li style=\"text-align: left;\">13.1 <strong>本协议的成立、生效、履行、解释及争议的解决均应适用中华人民共和国大陆地区法律。</strong>倘若本协议之任何规定因与中华人民共和国大陆地区的法律抵触而无效，则这些条款将尽可能接近本协议原条文意旨重新解析，且本协议其它规定仍应具有完整的效力及效果。</li><li style=\"text-align: left;\"><strong>13.2 本协议中的相关条款会持续完善，并在测试和对外发布过程中，持续改进和调整。我方会将完善后的条款以适当的方式予以发布，并主动通知您查阅。前述完善后的条款一经正式发布， 即作为本协议不可分割的组成部分并具有等同于本协议的法律效力。在本协议完善后，如果您继续使用“AI数字人”，即视为您认可并接受修改后的协议条款。如果您对修改后的条款存有异议，请您立即停止使用“AI数字人 ”。</strong></li><li style=\"text-align: left;\">13.3 本协议中的标题仅为方便及阅读而设，并不影响本协议中任何规定的含义或解释。</li><li style=\"text-align: left;\">13.4 您和本公司均是独立的主体，在任何情况下本协议不构成公司对用户的任何形式的明示或暗示担保或条件，双方之间亦不构成代理、合伙、合营或雇佣关系。</li></ol>',
        1731424715, 1732609293);
INSERT INTO `la_tenant_config` (`id`, `tenant_id`, `type`, `name`, `value`, `create_time`, `update_time`)
VALUES (3, 0, 'agreement', 'privacy_title', '隐私政策', 1731424715, 1731424715);
INSERT INTO `la_tenant_config` (`id`, `tenant_id`, `type`, `name`, `value`, `create_time`, `update_time`)
VALUES (4, 0, 'agreement', 'privacy_content',
        '<p>您在使用本软件前，请仔细阅读本政策，以了解我们处理您个人信息的详情。其中有关个人敏感信息以及与您个人信息权益相关的重要内容我们已用加粗形式提示，请特别关注。</p><p>特别提示：</p><p>在使用AI数字人的各项服务前，请您务必仔细阅读并透彻理解本政策，在确认充分理解并同意后方使用相关产品和服务。如果您不同意本政策任何内容，请您立即停止使用AI数字人各项服务和产品尤其是涉及交易往来的服务和产品。当您使用AI数字人提供的任一服务时，即表示您已充分阅读、理解并同意我们按照本政策的约定收集、使用、存储和保护您的相关信息。</p><p>本政策将帮助您了解以下内容：</p><p>一、定义与解释</p><p>二、我们如何收集和使用您的个人信息</p><p>三、我们如何使用Cookie和同类技术</p><p>四、我们如何委托处理、共享、转让、公开披露您的个人信息</p><p>五、我们如何保护您的个人信息</p><p>六、我们如何存储您的个人信息</p><p>七、您如何实现管理您个人信息的权利</p><p>八、未成年人条款</p><p>九、我们如何更新本政策</p><p>十、如何联系我们</p><p>十一、其他</p><p>一、定义与解释</p><ol><li>AI数字人产品与/或服务</li><li>包括AI数字人PC端、小程序、H5端的产品与/或服务（以下简称“AI数字人”）。</li><li>用户</li><li>是指注册、登录以及使用AI数字人服务的用户，在本政策中更多地称为\"您\"。</li><li>账号</li><li>指您完成首次登录后，享有的用户账号，该用户账号由您负责保管；您应当对以您账号下进行的所有行为负法律责任。</li><li>个人信息</li><li>个人信息是以电子或者其他方式记录的与已识别或者可识别的自然人有关的各种信息，不包括匿名化处理后的信息。为免疑义，个人信息包括但不限于敏感个人信息。</li><li>敏感个人信息</li><li>敏感个人信息是一旦泄露或者非法使用，容易导致自然人的人格尊严受到侵害或者人身、财产安全受到危害的个人信息，包括生物识别、宗教信仰、特定身份、医疗健康、金融账号、行踪轨迹等信息，以及不满十四周岁未成年人的个人信息。</li><li>个人信息的处理</li></ol>',
        1731424715, 1732609293);
INSERT INTO `la_tenant_config` (`id`, `tenant_id`, `type`, `name`, `value`, `create_time`, `update_time`)
VALUES (5, 0, 'agreement', 'use_title', '使用协议', 1731424715, 1731424715);
INSERT INTO `la_tenant_config` (`id`, `tenant_id`, `type`, `name`, `value`, `create_time`, `update_time`)
VALUES (6, 0, 'agreement', 'use_content',
        '<p>请您知悉，本平台部分产品和服务依托人工智能模型生成。我们强烈要求并建议您：</p><p>严禁利用AI数字人技术侵犯他人合法权益 ：请勿利用AI技术传播违反法律法规、侵犯其他用户或第三方合法权益的内容，不得利用AI技术造谣传谣、恶意中伤他人、侵犯他人隐私、侵犯他人知识产权、传播违法信息等。 否则一切后果由您本人承担。</p><p>请您仔细阅读本协议内容，尤其是字体加粗部分。如您对本协议内容或页面提示信息有疑问，请勿进行下一步操作。您可通过“AI数字人”的官方沟通渠道（详见第十六条）进行咨询，以便我们为您解释和说明。您通过页面点击或直接开始使用“AI数字人”产品及相关服务等方式即视为表示您已同意本协议。</p><p><br></p><p>一、用户的账号与使用</p><p>1.1 您理解并确认，在您开始注册使用“AI数字人”服务之前，您应当具备中华人民共和国法律规定的与您行为相适应的民事行为能力。</p><p>若您不具备与您行为相适应的民事行为能力，请在法定监护人的陪同下仔细阅读并在充分理解本协议，且征得法定监护人的同意后方可注册使用“AI数字人”服务。您及您的监护人应承担因您的注册和使用行为而导致的一切后果。</p><p>如果您代表一家企业、组织机构或其他法律主体进行注册或以其他我们允许的方式实际使用“AI数字人”，则您声明和保证，您已经获得充分授权并有权代表该企业、组织机构或其他法律主体注册使用，并受本协议的约束。此外，您还需确保您不是任何国家、经济制裁或其他法律法规限制的对象，也未直接或间接为前述对象提供资金、商品或服务，否则您应当停止使用“AI数字人”服务。同时，您理解违反前述要求可能会导致您无法正常注册及使用“AI数字人”服务。</p><p>1.2 注册与登录</p><p>1.2.1您可通过提供手机号码并完成动态验证，以完成“AI数字人”用户账号的注册。</p><p>1.2.2您可以通过“AI数字人”提供的其他注册方式，完成“AI数字人”用户账号的注册。</p><p>1.2.3当您按照注册页面提示填写信息、阅读并同意本协议且完成全部注册程序后，您可获得“AI数字人”账户并成为“AI数字人”用户。</p><p>1.3 您有责任妥善保管“AI数字人”账号信息及账号密码的安全，您需要对“AI数字人”账号以及密码下的行为承担法律责任。您同意在任何情况下不向他人透露账号及密码信息。请您知悉，我们也不会主动向您索要账号密码。当在您怀疑账号出现被盗等不安全情况时，请您立即通知我们。关于账号的安全，“AI数字人”将会在目前技术水平下努力保护您的账号在服务器端的安全，并不断更新技术措施。因您主动泄露或因您遭受他人攻击、诈骗等行为导致的损失及后果，您可以通过司法、行政等救济途径向侵权行为人追偿。如您需要“AI数字人”协助的，“AI数字人”会在届时的法律框架下尽力协助。</p><p>1.4 “AI数字人”账号的所有权归我们所有，您完成申请注册手续后，我们授予您所注册“AI数字人”账号的使用权，且仅限您本人使用。同时，由于您的账号行为（包括但不限于在线签署各类协议、发布信息及披露信息等）均代表您本人行为，您应妥善保管您的账号信息及密码并对您账号行为的后果负责，未经我们书面同意，您不得以任何形式出借、出租、赠与、转让、售卖或以其他方式许可他人使用“AI数字人”账号；如果我们发现或者有合理理由认为使用者并非账号初始注册人，为保障账号安全，我们有权立即暂停或终止向该账号提供服务，并有权永久禁用该账号。如您的账号遭到不属于您本人的操作或使用，请您立即通知我们，以避免扩大的损失和后果；如我们判断您“AI数字人”账号的使用可能危及您的账号安全及/或“AI数字人”平台信息安全的，基于维护您的财产及信息安全考虑，您同意我们可中止提供相应服务直至您本人通过可信方式要求恢复且该账号对“AI数字人”平台信息安全的威胁已经消除。</p><p>1.5 您理解并承诺，您所设置的“AI数字人”账号信息不得违反国家法律法规、政策及“AI数字人”所提供的服务的相关规则。您设置的账号名称、头像、个人介绍及个人资料等注册信息中不得出现违法或不良信息，未经他人许可不得用他人名义（包括但不限于冒用他人姓名、名称、商标、字号、肖像、头像等具有独特的识别性元素或足以让人混淆的方式）注册“AI数字人”账号或设置“AI数字人”账号信息，不得通过频繁注册、批量注册等行为恶意注册“AI数字人”账号，否则我们有权不予注册或停止服务并收回账号，因此产生的损失由您自行承担。</p><p>1.6 您了解并同意，您有义务保持您提供信息的真实性、有效性并及时更新。在法律有明确规定要求我们作为服务提供者须对用户信息进行核实的情况下，我们将依法适时地对您的信息进行核实，您应当配合提供最新、真实、完整、有效的信息。</p><p>1.7 依照国家相关法律法规的规定，为使用“AI数字人”的部分或全部服务功能，我们可能要求您填写真实的身份信息（如：手机号码）并根据法律法规的要求完成实名认证。若您提交的材料或提供的信息不准确、不真实、不规范、不合法或者我们有合理理由判断为不真实或不合法的资料，则我们有权拒绝您的注册申请或停止为您提供相关服务，您可能无法使用“AI数字人”服务或者部分服务的使用受到限制。 </p><p>1.8为保护账号安全，防止账号被盗等情况发生，我们可能会不时采用一种或多种方式对您进行身份验证（如短信验证、邮件验证等），如您未成功通过验证，我们有合理理由怀疑该账号出现被盗等不安全情况，并可能视情节严重情况而单方决定是否中止或终止向该账号继续提供我们的产品及/或服务及/或采取进一步措施。您同意，我们采取前述措施是为了保护您账号安全，我们无需因此承担不合理责任。</p><p>1.9您有责任维护您的账户的安全性与保密性，对以您的名义所从事的活动承担全部法律责任。您应高度重视对账户与密码的保密，在任何情况下不向他人透露账户及密码。否则未经授权的使用行为均视为您本人的行为，您将自行承担所有由此导致的损失及后果。您理解我们对您的任何请求采取行动均需要合理时间，且我们应您请求而采取的行动可能无法避免或阻止侵害后果的形成或扩大，除我们存在法定过错外，我们对在采取行动前已经产生的后果不承担责任。</p><p><br></p><p><br></p><p>二、个人信息保护与信息合法性承诺</p><p>2.1 我们非常重视用户个人信息的保护，在您使用“AI数字人”服务时，您同意我们按照所公布的《“AI数字人”隐私政策》收集、存储、使用、披露和保护您的个人信息。我们希望通过隐私政策向您清楚地介绍我们对您个人信息的处理方式，您应当完整地阅读《“AI数字人”隐私政策》，以帮助您更好地保护您的个人信息。</p><p>2.2 非用户个人信息的保证与授权</p><p>2.2.1 您承诺并保证，您在“AI数字人”上传、使用、发布以及处理您上传的信息不会侵犯任何第三方的权利(包括但不限于肖像权、著作权及著作权邻接权、专利权、商标权、名誉权、荣誉权、财产权、个人信息、保密信息等)，否则，我们有权对您发布的信息依法或依本协议进行删除或屏蔽，并保留向您追偿的权利。</p><p>2.2.2 您应当确保您所发布的信息不包含以下内容:</p><p>(1)违反国家法律法规禁止性规定的:</p><p>(2)政治宣传、封建迷信、淫秽、色情、赌博、暴力、恐怖或者教唆犯罪的；</p><p>(3)欺诈、虚假、不准确或存在误导性的；</p><p>(4)侵犯他人知识产权或涉及第三方商业秘密或其他专有权利的；</p><p>(5)侮辱、诽谤、恐吓、涉及他人隐私等侵害他人合法权益的；</p><p>(6)存在可能破坏、篡改、删除、影响“AI数字人”任何系统正常运行或未经授权擅自获取“AI数字人”及其他用户的数据、个人资料的病毒、木马、爬虫等恶意软件、程序代码的；</p><p>(7)其他违背社会公共利益或公共道德或依据“AI数字人”协议/平台规则不适合在“AI数字人”上发布的。</p><p><br></p><p>三、第三方网站及应用</p><p>3.1 “AI数字人”可能设有向第三方网站、平台的跳转链接，也不排除其他用户上传或发布的内容中添加了指向外部网站的链接，您应仔细阅读这些外部网站的使用条款及隐私政策，在法律允许的范围内，我们不对外部网站的真实性、合法性或安全性承担责任。</p><p>3.2 您在访问“AI数字人”或使用“AI数字人”服务时如果使用了第三方提供的应用、产品、软件或服务，除遵守“AI数字人”的相关平台规则外，还可能需要同意并遵守第三方的协议或规则。如因第三方应用、产品、软件及相关服务产生争议、损失或损害，由您自行与第三方解决，我们并不就此而对您或任何第三方承担责任。</p><p><br></p><p>四、用户行为规范</p><p>4.1 用户行为要求</p><p>您应当对您使用“AI数字人”产品及相关服务的行为负责，除非法律允许或经我们事先书面许可，您访问和使用“AI数字人”不得自行从事或授权、允许、协助他人采取如下行为：</p><p>4.1.1 使用未经我们授权或许可的任何插件、外挂、系统或第三方工具对“AI数字人”产品及相关服务的正常运行进行干扰、破坏、修改或施加其他影响。</p><p>4.1.2 利用或针对“AI数字人”产品及相关服务进行任何危害计算机网络安全的行为，包括但不限于：</p><p>(1)非法侵入他人网络、干扰他人网络正常功能、窃取网络数据等危害网络安全的活动；</p><p>(2)提供专门用于从事侵入网络、干扰网络正常功能及防护措施、窃取网络数据等危害网络安全活动的程序、工具；</p><p>(3)明知他人从事危害网络安全的活动的，为其提供技术支持、广告推广、支付结算等帮助；</p><p>(4)使用未经许可的数据或进入未经许可的服务器/账号；</p><p>(5)未经允许进入公众计算机网络或他人计算机系统并删除、修改、增加存储信息；</p><p>(6)未经许可，企图探查、扫描、测试等破坏“AI数字人”系统或网络安全的行为；</p><p>(7)企图干涉、破坏“AI数字人”系统或网站的正常运行，故意传播恶意程序或病毒以及其他破坏干扰正常网络信息服务的行为；</p><p>(8)伪造TCP/IP数据包名称或部分名称。</p><p>4.1.3 对“AI数字人”网站进行反向工程、反向汇编、编译或以其他方式尝试发现“AI数字人”网站的源代码。</p><p>4.1.4 对“AI数字人”网站或其运行过程中释放到任何终端内存中的数据、软件运行过程中客户端与服务器端的交互数据，以及“AI数字人”网站运行所必需的系统数据，进行获取、复制、修改、增加、删除、挂接运行或创作任何衍生作品，形式包括但不限于使用插件、外挂、群控或非经我们授权的第三方工具/服务接入“AI数字人”网站和相关系统。</p><p>4.1.5 通过修改或伪造“AI数字人”网站运行中的指令、数据，增加、删减、变动“AI数字人”网站的功能或运行效果，或将用于上述用途的软件、方法进行运营或向公众传播，无论这些行为是否为商业目的。</p><p>4.1.6 删除“AI数字人”网站和相关内容上关于知识产权的信息，或修改、删除、避开为保护知识产权而设置的任何技术措施。</p><p>4.1.7 对我们拥有知识产权的内容或“AI数字人”网站内其他用户发布的内容进行使用、出租、出借、复制、修改、链接、转载、汇编、发表、出版、建立镜像站点等。</p><p>4.1.8 利用“AI数字人”账号参与任何非法或有可能非法的活动或交易，包括传授犯罪方法、出售任何非法药物、洗钱活动、诈骗等。</p><p>4.1.9 其他违反法律法规、本协议、“AI数字人”平台规则及侵犯他人合法权益的其他行为。</p><p>如果我们有合理理由认为您的任何行为违反或可能违反上述约定的，我们可独立进行判断并采取必要措施进行处理，紧急情况时可在不事先通知的情况下终止向您提供服务，并依法依约追究相关责任。</p><p>4.2.信息内容规范</p><p>4.2.1 本条所述信息内容是指用户使用“AI数字人”产品及服务过程中所输入、制作、复制、发布、传播、上传、输出的任何内容，包括但不限于“AI数字人”账号的头像、昵称、个人介绍等个人主页所展示的信息，或发布、传播的文字、图片、音频、视频等信息。</p><p>4.2.2 您理解并同意，“AI数字人”一直致力于为用户提供文明健康、规范有序的网络环境，您不得利用“AI数字人”账号或服务直接或间接输入、制作、复制、发布、传播、上传、输出下列违法违规、干扰正常运营，以及侵犯其他用户或第三方合法权益的内容，包括但不限于：</p><p>(1)违反宪法确定的基本原则的；</p><p>(2)危害国家安全，泄露国家秘密，颠覆国家政权，破坏国家统一的；</p><p>(3)损害国家荣誉和利益的；</p><p>(4)煽动民族仇恨、民族歧视，破坏民族团结的；</p><p>(5)破坏国家宗教政策，宣扬邪教和封建迷信的；</p><p>(6)散布谣言，扰乱社会秩序，破坏社会稳定的；</p><p>(7)散布淫秽、色情、赌博、暴力、恐怖或教唆犯罪的；</p><p>(8)侮辱或诽谤他人，侵害他人合法权益的；</p><p>(9)不符合法律法规、社会主义制度、国家利益、公民合法利益、公共秩序、社会道德风尚和信息真实性等“七条底线”要求的；</p><p>(10)煽动地域歧视、地域仇恨的;</p><p>(11)对他人进行暴力恐吓、威胁，实施人肉搜索的;</p><p>(12)涉及泄露、窃取、侵犯他人隐私、个人信息或资料的；</p><p>(13)散布污言秽语，损害社会公序良俗的;</p><p>(14)发布、传送、传播、储存侵害他人名誉权、肖像权、知识产权、商业秘密等合法权利的内容；</p><p>(15)制作、复制、发布、传播骚扰或含有任何性或性暗示的信息的；</p><p>(16)制作、复制、发布、传播商业推广信息、广告信息、过度营销信息或垃圾信息的；</p><p>(17)所发表的信息毫无意义的，或刻意使用字符组合以逃避技术审核的;</p><p>(18)侵害未成年人合法权益或损害未成年人身心健康的；</p><p>(19)发布禁止销售/推广或限制销售/推广的商品或服务信息，除非取得合法且足够的行政许可；</p><p>(20)冒充他人或利用他人的名义使用本服务或传播任何信息，恶意使用注册账号导致其他用户误认的；</p><p>(21)包含恐怖、暴力血腥、高危险性、危害表演者自身或他人身心健康内容的，包括但不限于以下情形：</p><p>i.任何暴力和/或自残行为内容；</p><p>ii.任何威胁生命健康、利用刀具等危险器械表演的危及自身或他人人身及/或财产权利的内容；</p><p>iii.怂恿、诱导他人参与可能会造成人身伤害或导致死亡的危险或违法活动的内容；</p><p>(22)从事或协助非法或高风险活动，例如：军事和战争、发展武器、爆炸物或危险材料、关键基础设施（如运输、能源）的管理或运营、创建或分销管控物质或服务、具有高经济损害风险的活动，含赌博、自动确定信贷资格、就业和教育决策；</p><p>(23) 其他违反法律法规、政策及公序良俗、干扰本产品及相关服务正常运营或侵犯其他用户或第三方合法权益内容的其他信息。</p><p>4.3 服务使用限制</p><p>4.3.1 未经我们书面许可，任何用户、第三方不得自行或授权、允许、协助任何他人对“AI数字人”中信息内容进行如下行为：</p><p>（1）复制、读取、采用“AI数字人”的信息内容，用于包括但不限于宣传、增加阅读量、浏览量等商业用途；</p><p>（2）擅自编辑、整理、编排“AI数字人”的信息内容后在“AI数字人”的源页面以外的渠道进行展示；</p><p>（3）采用包括但不限于特殊标识、特殊代码等任何形式的识别方法，自行或协助第三人对“AI数字人”的信息内容产生流量、阅读量引导、转移、劫持等不利影响；</p><p>（4）其他非法获取或使用“AI数字人”的信息内容的行为。</p><p>4.3.2 未经我们书面许可，任何用户、第三方不得以任何方式（包括但不限于盗链、冗余盗取、非法抓取、模拟下载、深度链接、假冒注册等）直接或间接盗取“AI数字人”的文字、图片、音频、视频等信息内容，或以任何方式（包括但不限于隐藏或者修改域名、平台特有标识、用户名、以及专利、版权、商标或其他所有权声明等）删除、隐匿或改变相关信息内容的权利管理电子信息、或以任何方式删除、隐匿、改变“AI数字人”平台上显示或其中包含的围绕“AI数字人”相关的任何专利、版权、商标、品牌标识或其他所有权声明。</p><p>4.3.3 经我们书面许可后，任何用户、第三方对“AI数字人”的信息内容的分享、转发等行为，还应符合以下规范：</p><p>（1）对抓取、统计、获得的相关搜索热词、命中率、分类、搜索量、点击率、阅读量等相关数据，未经我们事先书面同意，不得将上述数据以任何方式公示、提供、泄露给任何第三人；</p><p>（2）不得对“AI数字人”的源网页进行任何形式的任何改动，包括但不限于“AI数字人”的首页（profile页面）链接，广告系统链接等入口，也不得对“AI数字人”的源页面的展示进行任何形式的遮挡、插入、弹窗等妨碍；</p><p>（3）应当采取安全、有效、严密的措施，防止“AI数字人”的信息内容被第三方通过包括但不限于“蜘蛛（spider）”程序等任何形式进行非法获取；</p><p>（4）不得把相关数据内容用于我们书面许可范围之外的目的，进行任何形式的销售和商业使用，或向第三方泄露、提供或允许第三方为任何方式的使用；</p><p>（5）向任何第三人分享、转发、复制“AI数字人”信息内容的行为，应当遵守本协议及我们为此制定的其他规范和标准。</p><p>4.3.4 您不得利用基于深度学习等新技术新应用制作、发布、传播虚假信息。您在发布或传播利用基于深度学习、虚拟现实、生成式人工智能等新技术新应用制作的非真实文字、图片、音视频信息，或其他可能导致公众混淆或误认的信息内容时，应当以显著方式予以标识。同时，未经我们书面明确同意，您不得以任何方式遮挡、涂抹或删除我们对信息内容标注的显著标识。</p><p>4.3.5 如您输入、上传、制作、发布、传播的信息内容需要取得相关法律法规规定的许可或取得相关合法资质的，您应自行取得相关资质并承担可能因此产生的全部法律责任。</p><p>4.4对自己行为负责</p><p>4.4.1 我们有权采取技术或者人工方式对您的输入数据和AI的合成结果进行审核。您必须为自己注册账号下的一切行为负责，包括您所发布的任何内容以及由此产生的任何后果。您不得在使用本服务过程中进行任何违反中国法律、法规、规章、条例以及任何具有法律效力之规范的行为。 </p><p>4.4.2 “AI数字人”合成结果仅供您参考，您不应将输出的内容作为专业建议、商业用途或用于其他目的。</p>',
        1731424715, 1732609293);
INSERT INTO `la_tenant_config` (`id`, `tenant_id`, `type`, `name`, `value`, `create_time`, `update_time`)
VALUES (7, 0, 'agreement', 'recharge_title', '充值协议', 1731424715, 1731424715);
INSERT INTO `la_tenant_config` (`id`, `tenant_id`, `type`, `name`, `value`, `create_time`, `update_time`)
VALUES (8, 0, 'agreement', 'recharge_content',
        '<p>欢迎您使用“AI数字人”充值服务，请您仔细阅读、理解并遵守如下协议。</p><p>《“AI数字人”充值服务协议》（以下称“本协议”）适用于AI数字人充值服务及相关功能（以下称“AI数字人充值服务”、“ 充值服务”或“本服务”）。</p><p>为使用AI数字人充值服务及相关功能，您应当阅读并遵守《“AI数字人”充值服务协议》（以下简称“本协议”）、《“AI数字人”用户服务协议》、《“AI数字人”隐私政策》、AI数字人创作功能相关使用须知等协议及规则（以下统称“服务协议及规则”）。本协议为《“AI数字人”用户服务协议》、《“AI数字人”隐私政策》的补充协议，是其不可分割的组成部分，与其构成统一整体，本协议与上述协议存在冲突或不一致的，以本协议为准。</p><p>请您务必审慎阅读、充分理解各协议及规则内容，特别是免除或限制责任条款、法律适用和争议解决条款以及购买或使用某项服务的单独协议和/或规则。特别提醒您仔细阅读并留意本协议中加粗提示的内容。您通过页面勾选/点击或直接开始使用本服务等其他方式确认即表示您已同意本协议。</p><p>我们有权根据实际的运营情况等原因对本协议进行修订，建议您应及时查看修订的版本。您的使用行为视为对本协议及其修订版本的确认和同意。</p><p>未满18周岁的未成年人或者因智力、精神健康状况等而不具有完全民事行为能力的用户，请在法定监护人陪同下仔细阅读本协议并决定是否同意接受本协议。</p><p>一、定义</p><p>1 .“AI数字人”充值服务:即“AI数字人”平台基于“AI数字人”产品向您提供的通过资格获取或支付一定费用，享用相应“AI数字人”产品权益的服务。您获得资格或支充值用后，可在相应服务期限内，持续体验“AI数字人”平台指定范围内的充值权益,以下称“AI数字人充值服务”、或“本服务”。</p><p>2.“本协议”：本公司及/或其关联方与您（以下或称“用户”）之间，就您使用“AI数字人”充值服务相关事宜与您达成《“AI数字人”充值服务协议》,以下称“本协议”。</p><p>3“用户”：指登录、使用“AI数字人”并拟开通充值服务的“AI数字人”用户，以下称“用户”、“您”。</p><p>4.“会员服务”：指平台为您提供的会员服务，会员服务权益说明请您查看本协议第三条，具体权益内容以您实际购买时的产品界面公示及服务权益实际展示为准。</p><p>5.“算力值”：是我们向用户提供的用于在“AI数字人”及我们合作平台内进行相关消费的虚拟工具，您可以使用“算力值”兑换“AI数字人”及我们合作平台上的特定功能（以下称“算力值服务”）。为免疑义，算力值服务是平台向用户提供的网络技术及相关服务，算力值服务并非网络支付服务，算力值也不是代币票券、虚拟货币或预付款凭证，不具备货币价值、预付价值。具体权益内容以您实际购买时的产品界面公示及服务权益实际展示为准。</p><p>6.“用户账号”：是指用户自己注册、登录并使用的AI数字人的账号。用户账号类型包括“会员账号”、“普通账号”；用户可以使用“用户账号”开通、购买一项或多项的“AI数字人”充值服务。</p><p>7.“会员账号”：指与用户订阅的“AI数字人”会员服务绑定的用户账号。您成功开通会员服务后即成为“会员用户”。</p><p>8.“普通账号”：指并非会员账号的用户账号。</p><p>9.“服务费”：指用户为购买一项或多项充值服务所实际支付的费用，服务费收取方案以相关充值服务产品前端界面公示为准。</p><p>10.“有效期限”/“有效期”/“使用期限”/“订阅期限”：指用户购买一项或多项充值服务后能够获得的使用该服务及相关权益的有效期限，具体以相应充值服务产品前端界面公示的有效期限为准。</p><p>11.“平台规则”：指本协议、《“AI数字人”用户服务协议》、《“AI数字人”隐私政策》，及公司已经或在未来不时发布的“AI数字人”相关服务、单项/多项充值服务的协议、规则等协议和内容的统称。上述内容一经正式发布即生效，为本协议不可分割的组成部分。</p><p><br></p><p>二、充值服务的开通及使用</p><p>1.您须先注册或登录“AI数字人”，方能继续进行本服务的购买、使用。</p><p>2.您在购买、使用任一充值服务前，请务必审慎阅读、充分理解本协议，尤其是本协议中您拟购买的具体充值服务的相关使用规则，以及我们通过黑体加粗等合理方式提示您注意的条款（包括但不限于充值与退订相关规则、充值权益相关使用规则、免除或限制责任条款、法律适用和争议解决条款等）。</p><p>3.如果您不同意本协议的任一或全部条款内容，请不要以确认形式（包括但不限于勾选同意本协议、点击立即购买、支付行为等）进行下一步操作或使用任一项充值服务。当您以确认形式进行下一步操作或使用任一项充值服务，即表示您已阅读并同意签署本协议所有内容，本协议即在您与我们之间产生法律效力，成为对双方均具有约束力的法律文件。</p><p>4.您需确认，您使用任一充值服务，应当具备中华人民共和国法律法规规定的与您行为相适应的民事行为能力。如果您不具备前述与您行为相适应的民事行为能力，则应获得您监护人的知情同意，您及您的监护人应依照法律规定承担因此而导致的相应的责任。特别地，如果您是未成年人，请在您监护人指导和陪同下阅读并判断是否同意本协议及其他相关协议，并特别注意未成年人使用相关约定。若您不具备完全民事行为能力，在使用本服务前，应事先取得您的监护人的同意。</p><p>5.您按照实际产品购买界面完成相应操作步骤，并确认成功支付服务费后即可开通会员服务及/或算力值服务，并获得对应的充值服务权益。 </p><p>6.请注意，您所获得的上述权益的有效期限及所包含的权益范围，需以产品界面公示为准。</p><p>7.请您了解，我们会对同一会员充值账号最多可登录的终端数量、同一会员账号同时在线的终端数量进行限制，具体规则以产品界面公示为准。如您超出上述限制，公司有权视您的超出使用情况对您作出限制登录、限制使用、中断或终止服务等处理措施。</p><p>8.如因法律法规变动、政府行为、情势变更等因素，导致您不能正常使用所购买充值服务的，我们会以适当方式通知您，但不承担由此对您造成的任何损失，您如对此有任何疑问，可以通过本协议约定的投诉和联系方式与我们联系。</p><p>9.请您了解，我们可能会通过您用户账号所绑定的手机号或通过站内信、站内公告等方式向您发送推荐内容、充值用户福利、优惠活动等信息。如您不同意接收，您可随时按照前述信息随附的关闭或退订选项进行关闭或退订。</p><p><br></p><p>三、会员服务</p><p>（一）会员服务</p><p>1.权益类型</p><p>公司向用户提供不同会员权益档位的会员套餐。</p><p>具体套餐以及其对应会员权益，应以您实际购买时的产品界面公示及服务权益实际展示为准。</p><p>2.权益内容</p><p>您开通会员服务后，在您的会员服务有效期内，您将根据您所购买的会员套餐获得以下会员权益：</p><p>（1）会员专享赠送“算力值”权益，（赠送的“算力值”可在有效期内用于数字人分身、语音克隆、合成等功能。</p><p>（2）不限制会员对使用“AI数字人”产品及服务过程中输出的内容出于商业目的进行合法使用、复制和分发、对其进行修改以及创作衍生作品（但不得用于开发或提供“AI数字人”的竞争性产品或者服务）</p><p>（3）其他为提升会员用户体验不时更新的会员服务权益。</p><p>此外，提示您注意，您可享受的会员权益的内容最终以您实际购买时的产品界面展示及服务权益实际展示为准。</p><p><br></p><p>3.权益的变更</p><p>请您了解，为了改善用户体验、完善服务内容，公司可能不时对各会员服务、功能、收费方案、会员订阅方案或升降级方案及用户权益等进行更新、优化，更新及优化过程中可能涉及部分已上线功能、权益的下线或调整，公司将会尽力保障您的合法权益。</p><p>4.权益有效期</p><p>在您开通的充值服务有效期限内，您享有前述权益的用户账号即为您的会员账号。会员服务需要您登录您的会员账号方可使用。</p><p>您购买的会员服务的有效期，以相应会员服务产品前端界面公示的有效期限为准。您所开通的会员服务有效期限届满后，如您未进行续费或继续购买其他会员服务，您的会员账号即恢复为普通账号，您将无法再继续享有相应会员权益。</p><p>公司在此提示您，由于使用有效期限等使用规则与您自身权益相关，请您在购买时务必仔细查阅相关会员服务购买界面及对服务的详情页说明，充分理解本协议、购买界面及详情页说明所公示的内容，以确保您对所购买的会员服务、获得的对应权益及使用规则有清晰地了解和认知。</p><p>5.有效期查询</p><p>您开通会员服务后，您可登录会员账号查询您的充值权益信息（如已开通的权限、有效期限等）</p><p>为提升用户体验，在部分场景下，我们支持会员用户在现有套餐基础上更新会员套餐。</p><p>平台支持更新的会员套餐类型、支付规则、会员权益生效顺序以相关会员购买页面展示为准。</p><p><br></p><p>四、“算力值”服务</p><p>（一）“算力值”权益</p><p>1.“算力值”是我们向用户提供的用于在“AI数字人”平台兑换相关功能的虚拟工具，您可以使用算力值来兑换“AI数字人”上的特定功能（具体请查看“AI数字人”相关服务页面的提示说明）。</p><p>2.为避免产生疑义，算力值服务是平台向用户提供的网络技术及相关服务，算力值服务并非网络支付服务，算力值也不是代币票券、虚拟货币或预付款凭证，不具备货币价值、预付价值。</p><p>3.算力值只能被用于兑换本协议约定的“AI数字人”产品或服务，算力值不支持被反向兑换为人民币及其他货币。</p><p>4.您不得以营利等非个人使用目的获取/使用算力值，或通过赠与、出借、转让、销售、抵押、许可他人使用等方式获取/处置算力值。 </p><p>（二）“算力值”的获取方式及有效期</p><p>1.充值购买</p><p>所有用户均可按照本协议及相关平台规则充值购买算力值，充值购买算力值的费用将由公司或公司指定的合作方向用户收取。</p><p>各充值方式对应的渠道商可能会按其标准制定相关充值渠道手续费用，并在用户充值时收取。因此如您拟充值购买算力值，请您务必注意各充值方式的渠道商服务手续费，并按自身需求选择充值方式。</p><p>2.会员权益额外赠送算力值</p><p>在会员权益订阅生效后，您会员权益项下额外赠送的算力值，将在订阅有效期内以月为周期发放。算力值有效期为自系统发放之日起2年。</p><p>若会员权益发生变更，会员权益赠送的算力值的系统发放时间会跟随会员权益变动而变动，算力值有效期为自系统发放之日起2年。会员权益变动前已下发完毕的会员权益赠送的算力值的有效期不发生变动。</p><p>实际以前端产品功能展示的相关规则说明为准。</p><p>3.活动赠送</p><p>我们可能会根据实际运营情况，向满足一定条件的用户账号（包括会员账号、普通账号）赠送算力值奖励或给予其他优惠。</p><p>赠送算力值的有效期、使用范围等具体细节和活动规则，应以相关服务页面的规则说明为准，请您仔细阅读、理解并遵守。</p><p>（三）算力值使用规则</p><p>1.算力值获得后，不可兑换会员；不同的用户之间、同一用户控制的不同账号之间不可进行算力值的转移、赠送等操作；不支持退款或反向兑换人民币提现。</p><p>2.算力值只能兑换平台内指定的功能使用权或增值服务。</p><p>3.此外，请您知悉，“会员服务”为会员用户专享服务，无法通过算力值兑换获得。</p><p>4.算力值存在有效期，您应当在有效期内使用。 </p><p>5.特别提示您：您会员权益项下的算力值的有效期、您额外充值购买的算力值有效期、您获得赠送的算力值有效期等不同算力值的有效期都可能不同，请您仔细阅读相关服务页面的规则说明。</p><p>6.算力值消耗的优先级规则为：剩余有效期短的算力值优先于剩余有效期长的算力值。</p><p>7.请您了解，为了改善用户体验、完善服务内容，我们可能不时对各算力值服务、功能、收费方案及用户权益等进行更新、优化，更新及优化过程中可能涉及部分已上线功能、权益的下线或调整，我们将会尽力保障您的合法权益。</p><p><br></p><p>五、收费</p><p>（一）费用标准</p><p>1.收费标准</p><p>请您了解，公司对“AI数字人”提供的充值服务享有自主定价的权利。“AI数字人”的各项充值服务适用不同的充值模式，请您仔细阅读本协议及前端产品充值页面中您拟购买的具体充值服务的相关使用规则。公司可能会依据不同营销策略及产品具体情况调整充值模式，实际应以前端产品功能展示的充值模式及相关规则说明为准。</p><p>2.收费方式变更</p><p>请您了解，基于市场与业务发展、经营需要、权益调整，各项充值服务的收费方案或服务项下所包含的具体权益、充值模式、有效期限、赠送权益（如有）等内容可能会不时调整，收费方案的调整将自公布之日起生效，您于该等调整生效前已购买的充值服务相应的使用权益不会受到影响，但若相应权益使用完后需另行购买，需以调整生效后的收费方案为准。公司将尽力保证您的权益，您对此予以理解并同意按照最新公示的收费方案购买相应服务。</p><p>您同意您继续操作的行为（包括但不限于点击同意、或继续购买、或完成支付行为、或使用充值服务等），即视为您知悉并同意变更后的收费方式、收费标准。</p><p>3.价格说明</p><p>除具体充值服务购买界面及相关权益详情页另有解释说明外，购买界面及相关权益详情页中对于价格的说明以本条理解和解释为准：</p><p>划线价格：指公司制定的充值服务权益市场参考价、指导价或其曾经展示过的销售价等。由于地区、时间的差异性和市场行情波动，充值服务权益的参考价、指导价等可能与您实际购买/消费时的展示销售价不一致，该划横线价格仅供您参考。</p><p>未划线价格：指充值服务权益展示的销售价、实时标价，不因表述的差异改变性质。具体成交价格可能根据充值服务全部或部分权益促销活动或用户使用优惠券、算力值等情况发生变化，您购买充值服务权益的实际价格应以订单结算页价格为准。</p><p>活动促销价/折扣价：如无特殊说明，活动促销价/折扣价是在划线价格或市场参考价、指导价基础上给予的优惠价格。如有疑问，您可以在购买前与客服联系。</p><p>会员价：如无特殊说明，指在划线价格或市场参考价、指导价基础上给予的会员用户专享优惠价格。如有疑问，您可以在购买前与客服联系。</p><p>价格异常：因可能存在系统缓存、页面更新延迟等不确定性情况，导致价格显示异常，单项充值服务全部或部分权益的实际价格请以订单结算页价格为准。如您发现异常情况出现，请立即联系我们补正，以便您能顺利完成充值服务相关权益的购买。</p><p>（二）购买和支付</p><p>1.请您注意，本协议项下各充值服务的购买均需在您登录“AI数字人”用户账号后操作，如您不登录用户账号（即“游客模式”）将无法购买充值服务或进行支付操作。</p><p>2.请您务必使用“AI数字人”指定的官方充值方式进行充值（不同终端和系统可支持的支付方式可能不同，请您根据支付页面的指示完成支付）。若您使用非“AI数字人”所指定的充值方式或非法的方式进行充值，公司不保证该充值顺利或者正确完成，同时可能引发其他风险，若因此造成您或任何第三方权益受损，由您自行应对处理并承担相应责任，公司不会作出任何补偿或赔偿，同时公司保留随时中止您的部分或全部权限、冻结您的充值余额、短期或永久封禁您的账号及/或禁止您使用各项充值服务的权利。</p><p>3.不可退费</p><p>由于“AI数字人”充值服务及权益为虚拟消费商品，服务费是您所购买的充值服务所对应的网络商品价格，而非预付款或者存款、定金、储蓄卡等性质，充值服务（包括会员服务及算力值服务）一经开通后不可退款。</p><p>请您在购买相应充值服务之前，仔细核对服务及权益信息、价格、使用期限及本协议相关使用规则，并注意仔细核实您购买服务的用户账号信息。</p><p><br></p><p>六、用户行为规范和违约处理</p><p>1.您在使用充值服务的过程中，不得存在以下行为：</p><p>（1）以盗窃、利用系统漏洞（包括但不限于机器人软件、蜘蛛软件、爬虫软件、刷屏软件等）及规则/系统设置缺陷或错误、通过任何非公司官方或授权渠道、途径、方式获得的任一或多项充值服务及权益（包括但不限于购买、租用、借用、分享、受让等方式获得）、恶意利用或破坏充值服务及权益的行为；</p><p>（2）利用任一或多项充值服务及权益进行营利或非法获利，以各种方式销售、转让、许可或转移您所享有的任一或多项充值服务或充值权益，或将本服务或充值权益有偿借给他人使用；</p><p>（3）通过非法手段对用户账号项下已购买的任一或多项充值服务的服务内容、服务期限、消费记录、交易状态等进行修改，或用非法的方式或为了非法的目的使用已购买的任一或多项充值服务；</p><p>（4）主动对公司用于保护各项充值服务及权益的任何安全措施技术进行破解、更改、反操作、篡改、或其他破坏，或协助他人进行上述行为；</p><p>（5）利用任一或多项充值服务及权益侵犯公司及任何第三方的知识产权、财产权、名誉权等合法权益的行为；</p><p>（6）利用任一或多项充值服务及权益侵害或涉嫌危害未成年人的行为；</p><p>（7）其他违反法律法规或监管政策，违反诚实信用原则、侵犯第三方或AI数字人合法权益的行为以及违反本协议及相关平台规则的行为。</p><p>2.平台尊重并保护用户及他人的合法权益。提醒您注意，如果您使用“AI数字人”时上传/输入涉及个人信息或保密信息等类敏感信息，您应严格遵循相关法律规定，包括获得合法有效的同意。您需保证，在使用“AI数字人”各项功能及服务时，对于您所上传、输入、提交、输出、发布的视频、图片、照片及其他素材、资料等原始内容（以下简称“信息内容”）合法、合规且不侵犯任何第三方的合法权益（包括但不限于肖像权、人格权、著作权及著作权邻接权、专利权、商标权、名誉权、荣誉权、财产权、个人信息、保密信息等，下同），如您违反该要求，相关纠纷需由您自行解决，相关法律责任需由您自行承担，且我们有权在收到并按照相关平台规则核实相关权利方投诉的情况下，自主决定移除该等侵权内容，或在平台权利范围内采取措施禁止您继续应用该等内容，您需自行承担因此给您造成的损失或不利后果；公司向您提供相关服务，不视为认可您上传和发布的“信息内容”合法、合规以及不侵权，如因此给公司造成损失的，公司有权向您追偿，并有权视情况按照本协议第八条采取相关处理措施。</p><p><br></p><p>七、服务中止、终止</p><p>1.您所购买的充值服务的中止或终止包含如下情况：</p><p>（1）您主动中止或终止，包括但不限于中止或终止已购充值服务、使用期限到期未进行续费，或您将用户账号注销等；</p><p>（2）因为您的违约行为，公司主动中止或终止相应充值服务的；</p><p>（3）因国家或相关政府监管部门要求或发生不可抗力事件时，公司中止或终止相应充值服务的；</p><p>（4）其他根据法律法规应中止或终止相应充值服务的。</p><p>2.中止或终止相应充值服务后，公司有权利但无义务确保您收到特别提示或通知，当您发现无法正常使用服务时，您可通过本协议第十一条约定的方式联系公司。</p><p>3.当发生第七条第1项约定的中止或终止情形时：</p><p>（1）除法律规定的责任外，公司不对您和任何第三人承担任何责任；</p><p>（2）除本协议特别约定外，已收取的费用不予退还；</p><p>（3）公司有权利但无义务确保您就相关充值服务的用户数据信息能予以保留。</p><p>4.当发生前述所购买的相应充值服务终止情况后，您无权要求公司继续向您提供相应充值服务、用户权益，或要求公司履行任何其他已终止该充值服务相关的义务，但这并不影响终止前您与公司基于本协议产生的权利义务，因您的原因导致公司遭受第三方索赔、行政部门处罚等，您应当赔偿公司因此产生的损失及（或）发生的费用。</p><p><br></p><p>八、风险和责任</p><p>1.平台倡导理性消费，请您务必根据自身实际需求购买相应充值服务。 </p><p>2.请您了解，为了改善用户体验、完善服务内容，公司可能不时对各充值服务、功能、收费方案、会员订阅方案或升降级方案及用户权益等进行更新、优化，更新及优化过程中可能涉及部分已上线功能、权益的下线或调整，公司将会尽力保障您的合法权益。</p><p>3.请您在每次进行支付操作前，确认充值设备、充值金额、充值账号、账号绑定手机号码、充值需求、操作系统与渠道等信息是否准确无误。您应按照相关功能页面指引完成支付与使用。若因您自身输入账号/账号绑定手机号错误、金额错误、操作不当或不了解、未充分了解充值计费方式等因素造成充错账号、充值金额错误、错选充值种类等情形而导致自身权益受损的，由此带来的损失由您自行承担，我们不会作出补偿或赔偿。</p><p>4.请您注意，各充值服务及相应充值权益仅限您本人通过您注册的用户账号使用。未经公司书面同意，禁止以任何形式赠与、借用、出租、转让、售卖或以其他方式许可他人使用该用户账号及账号内已购服务、权益。如果公司发现或者有合理理由认为实际使用者并非用户账号所有人，为保障用户账号及用户权益安全，公司有权立即暂停或终止向该用户账号提供相应服务或权益。您应正确使用及妥善保管、维护您的用户账号及密码，如非因我们过错而发生的泄漏、遗失、被盗等情况，您需自行承担相应损失。</p><p>5.您开通、购买相应充值服务后，即可按需在对应有效期限内随时使用该充值服务。公司在此提示您，由于使用有效期限等使用规则与您自身权益相关，请您在购买时务必仔细查阅相关充值服务购买界面及对服务的详情页说明，充分理解本协议、购买界面及详情页说明所公示的内容，以确保您对所购买的充值服务、获得的对应权益及使用规则有清晰地了解和认知。</p><p>6.如因法律法规变动、政府行为、情势变更等因素，导致您不能正常使用所购买充值服务的，公司会以适当方式通知您，但不承担由此对您造成的任何损失，您如对此有任何疑问，可以通过本协议约定的投诉和联系方式与公司联系。</p><p>7.您理解并同意，因您自身违反法律、法规，违反本协议或相关平台规则的规定，导致或产生第三方主张的任何索赔、要求或损失，您需独立承担责任，如因此给公司或“AI数字人”造成损失的，您需承担赔偿责任。</p><p>8.如您存在任何违反国家法律法规或监管政策、违反本协议或有损AI数字人或/及其关联公司的声誉、利益的行为的，AI数字人有权采取以下一项或多项处理措施，该等措施包括但不限于：</p><p>（1）通过站内信通知等方式发出警示，要求整改；</p><p>（2）采取一种或多种措施制止您的行为及行为产生的后果，如限制/禁止您继续购买任一或多项充值服务或享受相应服务权益、删除/屏蔽相关链接或内容、限制/取消您的账号/账户部分或全部权限/功能、暂时冻结或者永久性地封禁/冻结您的用户账号等；</p><p>（3）在不通知您的情况下立即中断或终止部分或全部充值服务及对应权益，且您已交纳的充值服务费用将不予退还且不获得任何形式的补偿/赔偿；</p><p>（4）您应对您违法、不当开通、使用任一或多项充值服务的行为及法律后果负责；</p><p>（5）平台有权要求您退还您通过出售、转让、许可等其他方式取得的收益或非法获利（如有）；</p>',
        1731424715, 1732609293);
INSERT INTO `la_tenant_config` (`id`, `tenant_id`, `type`, `name`, `value`, `create_time`, `update_time`)
VALUES (9, 0, 'agreement', 'currency_title', '算力说明', 1731424715, 1731424715);
INSERT INTO `la_tenant_config` (`id`, `tenant_id`, `type`, `name`, `value`, `create_time`, `update_time`)
VALUES (10, 0, 'agreement', 'currency_content',
        '<p style=\"text-align: left;\">1. “算力值”是什么？</p><p style=\"text-align: left;\">2. “算力值”指我们为满足用户使用AI数字人更多服务的需求，进行相关消费，而设计的一种虚拟道具。</p><p style=\"text-align: left;\">3. “算力值”可用于兑换AI数字人平台内指定的功能使用权或增值服务。</p><p style=\"text-align: left;\">4. “算力值”仅限限绑定本账号的用户在AI数字人平台及我们合作平台内获得和使用。</p><p style=\"text-align: left;\">5. 为免疑义，算力值服务是平台向用户提供的网络技术及相关服务，算力值服务并非网络支付服务，算力值也不是代币票券、虚拟货币或预付款凭证，不具备货币价值、预付价值。</p><p style=\"text-align: left;\">6. 购买算力值的收费标准是什么？</p><p style=\"text-align: left;\">7. 标准定价：1元人民币=1000算力值。</p><p style=\"text-align: left;\">8. 优惠定价：我们可能不定期提供购买优惠活动，优惠定价以相关产品界面、活动规则公示为准。</p><p style=\"text-align: left;\">9. 我们可能不时对各算力值服务、功能、收费方案及用户权益等进行更新、优化，最终您获得的算力值额度以相关的产品服务宣传及支付页面向您展示的为准。</p><ol><li style=\"text-align: left;\">“算力值”有什么用，怎么用？</li><li style=\"text-align: left;\">目前，用户充值“算力值”后，可在AI数字人平台及我们合作平台通过消耗“算力值”使用AI创作等功能。具体服务以届时相关界面展示为准。</li><li>如何查看“算力值”余额及使用明细？</li><li>可在【个人中心】面板下的“充值中心”“充值记录”“余额明细”查看具体“算力值”余额和使用明细。</li></ol>',
        1731424715, 1732609293);
INSERT INTO `la_tenant_config` (`id`, `tenant_id`, `type`, `name`, `value`, `create_time`, `update_time`)
VALUES (11, 0, 'agreement', 'disclaimer_title', '免责声明', 1731424715, 1732525836);
INSERT INTO `la_tenant_config` (`id`, `tenant_id`, `type`, `name`, `value`, `create_time`, `update_time`)
VALUES (12, 0, 'agreement', 'disclaimer_content',
        '<p>1.在使用AI数字人产品及相关服务（以下简称“本服务”）前，请您务必仔细阅读并理解透彻本《免责声明》（以下简称“本声明”）。 请您知悉，如果您选择继续使用本服务，意味着您充分知悉并接受以下使用条件： 使用本服务请确保自定义输入文本未侵害他人权益，同时请勿输入政治相关及违反法律法规的内容。 您确认并知悉当前体验服务生成的所有内容都是由人工智能模型生成，我们对其生成内容的准确性、完整性和功能性不做任何保证， 并且其生成的内容不代表我们的态度或观点。我们的服务来自于法律法规允许的包括但不限于公开互联网等信息积累， 并已经过不断的自动及人工敏感数据过滤，但仍不排除其中部分信息具有瑕疵、不合理或引发不快。遇有此情形的， 欢迎并感谢您随时通过本平台用户反馈入口举报。 在同意接受本声明之前，具体的使用规范请严格按照下方“服务使用条款”的内容。此外，无论是否实际阅读本声明，您通过网络页面点击确认同意本声明或实际使用本平台（“我方”）提供的本服务，均表示您与我方已就本声明达成一致并同意接受本声明的全部约定内容。如果您不同意本声明的任意内容，或者无法准确理解我方对本声明条款的解释，请不要同意或使用本服务。否则，表示您已接受了以下所述的条款和条件，同意受本声明约束。 我们尊重并保护所有使用本服务用户的个人隐私，但此提醒用户：您确认使用本服务时输入的内容将不包含您的个人信息。 您理解并且同意，为持续改善本服务，除非有相反证明，您使用本服务上传、发布或传输内容即代表了您有权且同意在全世界范围内，永久性的、不可撤销的、免费的授予我方及其关联方的全部产品或服务对该内容的存储、使用、发布、复制、修改、改编、出版、翻译、据以创作衍生作品、传播、表演和展示等权利；将内容的全部或部分编入其他任何形式的作品、媒体、技术中的权利；对您的上传、发布的内容进行商业开发的权利及其相关宣传和推广等服务的权利；以及再授权给其他第三方以上述方式使用的权利。 服务使用条款 1.您承诺，严格依照本声明使用本服务，并且不会利用本服务进行任何违法或不当的活动，发布、传送或分享含有下列内容之一的信息： (1) 反对宪法所确定的基本原则的； (2) 危害国家安全，泄露国家秘密，颠覆国家政权，破坏国家统一的； (3) 损害国家荣誉和利益的； (4) 煽动民族仇恨、民族歧视、破坏民族团结的； (5) 破坏国家宗教政策，宣扬邪教和封建迷信的； (6) 散布谣言，扰乱社会秩序，破坏社会稳定的； (7) 散布淫秽、色情、赌博、暴力、凶杀、恐怖或者教唆犯罪的； (8) 侮辱或者诽谤他人，侵害他人合法权利的； (9) 含有虚假、诈骗、有害、胁迫、侵害他人隐私、骚扰、侵害、中伤、粗俗、猥亵、或其它道德上令人反感的内容； (10) 含有中国或其您所在国国家管辖法所适用的法律、法规、规章、条例以及任何具有法律效力之规范所限制或禁止的其它内容的。 您应当对使用本服务 是否符合法律法规规定进行必要审查，并由您承担由此产生的所有责任。您确认并同意，我方不会因为本服务或您使用本服务违反上述约定，而需要承担任何 责任。 </p><p>2.您使用本服务不得侵害他人权益（包括但不限于著作权、专利权、商标权等知识产权与其他权益）。同时，您同意并承诺，您使用本服务所提供、使用的 文本内容已获得了充分、必要且有效的合法许可及授权。 </p><p>3.您同意并承诺，在使用本服务时，不会披露任何保密、敏感或个人信息。 </p><p>4.您同意并承诺，本服务以及因使用本服务所取得的任何产出或成果，仅限您本人用于学术测试等合理非商业目的，若您将本服务以及前述产出或成果用于任何商业或其他目的与用途，或自行或透过他人以任何方式或载体向第三方披露、提供、转发、传播或公开所造成的任何纠纷或案件，由您本人承担和解决，我方将不承担任何责任。 </p><p>5.您应理解并同意，本服务尚存在不完备性，我方不对任何服务可用性、可靠性做出任何承诺。我方不对您使用本服务或本服务结果承担任何责任，且本服务结果不代表我方立场。 </p><p>6.我方有权因为业务发展或法律法规的变动而随时对本服务的内容和/或提供方式进行变动，或者暂停或终止本服务。您同意我方将不对因上述情况导致的任何后果，向您或第三方承担任何责任。 </p><p>7.在任何情况下，我方均不就因本服务所发生的任何直接性、间接性、后果性、惩戒性、偶然性、特殊性的损害 (包括但不限于：您使用本服务而遭受的利润损失)，承担任何责任(即使您已事先被告知该等损害发生的可能性)。 </p><p>8.若您违反本声明任何约定，则我方有权随时单方面终止本声明，且我方无需承担任何责任。同时，我方有权根据实际损失向您请求赔偿。 </p><p>9.除双方另有约定外，本声明之解释与适用，以及与本声明有关的争议，均依照中华人民共和国法律予以处理(不包含冲突法) ，并由我方所在地有管辖权的人民法院管辖。</p>',
        1731424715, 1732609225);
COMMIT;

-- ----------------------------
-- Table structure for la_tenant_dept
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_dept`;
CREATE TABLE `la_tenant_dept`
(
    `id`          int(11)                                                      NOT NULL AUTO_INCREMENT COMMENT 'id',
    `tenant_id`   int(11)                                                      NOT NULL COMMENT '租户ID',
    `name`        varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '部门名称',
    `pid`         bigint(20)                                                   NOT NULL DEFAULT 0 COMMENT '上级部门id',
    `sort`        int(11)                                                      NOT NULL DEFAULT 0 COMMENT '排序',
    `leader`      varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT NULL COMMENT '负责人',
    `mobile`      varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT NULL COMMENT '联系电话',
    `status`      tinyint(1)                                                   NOT NULL DEFAULT 0 COMMENT '部门状态（0停用 1正常）',
    `create_time` int(10)                                                      NOT NULL COMMENT '创建时间',
    `update_time` int(10)                                                      NULL     DEFAULT NULL COMMENT '修改时间',
    `delete_time` int(10)                                                      NULL     DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 2
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '租户部门表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of la_tenant_dept
-- ----------------------------
BEGIN;
INSERT INTO `la_tenant_dept`
VALUES (1, 0, '公司', 0, 0, 'boss', '12345698745', 1, 1650592684, 1653640368, NULL);
COMMIT;

-- ----------------------------
-- Table structure for la_tenant_file
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_file`;
CREATE TABLE `la_tenant_file`
(
    `id`          int(10) UNSIGNED                                              NOT NULL AUTO_INCREMENT COMMENT '主键ID',
    `tenant_id`   int(11)                                                       NOT NULL COMMENT '租户ID',
    `cid`         int(10) UNSIGNED                                              NOT NULL DEFAULT 0 COMMENT '类目ID',
    `source_id`   int(11) UNSIGNED                                              NOT NULL DEFAULT 0 COMMENT '上传者id',
    `source`      tinyint(1)                                                    NOT NULL DEFAULT 0 COMMENT '来源类型[0-后台,1-用户]',
    `type`        tinyint(2) UNSIGNED                                           NOT NULL DEFAULT 10 COMMENT '类型[10=图片, 20=视频]',
    `name`        varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '文件名称',
    `ip`          varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'ip地址',
    `uri`         varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '文件路径',
    `create_time` int(10) UNSIGNED                                              NULL     DEFAULT NULL COMMENT '创建时间',
    `update_time` int(10)                                                       NULL     DEFAULT NULL COMMENT '更新时间',
    `delete_time` int(10)                                                       NULL     DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '文件表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_tenant_file_cate
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_file_cate`;
CREATE TABLE `la_tenant_file_cate`
(
    `id`          int(10) UNSIGNED                                             NOT NULL AUTO_INCREMENT COMMENT '主键ID',
    `tenant_id`   int(11)                                                      NOT NULL COMMENT '租户ID',
    `pid`         int(10) UNSIGNED                                             NOT NULL DEFAULT 0 COMMENT '父级ID',
    `type`        tinyint(2) UNSIGNED                                          NOT NULL DEFAULT 10 COMMENT '类型[10=图片，20=视频，30=文件]',
    `name`        varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '分类名称',
    `create_time` int(10) UNSIGNED                                             NULL     DEFAULT NULL COMMENT '创建时间',
    `update_time` int(10) UNSIGNED                                             NULL     DEFAULT NULL COMMENT '更新时间',
    `delete_time` int(10) UNSIGNED                                             NULL     DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '文件分类表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_tenant_jobs
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_jobs`;
CREATE TABLE `la_tenant_jobs`
(
    `id`          int(11)                                                       NOT NULL AUTO_INCREMENT COMMENT 'id',
    `tenant_id`   int(11)                                                       NOT NULL COMMENT '租户ID',
    `name`        varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '岗位名称',
    `code`        varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '岗位编码',
    `sort`        int(11)                                                       NULL     DEFAULT 0 COMMENT '显示顺序',
    `status`      tinyint(1)                                                    NOT NULL DEFAULT 0 COMMENT '状态（0停用 1正常）',
    `remark`      varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT NULL COMMENT '备注',
    `create_time` int(10)                                                       NOT NULL COMMENT '创建时间',
    `update_time` int(10)                                                       NULL     DEFAULT NULL COMMENT '修改时间',
    `delete_time` int(10)                                                       NULL     DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '岗位表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_tenant_notice_record
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_notice_record`;
CREATE TABLE `la_tenant_notice_record`
(
    `id`          int(10) UNSIGNED                                              NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `tenant_id`   int(11)                                                       NOT NULL COMMENT '租户ID',
    `user_id`     int(10) UNSIGNED                                              NOT NULL COMMENT '用户id',
    `title`       varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '标题',
    `content`     text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci         NOT NULL COMMENT '内容',
    `scene_id`    int(10) UNSIGNED                                              NULL     DEFAULT 0 COMMENT '场景',
    `read`        tinyint(1)                                                    NULL     DEFAULT 0 COMMENT '已读状态;0-未读,1-已读',
    `recipient`   tinyint(1)                                                    NULL     DEFAULT 0 COMMENT '通知接收对象类型;1-会员;2-商家;3-平台;4-游客(未注册用户)',
    `send_type`   tinyint(1)                                                    NULL     DEFAULT 0 COMMENT '通知发送类型 1-系统通知 2-短信通知 3-微信模板 4-微信小程序',
    `notice_type` tinyint(1)                                                    NULL     DEFAULT NULL COMMENT '通知类型 1-业务通知 2-验证码',
    `extra`       varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT '' COMMENT '其他',
    `create_time` int(10)                                                       NULL     DEFAULT NULL COMMENT '创建时间',
    `update_time` int(10)                                                       NULL     DEFAULT NULL COMMENT '更新时间',
    `delete_time` int(10)                                                       NULL     DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '通知记录表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_tenant_notice_setting
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_notice_setting`;
CREATE TABLE `la_tenant_notice_setting`
(
    `id`            int(11)                                                       NOT NULL AUTO_INCREMENT,
    `tenant_id`     int(11)                                                       NOT NULL COMMENT '租户ID',
    `scene_id`      int(10)                                                       NOT NULL COMMENT '场景id',
    `scene_name`    varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '场景名称',
    `scene_desc`    varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '场景描述',
    `recipient`     tinyint(1)                                                    NOT NULL DEFAULT 1 COMMENT '接收者 1-用户 2-平台',
    `type`          tinyint(1)                                                    NOT NULL DEFAULT 1 COMMENT '通知类型: 1-业务通知 2-验证码',
    `system_notice` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci         NULL COMMENT '系统通知设置',
    `sms_notice`    text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci         NULL COMMENT '短信通知设置',
    `oa_notice`     text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci         NULL COMMENT '公众号通知设置',
    `mnp_notice`    text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci         NULL COMMENT '小程序通知设置',
    `support`       char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci     NOT NULL DEFAULT '' COMMENT '支持的发送类型 1-系统通知 2-短信通知 3-微信模板消息 4-小程序提醒',
    `update_time`   int(10)                                                       NULL     DEFAULT NULL COMMENT '更新时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 6
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '通知设置表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of la_tenant_notice_setting
-- ----------------------------
BEGIN;
INSERT INTO `la_tenant_notice_setting`
VALUES (1, 0, 101, '登录验证码', '用户手机号码登录时发送', 1, 2,
        '{\"type\":\"system\",\"title\":\"\",\"content\":\"\",\"status\":\"0\",\"is_show\":\"\",\"tips\":[\"可选变量 验证码:code\"]}',
        '{\"type\":\"sms\",\"template_id\":\"SMS_123456\",\"content\":\"您正在登录，验证码${code}，切勿将验证码泄露于他人，本条验证码有效期5分钟。\",\"status\":\"1\",\"is_show\":\"1\"}',
        '{\"type\":\"oa\",\"template_id\":\"\",\"template_sn\":\"\",\"name\":\"\",\"first\":\"\",\"remark\":\"\",\"tpl\":[],\"status\":\"0\",\"is_show\":\"\",\"tips\":[\"可选变量 验证码:code\",\"配置路径：小程序后台 > 功能 > 订阅消息\"]}',
        '{\"type\":\"mnp\",\"template_id\":\"\",\"template_sn\":\"\",\"name\":\"\",\"tpl\":[],\"status\":\"0\",\"is_show\":\"\",\"tips\":[\"可选变量 验证码:code\",\"配置路径：小程序后台 > 功能 > 订阅消息\"]}',
        '2', NULL);
INSERT INTO `la_tenant_notice_setting`
VALUES (2, 0, 102, '绑定手机验证码', '用户绑定手机号码时发送', 1, 2,
        '{\"type\":\"system\",\"title\":\"\",\"content\":\"\",\"status\":\"0\",\"is_show\":\"\"}',
        '{\"type\":\"sms\",\"template_id\":\"SMS_123456\",\"content\":\"您正在绑定手机号，验证码${code}，切勿将验证码泄露于他人，本条验证码有效期5分钟。\",\"status\":\"1\",\"is_show\":\"1\"}',
        '{\"type\":\"oa\",\"template_id\":\"\",\"template_sn\":\"\",\"name\":\"\",\"first\":\"\",\"remark\":\"\",\"tpl\":[],\"status\":\"0\",\"is_show\":\"\"}',
        '{\"type\":\"mnp\",\"template_id\":\"\",\"template_sn\":\"\",\"name\":\"\",\"tpl\":[],\"status\":\"0\",\"is_show\":\"\"}',
        '2', NULL);
INSERT INTO `la_tenant_notice_setting`
VALUES (3, 0, 103, '变更手机验证码', '用户变更手机号码时发送', 1, 2,
        '{\"type\":\"system\",\"title\":\"\",\"content\":\"\",\"status\":\"0\",\"is_show\":\"\",\"tips\":[\"可选变量 验证码:code\"]}',
        '{\"type\":\"sms\",\"template_id\":\"SMS_123456\",\"content\":\"您正在变更手机号，验证码${code}，切勿将验证码泄露于他人，本条验证码有效期5分钟。\",\"status\":\"1\",\"is_show\":\"1\"}',
        '{\"type\":\"oa\",\"template_id\":\"\",\"template_sn\":\"\",\"name\":\"\",\"first\":\"\",\"remark\":\"\",\"tpl\":[],\"status\":\"0\",\"is_show\":\"\",\"tips\":[\"可选变量 验证码:code\",\"配置路径：小程序后台 > 功能 > 订阅消息\"]}',
        '{\"type\":\"mnp\",\"template_id\":\"\",\"template_sn\":\"\",\"name\":\"\",\"tpl\":[],\"status\":\"0\",\"is_show\":\"\",\"tips\":[\"可选变量 验证码:code\",\"配置路径：小程序后台 > 功能 > 订阅消息\"]}',
        '2', NULL);
INSERT INTO `la_tenant_notice_setting`
VALUES (4, 0, 104, '找回登录密码验证码', '用户找回登录密码号码时发送', 1, 2,
        '{\"type\":\"system\",\"title\":\"\",\"content\":\"\",\"status\":\"0\",\"is_show\":\"\",\"tips\":[\"可选变量 验证码:code\"]}',
        '{\"type\":\"sms\",\"template_id\":\"SMS_123456\",\"content\":\"您正在找回登录密码，验证码${code}，切勿将验证码泄露于他人，本条验证码有效期5分钟。\",\"status\":\"0\",\"is_show\":\"1\",\"tips\":[\"可选变量 验证码:code\",\"示例：您正在找回登录密码，验证码${code}，切勿将验证码泄露于他人，本条验证码有效期5分钟。\",\"生效条件：1、管理后台完成短信设置。 2、第三方短信平台申请模板。\"]}',
        '{\"type\":\"oa\",\"template_id\":\"\",\"template_sn\":\"\",\"name\":\"\",\"first\":\"\",\"remark\":\"\",\"tpl\":[],\"status\":\"0\",\"is_show\":\"\",\"tips\":[\"可选变量 验证码:code\",\"配置路径：小程序后台 > 功能 > 订阅消息\"]}',
        '{\"type\":\"mnp\",\"template_id\":\"\",\"template_sn\":\"\",\"name\":\"\",\"tpl\":[],\"status\":\"0\",\"is_show\":\"\",\"tips\":[\"可选变量 验证码:code\",\"配置路径：小程序后台 > 功能 > 订阅消息\"]}',
        '2', NULL);
INSERT INTO `la_tenant_notice_setting`
VALUES (5, 0, 105, '注册验证码', '用户注册账号时发送', 1, 2,
        '{\"type\":\"system\",\"title\":\"\",\"content\":\"\",\"status\":\"0\",\"is_show\":\"\",\"tips\":[\"可选变量 验证码:code\"]}',
        '{\"type\":\"sms\",\"template_id\":\"SMS_175615071\",\"content\":\"验证码${code}，您正在注册成为新用户，感谢您的支持！\",\"status\":\"1\",\"is_show\":\"1\",\"tips\":[\"可选变量 验证码:code\",\"示例：您正在申请注册，验证码${code}，切勿将验证码泄露于他人，本条验证码有效期5分钟。\",\"生效条件：1、管理后台完成短信设置。 2、第三方短信平台申请模板。\"]}',
        '{\"type\":\"oa\",\"template_id\":\"\",\"template_sn\":\"\",\"name\":\"\",\"first\":\"\",\"remark\":\"\",\"tpl\":[],\"status\":\"0\",\"is_show\":\"\",\"tips\":[\"可选变量 验证码:code\",\"配置路径：小程序后台 > 功能 > 订阅消息\"]}',
        '{\"type\":\"mnp\",\"template_id\":\"\",\"template_sn\":\"\",\"name\":\"\",\"tpl\":[],\"status\":\"0\",\"is_show\":\"\",\"tips\":[\"可选变量 验证码:code\",\"配置路径：小程序后台 > 功能 > 订阅消息\"]}',
        '2', NULL);
COMMIT;

-- ----------------------------
-- Table structure for la_tenant_pay_config
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_pay_config`;
CREATE TABLE `la_tenant_pay_config`
(
    `id`        int(11) UNSIGNED                                              NOT NULL AUTO_INCREMENT,
    `tenant_id` int(11)                                                       NOT NULL COMMENT '租户ID',
    `name`      varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '模版名称',
    `pay_way`   tinyint(1)                                                    NOT NULL COMMENT '支付方式:1-余额支付;2-微信支付;3-支付宝支付;',
    `config`    text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci         NULL COMMENT '对应支付配置(json字符串)',
    `icon`      varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT NULL COMMENT '图标',
    `sort`      int(5)                                                        NULL     DEFAULT NULL COMMENT '排序',
    `remark`    varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT NULL COMMENT '备注',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 4
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '支付配置表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of la_tenant_pay_config
-- ----------------------------
BEGIN;
INSERT INTO `la_tenant_pay_config`
VALUES (1, 0, '余额支付', 1, '', 'resource/image/common/balance_pay.png', 128, '余额支付备注');
INSERT INTO `la_tenant_pay_config`
VALUES (2, 0, '微信支付', 2,
        '{\"interface_version\":\"v3\",\"merchant_type\":\"ordinary_merchant\",\"mch_id\":\"\",\"pay_sign_key\":\"\",\"apiclient_cert\":\"\",\"apiclient_key\":\"\"}',
        '/resource/image/common/wechat_pay.png', 123, '微信支付备注');
INSERT INTO `la_tenant_pay_config`
VALUES (3, 0, '支付宝支付', 3,
        '{\"mode\":\"normal_mode\",\"merchant_type\":\"ordinary_merchant\",\"app_id\":\"\",\"private_key\":\"\",\"ali_public_key\":\"\"}',
        '/resource/image/common/ali_pay.png', 123, '支付宝支付');
COMMIT;

-- ----------------------------
-- Table structure for la_tenant_pay_way
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_pay_way`;
CREATE TABLE `la_tenant_pay_way`
(
    `id`            int(11) unsigned NOT NULL AUTO_INCREMENT,
    `tenant_id`     int(11) NOT NULL COMMENT '租户ID',
    `pay_config_id` int(11) NOT NULL COMMENT '支付配置ID',
    `scene`         tinyint(1) NOT NULL COMMENT '场景:1-微信小程序;2-微信公众号;3-H5;4-PC;5-APP;',
    `is_default`    tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否默认支付:0-否;1-是;',
    `status`        tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态:0-关闭;1-开启;',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='租户支付方式表';

-- ----------------------------
-- Records of la_tenant_pay_way
-- ----------------------------
BEGIN;
INSERT INTO `la_tenant_pay_way`
VALUES (1, 0, 1, 1, 0, 1);
INSERT INTO `la_tenant_pay_way`
VALUES (2, 0, 2, 1, 1, 1);
INSERT INTO `la_tenant_pay_way`
VALUES (3, 0, 1, 2, 0, 1);
INSERT INTO `la_tenant_pay_way`
VALUES (4, 0, 2, 2, 1, 1);
INSERT INTO `la_tenant_pay_way`
VALUES (5, 0, 1, 3, 0, 1);
INSERT INTO `la_tenant_pay_way`
VALUES (6, 0, 2, 3, 1, 1);
INSERT INTO `la_tenant_pay_way`
VALUES (7, 0, 3, 3, 0, 1);
INSERT INTO `la_tenant_pay_way`
VALUES (8, 0, 2, 4, 1, 1);
INSERT INTO `la_tenant_pay_way`
VALUES (9, 0, 3, 4, 0, 0);
COMMIT;

-- ----------------------------
-- Table structure for la_tenant_sms_log
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_sms_log`;
CREATE TABLE `la_tenant_sms_log`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
    `tenant_id`   int(11) NOT NULL COMMENT '租户ID',
    `scene_id`    int(11) NOT NULL COMMENT '场景id',
    `mobile`      varchar(11)  NOT NULL COMMENT '手机号码',
    `content`     varchar(255) NOT NULL COMMENT '发送内容',
    `code`        varchar(32) DEFAULT NULL COMMENT '发送关键字（注册、找回密码）',
    `is_verify`   tinyint(1) DEFAULT '0' COMMENT '是否已验证；0-否；1-是',
    `check_num`   int(5) DEFAULT '0' COMMENT '验证次数',
    `send_status` tinyint(1) NOT NULL COMMENT '发送状态：0-发送中；1-发送成功；2-发送失败',
    `send_time`   int(10) NOT NULL COMMENT '发送时间',
    `results`     text COMMENT '短信结果',
    `create_time` int(10) DEFAULT NULL COMMENT '创建时间',
    `update_time` int(10) DEFAULT NULL COMMENT '更新时间',
    `delete_time` int(10) DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='租户短信记录表';

-- ----------------------------
-- Table structure for la_tenant_system_menu
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_system_menu`;
CREATE TABLE `la_tenant_system_menu`
(
    `id`          int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
    `tenant_id`   int(11) NOT NULL COMMENT '租户ID',
    `pid`         int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级菜单',
    `type`        char(2)      NOT NULL DEFAULT '' COMMENT '权限类型: M=目录，C=菜单，A=按钮',
    `name`        varchar(100) NOT NULL DEFAULT '' COMMENT '菜单名称',
    `icon`        varchar(100) NOT NULL DEFAULT '' COMMENT '菜单图标',
    `sort`        smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '菜单排序',
    `perms`       varchar(100) NOT NULL DEFAULT '' COMMENT '权限标识',
    `paths`       varchar(100) NOT NULL DEFAULT '' COMMENT '路由地址',
    `component`   varchar(200) NOT NULL DEFAULT '' COMMENT '前端组件',
    `selected`    varchar(200) NOT NULL DEFAULT '' COMMENT '选中路径',
    `params`      varchar(200) NOT NULL DEFAULT '' COMMENT '路由参数',
    `is_cache`    tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否缓存: 0=否, 1=是',
    `is_show`     tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示: 0=否, 1=是',
    `is_disable`  tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否禁用: 0=否, 1=是',
    `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
    `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=300 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC COMMENT='租户系统菜单表';

-- ----------------------------
-- Records of la_tenant_system_menu
-- ----------------------------
BEGIN;
INSERT INTO `la_tenant_system_menu`
VALUES (1, 0, 0, 'M', '权限管理', 'el-icon-Lock', 300, '', 'permission', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (2, 0, 0, 'C', '工作台', 'el-icon-Monitor', 1000, 'workbench/index', 'workbench', 'workbench/index', '', '', 0,
        1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (3, 0, 0, 'M', '组织管理', 'el-icon-OfficeBuilding', 400, '', 'organization', '', '', '', 0, 1, 0, 1733305156,
        1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (4, 0, 0, 'M', '系统设置', 'el-icon-Setting', 200, '', 'setting', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (5, 0, 0, 'M', '渠道设置', 'el-icon-Message', 500, '', 'channel', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (6, 0, 0, 'M', '装修管理', 'el-icon-Brush', 600, '', 'decoration', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (7, 0, 0, 'M', '用户管理', 'el-icon-User', 900, '', 'consumer', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (8, 0, 0, 'M', '应用管理', 'el-icon-Postcard', 800, '', 'app', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (9, 0, 0, 'M', '财务管理', 'local-icon-HugeiconsChartHistogram', 700, '', 'finance', '', '', '', 0, 1, 0,
        1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (10, 0, 1, 'C', '菜单', 'el-icon-Operation', 100, 'auth.menu/lists', 'menu', 'permission/menu/index', '', '', 1,
        1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (11, 0, 1, 'C', '管理员', 'local-icon-shouyiren', 80, 'auth.admin/lists', 'admin', 'permission/admin/index', '',
        '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (12, 0, 1, 'C', '角色', 'el-icon-Female', 90, 'auth.role/lists', 'role', 'permission/role/index', '', '', 0, 1,
        0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (13, 0, 10, 'A', '新增', '', 1, 'auth.menu/add', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (14, 0, 10, 'A', '编辑', '', 1, 'auth.menu/edit', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (15, 0, 10, 'A', '删除', '', 1, 'auth.menu/delete', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (16, 0, 10, 'A', '详情', '', 0, 'auth.menu/detail', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (17, 0, 11, 'A', '新增', '', 1, 'auth.admin/add', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (18, 0, 11, 'A', '编辑', '', 1, 'auth.admin/edit', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (19, 0, 11, 'A', '删除', '', 1, 'auth.admin/delete', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (20, 0, 11, 'A', '详情', '', 0, 'auth.admin/detail', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (21, 0, 12, 'A', '新增', '', 1, 'auth.role/add', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (22, 0, 12, 'A', '编辑', '', 1, 'auth.role/edit', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (23, 0, 12, 'A', '删除', '', 1, 'auth.role/delete', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (24, 0, 3, 'C', '部门管理', 'el-icon-Coordinate', 100, 'dept.dept/lists', 'department',
        'organization/department/index', '', '', 1, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (25, 0, 3, 'C', '岗位管理', 'el-icon-PriceTag', 90, 'dept.jobs/lists', 'post', 'organization/post/index', '', '',
        0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (26, 0, 24, 'A', '新增', '', 1, 'dept.dept/add', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (27, 0, 24, 'A', '编辑', '', 1, 'dept.dept/edit', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (28, 0, 24, 'A', '删除', '', 1, 'dept.dept/delete', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (29, 0, 24, 'A', '详情', '', 0, 'dept.dept/detail', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (30, 0, 25, 'A', '新增', '', 1, 'dept.jobs/add', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (31, 0, 25, 'A', '编辑', '', 1, 'dept.jobs/edit', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (32, 0, 25, 'A', '删除', '', 1, 'dept.jobs/delete', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (33, 0, 25, 'A', '详情', '', 0, 'dept.jobs/detail', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (34, 0, 4, 'M', '网站设置', 'el-icon-Basketball', 100, '', 'website', '', '', '', 0, 1, 0, 1733305156,
        1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (35, 0, 4, 'M', '系统维护', 'el-icon-SetUp', 50, '', 'system', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (36, 0, 4, 'C', '热门搜索', 'el-icon-Search', 60, 'setting.hot_search/getConfig', 'search',
        'setting/search/index', '', '', 0, 0, 1, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (37, 0, 4, 'M', '用户设置', 'local-icon-keziyuyue', 90, '', 'user', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (38, 0, 4, 'M', '支付设置', 'local-icon-set_pay', 80, '', 'pay', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (39, 0, 34, 'C', '网站信息', '', 1, 'setting.web.web_setting/getWebsite', 'information',
        'setting/website/information', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (40, 0, 34, 'C', '网站备案', '', 1, 'setting.web.web_setting/getCopyright', 'filing', 'setting/website/filing',
        '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (41, 0, 34, 'C', '政策协议', '', 1, 'setting.web.web_setting/getAgreement', 'protocol',
        'setting/website/protocol', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (42, 0, 34, 'C', '站点统计', '', 0, 'setting.web.web_setting/getSiteStatistics', 'statistics',
        'setting/website/statistics', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (43, 0, 39, 'A', '保存', '', 1, 'setting.web.web_setting/setWebsite', '', '', '', '', 0, 1, 0, 1733305156,
        1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (44, 0, 40, 'A', '保存', '', 1, 'setting.web.web_setting/setCopyright', '', '', '', '', 0, 1, 0, 1733305156,
        1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (45, 0, 41, 'A', '保存', '', 1, 'setting.web.web_setting/setAg         reement', '', '', '', '', 0, 1, 0,
        1733305156,
        1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (46, 0, 35, 'C', '系统缓存', '', 80, '', 'cache', 'setting/system/cache', '', '', 0, 1, 0, 1733305156,
        1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (47, 0, 46, 'A', '清除系统缓存', '', 1, 'setting.system.cache/clear', '', '', '', '', 0, 1, 0, 1733305156,
        1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (48, 0, 94, 'C', '素材中心', 'el-icon-PictureRounded', 0, '', 'index', 'material/index', '', '', 0, 1, 0,
        1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (49, 0, 95, 'C', '文章管理', 'el-icon-ChatDotSquare', 0, 'article.article/lists', 'lists', 'article/lists/index',
        '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (50, 0, 95, 'C', '文章添加/编辑', '', 0, 'article.article/add:edit', 'lists/edit', 'article/lists/edit',
        '/article/lists', '', 0, 0, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (51, 0, 95, 'C', '文章栏目', 'el-icon-CollectionTag', 0, 'article.articleCate/lists', 'column',
        'article/column/index', '', '', 1, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (52, 0, 49, 'A', '新增', '', 0, 'article.article/add', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (53, 0, 49, 'A', '详情', '', 0, 'article.article/detail', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (54, 0, 49, 'A', '删除', '', 0, 'article.article/delete', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (55, 0, 49, 'A', '修改状态', '', 0, 'article.article/updateStatus', '', '', '', '', 0, 1, 0, 1733305156,
        1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (56, 0, 49, 'A', '编辑', '', 0, 'article.article/edit', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (57, 0, 51, 'A', '添加', '', 0, 'article.articleCate/add', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (58, 0, 51, 'A', '删除', '', 0, 'article.articleCate/delete', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (59, 0, 51, 'A', '详情', '', 0, 'article.articleCate/detail', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (60, 0, 51, 'A', '修改状态', '', 0, 'article.articleCate/updateStatus', '', '', '', '', 0, 1, 0, 1733305156,
        1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (61, 0, 5, 'C', 'h5设置', 'el-icon-Cellphone', 100, 'channel.web_page_setting/getConfig', 'h5', 'channel/h5', '',
        '', 0, 0, 1, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (62, 0, 5, 'M', '微信公众号', 'local-icon-dingdan', 80, '', 'wx_oa', '', '', '', 0, 1, 0, 1733305156,
        1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (63, 0, 156, 'C', '小程序配置', '', 90, 'channel.mnp_settings/getConfig', 'weapp',
        'channel/weapp', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (64, 0, 5, 'C', '微信开放平台', 'local-icon-notice_buyer', 70, 'channel.open_setting/getConfig', 'open_setting',
        'channel/open_setting', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (65, 0, 61, 'A', '保存', '', 0, 'channel.web_page_setting/setConfig', '', '', '', '', 0, 1, 0, 1733305156,
        1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (66, 0, 62, 'C', '公众号配置', '', 0, 'channel.official_account_setting/getConfig', 'config',
        'channel/wx_oa/config', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (67, 0, 62, 'C', '菜单管理', '', 0, 'channel.official_account_menu/detail', 'menu', 'channel/wx_oa/menu', '', '',
        0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (68, 0, 62, 'C', '关注回复', '', 0, 'channel.official_account_reply/lists', 'follow',
        'channel/wx_oa/reply/follow_reply', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (69, 0, 62, 'C', '关键字回复', '', 0, '', 'keyword', 'channel/wx_oa/reply/keyword_reply', '', '', 0, 1, 0,
        1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (70, 0, 62, 'C', '默认回复', '', 0, '', 'default', 'channel/wx_oa/reply/default_reply', '', '', 0, 1, 0,
        1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (71, 0, 66, 'A', '保存', '', 0, 'channel.official_account_setting/setConfig', '', '', '', '', 0, 1, 0,
        1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (72, 0, 66, 'A', '保存并发布', '', 0, 'channel.official_account_menu/save', '', '', '', '', 0, 1, 0, 1733305156,
        1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (73, 0, 63, 'A', '保存', '', 0, 'channel.mnp_settings/setConfig', '', '', '', '', 0, 1, 0, 1733305156,
        1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (74, 0, 6, 'M', '移动端', '', 100, '', 'mobile', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (75, 0, 6, 'M', 'PC端', '', 90, '', 'pc', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (76, 0, 109, 'A', '保存', '', 0, 'decorate.page/save', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (77, 0, 110, 'A', '保存', '', 0, 'decorate.tabbar/save', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (78, 0, 96, 'C', '通知设置', '', 0, 'notice.notice/settingLists', 'notice', 'message/notice/index', '', '', 0, 1,
        0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (79, 0, 96, 'C', '通知设置编辑', '', 0, 'notice.notice/set', 'notice/edit', 'message/notice/edit',
        '/message/notice', '', 0, 0, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (80, 0, 96, 'C', '短信设置', '', 0, 'notice.sms_config/getConfig', 'short_letter', 'message/short_letter/index',
        '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (81, 0, 78, 'A', '详情', '', 0, 'notice.notice/detail', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (82, 0, 80, 'A', '设置', '', 0, 'notice.sms_config/setConfig', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (83, 0, 80, 'A', '详情', '', 0, 'notice.sms_config/detail', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (84, 0, 36, 'A', '保存', '', 0, 'setting.hot_search/setConfig', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (85, 0, 37, 'C', '用户设置', '', 0, 'setting.user.user/getConfig', 'setup', 'setting/user/setup', '', '', 0, 1,
        0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (86, 0, 37, 'C', '登录注册', '', 0, 'setting.user.user/getRegisterConfig', 'login_register',
        'setting/user/login_register', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (87, 0, 85, 'A', '保存', '', 0, 'setting.user.user/setConfig', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (88, 0, 86, 'A', '保存', '', 0, 'setting.user.user/setRegisterConfig', '', '', '', '', 0, 1, 0, 1733305156,
        1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (89, 0, 7, 'C', '用户列表', 'local-icon-user_guanli', 100, 'user.user/lists', 'lists', 'consumer/lists/index',
        '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (90, 0, 7, 'C', '用户详情', '', 90, 'user.user/detail', 'lists/detail', 'consumer/lists/detail',
        '/consumer/lists', '', 0, 0, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (91, 0, 90, 'A', '编辑', '', 0, 'user.user/edit', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (92, 0, 90, 'A', '余额调整', '', 0, 'user.user/adjustMoney', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (93, 0, 64, 'A', '保存', '', 0, 'channel.open_setting/setConfig', '', '', '', '', 0, 1, 0, 1733305156,
        1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (94, 0, 8, 'M', '素材管理', 'el-icon-Picture', 0, '', 'material', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (95, 0, 8, 'M', '帮助中心', 'el-icon-ChatLineSquare', 90, '', 'article', '', '', '', 0, 1, 0, 1733305156,
        1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (96, 0, 8, 'M', '消息管理', 'el-icon-ChatDotRound', 80, '', 'message', '', '', '', 0, 1, 0, 1733305156,
        1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (97, 0, 8, 'C', '用户充值', 'local-icon-fukuan', 100, 'recharge.recharge/getConfig', 'recharge',
        'app/recharge/index', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (98, 0, 97, 'A', '保存', '', 0, 'recharge.recharge/setConfig', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (99, 0, 38, 'C', '支付方式', '', 0, 'setting.pay.pay_way/getPayWay', 'method', 'setting/pay/method/index', '',
        '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (100, 0, 38, 'C', '支付配置', '', 0, 'setting.pay.pay_config/lists', 'config', 'setting/pay/config/index', '',
        '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (101, 0, 99, 'A', '设置支付方式', '', 0, 'setting.pay.pay_way/setPayWay', '', '', '', '', 0, 1, 0, 1733305156,
        1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (102, 0, 100, 'A', '配置', '', 0, 'setting.pay.pay_config/setConfig', '', '', '', '', 0, 1, 0, 1733305156,
        1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (103, 0, 9, 'C', '充值记录', 'el-icon-Wallet', 90, 'recharge.recharge/lists', 'recharge_record',
        'finance/recharge_record', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (104, 0, 9, 'C', '余额明细', 'local-icon-qianbao', 100, 'finance.account_log/lists', 'balance_details',
        'finance/balance_details', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (105, 0, 9, 'C', '退款记录', 'local-icon-heshoujilu', 0, 'finance.refund/record', 'refund_record',
        'finance/refund_record', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (106, 0, 103, 'A', '退款', '', 0, 'recharge.recharge/refund', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (107, 0, 105, 'A', '重新退款', '', 0, 'recharge.recharge/refundAgain', '', '', '', '', 0, 1, 0, 1733305156,
        1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (108, 0, 105, 'A', '退款日志', '', 0, 'finance.refund/log', '', '', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (109, 0, 74, 'C', '页面装修', 'el-icon-CopyDocument', 100, 'decorate.page/detail', 'pages',
        'decoration/pages/index', '', '', 0, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (110, 0, 74, 'C', '底部导航', 'el-icon-Position', 90, 'decorate.tabbar/detail', 'tabbar', 'decoration/tabbar',
        '', '', 0, 0, 1, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (111, 0, 74, 'C', '系统风格', 'el-icon-Brush', 80, '', 'style', 'decoration/style/style', '', '', 0, 0, 1,
        1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (112, 0, 75, 'C', 'PC端装修', 'el-icon-Monitor', 8, '', 'pc', 'decoration/pc', '', '', 0, 1, 0, 1733305156,
        1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (113, 0, 42, 'A', '保存', '', 0, 'setting.web.web_setting/saveSiteStatistics', '', '', '', '', 1, 1, 0,
        1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (114, 0, 0, 'M', '营销中心', 'local-icon-HugeiconsGift', 750, '', 'marketing', '', '', '', 1, 1, 0, 1733305156,
        1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (115, 0, 114, 'C', '充值套餐', '', 0, '', 'recharge', 'marketing/recharge/index', '', '', 1, 1, 0, 1733305156,
        1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (116, 0, 115, 'A', '新增套餐', '', 0, 'power.powerPackage/add', '', '', '', '', 1, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (117, 0, 115, 'A', '删除套餐', '', 0, 'power.powerPackage/delete', '', '', '', '', 1, 1, 0, 1733305156,
        1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (118, 0, 115, 'A', '套餐详情', '', 0, 'power.powerPackage/detail', '', '', '', '', 1, 1, 0, 1733305156,
        1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (119, 0, 0, 'M', '数字分身', 'local-icon-HugeiconsArtificialIntelligence02', 990, '', 'avatar', '', '', '', 1, 1,
        0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (120, 0, 119, 'C', '合成记录', '', 0, 'avatar.aiAvatarRecord/lists', 'lists', 'tool/avatar/index', '', '', 1, 1,
        0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (121, 0, 120, 'A', '删除', '', 0, 'avatar.aiAvatarRecord/delete', '', '', '', '', 1, 1, 0, 1733305156,
        1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (122, 0, 120, 'A', '详情', '', 0, 'avatar.aiAvatarRecord/detail', '', '', '', '', 1, 1, 0, 1733305156,
        1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (123, 0, 119, 'C', '应用配置', '', 0, 'setting.power.powerConfig/getAllAvtarConfig', 'config',
        'tool/avatar/config', '', '', 1, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (124, 0, 123, 'A', '设置', '', 0, 'setting.power.powerConfig/setAvtarConfig', '', '', '', '', 1, 1, 0,
        1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (125, 0, 119, 'C', '形象列表', '', 0, 'video.video/lists', 'video', 'tool/avatar/video', '', '', 0, 1, 0,
        1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (126, 0, 0, 'M', '声音克隆', 'local-icon-HugeiconsAudioBook03', 980, '', 'avatarVoice', '', '', '', 1, 1, 0,
        1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (127, 0, 126, 'C', '克隆记录', '', 0, 'voice.avatarVoice/lists', 'lists', 'tool/voice/index', '', '', 1, 1, 0,
        1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (128, 0, 127, 'A', '删除', '', 0, 'voice.avatarVoice/delete', '', '', '', '', 1, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (129, 0, 127, 'A', '查看音频', '', 0, 'voice.avatarVoice/detail', '', '', '', '', 1, 1, 0, 1733305156,
        1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (130, 0, 126, 'C', '应用配置', '', 0, 'setting.power.powerConfig/getVoiceCloneConfig', 'config',
        'tool/voice/config', '', '', 1, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (131, 0, 0, 'M', '声音合成', 'local-icon-HugeiconsFileAudio', 970, '', 'voicerecord', '', '', '', 1, 1, 0,
        1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (132, 0, 131, 'C', '合成记录', '', 0, 'voicerecord.voiceRecord/lists', 'lists', 'tool/voiceRecord/index', '', '',
        1, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (133, 0, 132, 'A', '查看结果', '', 0, 'voicerecord.voiceRecord/detail', '', '', '', '', 1, 1, 0, 1733305156,
        1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (134, 0, 132, 'A', '删除', '', 0, 'voicerecord.voiceRecord/delete', '', '', '', '', 1, 1, 0, 1733305156,
        1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (135, 0, 131, 'C', '应用配置', '', 0, 'setting.power.powerConfig/getVoiceConfig', 'config',
        'tool/voiceRecord/config', '', '', 1, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (136, 0, 114, 'C', '注册赠送', '', 0, 'marketing.marketingSetting/getGiftConfig', 'gift', 'marketing/gift/index',
        '', '', 1, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (137, 0, 130, 'A', '保存', '', 0, 'setting.power.powerConfig/setVoiceCloneConfig', '', '', '', '', 1, 1, 0,
        1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (138, 0, 135, 'A', '保存', '', 0, 'setting.power.powerConfig/setVoiceConfig', '', '', '', '', 1, 1, 0,
        1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (139, 0, 136, 'A', '保存', '', 0, 'marketing.marketingSetting/setGiftConfig', '', '', '', '', 1, 1, 0,
        1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (140, 0, 0, 'M', 'AI设置', 'local-icon-HugeiconsAiBrain03', 960, '', 'ai_setting', '', '', '', 1, 1, 0,
        1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (141, 0, 140, 'C', 'key池管理', '', 0, 'key.keyPool/lists', 'key_setting', 'ai_setting/key_setting/index', '',
        '', 1, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (142, 0, 141, 'A', '删除', '', 0, 'key.keyPool/del', '', '', '', '', 1, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (143, 0, 141, 'A', '编辑', '', 0, 'key.keyPool/edit', '', '', '', '', 1, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (144, 0, 141, 'A', '新增', '', 0, 'key.keyPool/add', '', '', '', '', 1, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (145, 0, 141, 'A', '状态', '', 0, 'key.keyPool/status', '', '', '', '', 1, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (146, 0, 140, 'C', '内容审核', '', 0, 'sensitive.words/lists', 'sensitive', 'ai_setting/sensitive/index', '', '',
        1, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (147, 0, 146, 'A', '新增', '', 0, 'sensitive.words/add', '', '', '', '', 1, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (148, 0, 146, 'A', '删除', '', 0, 'sensitive.words/delete', '', '', '', '', 1, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (149, 0, 146, 'A', '编辑', '', 0, 'sensitive.words/edit', '', '', '', '', 1, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (150, 0, 140, 'C', '其他设置', '', 0, 'marketing.marketingSetting/getMarketinConfig', 'unit',
        'marketing/unit/index', '', '', 1, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (151, 0, 150, 'A', '保存', '', 0, 'marketing.marketingSetting/setMarketingCinfig', '', '', '', '', 1, 1, 0,
        1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (152, 0, 125, 'A', '查看视频', '', 0, 'video.video/detail', '', '', '', '', 1, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (153, 0, 125, 'A', '删除', '', 0, 'video.video/delete', '', '', '', '', 1, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (154, 0, 4, 'C', '客服设置', 'local-icon-HugeiconsCustomerService', 0, '', 'service', 'setting/service/index',
        '', '', 1, 1, 0, 1733305156, 1733305156);
INSERT INTO `la_tenant_system_menu`
VALUES (155, 0, 7, 'A', '新建用户', '', 80, 'user.user/add', '', '', '', '', 1, 1, 0, 1733728448, 1733728457);
INSERT INTO `la_tenant_system_menu`
VALUES (156, 0, 5, 'M', '微信小程序', 'local-icon-HugeiconsWechat', 20, '', 'weapp', '', '', '', 1, 1, 0, 1736504607,
        1736504607);
INSERT INTO `la_tenant_system_menu`
VALUES (157, 0, 156, 'C', '一键上传', '', 0, 'channel.mnp_settings/uploadMnp', 'upload', 'channel/weapp_upload', '', '',
        0, 1, 0, 1736504781, 1736504781);
COMMIT;

-- ----------------------------
-- Table structure for la_tenant_system_role
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_system_role`;
CREATE TABLE `la_tenant_system_role`
(
    `id`          int(11) UNSIGNED                                             NOT NULL AUTO_INCREMENT,
    `tenant_id`   int(11)                                                      NOT NULL COMMENT '租户ID',
    `name`        varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '名称',
    `desc`        varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci      NOT NULL DEFAULT '' COMMENT '描述',
    `sort`        int(11)                                                      NULL     DEFAULT 0 COMMENT '排序',
    `create_time` int(10)                                                      NULL     DEFAULT NULL COMMENT '创建时间',
    `update_time` int(10)                                                      NULL     DEFAULT NULL COMMENT '更新时间',
    `delete_time` int(10)                                                      NULL     DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '角色表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_tenant_system_role_menu
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_system_role_menu`;
CREATE TABLE `la_tenant_system_role_menu`
(
    `role_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '角色ID',
    `menu_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '菜单ID',
    PRIMARY KEY (`role_id`, `menu_id`) USING BTREE
) ENGINE = InnoDB
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '角色菜单关系表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_user
-- ----------------------------
DROP TABLE IF EXISTS `la_user`;
CREATE TABLE `la_user`
(
    `id`                    int(10) UNSIGNED                                              NOT NULL AUTO_INCREMENT COMMENT '主键',
    `tenant_id`             int(11)                                                       NOT NULL COMMENT '租户ID',
    `sn`                    int(10) UNSIGNED                                              NOT NULL DEFAULT 0 COMMENT '编号',
    `avatar`                varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '头像',
    `real_name`             varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  NOT NULL DEFAULT '' COMMENT '真实姓名',
    `nickname`              varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  NOT NULL DEFAULT '' COMMENT '用户昵称',
    `account`               varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  NOT NULL DEFAULT '' COMMENT '用户账号',
    `password`              varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  NOT NULL DEFAULT '' COMMENT '用户密码',
    `mobile`                varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci  NOT NULL DEFAULT '' COMMENT '用户电话',
    `email`                 varchar(100)                                                  NOT NULL DEFAULT '' COMMENT '用户邮箱',
    `sex`                   tinyint(1) UNSIGNED                                           NOT NULL DEFAULT 0 COMMENT '用户性别: [1=男, 2=女]',
    `channel`               tinyint(1) UNSIGNED                                           NOT NULL DEFAULT 0 COMMENT '注册渠道: [1-微信小程序 2-微信公众号 3-手机H5 4-电脑PC 5-苹果APP 6-安卓APP]',
    `is_disable`            tinyint(1) UNSIGNED                                           NOT NULL DEFAULT 0 COMMENT '是否禁用: [0=否, 1=是]',
    `login_ip`              varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '最后登录IP',
    `login_time`            int(10) UNSIGNED                                              NOT NULL DEFAULT 0 COMMENT '最后登录时间',
    `is_new_user`           tinyint(1)                                                    NOT NULL DEFAULT 0 COMMENT '是否是新注册用户: [1-是, 0-否]',
    `user_money`            decimal(10, 2) UNSIGNED                                       NULL     DEFAULT 0.00 COMMENT '用户余额',
    `total_recharge_amount` decimal(10, 2) UNSIGNED                                       NULL     DEFAULT 0.00 COMMENT '累计充值',
    `create_time`           int(10) UNSIGNED                                              NOT NULL DEFAULT 0 COMMENT '创建时间',
    `update_time`           int(10) UNSIGNED                                              NOT NULL DEFAULT 0 COMMENT '更新时间',
    `delete_time`           int(10) UNSIGNED                                              NULL     DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE,
    UNIQUE INDEX `sn` (`sn`) USING BTREE COMMENT '编号唯一',
    UNIQUE INDEX `account` (`account`) USING BTREE COMMENT '账号唯一'
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '用户表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_user_account_log
-- ----------------------------
DROP TABLE IF EXISTS `la_user_account_log`;
CREATE TABLE `la_user_account_log`
(
    `id`            int(11) UNSIGNED                                              NOT NULL AUTO_INCREMENT,
    `tenant_id`     int(11)                                                       NOT NULL COMMENT '租户ID',
    `sn`            varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT '' COMMENT '流水号',
    `user_id`       int(11)                                                       NOT NULL COMMENT '用户id',
    `change_object` tinyint(1)                                                    NOT NULL DEFAULT 0 COMMENT '变动对象',
    `change_type`   smallint(5)                                                   NOT NULL COMMENT '变动类型',
    `action`        tinyint(1)                                                    NOT NULL DEFAULT 0 COMMENT '动作 1-增加 2-减少',
    `change_amount` decimal(10, 2)                                               NOT NULL COMMENT '变动数量',
    `left_amount`   decimal(10, 2)                                               NOT NULL DEFAULT 100.00 COMMENT '变动后数量',
    `source_sn`     varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT NULL COMMENT '关联单号',
    `remark`        varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT '' COMMENT '备注',
    `extra`         text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci         NULL COMMENT '预留扩展字段',
    `create_time`   int(10)                                                       NULL     DEFAULT NULL COMMENT '创建时间',
    `update_time`   int(10)                                                       NULL     DEFAULT NULL COMMENT '更新时间',
    `delete_time`   int(10)                                                       NULL     DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '用户账户变动记录表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_user_auth
-- ----------------------------
DROP TABLE IF EXISTS `la_user_auth`;
CREATE TABLE `la_user_auth`
(
    `id`          int(11)                                                       NOT NULL AUTO_INCREMENT,
    `tenant_id`   int(11)                                                       NOT NULL COMMENT '租户ID',
    `user_id`     int(11)                                                       NOT NULL COMMENT '用户id',
    `openid`      varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '微信openid',
    `unionid`     varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL     DEFAULT '' COMMENT '微信unionid',
    `terminal`    tinyint(1)                                                    NOT NULL DEFAULT 1 COMMENT '客户端类型：1-微信小程序；2-微信公众号；3-手机H5；4-电脑PC；5-苹果APP；6-安卓APP',
    `create_time` int(10)                                                       NULL     DEFAULT NULL COMMENT '创建时间',
    `update_time` int(10)                                                       NULL     DEFAULT NULL COMMENT '更新时间',
    PRIMARY KEY (`id`) USING BTREE,
    UNIQUE INDEX `openid` (`openid`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '用户授权表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_user_session
-- ----------------------------
DROP TABLE IF EXISTS `la_user_session`;
CREATE TABLE `la_user_session`
(
    `id`          int(11)                                                      NOT NULL AUTO_INCREMENT,
    `tenant_id`   int(11)                                                      NOT NULL COMMENT '租户ID',
    `user_id`     int(11)                                                      NOT NULL COMMENT '用户id',
    `terminal`    tinyint(1)                                                   NOT NULL DEFAULT 1 COMMENT '客户端类型：1-微信小程序；2-微信公众号；3-手机H5；4-电脑PC；5-苹果APP；6-安卓APP',
    `token`       varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '令牌',
    `update_time` int(10)                                                      NULL     DEFAULT NULL COMMENT '更新时间',
    `expire_time` int(10)                                                      NOT NULL COMMENT '到期时间',
    PRIMARY KEY (`id`) USING BTREE,
    UNIQUE INDEX `admin_id_client` (`user_id`, `terminal`) USING BTREE COMMENT '一个用户在一个终端只有一个token',
    UNIQUE INDEX `token` (`token`) USING BTREE COMMENT 'token是唯一的'
) ENGINE = InnoDB
  AUTO_INCREMENT = 1
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_general_ci COMMENT = '用户会话表'
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for la_tenant_ai_avatar_record
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_ai_avatar_record`;
CREATE TABLE `la_tenant_ai_avatar_record`
(
    `id`              int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
    `tenant_id`       int(11) NOT NULL COMMENT '租户ID',
    `uid`             int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户ID',
    `task_id`         varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '流水号',
    `title`           varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '标题',
    `cover`           varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '封面',
    `voice_id`        varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '克隆声素材ID',
    `mode`            int(2) COMMENT '模型',
    `video_id`        varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '数字人素材ID',
    `cost_power`      varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '消耗算力',
    `status`          varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '状态：0=合成中，1=已完成 2=失败',
    `terminal`        INT(2)   COMMENT '来源:1-微信小程序,2-微信公众号,3-手机H5,4-电脑PC,5-苹果APP,6-安卓APP',
    `completion_time` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '完成时间',
    `cost_time`       varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '耗时',
    `file_id`         varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '生成文件id',
    `duration`        varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '视频时长',
    `size`            varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '大小',
    `fail_reason`     varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '失败原因',
    `timbre_name`     varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '音色名称',
    `create_time`     int(10) UNSIGNED NULL DEFAULT NULL COMMENT '创建时间',
    `update_time`     int(11) NULL DEFAULT NULL COMMENT '更新时间',
    `delete_time`     int(11) NULL DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '租户数字人合成记录表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for la_tenant_video
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_video`;
CREATE TABLE `la_tenant_video`
(
    `id`          int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
    `tenant_id`   int(11) NOT NULL COMMENT '租户ID',
    `uid`         int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户ID',
    `name`        varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '数字人素材名',
    `record`      varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '备注',
    `duration`    varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '视频时长',
    `file_id`     varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件id',
    `cover`       varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '封面',
    `image_id`    varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'V3模型号',
    `status`      int(10) NULL DEFAULT 0 COMMENT '状态：0=合成中，1=已完成 2=失败',
    `terminal`    INT(2)   COMMENT '来源:1-微信小程序,2-微信公众号,3-手机H5,4-电脑PC,5-苹果APP,6-安卓APP',
    `create_time` int(10) UNSIGNED NULL DEFAULT NULL COMMENT '创建时间',
    `update_time` int(11) NULL DEFAULT NULL COMMENT '更新时间',
    `delete_time` int(11) NULL DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '租户数字人素材表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for la_tenant_voice
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_voice`;
CREATE TABLE `la_tenant_voice`
(
    `id`               int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
    `tenant_id`        int(11) NOT NULL COMMENT '租户ID',
    `uid`              int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户ID',
    `task_id`          varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '流水号',
    `name`             varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '克隆声素材名',
    `cover`            varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '封面',
    `record`           varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '备注',
    `file_id`          varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文件id',
    `timbre_name`      varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '音色代号',
    `duration`         varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '时长',
    `status`           varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '状态：0=合成中，1=已完成 2=失败',
    `terminal`         INT(2)   COMMENT '来源:1-微信小程序,2-微信公众号,3-手机H5,4-电脑PC,5-苹果APP,6-安卓APP',
    `expected_content` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '应录制文案',
    `actual_content`   varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '实际录制文案',
    `create_time`      int(10) UNSIGNED NULL DEFAULT NULL COMMENT '创建时间',
    `update_time`      int(11) NULL DEFAULT NULL COMMENT '更新时间',
    `delete_time`      int(11) NULL DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '租户克隆声素材表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for la_tenant_voice_record
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_voice_record`;
CREATE TABLE `la_tenant_voice_record`
(
    `id`                   int(11) NOT NULL AUTO_INCREMENT COMMENT '主键ID',
    `tenant_id`            int(11) NOT NULL COMMENT '租户ID',
    `uid`                  int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户ID',
    `task_id`              varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '流水号',
    `title`                varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '合成声标题',
    `cover`                varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '封面',
    `voice_id`             varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '克隆声素材',
    `content`              varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '文本内容',
    `speed`                float NOT NULL DEFAULT 1.00 COMMENT '语速',
    `once_use_video`       int(10) NULL DEFAULT NULL COMMENT '创建合成声音同时合成对应形象id',
    `once_use_video_model` int(10) NULL DEFAULT NULL COMMENT '创建合成声音同时合成使用模型',
    `upload_flag`          int(1) NOT NULL DEFAULT 0 COMMENT '是否为手动上传文件     0:否,1:是',
    `cost_power`           varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '消耗算力',
    `status`               varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT '0' COMMENT '状态：0=合成中，1=已完成 2=失败',
    `terminal`             INT(2)   COMMENT '来源:1-微信小程序,2-微信公众号,3-手机H5,4-电脑PC,5-苹果APP,6-安卓APP',
    `completion_time`      varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '完成时间',
    `cost_time`            varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '耗时',
    `file_id`              varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '生成文件id',
    `duration`             varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '声音时长',
    `timbre_name`          varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '声音别名',
    `size`                 varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '大小',
    `remark`               varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '备注',
    `create_time`          int(10) UNSIGNED NULL DEFAULT NULL COMMENT '创建时间',
    `update_time`          int(11) NULL DEFAULT NULL COMMENT '更新时间',
    `delete_time`          int(11) NULL DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci COMMENT = '租户声音合成记录表' ROW_FORMAT = DYNAMIC;

-- ----------------------------
-- Table structure for la_tenant_power_package
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_power_package`;
CREATE TABLE `la_tenant_power_package`
(
    `id`            int(10) NOT NULL AUTO_INCREMENT COMMENT '唯一标识',
    `tenant_id`     int(10) NULL DEFAULT NULL COMMENT '租户号',
    `title`         varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '标题',
    `cost`          varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '金额',
    `power`         varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '算力',
    `status`        int(10) NULL DEFAULT 0  COMMENT '状态 1：启用 0 禁用',
    `original_cost` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '原价',
    `recommend`     int(10) NULL DEFAULT NULL COMMENT '是否推荐',
    `gift`          int(10) NULL DEFAULT NULL COMMENT '额外赠送',
    `gift_power`    varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '赠送算力',
    `sort`          int(10) NULL DEFAULT 0 COMMENT '排序',
    `expire_time`   datetime NULL DEFAULT NULL COMMENT '有效期',
    `note`          varchar(500) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '备注',
    `create_time`   int(10) NULL DEFAULT NULL COMMENT '创建时间',
    `update_time`   int(10) NULL DEFAULT NULL COMMENT '更新时间',
    `delete_time`   int(10) NULL DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '租户算力套餐配置' ROW_FORMAT = Dynamic;


-- ----------------------------
-- Table structure for la_tenant_key_pool
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_key_pool`;
CREATE TABLE `la_tenant_key_pool`
(
    `id`          int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `tenant_id`   int(10) NOT NULL COMMENT '租户号',
    `type`        tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '类型: [1=声音克隆, 2=声音合成, 3=数字人合成]',
    `key`         varchar(800) NOT NULL DEFAULT '' COMMENT '密钥',
    `status`      tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '密钥状态',
    `remark`      varchar(255)          DEFAULT NULL COMMENT '备注',
    `create_time` int(10) NULL DEFAULT NULL COMMENT '创建时间',
    `update_time` int(10) NULL DEFAULT NULL COMMENT '更新时间',
    `delete_time` int(10) NULL DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 COMMENT='密钥池表';


-- ----------------------------
-- Table structure for la_tenant_voice_sample
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_voice_sample`;
CREATE TABLE `la_tenant_voice_sample`
(
    `id`          INT(10) NOT NULL AUTO_INCREMENT  COMMENT '主键',
    `tenant_id`   INT(20)   COMMENT '租户标识',
    `title`       VARCHAR(255) COMMENT '标题',
    `content`     VARCHAR(1000) COMMENT '内容',
    `status`      INT COMMENT '状态',
    `sort`        INT DEFAULT 0 COMMENT '排序',
    `create_time` int(10) NULL DEFAULT NULL COMMENT '创建时间',
    `update_time` int(10) NULL DEFAULT NULL COMMENT '更新时间',
    `delete_time` int(10) NULL DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (id) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT = 1  DEFAULT CHARSET=utf8mb4 COMMENT = '声音合成示例表';

-- ----------------------------
-- Table structure for la_tenant_sensitive_words
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_sensitive_words`;
CREATE TABLE `la_tenant_sensitive_words`
(
    `id`          INT(10) NOT NULL AUTO_INCREMENT COMMENT '主键',
    `tenant_id`   INT(20)   COMMENT '租户标识',
    `words`       VARCHAR(1000) COMMENT '敏感词',
    `status`      INT COMMENT '状态',
    `create_time` int(10) NULL DEFAULT NULL COMMENT '创建时间',
    `update_time` int(10) NULL DEFAULT NULL COMMENT '更新时间',
    `delete_time` int(10) NULL DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET=utf8mb4 COMMENT = '敏感词检查表';

-- ----------------------------
-- Table structure for la_tenant_generate
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_generate`;
CREATE TABLE `la_tenant_generate`
(
    `id`              INT AUTO_INCREMENT COMMENT '主键ID',
    `tenant_id`       INT NOT NULL COMMENT '租户ID',
    `uid`             INT UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户ID',
    `task_id`         VARCHAR(100) COMMENT '流水号',
    `theme`           VARCHAR(100) COMMENT '主题',
    `content`         VARCHAR(1000) COMMENT '描述内容',
    `cost_power`      VARCHAR(100) COMMENT '消耗算力',
    `status`          INT(2)  DEFAULT 0 COMMENT '状态：0=合成中，1=已完成 2=失败',
    `terminal`        INT(2)   COMMENT '来源:1-微信小程序,2-微信公众号,3-手机H5,4-电脑PC,5-苹果APP,6-安卓APP',
    `completion_time` VARCHAR(255) COMMENT '完成时间',
    `cost_time`       VARCHAR(255) COMMENT '耗时',
    `size`            VARCHAR(10) COMMENT '字数',
    `result_copy`     BLOB COMMENT '结果文案',
    `create_time`     int(10) NULL DEFAULT NULL COMMENT '创建时间',
    `update_time`     int(10) NULL DEFAULT NULL COMMENT '更新时间',
    `delete_time`     int(10) NULL DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT = '租户文案生成表';

-- ----------------------------
-- Table structure for la_tenant_generate_theme
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_generate_theme`;
CREATE TABLE `la_tenant_generate_theme`
(
    `id`          INT AUTO_INCREMENT COMMENT '主键ID',
    `tenant_id`   INT NOT NULL COMMENT '租户ID',
    `name`        VARCHAR(100) COMMENT '主题名称',
    `status`      INT(2)  DEFAULT 0 COMMENT '状态：0=禁用，1=启用',
    `sort`        INT(10)  DEFAULT 0 COMMENT '排序',
    `create_time` int(10) NULL DEFAULT NULL COMMENT '创建时间',
    `update_time` int(10) NULL DEFAULT NULL COMMENT '更新时间',
    `delete_time` int(10) NULL DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT = '租户文案生成主题示例表';

-- ----------------------------
-- Table structure for la_tenant_complete_flow
-- ----------------------------
DROP TABLE IF EXISTS `la_tenant_complete_flow`;
CREATE TABLE `la_tenant_complete_flow`
(
    `id`               INT AUTO_INCREMENT COMMENT '主键ID',
    `tenant_id`        INT NOT NULL COMMENT '租户ID',
    `uid`              varchar(100) COMMENT '用户id',
    `host`             varchar(100) COMMENT '租户域名',
    `task_id`          varchar(32) COMMENT '任务号',
    `voice_id`         Int(10) COMMENT '音色id',
    `voice_mode`       Int(2) COMMENT '音色模型',
    `content`          varchar(1000)COMMENT '声音合成内容',
    `timbre`           VARCHAR(100) COMMENT '音色名称——V2通道时必须',
    `voice_record_id`  int(2) COMMENT '声音合成记录id',
    `duration`         VARCHAR(100) COMMENT '时长',
    `video_mode`       varchar(100) COMMENT '数字人合成通道',
    `video_id`         varchar(100) COMMENT '视频id',
    `video_file_id`    varchar(100) COMMENT '视频文件id',
    `video_name`       varchar(100) COMMENT '数字人形象名称',
    `avatar_record_id` Int(10)  COMMENT '数字人视频合成结果id',
    `status`           INT(2)  DEFAULT 0 COMMENT '状态：0=待处理，2=声音处理中，3=模型处理中，4=数字人合成中，5=完成，99=失败',
    `terminal`         int(2) COMMENT '来源',
    `err_msg`          varchar(1000)COMMENT '失败原因',
    `create_time`      int(10) NULL DEFAULT NULL COMMENT '创建时间',
    `update_time`      int(10) NULL DEFAULT NULL COMMENT '更新时间',
    `delete_time`      int(10) NULL DEFAULT NULL COMMENT '删除时间',
    PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT = '创建全流程提交';

SET
FOREIGN_KEY_CHECKS = 1;
