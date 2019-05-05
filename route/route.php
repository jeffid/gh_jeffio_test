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


/*Route::group(function () {
报错
    Route::get('hi', function () {
        return env('HI');
    });

})->prefix('/t/');*/

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

/*

广州天气
http://wx.weather.com.cn/mweather/101280101.shtml?from=singlemessage&isappinstalled=0#1
坐车网
http://zuoche.com/touch/
广铁状态
http://ydyc.gzmtr.cn:13050/indexNotBrowser.html#/mobile?s=wechat&from=singlemessage&isappinstalled=0
 * */

//Route::group('blog', function () {
//    Route::get(':id', 'read');
//    Route::post(':id', 'update');
//    Route::delete(':id', 'delete');
//
//})->prefix('blog/');

