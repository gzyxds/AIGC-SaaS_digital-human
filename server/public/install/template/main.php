<?php !defined('install') && exit(); ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>AI数字人SaaS系统安装</title>
        <link rel="stylesheet" type="text/css" href="./css/layui.css"/>
        <link rel="stylesheet" type="text/css" href="./css/mounted.css"/>
        <link rel="shortcut icon" href="./favicon.ico"/>
    </head>

    <body>
    <div class="header">
        <div class="logo" style="width: 220px;">
            <img src="./images/slogn.png?v=1" alt="logo"/>
        </div>
    </div>
    <div class="mounted" id="mounted">
        <div class="mounted-box">
            <form method="post" action="#" name="main_form">
                <div class="mounted-container" id="tab">
                    <div class="mounted-nav-container">
                        <ul class="mounted-nav" id="nav">
                            <li <?php if ($step == "1") { ?>class="active" <?php } ?>>许可协议</li>
                            <li <?php if ($step == "2") { ?>class="active" <?php } ?>>环境监测</li>
                            <li <?php if ($step == "3") { ?>class="active" <?php } ?>>参数配置</li>
                            <li <?php if ($step == "4" or $step == '5') { ?>class="active" <?php } ?>>安装</li>
                        </ul>
                    </div>

                    <!-- 阅读许可 -->
                    <?php if ($step == '1') { ?>
                        <div class="mounted-content-item show" style="line-height: 1.8">
                            <div class="content-header">
                                阅读许可协议
                            </div>
                            <div class="content">
                                <h2 style="text-align: center">AI数字人SaaS系统（源代码）安装使用授权协议</h2>

                                <p style="margin-bottom: 1em;margin-top: 1rem; font-weight: bold;">
                                    一、本授权协议适用于本AI数字人SaaS系统（源代码）（以下简称本系统）的任何版本，本系统开发团队（以下简称我们）拥有本授权协议的最终解释权。</p>

                                <p style="margin-bottom: 1em;font-weight: bold">二、协议许可的权利</p>
                                <ol style="margin-left: 2em; margin-bottom: 1em;">
                                    <li>1. 本系统需要取得商业授权方可使用，否则会被视为盗版使用并承担相应的法律责任。</li>
                                    <li>2. 请尊重我们的劳动成果，严禁公开传播、转手、转卖、倒卖等盗版行为。</li>
                                    <li>3. 您可以修改本系统产品功能或界面风格以适应您的使用要求。</li>
                                    <li>
                                        4.
                                        您拥有使用本系统产生的内容所有权，并独立承担与这些内容的相关法律义务。我们不会且没有能力获得本系统产生的内容，不会参与本系统的运营使用，不承担您使用本系统的相关法律义务。
                                    </li>
                                    <li>
                                        5.
                                        获得商业授权之后，您可以将本系统应用于商业用途，同时依据所购买的授权类型中确定的技术支持内容，自购买时刻起，在技术支持期限内拥有通过指定的方式获得指定范围内的技术支持服务。商业授权用户享有反映和提出意见的权力，相关意见将被作为建议考虑，但没有一定被采纳的承诺或保证。
                                    </li>
                                </ol>

                                <p style="margin-bottom: 1em;font-weight: bold">三、协议规定的约束和限制</p>
                                <ol style="margin-left: 2em; margin-bottom: 1em;">
                                    <li>1. 未经我们允许，不得对本系统或与之关联的商业授权进行出租、出售、抵押或发放子许可证。
                                    </li>
                                    <li>
                                        2. 未经我们允许，禁止在本系统的整体或任何部分基础上发展任何派生版本、修改版本或第三方版本用于重新分发。
                                    </li>
                                    <li>
                                        3. 如果您未能遵守本协议的条款，您的授权将被终止，所被许可的权利将被收回，并承担相应法律责任。
                                    </li>
                                </ol>

                                <p style=" margin-bottom: 1em;font-weight: bold">四、协议约定的使用条款</p>
                                <ol style="margin-left: 2em; margin-bottom: 1em;">
                                    <li>
                                        1. 您承诺，严格依照本协议使用本系统，并且不会利用本系统进行任何违法或不当的活动，发布、传送或分享含有下列内容之一的信息：
                                    </li>
                                    <ol>
                                        <li>（1）反对宪法所确定的基本原则的；</li>
                                        <li>（2）危害国家安全，泄露国家秘密，颠覆国家政权，破坏国家统一的；</li>
                                        <li>（3）损害国家荣誉和利益的；</li>
                                        <li>（4）煽动民族仇恨、民族歧视、破坏民族团结的；</li>
                                        <li>（5）破坏国家宗教政策，宣扬邪教和封建迷信的；</li>
                                        <li>（6）散布谣言，扰乱社会秩序，破坏社会稳定的；</li>
                                        <li>（7）散布淫秽、色情、赌博、暴力、凶杀、恐怖或者教唆犯罪的；</li>
                                        <li>（8）侮辱或者诽谤他人，侵害他人合法权利的；</li>
                                        <li>
                                            （9）含有虚假、诈骗、有害、胁迫、侵害他人隐私、骚扰、侵害、中伤、粗俗、猥亵、或其它道德上令人反感的内容；
                                        </li>
                                        <li>
                                            （10）含有中国或其您所在国国家管辖法所适用的法律、法规、规章、条例以及任何具有法律效力之规范所限制或禁止的其它内容的。
                                        </li>
                                    </ol>
                                    <li>
                                        2.
                                        您使用本系统不得侵害他人权益（包括但不限于著作权、专利权、商标权等知识产权与其他权益）。同时，您同意并承诺，您使用本系统所提供、使用的文本内容已获得了充分、必要且有效的合法许可及授权。
                                    </li>
                                    <li>3. 您同意并承诺，在使用本系统时，不会披露任何保密、敏感或个人信息。</li>
                                    <li>
                                        4.
                                        您同意并承诺，本系统以及因使用本系统所取得的任何产出或成果，仅限您本人用于学术测试等合理非商业目的，若您将本系统以及前述产出或成果用于任何商业或其他目的与用途，或自行或透过他人以任何方式或载体向第三方披露、提供、转发、传播或公开所造成的任何纠纷或案件，由您本人承担和解决，我方将不承担任何责任。
                                    </li>
                                    <li>
                                        5.
                                        您应理解并同意，本系统尚存在不完备性，我方不对任何系统可用性、可靠性做出任何承诺。我方不对您使用本系统或本系统结果承担任何责任，且本系统结果不代表我方立场。
                                    </li>
                                    <li>
                                        6.
                                        我方有权因为业务发展或法律法规的变动而随时对本系统的内容和/或提供方式进行变动，或者暂停或终止本系统发行。您同意我方将不对因上述情况导致的任何后果，向您或第三方承担任何责任。
                                    </li>
                                    <li>7. 在任何情况下，我方均不就因本系统所发生的任何直接性、间接性、后果性、惩戒性、偶然性、特殊性的损害
                                        (包括但不限于：您使用本系统而遭受的利润损失)，承担任何责任(即使您已事先被告知该等损害发生的可能性)。
                                    </li>
                                </ol>

                                <p style="margin-bottom: 1em;font-weight: bold">五、有限担保和免责声明</p>
                                <ol style="margin-left: 2em; margin-bottom: 1em;">
                                    <li>1. 本系统及所附带的文件是作为不提供任何明确的或隐含的赔偿或担保的形式提供的。
                                    </li>
                                    <li>
                                        2 您出于自愿而使用本系统，您必须了解使用本系统的相关法律和技术风险，我们不承诺对用户提供任何形式的使用担保，也不承担任何因使用本软件而产生问题的相关责任。
                                    </li>
                                    <li>
                                        3. 任何由于黑客攻击、计算机病毒侵入或发作、因政府管制而造成的暂时性关闭等影响网络正常经营的不可抗力而造成的损失，我们不承担任何直接、简介或者连带的责任。
                                    </li>
                                    <li>
                                        4.
                                        电子文本形式的授权协议如同双方书面签署的协议一样，具有完全的和等同的法律效力。您一旦开始确认本协议并安装本系统，即被视为完全理解并接受本协议的各项条款，在享有上述条款授予的权力的同时，受到相关的约束和限制。协议许可范围以外的行为，将直接违反本授权协议并构成侵权，我们有权随时终止授权，责令停止损害，并保留追究相关责任的权力。
                                    </li>
                                </ol>

                            </div>
                        </div>
                    <?php } ?>

                    <!-- 检查信息 -->
                    <?php if ($step == '2') { ?>
                        <div class="mounted-content-item show">
                            <div class="mounted-env-container">
                                <div class="mounted-item">
                                    <div class="content-header">
                                        服务器信息
                                    </div>
                                    <div class="content-table">
                                        <table class="layui-table" lay-skin="line">
                                            <colgroup>
                                                <col width="210">
                                                <col width="730">
                                            </colgroup>
                                            <thead>
                                            <tr>
                                                <th>参数</th>
                                                <th>值</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>服务器操作系统</td>
                                                <td><?php echo PHP_OS ?></td>
                                            </tr>
                                            <tr>
                                                <td>web服务器环境</td>
                                                <td><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td>
                                            </tr>
                                            <tr>
                                                <td>PHP版本</td>
                                                <td><?php echo @phpversion(); ?></td>
                                            </tr>
                                            <tr>
                                                <td>程序安装目录</td>
                                                <td><?php echo realpath(__DIR__ . '../../../'); ?></td>
                                            </tr>
                                            <tr>
                                                <td>磁盘空间</td>
                                                <td><?php echo $modelInstall->freeDiskSpace(realpath(__DIR__ . '../../../')) ?></td>
                                            </tr>
                                            <tr>
                                                <td>上传限制</td>
                                                <?php if (ini_get('file_uploads')): ?>
                                                    <td><?php echo ini_get('upload_max_filesize'); ?></td>
                                                <?php else: ?>
                                                    <td>禁止上传</td>
                                                <?php endif; ?>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="mounted-tips mt16">
                                    PHP环境要求必须满足下列所有条件，否则系统或系统部分功能将无法使用。
                                </div>
                                <div class="mounted-item mt16">
                                    <div class="content-header">
                                        PHP环境要求
                                    </div>
                                    <div class="content-table">
                                        <table class="layui-table" lay-skin="line">
                                            <colgroup>
                                                <col width="210">
                                                <col width="210">
                                                <col width="120">
                                                <col width="400">
                                            </colgroup>
                                            <thead>
                                            <tr>
                                                <th>选项</th>
                                                <th>要求</th>
                                                <th>状态</th>
                                                <th>说明及帮助</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>PHP版本</td>
                                                <td>大于8.0</td>
                                                <?php echo $modelInstall->correctOrFail($modelInstall->checkPHP()) ?>
                                                <td>建议使用PHP8.0.8版本</td>
                                            </tr>
                                            <tr>
                                                <td>PDO_MYSQL</td>
                                                <td>支持 (强烈建议支持)</td>
                                                <?php echo $modelInstall->correctOrFail($modelInstall->checkPDOMySQL()) ?>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>allow_url_fopen</td>
                                                <td>支持 (建议支持cURL)</td>
                                                <?php echo $modelInstall->correctOrFail($modelInstall->checkCurl()) ?>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>GD2</td>
                                                <td>支持</td>
                                                <?php echo $modelInstall->correctOrFail($modelInstall->checkGd2()) ?>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>DOM</td>
                                                <td>支持</td>
                                                <?php echo $modelInstall->correctOrFail($modelInstall->checkDom()) ?>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>fileinfo</td>
                                                <td>支持</td>
                                                <?php echo $modelInstall->correctOrFail($modelInstall->checkFileInfo()) ?>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>swoole_loader扩展</td>
                                                <td>支持</td>
                                                <?php echo $modelInstall->correctOrFail($modelInstall->checkSwooleLoader()) ?>
                                                <td>
                                                    <?php echo $modelInstall->checkSwooleLoaderRemark() ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>session.auto_start</td>
                                                <td>关闭</td>
                                                <?php echo $modelInstall->correctOrFail($modelInstall->checkSessionAutoStart()) ?>
                                                <td></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="mounted-tips mt16">
                                    系统要求系统安装目录下的runtime和upload必须可写，才能使用所有功能。
                                </div>
                                <div class="mounted-item mt16">
                                    <div class="content-header">
                                        目录权限监测
                                    </div>
                                    <div class="content-table">
                                        <table class="layui-table" lay-skin="line">
                                            <colgroup>
                                                <col width="210">
                                                <col width="210">
                                                <col width="120">
                                                <col width="400">
                                            </colgroup>
                                            <thead>
                                            <tr>
                                                <th>目录</th>
                                                <th>要求</th>
                                                <th>状态</th>
                                                <th>说明及帮助</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>/runtime</td>
                                                <td>runtime目录可写</td>
                                                <?php echo $modelInstall->correctOrFail($modelInstall->checkDirWrite('runtime')) ?>
                                                <td><?php if ($modelInstall->checkDirWrite('runtime') == 'fail') echo '请给runtime目录权限，若目录不存在先新建'; ?></td>
                                            </tr>
                                            <tr>
                                                <td>/public/uploads</td>
                                                <td>uploads目录可写</td>
                                                <?php echo $modelInstall->correctOrFail($modelInstall->checkDirWrite('public/uploads')) ?>
                                                <td><?php if ($modelInstall->checkDirWrite('public/uploads') == 'fail') echo '请给public/uploads目录权限，若目录不存在先新建'; ?></td>
                                            </tr>
                                            <tr>
                                                <td>/public/platform</td>
                                                <td>platform目录可写</td>
                                                <?php echo $modelInstall->correctOrFail($modelInstall->checkDirWrite('public/platform')) ?>
                                                <td><?php if ($modelInstall->checkDirWrite('public/platform') == 'fail') echo '请给public/platform，若目录不存在先新建'; ?></td>
                                            </tr>
                                            <tr>
                                                <td>/public/admin</td>
                                                <td>admin目录可写</td>
                                                <?php echo $modelInstall->correctOrFail($modelInstall->checkDirWrite('public/admin')) ?>
                                                <td><?php if ($modelInstall->checkDirWrite('public/admin') == 'fail') echo '请给public/admin，若目录不存在先新建'; ?></td>
                                            </tr>
                                            <tr>
                                                <td>../config</td>
                                                <td>config目录可写</td>
                                                <?php echo $modelInstall->correctOrFail($modelInstall->checkDirWrite('config')) ?>
                                                <td><?php if ($modelInstall->checkDirWrite('config') == 'fail') echo '请给config目录权限，若目录不存在先新建'; ?></td>
                                            </tr>
                                            <tr>
                                                <td>../.env</td>
                                                <td>.env文件可写</td>
                                                <?php echo $modelInstall->correctOrFail($modelInstall->checkDirWrite('.env')) ?>
                                                <td><?php if ($modelInstall->checkDirWrite('.env') == 'fail') echo '请给.env文件权限，若文件不存在，注意文件名第1字符是" . "'; ?></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- 数据库设置 -->
                    <?php if ($step == '3') { ?>
                        <div class="mounted-content-item show">
                            <div class="mounted-item">
                                <div class="content-header">
                                    数据库选项
                                </div>
                                <div class="content-form">

                                    <div class="form-box-item">
                                        <div class="form-desc">
                                            数据库主机
                                        </div>
                                        <div>
                                            <input type="text" name="host" value="<?= $post['host'] ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-box-item">
                                        <div class="form-desc">
                                            端口号
                                        </div>
                                        <div>
                                            <input type="text" name="port" value="<?= $post['port'] ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-box-item">
                                        <div class="form-desc">
                                            数据库用户
                                        </div>
                                        <div>
                                            <input type="text" name="user" value="<?= $post['user'] ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-box-item">
                                        <div class="form-desc">
                                            数据库密码
                                        </div>
                                        <div>
                                            <input type="text" name="password" value="<?= $post['password'] ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-box-item">
                                        <div class="form-desc">
                                            数据库名称
                                        </div>
                                        <div>
                                            <input type="text" name="name" value="<?= $post['name'] ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-box-item">
                                        <div class="form-desc">
                                            数据表前缀
                                        </div>
                                        <div>
                                            <input type="text" name="prefix" value="<?= $post['prefix'] ?>"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mounted-item">
                                <div class="content-header mt16">
                                    管理选项
                                </div>
                                <div class="content-form">

                                    <div class="form-box-item">
                                        <div class="form-desc">
                                            管理员账号
                                        </div>
                                        <div>
                                            <input type="text" name="admin_user" value="<?= $post['admin_user'] ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-box-item">
                                        <div class="form-desc">
                                            管理员密码
                                        </div>
                                        <div>
                                            <input type="password" name="admin_password"
                                                   value="<?= $post['admin_password'] ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-box-item">
                                        <div class="form-desc">
                                            确认密码
                                        </div>
                                        <div>
                                            <input type="password" name="admin_confirm_password"
                                                   value="<?= $post['admin_confirm_password'] ?>"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- 安装中 -->
                    <?php if ($step == '4' or $step == '5') { ?>
                        <div class="mounted-content-item show">
                            <?php if ($step == '4') { ?>
                                <div id="mounting">
                                    <div class="content-header">
                                        正在安装中
                                    </div>
                                    <div class="mounting-container " id="install_message">
                                        <?php if (count($successTables) > 0): ?>
                                            <p style="margin-bottom: 4px;">成功创建数据库：<?= $post['name'] ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php } ?>

                            <?php if ($step == '5') { ?>
                                <div class="show" id="mounting-success">
                                    <div class="content-header">
                                        安装成功
                                    </div>
                                    <div class="success-content">
                                        <div style="width: 48px;height: 48px;">
                                            <img src="./images/icon_mountSuccess.png"/>
                                        </div>
                                        <div class="mt16 result">安装完成，进入管理后台</div>
                                        <div style="margin-top: 10px;font-size:14px;color: #444444">版本号：1.1.1</div>
                                        <div class="tips">
                                            为了您站点的安全，安装完成后即可将网站根目录下的“install”文件夹删除，或者config/目录下创建install.lock文件防止重复安装。
                                        </div>
                                        <div class="btn-group">
                                            <a class="btn" href="/platform/" style="margin-left: 20px;">进入平台管理端后台</a>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </form>
            <?php if ($step == '1') { ?>
                <div class="item-btn-group show">
                    <button class="accept-btn" onclick="goStep(<?php echo $nextStep ?>)">我已阅读并同意</button>
                </div>
            <?php } elseif (in_array($step, ['2', "3"])) { ?>
                <div class="item-btn-group show">
                    <button class="cancel-btn" onclick="cancel()" style="padding: 7px 63px;margin-right: 16px">返回
                    </button>
                    <?php if ($modelInstall->getAllowNext()): ?>
                        <button class="accept-btn" onclick="goStep(<?php echo $nextStep ?>)" style="padding: 7px 63px;">
                            继续
                        </button>
                    <?php else: ?>
                        <button class="accept-btn" onclick="goStep(<?php echo $step ?>)" style="padding: 7px 63px;">重新检查
                        </button>
                    <?php endif; ?>
                </div>
            <?php } elseif ($step == "4") { ?>
                <div class="item-btn-group show">
                    <button class="disabled-btn" disabled="disabled">
                        <div class="layui-icon layui-icon-loading layui-anim layui-anim-rotate layui-anim-loop"></div>
                        <div style="font-size: 14px;margin-left: 7px;">正在安装中...</div>
                    </button>
                </div>
            <?php } ?>
        </div>
    </div>
    <script src="./js/layui.js"></script>
    <?php if (count($successTables) > 0): ?>
        <script>
            var successTables = eval(<?= json_encode($successTables) ?>);
        </script>
    <?php endif; ?>
    <script src="./js/mounted.js"></script>
    </body>

    </html>
<?php if ($message != ''): ?>
    <script>
        alert('<?= $message; ?>');
    </script>
<?php endif; ?>