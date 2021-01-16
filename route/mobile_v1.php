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
use think\facade\Route;


Route::group('/mobile/v1', function(){
    Route::get('/', function () {
        return 'hello,mobile!';
    });

    Route::group('login', function () {
        Route::any('login', 'mobile.v1.Login/login');

    });

    Route::group('monitor', function () {
        Route::any('navigateBarInfo', 'mobile.v1.Monitor/navigateBarInfo');

    });

    Route::group('plan', function () {
        Route::any('curDay', 'Plan.v1.Plan/curDay');
        Route::any('mockDay', 'Plan.v1.Plan/mockDay');

    });

    Route::group('user', function () {
        Route::any('detail', 'mobile.v1.User/detail');

    });



})->middleware([app\middleware\ApiCorsMiddleware::class]);

