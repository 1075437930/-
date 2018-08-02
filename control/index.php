<?php

/**
 * 淘玉php 首页控制器
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * $Id: index.php 17217 2015-05-19 06:29:08Z 淘玉 $
 */
defined('APP_ID') or exit('Access Invalid!');

class indexControl extends BaseControl {
    /**
     * @return 构造函数
     */
    public function __construct() {
        parent::__construct();
        Tpl::assign('index_sign', 'index');
    }
    
    /**
     * @return 进入首页函数
     */
    public function index() {

        Tpl::display('index.htm');
    }
    
    /**
     * @return 主页菜单栏
     */
    function menu() {
        $modules = $GLOBALS['modules'];
        $purview = $GLOBALS['purview'];
        foreach ($modules AS $key => $value) {
            ksort($modules[$key]);
        }
        ksort($modules);
        foreach ($modules AS $key => $val) {
            $menus[$key]['label'] = L($key);
            if (is_array($val)) {
                foreach ($val AS $k => $v) {
                    if (isset($purview[$k])) {
                        if (is_array($purview[$k])) {
                             $boole = false;
                            foreach ($purview[$k] as $action) {
                                $boole = $boole || admin_priv($action, '', false);
                            }
                            if (!$boole) {
                                continue;
                            }
                        } else {
                            if (!admin_priv($purview[$k], '', false)) {
                                continue;
                            }
                        }
                    }
                    if ($k == 'ucenter_setup' && C('integrate_code') != 'ucenter') {
                        continue;
                    }
                    $menus[$key]['children'][$k]['label'] = L($k);
                    $menus[$key]['children'][$k]['action'] = $v;
                }
            } else {
                $menus[$key]['action'] = $val;
            }
            // 如果children的子元素长度为0则删除该组
            if (empty($menus[$key]['children'])) {
                unset($menus[$key]);
            }
        }
        
        Tpl::assign('menus', $menus);
        Tpl::assign('no_help', L('no_help'));
        Tpl::assign('help_lang', C('lang'));
        Tpl::assign('charset', EC_CHARSET);
        Tpl::assign('admin_id', $this->admin_info['user_id']);
        Tpl::display('menu.htm');
    }
    
    /**
     * @return 主页菜单对用mian版块
     */
    public function main() {
        Tpl::display('start.htm');
    }
    
    /**
     * @return 主页菜单对用top版块
     */
    public function top() {
        // 获得管理员设置的菜单
        $lst = array();
        $where['user_id'] = $this->admin_info['user_id'];
        $arr = Model('admin')->select_admin_info('nav_list',$where);
        $nav = $arr['nav_list'];
        if (!empty($nav))
        {
            $arr = explode(',', $nav);
            foreach ($arr AS $val)
            {
                $tmp = explode('|', $val);
                $lst[$tmp[1]] = $tmp[0];
            }
        }
        // 代码修改   By  www.taoyumall.com Start
        // 获得管理员设置的菜单
        // 修改内容：订单列表只统计自营商品，增加了自营查询条件
       $whersorder = " supplier_id = 0 AND extension_code = '' "; 
       $datainfo['order'] = Model('order')->get_order_info_count($whersorder);
        // 修改内容：商品列表计数只统计自营商品，条件增加了以下查询条件：1.未被删除 2.单独销售 3.实物 4.自营。
       $whersgoods = " supplier_id = 0 AND is_real = 1 AND is_delete = 0 AND is_alone_sale = 1 "; 
       $datainfo['goods'] = Model('goods')->get_goods_count($whersgoods);
        // 代码修改   By  www.taoyumall.com End
        // 获得管理员ID

        Tpl::assign('send_mail_on',C('send_mail_on'));
        Tpl::assign('nav_list', $lst);
        Tpl::assign('admin_id', $this->admin_info['user_id']);
        Tpl::assign('certi', C('certi'));
        Tpl::assign('datainfo', $datainfo);
        Tpl::display('top.htm');
    }

    /*更新前台首页静态*/
    public function clear_index(){
        if(clearhtml_index_file()){
            echo 1;
        }
    }

    /*更新前台全部静态*/
    public function clear_html(){
        if(clearhtml_all())
        {
            echo 1;
        }
    }

    public function ce(){
        $tmp_dir = DATA_DIR ;
        $cache_dir = ROOT_PATH . $tmp_dir . '/caches/';
        echo $cache_dir;
    }

    /*清除缓存*/
    public function clear_cache(){
        if(clearhtml_all() && clear_all_files())
        {
            echo 1;
        }
    }


    
}
