<?php

Route::group('', function () {
    
    Route::post('/', 'WxGh/index');
    
    Route::group('/gh/', function () {
        
        Route::get('menu', 'WxGh/getMenu');
        Route::post('menu', 'WxGh/setMenu');
        
    });
    
    
})->prefix('api/')->middleware(['loginput']);

return [];


