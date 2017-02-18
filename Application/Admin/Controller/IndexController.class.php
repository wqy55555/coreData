<?php
// +----------------------------------------------------------------------
// | DT [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.dtmap.com.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: wqy <3471431354@qq.com>
// +----------------------------------------------------------------------
namespace Admin\Controller;

/**
 * 后台默认控制器
 * @author wqy <3471431354@qq.com>
 */
class IndexController extends AdminController
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

    /**
     * 删除缓存
     * @author wqy <3471431354@qq.com>
     */
    public function removeRuntime()
    {
        $file   = new \Util\File();
        $result = $file->del_dir(RUNTIME_PATH);
        if ($result) {
            $this->success("缓存清理成功");
        } else {
            $this->error("缓存清理失败");
        }
    }
}
