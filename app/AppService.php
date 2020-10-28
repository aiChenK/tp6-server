<?php
declare (strict_types = 1);

namespace app;

use KaySess\Session;
use think\Service;

/**
 * 应用服务类
 */
class AppService extends Service
{
    public function register()
    {
        // 服务注册
        $this->app->bind('session', function() {
            return invoke(Session::class);
        });
    }

    public function boot()
    {
        // 服务启动
    }
}
