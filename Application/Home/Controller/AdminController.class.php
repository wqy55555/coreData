<?php
// +----------------------------------------------------------------------
// | DT [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.dtmap.com.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: wqy <3471431354@qq.com>
// +----------------------------------------------------------------------
namespace Home\Controller;

use Think\Controller;

/**
 * 跳转到后台控制器
 * @author wqy <3471431354@qq.com>
 */
class AdminController extends Controller
{
    /**
     * 自动跳转到后台入口文件
     * @author wqy <3471431354@qq.com>
     */
    public function index()
    {
        redirect(C('HOME_PAGE') . '/admin.php');
    }
}
