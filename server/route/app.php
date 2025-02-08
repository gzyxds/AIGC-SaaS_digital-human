<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Console;
use think\facade\Route;

// 平台管理后台
Route::rule('platform/:any', function () {
    return view(app()->getRootPath() . 'public/platform/index.html');
})->pattern(['any' => '\w+']);

// 租户管理后台
Route::rule('admin/:any', function () {
    return view(app()->getRootPath() . 'public/admin/index.html');
})->pattern(['any' => '\w+']);

// 移动端
Route::rule('mobile/:any', function () {
    return view(app()->getRootPath() . 'public/mobile/index.html');
})->pattern(['any' => '\w+']);

// // PC端
// Route::rule('pc/:any', function () {
//     return view(app()->getRootPath() . 'public/pc/index.html');
// })->pattern(['any' => '\w+']);

//定时任务
Route::rule('crontab', function () {
    Console::call('crontab');
});
