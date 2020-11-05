<?php
/**
 * Created by PhpStorm.
 * User: aiChenK
 * Date: 2020-05-28
 * Time: 17:26
 */

use think\facade\Route;

Route::any('/api/:version', 'Api/*')->pattern(['version' => 'v\d+']);

Route::miss('error/*');

Route::get('/', function () {
    return 'hello, tp6-server!';
});

