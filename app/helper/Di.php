<?php
/**
 * Created by PhpStorm.
 * User: aiChenK
 * Date: 2020-05-26
 * Time: 17:24
 */

namespace app\helper;

use think\App;

/**
 * 依赖注入获取（增加ide提示）
 *
 * @property App $app
 *
 * @author aiChenK
 */
class Di
{
    
    private static $instance;
    private $app;

    /**
     * 获取app对象保存到自身，可快捷获取对象
     * - 建议使用__get()魔术方法配合`property`增加ide提示
     *
     * @param string $name
     * @return Di|App
     *
     * @author aiChenK
     * @since 1.0
     * @version 1.0
     */
    public static function get($name = '')
    {
        if (!self::$instance) {
            self::$instance = new self();
            self::$instance->app = app();
        }
        return $name
            ?  self::$instance->$name
            :  self::$instance;
    }

    /**
     * 获取对象
     *
     * @param $name
     * @return mixed
     *
     * @author aiChenK
     * @since 1.0
     * @version 1.0
     */
    public function __get($name)
    {
        return $this->app->$name;
    }
}