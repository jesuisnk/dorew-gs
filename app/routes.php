<?php

/**
 * Project name: Dorew MVC
 * Copyright (c) 2018 - 2024, Delopver: valedrat, agwinao
 */

use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Controllers\ShoutboxController;
use App\Controllers\ForumController;

/** @var \System\Classes\Router $router */

// trang chủ
$router->add('/', 'HomeController@index', 'GET');
$router->add('/home', 'HomeController@index', 'GET');
$router->add('/index.html', 'HomeController@index', 'GET');
$router->add('/forum', 'HomeController@index', 'GET');
$router->add('/manager', 'HomeController@manager', 'GET');

// plugin
$router->add('/plugin/{plugin:.*}', function ($plugin) {
    if (file_exists(TEMPLATES . 'plugin/' . $plugin . '.php')) {
        $template = 'plugin/' . $plugin;
    } else {
        $template = '404';
    }
    return view($template);
}, 'GET|POST');
# seo
$router->add('/robots.txt', function () {
    return view('forum/_seo.robots');
});
$router->add('/sitemap.xml', 'ForumController@Sitemap', 'GET');


// hồ sơ người dùng
# đăng ký thành viên
$router->add('/register', 'UserController@register', 'GET|POST');
$router->add('/login', 'UserController@login', 'GET|POST');
$router->add('/logout', 'UserController@logout', 'GET|POST');
# danh sách hồ sơ
$router->add('/users', 'UserController@UserList', 'GET|POST');
# thông tin hồ sơ
$router->add('/user{UriAccount:[a-zA-Z0-9\-_\/]*}', 'UserController@UserDetail', 'GET|POST');

// shoutbox
$router->add('/shoutbox', 'ShoutboxController@ChatHome', 'GET');
$router->add('/shoutbox/count', 'ShoutboxController@ChatCount', 'GET');
$router->add('/shoutbox/ele', 'ShoutboxController@ChatEle', 'GET');
$router->add('/shoutbox/list', 'ShoutboxController@ChatList', 'GET');
$router->add('/shoutbox/send', 'ShoutboxController@ChatSend', 'GET|POST');

// tin nhắn
$router->add('/mail', 'MailController@MailList', 'GET');
$router->add('/mail/send/{uri_receiver:[a-zA-Z0-9\-_]+}', 'MailController@MailSend', 'GET|POST');
# tin nhắn hệ thống
$router->add('/mail/system', 'MailController@MailSystem', 'GET|POST');

// diễn đàn
# tìm kiếm
$router->add('/search', 'ForumController@Search', 'GET');
# quản lý chuyên mục
$router->add('/forum/category', 'ForumController@CategoryPublish', 'GET|POST');
# viết bài nhanh
$router->add('/forum/post', 'ForumController@PostPublish', 'GET|POST');
# thao tác quản lý dữ liệu đối với bài viết
$router->add('/forum/{CategorySlug:[a-zA-Z0-9\-_]+}/post', 'ForumController@PostPublishOne', 'GET|POST'); # viết
$router->add('/forum/post-{:id}/add-chap', 'ForumController@ChapterPublish', 'GET|POST'); # thêm chương
$router->add('/forum/{action:(post|chapter)}-{:id}/edit', 'ForumController@ForumEdit', 'GET|POST'); # sửa bài, chương
$router->add('/forum/{action:(post|chapter)}-{:id}/delete', 'ForumController@ForumDelete', 'GET|POST'); # xóa bài, chương
# thông tin bài viết
$router->add('/forum/{PostSlug:[a-zA-Z0-9\-_]+}', 'ForumController@PostDetail', 'GET|POST');
$router->add('/forum/{PostSlug:[a-zA-Z0-9\-_]+}.html', 'ForumController@PostDetail', 'GET|POST');
# thông tin chương
$router->add('/view-chap/{ChapterSlug:[a-zA-Z0-9\-_]+}', 'ForumController@ChapterDetail', 'GET');
$router->add('/view-chap/{ChapterSlug:[a-zA-Z0-9\-_]+}.html', 'ForumController@ChapterDetail', 'GET');