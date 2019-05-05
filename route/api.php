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

//Route::get('think', function () {
//    return 'hello,ThinkPHP5!';
//});


Route::group('', function () {
    
    Route::get('/', 'WxGh/index');
    
})->prefix('api/')->middleware(['loginput']);

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

