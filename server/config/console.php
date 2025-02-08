<?php
// +----------------------------------------------------------------------
// | 控制台配置
// +----------------------------------------------------------------------
return [
    // 指令定义
    'commands' => [
        // 定时任务
        'crontab'       => 'app\common\command\Crontab',
        // 退款查询
        'query_refund'  => 'app\common\command\QueryRefund',
        // 合成任务自动失败
        'task_success'  => 'app\common\command\TaskSuccess',
        // 修改超级管理员密码
        'password'      => 'app\common\command\Password',
        // 全流程任务
        'complete_flow' => 'app\common\command\CompleteFlow',

    ],

    // 定时任务调度配置
    'schedule' => [
        'task_success' => [
            'command' => 'task_success',
            'cron'    => '* * * * *',     //每分钟执行一次
        ],
    ],
];
