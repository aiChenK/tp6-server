<?php
namespace app\controller;

use app\BaseController;
use think\exception\HttpException;

class ErrorController extends BaseController
{

    public function __call($method, $args)
    {
        throw new HttpException(404, 'method not exists:' . $this->request->controller() . '->' . $method . '()');
    }
}