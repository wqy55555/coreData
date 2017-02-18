<?php
// +----------------------------------------------------------------------
// | DT [ 简单 高效 卓越 ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://www.dtmap.com.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: wqy <3471431354@qq.com>
// +----------------------------------------------------------------------
namespace Admin\Controller;

use \Util\Tree;

/**
 * 区划控制器
 * @author wqy <3471431354@qq.com>
 */
class AreaController extends AdminController
{
    /**
     * 区划列表
     * @author wqy <3471431354@qq.com>
     */
    public function index($group = 'main')
    {
        //搜索
        $keyword         = I('keyword', '', 'string');
        $condition       = array('like', '%' . $keyword . '%');
        $map['id|title'] = array(
            $condition,
            $condition,
            '_multi' => true,
        );

        // 获取所有区划
        $map['status'] = array('egt', '0');
        $map['group']  = $group;
        $data_list     = D('Admin/Area')
            ->where($map)
            ->order('sort asc, id asc')
            ->select();

        // 给文章列表类型加上链接
        foreach ($data_list as &$val) {
            if ($val['type'] == 'post') {
                $val['title'] = '<a href="' . U('Admin/Post/index', array('cid' => $val['id'])) . '">' . $val['title'] . '</a>';
            }
        }

        // 转换成树状列表
        $tree      = new Tree();
        $data_list = $tree->array2tree($data_list);

        // 设置Tab区划数据列表
        $nav_group_list = C('NAV_GROUP_LIST'); // 获取分类分组
        foreach ($nav_group_list as $key => $val) {
            $tab_list[$key]['title'] = $val;
            $tab_list[$key]['href']  = U('index', array('group' => $key));
        }

        // 使用Builder快速建立列表页面。
        $builder = new \Common\Builder\ListBuilder();
        $builder->setMetaTitle('区划列表') // 设置页面标题
            ->addTopButton('addnew', array('href' => U('Admin/Area/add', array('group' => $group)))) // 添加新增按钮
            ->addTopButton('resume') // 添加启用按钮
            ->addTopButton('forbid') // 添加禁用按钮
            ->addTopButton('delete') // 添加删除按钮
            ->setSearch('请输入ID/区划名称', U('index', array('group' => $group)))
            ->setTabNav($tab_list, $group) // 设置页面Tab区划
            ->addTableColumn('id', 'ID')
            ->addTableColumn('icon', '图标', 'icon')
            ->addTableColumn('title_show', '标题')
            ->addTableColumn('sort', '排序')
            ->addTableColumn('status', '状态', 'status')
            ->addTableColumn('right_button', '操作', 'btn')
            ->setTableDataList($data_list) // 数据列表
            ->addRightButton('edit', array('href' => U('edit', array('group' => $group, 'id' => '__data_id__')))) // 添加编辑按钮
            ->addRightButton('forbid') // 添加禁用/启用按钮
            ->addRightButton('delete') // 添加删除按钮
            ->display();
    }

    // 根据区划类型设置表单项目
    private $extra_html = <<<EOF
    <script type="text/javascript">
        $(function(){
            $('input[name="type"]').change(function() {
                var type = $(this).val();
                // 链接类型
                if (type == 'link') {
                    $('.item_url').removeClass('hidden');
                    $('.item_content').addClass('hidden');
                    $('.item_module_name').addClass('hidden');
                // 模块类型
                } else if (type == 'module') {
                    $('.item_url').addClass('hidden');
                    $('.item_content').addClass('hidden');
                    $('.item_module_name').removeClass('hidden');
                // 单页类型
                } else if (type == 'page') {
                    $('.item_url').addClass('hidden');
                    $('.item_content').removeClass('hidden');
                    $('.item_module_name').addClass('hidden');
                // 文章列表类型
                } else if (type == 'post') {
                    $('.item_url').addClass('hidden');
                    $('.item_content').addClass('hidden');
                    $('.item_module_name').addClass('hidden');
                } else {
                    $('.item_url').addClass('hidden');
                    $('.item_content').addClass('hidden');
                    $('.item_module_name').addClass('hidden');
                }
            });
        });
    </script>
EOF;

    /**
     * 新增区划
     * @author wqy <3471431354@qq.com>
     */
    public function add($group)
    {
        if (IS_POST) {
            $nav_object = D('Admin/Area');
            $data       = $nav_object->create();
            if ($data) {
                $id = $nav_object->add($data);
                if ($id) {
                    $this->success('新增成功', U('index', array('group' => $group)));
                } else {
                    $this->error('新增失败');
                }
            } else {
                $this->error($nav_object->getError());
            }
        } else {
            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('新增区划') // 设置页面标题
                ->setPostUrl(U('', array('group' => $group))) // 设置表单提交地址
                ->addFormItem('group', 'hidden', '区划分组', '区划分组')
                ->addFormItem('pid', 'select', '上级区划', '上级区划', select_list_as_tree('Admin/Area', array('group' => $group), '顶级区划'))
                ->addFormItem('title', 'text', '区划标题', '区划前台显示标题')
                ->addFormItem('type', 'radio', '区划类型', '区划类型', D('Admin/Area')->nav_type())
                ->addFormItem('url', 'text', '外链URL地址', '支持http://格式或者TP的U函数解析格式')
                ->addFormItem('content', 'kindeditor', '单页内容', '单页内容', null, 'hidden')
                ->addFormItem('target', 'radio', '打开方式', '打开方式', array('' => '当前窗口', '_blank' => '新窗口打开'))
                ->addFormItem('icon', 'icon', '图标', '区划图标')
                ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->setFormData(array('type' => 'link', 'group' => $group))
                ->setExtraHtml($this->extra_html)
                ->display();
        }
    }

    /**
     * 编辑区划
     * @author wqy <3471431354@qq.com>
     */
    public function edit($id, $group)
    {
        if (IS_POST) {
            $nav_object = D('Admin/Area');
            $data       = $nav_object->create();
            if ($data) {
                if ($nav_object->save($data)) {
                    $this->success('更新成功', U('index', array('group' => $group)));
                } else {
                    $this->error('更新失败');
                }
            } else {
                $this->error($nav_object->getError());
            }
        } else {
            $info = D('Admin/Area')->find($id);

            // 使用FormBuilder快速建立表单页面。
            $builder = new \Common\Builder\FormBuilder();
            $builder->setMetaTitle('编辑区划') // 设置页面标题
                ->setPostUrl(U('')) // 设置表单提交地址
                ->addFormItem('id', 'hidden', 'ID', 'ID')
                ->addFormItem('group', 'hidden', '区划分组', '区划分组')
                ->addFormItem('pid', 'select', '上级区划', '上级区划', select_list_as_tree('Admin/Area', array('group' => $group), '顶级区划'))
                ->addFormItem('title', 'text', '区划标题', '区划前台显示标题')
                ->addFormItem('type', 'radio', '区划类型', '区划类型', D('Admin/Area')->nav_type())
                ->addFormItem('url', 'text', '外链URL地址', '支持http://格式或者TP的U函数解析格式', null, $info['type'] === 'link' ? '' : 'hidden')
                ->addFormItem('content', 'kindeditor', '单页内容', '单页内容', null, $info['type'] === 'page' ? '' : 'hidden')
                ->addFormItem('target', 'radio', '打开方式', '打开方式', array('' => '当前窗口', '_blank' => '新窗口打开'))
                ->addFormItem('icon', 'icon', '图标', '区划图标')
                ->addFormItem('sort', 'num', '排序', '用于显示的顺序')
                ->setFormData($info)
                ->setExtraHtml($this->extra_html)
                ->display();
        }
    }
}
