<?php

/**
 * 淘玉php 友情链接
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萧瑟 $
 * 友情链接
 * $Id: friendlink.php 17217 2018年4月28日16:33:46 萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class friendlinkControl extends BaseControl {

    /**
     * @return 构造函数方法 Description
     */
    public function __construct() {
        Language::read('friend_link'); //载入语言包
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /**
     * @return 友情链接列表页面
     * 
     */
    public function lists() {
    
        /* 模板赋值 */
        TPL::assign('ur_here', L('list_link'));
        TPL::assign('action_link', array('text' => L('add_link'), 'href' => 'index.php?act=friendlink&op=add_link'));
        TPL::assign('full_page', 1);
        /* 获取友情链接数据 */

        $links_list = $this->get_links_list();
        TPL::assign('links_list', $links_list['list']);
        TPL::assign('filter', $links_list['filter']);
        TPL::assign('record_count', $links_list['record_count']);
        TPL::assign('page_count', $links_list['page_count']);
        $sort_flag = sort_flag($links_list['filter']);
        TPL::assign($sort_flag['tag'], $sort_flag['img']);
//        assign_query_info();
        TPL::display('link_list.htm');
    }

    /**
     * @return 排序、分页、查询
     * 
     */
    public function link_query() {
        /* 获取友情链接数据 */
        $links_list = $this->get_links_list();
        TPL::assign('links_list', $links_list['list']);
        TPL::assign('filter', $links_list['filter']);
        TPL::assign('record_count', $links_list['record_count']);
        TPL::assign('page_count', $links_list['page_count']);
        $sort_flag = sort_flag($links_list['filter']);
        TPL::assign($sort_flag['tag'], $sort_flag['img']);
        make_json_result(TPL::fetch('link_list.htm'), '', array('filter' => $links_list['filter'], 'page_count' => $links_list['page_count']));
    }

    /**
     * @return 添加新链接页面
     */
    public function add_link() {
        admin_priv('friendlink');
        TPL::assign('ur_here', L('add_link'));
        TPL::assign('action_link', array('href' => 'index.php?act=friendlink&op=lists', 'text' => L('list_link')));
        TPL::assign('form_act', 'friendlink');
        TPL::assign('form_op', 'insert_link');
//        assign_query_info();
        TPL::display('link_info.htm');
    }

    /**
     * @return 处理添加的链接
     */
    public function insert_link() {
        $friend_model = Model('systemset');
        /* 变量初始化 */
        $link_logo = '';
        $show_order = (!empty($_POST['show_order'])) ? intval($_POST['show_order']) : 0;
        $link_name = (!empty($_POST['link_name'])) ? sub_str(trim($_POST['link_name']), 250, false) : '';
        /* 查看链接名称是否有重复 */
        $jieguo = $friend_model->select_friend_link_info('link_name', "link_name = " . $link_name);
        if (empty($jieguo)) {

            /* 处理上传的LOGO图片 */
            if ((isset($_FILES['link_img']['error']) && $_FILES['link_img']['error'] == 0) || (!isset($_FILES['link_img']['error']) && isset($_FILES['link_img']['tmp_name']) && $_FILES['link_img']['tmp_name'] != 'none')) {
                $img_up_info = @basename($image->upload_image($_FILES['link_img'], 'afficheimg'));
                $link_logo = DATA_DIR . '/afficheimg/' . $img_up_info;
            }
            /* 使用远程的LOGO图片 */
            if (!empty($_POST['url_logo'])) {
                if (strpos($_POST['url_logo'], 'http://') === false && strpos($_POST['url_logo'], 'https://') === false) {
                    $link_logo = 'http://' . trim($_POST['url_logo']);
                } else {
                    $link_logo = trim($_POST['url_logo']);
                }
            }
            /* 如果链接LOGO为空, LOGO为链接的名称 */
            if (((isset($_FILES['upfile_flash']['error']) && $_FILES['upfile_flash']['error'] > 0) || (!isset($_FILES['upfile_flash']['error']) && isset($_FILES['upfile_flash']['tmp_name']) && $_FILES['upfile_flash']['tmp_name'] == 'none')) && empty($_POST['url_logo'])) {
                $link_logo = '';
            }
            /* 如果友情链接的链接地址没有http://，补上 */
            if (strpos($_POST['link_url'], 'http://') === false && strpos($_POST['link_url'], 'https://') === false) {
                $link_url = 'http://' . trim($_POST['link_url']);
            } else {
                $link_url = trim($_POST['link_url']);
            }
            /* 插入数据 */
            $insert['link_name'] = $link_name;
            $insert['link_url'] = $link_url;
            $insert['link_logo'] = $link_logo;
            $insert['show_order'] = $show_order;
            $new_id = add_table_info('friend_link', $insert);
            /* 记录管理员操作 */
            admin_log($_POST['link_name'], 'add', 'friendlink');
            /* 清除缓存 */
            clear_cache_files();

            /* 提示信息 */
            $link[0]['text'] = L('continue_add');
            $link[0]['href'] = 'index.php?act=friendlink&op=add_link';
            $link[1]['text'] = L('back_list');
            $link[1]['href'] = 'index.php?act=friendlink&op=lists';
            showMessage(L('add') . "&nbsp;" . stripcslashes($_POST['link_name']) . " " . L('attradd_succed'), $link);
        } else {
            $link[] = array('text' => L('go_back'), 'href' => 'javascript:history.back(-1)');
            showMessage(L('link_name_exist'), $link);
        }
    }

    /**
     * @return 友情链接编辑页面
     */
    public function edit_link() {
        $friend_model = Model('systemset');
        admin_priv('friendlink');
        $w['link_id'] = intval($_REQUEST['id']);
        /* 取得友情链接数据 */
        $link_arr = $friend_model->select_friend_link_info('*', $w);

        /* 标记为图片链接还是文字链接 */
        if (!empty($link_arr['link_logo'])) {
            $type = 'img';
            $link_logo = $link_arr['link_logo'];
        } else {
            $type = 'chara';
            $link_logo = '';
        }

        $link_arr['link_name'] = sub_str($link_arr['link_name'], 250, false); // 截取字符串为250个字符避免出现非法字符的情况

        /* 模板赋值 */
        TPL::assign('ur_here', L('edit_link'));
        TPL::assign('action_link', array('href' => 'index.php?act=friendlink&op=lists', 'text' => L('list_link')));
        TPL::assign('form_op', 'update_link');
        TPL::assign('form_act', 'friendlink');
        TPL::assign('action', 'edit');
        TPL::assign('type', $type);
        TPL::assign('link_logo', $link_logo);
        TPL::assign('link_arr', $link_arr);
//        assign_query_info();
        TPL::display('link_info.htm');
    }

    /**
     * @return 编辑链接的处理页面
     */
    public function update_link() {
        $friend_model = Model('systemset');

        /* 变量初始化 */
        $id = (!empty($_REQUEST['id'])) ? intval($_REQUEST['id']) : 0;
        $show_order = (!empty($_POST['show_order'])) ? intval($_POST['show_order']) : 0;
        $link_name = (!empty($_POST['link_name'])) ? trim($_POST['link_name']) : '';

        /* 如果有图片LOGO要上传 */
        if ((isset($_FILES['link_img']['error']) && $_FILES['link_img']['error'] == 0) || (!isset($_FILES['link_img']['error']) && isset($_FILES['link_img']['tmp_name']) && $_FILES['link_img']['tmp_name'] != 'none')) {
            $img_up_info = @basename($image->upload_image($_FILES['link_img'], 'afficheimg'));
            $link_logo = ", link_logo = " . '\'' . DATA_DIR . '/afficheimg/' . $img_up_info . '\'';
        } elseif (!empty($_POST['url_logo'])) {
            $link_logo = ", link_logo = '$_POST[url_logo]'";
        } else {
            /* 如果是文字链接, LOGO为链接的名称 */
            $link_logo = ", link_logo = ''";
        }

        //如果要修改链接图片, 删除原来的图片
        if (!empty($img_up_info)) {
            //获取链子LOGO,并删除
            $w['link_id'] = $id;
            $linkarr = $friend_model->select_friend_link_info('*', $w);
            $old_logo = $linkarr['link_logo'];
            if ((strpos($old_logo, 'http://') === false) && (strpos($old_logo, 'https://') === false)) {
                $img_name = basename($old_logo);
                @unlink(ROOT_PATH . DATA_DIR . '/afficheimg/' . $img_name);
            }
        }

        /* 如果友情链接的链接地址没有http://，补上 */
        if (strpos($_POST['link_url'], 'http://') === false && strpos($_POST['link_url'], 'https://') === false) {
            $link_url = 'http://' . trim($_POST['link_url']);
        } else {
            $link_url = trim($_POST['link_url']);
        }

        /* 更新信息 */
        $param['link_name'] = $link_name;
        $param['link_url'] = $link_url;
        $param['show_order'] = $show_order;
        $where['link_id'] = $id;
        $result = $friend_model->update_friend_link($param, $where);
        $links = array('href' => 'index.php?act=friendlink&op=lists', 'text' => L('back_list'));
        if ($result) {
            /* 记录管理员操作 */
            admin_log($_POST['link_name'], 'edit', 'friendlink');
            /* 清除缓存 */
            clear_cache_files();
            /* 提示信息 */
            showMessage(L('edit') . "&nbsp;" . stripcslashes($_POST['link_name']) . "&nbsp;" . L('attradd_succed'), $links);
        } else {
            showMessage(L('edit') . "&nbsp;" . stripcslashes($_POST['link_name']) . "&nbsp;" . L('modify_failure'), $links);
        }
    }

    /**
     * @return 编辑链接名称
     */
    public function edit_link_name() {
        check_authz_json('friendlink');
        $id = intval($_POST['id']);
        $link_name = json_str_iconv(trim($_POST['val']));
        $friend_model = Model('systemset');
        /* 检查链接名称是否重复 */
        $jietu = $friend_model->select_friend_link_info('link_name', 'link_name=' . $link_name);
        if (!empty($jietu)) {
            make_json_error(sprintf(L('link_name_exist'), $link_name));
        } else {
            $param['link_name'] = $link_name;
            $where['link_id'] = $id;
            $result = $friend_model->update_friend_link($param, $where);
            if ($result) {
                admin_log($link_name, 'edit', 'friendlink');
                clear_cache_files();
                make_json_result(stripslashes($link_name));
            } else {
                make_json_error(Db::error());
            }
        }
    }

    /**
     * @return 删除友情链接
     */
    public function remove() {
        check_authz_json('friendlink');
        $id = intval($_GET['id']);
        $friend_model = Model('systemset');
        /* 获取链子LOGO,并删除 */
        $w['link_id'] = $id;
        $arr = $friend_model->select_friend_link_info('*', $w);
        $link_logo = $arr['link_logo'];
        if ((strpos($link_logo, 'http://') === false) && (strpos($link_logo, 'https://') === false)) {
            $img_name = basename($link_logo);
            @unlink(ROOT_PATH . DATA_DIR . '/afficheimg/' . $img_name);
        }
        $where['link_id'] = $id;
        $result = delete_table_info('friend_link', $where);
        ;
        if ($result) {
            clear_cache_files();
            admin_log('', 'remove', 'friendlink');
            $url = 'index.php?act=friendlink&op=link_query';
            ecs_header("Location: $url\n");
            exit;
        } else {
            $links = array('href' => 'index.php?act=friendlink&op=lists', 'text' => L('back_list'));
            showMessage(L('remove') . L('modify_failure'), $links);
        }
    }

    /**
     * @return 编辑排序
     */
    public function edit_show_order() {
        check_authz_json('friendlink');
        $id = intval($_POST['id']);
        $order = json_str_iconv(trim($_POST['val']));
        $friend_model = Model('systemset');
        /* 检查输入的值是否合法 */
        if (!preg_match("/^[0-9]+$/", $order)) {
            make_json_error(sprintf(L('enter_int'), $order));
        } else {
            $param['show_order'] = $order;
            $where['link_id'] = $id;
            $result = $friend_model->update_friend_link($param, $where);
            if ($result) {
                clear_cache_files();
                make_json_result(stripslashes($order));
            }
        }
    }

    /**
     * @return 获取友情链接数据列表
     * 
     */
    function get_links_list() {
        $result = get_filter();
        if ($result === false) {
            $filter = array();
            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'link_id' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

            /* 获得总记录数据 */
            $sql = 'SELECT COUNT(*) FROM ' . Model()->tablename('friend_link');
            $filter['record_count'] = Db::getOne($sql);
            $filter = page_and_size($filter);

            /* 获取数据 */
            $sql = 'SELECT link_id, link_name, link_url, link_logo, show_order' .
                    ' FROM ' . Model()->tablename('friend_link') .
                    " ORDER by $filter[sort_by] $filter[sort_order]" . " limit " . $filter['start'] . ',' . $filter['page_size'];
            set_filter($filter, $sql);
        } else {
            $sql = $result['sql'];
            $filter = $result['filter'];
        }
//        $res = Db::selectLimit($sql, $filter['page_size'], $filter['start']);
        $res = Db::getAll($sql);
        foreach ($res as $key => $value) {
            if (empty($value['link_logo'])) {
                $res[$key]['link_logo'] = '';
            } else {
                if ((strpos($value['link_logo'], 'http://') === false) && (strpos($value['link_logo'], 'https://') === false)) {
                    $res[$key]['link_logo'] = "<img src='" . '../' . $value['link_logo'] . "' width=88 height=31 />";
                } else {
                    $res[$key]['link_logo'] = "<img src='" . $value['link_logo'] . "' width=88 height=31 />";
                }
            }
        }
        return array('list' => $res, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
    }

}
?>

