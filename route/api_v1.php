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

Route::get('think', function () {
    return 'hello,ThinkPHP6!';
});

Route::group('/api/v1', function(){
    Route::get('/', function () {
        return 'hello,api!';
    });

    Route::group('user', function () {
        Route::any('index', 'api.v1.user/index');
        Route::any('add', 'api.v1.user/add');
        Route::any('edit', 'api.v1.user/edit');
        Route::any('delete', 'api.v1.user/delete');
    });


    // 需要 token 身份验证验证
    Route::group('', function () {
        // 用户
        Route::group('user', function () {
            Route::get('detail', 'miniprogram.user/detail'); // 获取用户信息
        });


    })->middleware([app\middleware\MiniProgramAuthCheck::class]);

    Route::any('login/fillWeChatInfo', 'miniprogram.login/fillWeChatInfo');


    Route::group('goods', function () {
        Route::get('lst', 'miniprogram.Goods/lst');
    });


    Route::group('login', function () {
        Route::get('login', 'miniprogram.login/login');
        Route::get('test', function () {

            return config('miniprogram.app_id');
            return cache('obQ1t5RZjW2z-o71pVYcM9Fruf4Y');
//            'LM/517MWSs4mTftudHszyg=='

            return 'hello,admin!';
        });
        Route::any('getOpenidByCode', 'miniprogram.login/getOpenidByCode');

    });

    Route::any('home/topCarousel', 'miniprogram.home/topCarousel');
    Route::any('home/promoteGoods', 'miniprogram.home/promoteGoods');
    Route::any('home/recommendGoods', 'miniprogram.home/recommendGoods');

    Route::any('goods/detail', 'miniprogram.goods/detail');




});

