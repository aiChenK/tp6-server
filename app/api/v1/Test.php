<?php
namespace app\api\v1;

use app\api\Base;

class Test extends Base
{

    public function index()
    {
        return 'v1-test-index';
    }

    public function indexPost()
    {
        return 'v1-test-index-post';
    }

    public function param($param1, $param2)
    {
        return "param1: {$param1} <br /> param2: {$param2}";
    }
}
