<?php
// +----------------------------------------------------------------------
// | DT [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.dtmap.com.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: wqy <3471431354@qq.com>
// +----------------------------------------------------------------------
return array(
    'app_init'     => array('Common\Behavior\InitModuleBehavior'), //初始化安装的模块行为扩展
    'app_begin'    => array('Common\Behavior\InitConfigBehavior'), //初始化系统配置行为扩展
    'action_begin' => array('Common\Behavior\InitHookBehavior'), //初始化插件钩子行为扩展
);
