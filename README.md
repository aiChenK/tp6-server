# tp6-server
> - 以thinkphp6框架作为服务代码模板，增加api模块
> - 通过Api控制器实现api模块路由
> - api模块接口支持版本划分，支持restful
> - 通过反射类实现api模块支持tp中间件，使用方式同controller

## 依赖
- PHP 7.2+
- composer

## 使用
- 下载完成后需运行 `composer install`
- 复制`.example.env`为`.env`文件配置
- `app\helper\Di`类通过注释设置`@property`可增加ide提示
- api模块下支持多级路面，控制器层级间使用`.`连接，如：`api/v1/top.sub.test/index`->`api/v1/top/sub/test@index`
- api模块下参数为顺序绑定
  ```php
  /** api/v1/Test.php **/
  public function param($param1, $param2)
  {
      return $this->json([
          'param1' => $param1,
          'param2' => $param2
      ]);
  }
  
  // 访问 http://xxx/api/v1/test/param/aaa/bbb
  // 输出：{"param1":"aaa","param2":"bbb"}
  ```


## 验证
- 执行：`curl http://xxxx/api/v1/test`
- 执行：`curl -X POST http://xxxx/api/v1/test`

## 更新
2022-03-15 - v1.2.2
- 去除`composer.lock`文件

2020-11-05 - v1.2.0
- 去除`index.php`全局变量
- 开启强制路由

2020-10-28 - v1.1.1
- 使用`tp-session`替换中间件session实现

2020-06-20 - v1.1.0
- `config/app.php`中增加`api_exception_handle`配置，处理api模块异常
- api模块异常默认返回json格式

2020-05-29 - v1.0.2
- 开启路由功能
- `BaseController`引入`TraitReturn`，统一返回结构

2020-05-28 - v1.0.1
- 修复Api路由，Post请求无法访问`indexPost`方法
