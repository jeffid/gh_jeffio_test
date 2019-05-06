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

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

Route::get('hello/:name', 'index/hello');

Route::group('/t/', function () {
    
    Route::any('hi', function () {
        return 'hello~';
    });
    
    Route::get('env', function () {
//    \think\Log::DEBUG('haha');
        Log::write('测试日志信息，这是警告级别，并且实时写入', 'notice');
        return env('APP_NAME');
    });
    
})->middleware(['loginput']);


return [];
