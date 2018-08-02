<?php

/**
 * 淘玉php 登录
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * 包括 登录 验证 退出 操作
 * $Id: index.php 17217 2015-05-19 06:29:08Z 淘玉 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class LoginControl extends BaseControl {
    /**
     * @return 不进行父类的登录验证，所以增加构造方法重写了父类的构造方法
     */
    public function __construct() {
//        parent::__construct();
        Language::read('common,log_action,privilege');
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        Tpl::assign('ceshisql', $_COOKIE['ECSCP']['ceshisql']);
        if ((intval($GLOBALS['setting_config']['captcha']) & CAPTCHA_ADMIN) && gd_version() > 0) {
            Tpl::assign('gd_version', gd_version());
            Tpl::assign('random', mt_rand());
        }
    }

    /**
     * @return 打开登陆界面
     * @param
     */
    public function login() {
        Tpl::display('login.htm','login');
    }

    /**
     * @return 账号密码登陆
     * @param
     */
    public function signin() {
        $_POST['username'] = isset($_POST['username']) ? trim($_POST['username']) : '';
        $_POST['password'] = isset($_POST['password']) ? trim($_POST['password']) : '';
        //调去设置model类 
        //第一个参数model的对应文件名称为 lib_admin_common 对应类名称为 adminCommon
        //第二个参数设置位置  如果为不空为整体根目录底下model 只要为空就是项目的model底下
        $admininto = Model('admin');
        $param['user_name'] = $_POST['username'];
        $files = ' user_id, user_name, password, last_login, action_list, last_login,ec_salt,admin_login_num ';
        $row = Model('admin')->select_admin_info($files, $param);
        if (!empty($row['ec_salt'])) {
            $password = md5(md5($_POST['password']) . $row['ec_salt']);
        } else {
            $password = md5($_POST['password']);
        }
        if ($password == $row['password']) {
            // 登录成功 原来这种不要了 换成systemSetKey存缓存
            $this->systemSetKey(array('user_name' => $row['user_name'], 'user_id' => $row['user_id']));
            if (empty($row['ec_salt'])) {
                $ec_salt = rand(1, 9999);
                $new_possword = md5(md5($_POST['password']) . $ec_salt);
                $update_info = array(
                    'admin_login_num' => ($row['admin_login_num'] + 1),
                    'last_login' => gmtime(),
                    'ec_salt' => $ec_salt,
                    'password' => $new_possword,
                    'last_ip' => getIp()
                );
            } else {
                $update_info = array(
                    'admin_login_num' => ($row['admin_login_num'] + 1),
                    'last_login' => gmtime(),
                    'last_ip' => getIp()
                );
            }
            $where1['user_id'] = $row['user_id'];
            $admininto->update_admin_info($update_info, $where1);
            admin_log('user_login', 'admin');
            if ($row['action_list'] == 'all' && empty($row['last_login'])) {
                $_COOKIE['shop_guide'] = true;
            }else{
                $_SESSION['action_list'] = $row['action_list'];
            }
            if (isset($_POST['ceshi'])) {
                $time = gmtime() + 3600 * 24;
                setcookie('ECSCP[ceshisql]', 1, $time);
            } else {
                $time = gmtime() + 3600 * 24 * 365;
                setcookie('ECSCP[ceshisql]', 0, $time);
            }
            @header('Location: index.php?act=index&op=index');
        } else {
            //sys_msg($_LANG['login_faild'], 1);
            showMessage(L('login_faild'), ['href' => 'index.php?act=login&op=login', 'text' => '登陆']);
        }
    }

    /**
     *  @return 退出登录
     */
    public function logout() {
        setNcCookie('sys_key',   '');
        Tpl::display('login.htm');
    }

}
