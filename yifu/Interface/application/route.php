<?php

use think\Route;

Route::rule('upload', 'Uploads/index', 'GET|POST');//上传图片 OK
Route::rule('auto', 'Auto/auto', 'GET|POST');//上传图片 OK
Route::rule('pay_callback', 'PayCallback/index', 'GET|POST');//上传图片 OK