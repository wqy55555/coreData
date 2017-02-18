<?php
// +----------------------------------------------------------------------
// | DT [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.dtmap.com.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: wqy <3471431354@qq.com>
// +----------------------------------------------------------------------
namespace Home\Controller;

/**
 * 前台默认控制器
 * @author wqy <3471431354@qq.com>
 */
class IndexController extends HomeController
{
    /**
     * 默认方法
     * @author wqy <3471431354@qq.com>
     */
    public function index()
    {
        $this->assign('meta_title', "首页");
        $this->display();
    }
}
