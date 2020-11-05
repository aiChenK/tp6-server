<?php
namespace app\controller;

use app\BaseController;
use think\exception\HttpException;

class ApiController extends BaseController
{

    /**
     * api路由处理
     *
     * @param $method
     * @param $args
     * @return mixed
     * @throws \ReflectionException
     *
     * @author aiChenK
     * @since 1.0
     * @version 1.0
     */
    public function __call($method, $args)
    {
        $params = array_filter(explode('/', $this->request->pathinfo()));

        //模块预处理
        $version    = $params[1];
        $controller = $params[2] ?? 'index';
        $action     = $params[3] ?? 'index';
        unset($params[0], $params[1], $params[2], $params[3]);

        //控制器层级处理
        $realController = explode('.', $controller);
        $className      = ucfirst(array_pop($realController));
        array_push($realController, $className);
        $realController = implode('\\', $realController);

        //初始化控制器类
        $classPath = "\\app\\api\\{$version}\\{$realController}";
        if (!class_exists($classPath)) {
            throw new HttpException(404, 'method not exists:' . $realController . '->' . $action . '()');
        }
        $class = new $classPath($this->app);

        //处理方法
        $this->request->setController($controller);
        $this->request->setAction($action);
        $restAction = $action . ucfirst(strtolower($this->request->method()));

        //注册执行中间件
        $this->registerApiMiddleware($class);
        return $this->execApi($class,
            is_callable([$class, $restAction]) ? $restAction : $action,
            $params
        );
    }

    /**
     * 注册api中间件
     *
     * @param $controller
     * @throws \ReflectionException
     *
     * @author aiChenK
     * @since 1.0
     * @version 1.0
     */
    private function registerApiMiddleware($controller): void
    {
        $class = new \ReflectionClass($controller);

        if ($class->hasProperty('middleware')) {
            $reflectionProperty = $class->getProperty('middleware');
            $reflectionProperty->setAccessible(true);

            $middlewares = $reflectionProperty->getValue($controller);

            foreach ($middlewares as $key => $val) {
                if (!is_int($key)) {
                    if (isset($val['only']) && !in_array($this->request->action(true), array_map(function ($item) {
                            return strtolower($item);
                        }, is_string($val['only']) ? explode(",", $val['only']) : $val['only']))) {
                        continue;
                    } elseif (isset($val['except']) && in_array($this->request->action(true), array_map(function ($item) {
                            return strtolower($item);
                        }, is_string($val['except']) ? explode(',', $val['except']) : $val['except']))) {
                        continue;
                    } else {
                        $val = $key;
                    }
                }

                if (is_string($val) && strpos($val, ':')) {
                    $val = explode(':', $val, 2);
                }

                $this->app->middleware->add($val, 'api');
            }
        }
    }

    /**
     * 执行api方法
     *
     * @param $class
     * @param $action
     * @param $args
     * @return mixed
     *
     * @author aiChenK
     * @since 1.0
     * @version 1.0
     */
    private function execApi($class, $action, $args)
    {
        return $this->app->middleware->pipeline('api')
            ->send($this->request)
            ->then(function () use ($class, $action, $args) {
                try {
                    if (is_callable([$class, $action])) {
                        try {
                            $reflect = new \ReflectionMethod($class, $action);
                        } catch (\ReflectionException $e) {
                            $reflect = new \ReflectionMethod($class, '__call');
                        }
                    } else {
                        // 操作不存在
                        throw new HttpException(404, 'method not exists:' . get_class($class) . '->' . $action . '()');
                    }
                    return $reflect->invokeArgs($class, $args);
                } catch (\Throwable $e) {
                    return $this->apiExceptionHandle($e);
                }
            });
    }

    /**
     * api异常处理，无定义则使用默认
     *
     * @param \Throwable $e
     * @return mixed
     * @throws \Throwable
     *
     * @author aiChenK
     * @version 1.0
     */
    private function apiExceptionHandle(\Throwable $e)
    {
        $handler = $this->app->config->get('app.api_exception_handle');
        if (!class_exists($handler)) {
            throw $e;
        }
        $handler = $this->app->make($handler);
        $handler->report($e);
        return $handler->render($this->app->request, $e);
    }
}