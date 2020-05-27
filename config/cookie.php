<?php
// +----------------------------------------------------------------------
// | Cookie设置
// +----------------------------------------------------------------------
return [
    // cookie 保存时间
    'expire'    => env('cookie.expire', 0),
    // cookie 保存路径
    'path'      => env('cookie.path', '/'),
    // cookie 有效域名
    'domain'    => env('cookie.domain', ''),
    //  cookie 启用安全传输
    'secure'    => env('cookie.secure', false),
    // httponly设置
    'httponly'  => env('cookie.httponly', false),
    // 是否使用 setcookie
    'setcookie' => true,
];
