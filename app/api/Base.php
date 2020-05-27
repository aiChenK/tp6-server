<?php
/**
 * Created by PhpStorm.
 * User: aiChenK
 * Date: 2020-05-22
 * Time: 14:09
 */

namespace app\api;

use app\BaseController;
use think\Response;

/**
 * api基础类
 *
 * 规定返回结构体
 *
 * @author aiChenK
 */
abstract class Base extends BaseController
{

    /**
     * 返回json结构体
     *
     * @param array $data
     * @param int $httpCode
     * @return \think\response\Json
     *
     * @author aiChenK
     * @since 1.0
     * @version 1.0
     */
    public function json(array $data = [], int $httpCode = 200)
    {
        return json($data, $httpCode);
    }

    /**
     * 返回成功消息
     *
     * @param string $msg
     * @param int $httpCode
     * @return \think\response\Json
     *
     * @author aiChenK
     * @since 1.0
     * @version 1.0
     */
    public function success(string $msg = 'success', int $httpCode = 200)
    {
        $data = ['msg' => $msg];
        return json($data, $httpCode);
    }

    /**
     * 返回错误消息
     *
     * @param int $httpCode
     * @param string $msg
     * @param string $description
     * @param int $code
     * @return \think\response\Json
     *
     * @author aiChenK
     * @since 1.0
     * @version 1.0
     */
    public function error(int $httpCode = 200, string $msg = '未知错误', string $description = '', int $code = 0)
    {
        $data = ['code' => $code ?: $httpCode, 'msg' => $msg, 'description' => $description];
        return json($data, $httpCode);
    }

    /**
     * 返回文本消息
     *
     * @param string $content
     * @param int $httpCode
     * @return Response
     *
     * @author aiChenK
     * @since 1.0
     * @version 1.0
     */
    public function text(string $content, int $httpCode = 200)
    {
        return Response::create($httpCode == 204 ? '' : $content, 'html', $httpCode);
    }

    /**
     * 获取json请求体
     *
     * @return array|mixed|null
     *
     * @author aiChenK
     * @since 1.0
     * @version 1.0
     */
    public function getJson()
    {
        return $this->request->post();
    }
}