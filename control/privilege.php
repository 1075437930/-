<?php

/**
 * 淘玉php 管理员信息以及权限管理程序
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * 管理员信息以及权限管理程序
 * $Id: privilege.php 17217 2015-05-19 06:29:08Z 淘玉 $
 */
defined('TaoyuShop') or exit('Access Invalid!');
class privilegeControl extends BaseControl {
    /**
     * @return 构造函数方法 Description
     */
    public function __construct() {
        Language::read('privilege');//载入语言包
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }
    
    /**
     * @return 管理员列表函数 Description
     */
    public function lists() {
        admin_priv('admin_manage');
        $admininto = $this->get_admin_userlist();
        Tpl::assign('ur_here', L('01_admin_list'));
        Tpl::assign('action_link', array('href' => 'index.php?act=privilege&op=add', 'text' => L('admin_add')));
        Tpl::assign('full_page', 1);
        Tpl::assign('admin_list', $admininto);
        Tpl::display('privilege_list.htm');
    }
    
    
    /**
     * @return 管理员列表刷新翻页等更新函数 
     */
    public function admin_query() {
       $admininto = $this->get_admin_userlist();
       Tpl::assign('admin_list',  $admininto);
       Tpl::assign('full_page', 0);
       make_json_result(Tpl::fetch('privilege_list.htm'));
    }
    
    /**
     * @return 进入添加管理员页面
     */
    public function add() {
        /* 检查权限 */
        admin_priv('admin_manage');
        $fields = 'role_id, role_name, action_list';
        $role_list = Model('admin')->get_role_list($fields,'');
        Tpl::assign('ur_here',  L('admin_add'));
        Tpl::assign('action_link', array('href'=>'index.php?act=privilege&op=lists', 'text' => L('01_admin_list')));
        Tpl::assign('form_act', 'privilege');
        Tpl::assign('form_op', 'insert');
        Tpl::assign('action', 'add');
        Tpl::assign('select_role',  $role_list);
        Tpl::display('privilege_info.htm');
    }
    
    
        /**
         * @return 插入管理员添加数据
         */
        public function insert() {
            /* 检查权限 */
            admin_priv('admin_manage');
            /* 判断管理员是否已经存在 */
            $adminmodel = Model('admin');
            $user_names = trim($_POST['user_name']);
            if (!empty($user_names)){
                $wheres = "user_name = '$user_names'";
                $is_only = $adminmodel->select_admin_info( '*', $wheres);
                if (!empty($is_only)){
                    showMessage(L('user_name_exist'), '', 'javascript','error');
                }
                $inseinto['user_name'] = $user_names;
            }
            /* 获取添加日期及密码 */
            $add_time = gmtime();
            $password  = md5($_POST['password']);
            $role_id = '';
            $action_list = '';
            if (!empty($_POST['select_role']))
            {
                $fields = 'action_list';
                $wheres = " role_id = '".$_POST['select_role']."'";
                $role_list = $adminmodel->get_role_list($fields,$wheres);       
                $inseinto['action_list']  = $role_list[0]['action_list'];
                $inseinto['role_id'] = $_POST['select_role'];
            }
            $inseinto['password'] = $password;
            $inseinto['add_time'] = $add_time;
            $inseinto['nav_list'] = '';
            $new_id = $adminmodel->insert_admin_info($inseinto);

            /*添加链接*/
            $link[0]['text'] = L('go_allot_priv');
            $link[0]['href'] = 'index.php?act=privilege&op=allot&id='.$new_id.'&user='.$user_names.'';
            $link[1]['text'] = L('continue_add');
            $link[1]['href'] = 'index.php?act=privilege&op=add';
            $mags = L('add') . "&nbsp;" .$user_names . "&nbsp;" . L('action_succeed');
            showMessage($mags,$link);
            /* 记录管理员操作 */
            admin_log($user_names,'add', 'admin');
        }
        
        /**‘’
         * @return 编辑管理员信息 Description
         */
        public function edit(){
            $user_id = !empty($_GET['id']) ? intval($_GET['id']) : 0;
            $user_into = $this->admin_info;
            /* 查看是否有权限编辑其他管理员的信息 */
            if ($user_into['user_id'] != $user_id)
            {
                admin_priv('admin_manage');
            }
            $adminmodel = Model('admin');
            /* 获取管理员信息 */
            $fleids = 'user_id, user_name, email, password, agency_id, role_id,action_list';
            $wheres = "user_id = '".$user_id."'";
            $admin_info = $adminmodel->select_admin_info($fleids,$wheres);
            /* 模板赋值 */
            Tpl::assign('ur_here',L('admin_edit'));
            Tpl::assign('action_link', array('text' => L('back_admin_list'), 'href'=>'index.php?act=privilege&op=lists'));
            Tpl::assign('user', $admin_info);
            /* 获得该管理员的权限 */
            $priv_str = $admin_info['action_list'];
            /* 如果被编辑的管理员拥有了all这个权限，将不能编辑 */
            if ($priv_str != 'all')
            {
               $role_list = $adminmodel->get_role_list('role_id, role_name, action_list','');
               Tpl::assign('select_role',  $role_list);
            }
            Tpl::assign('form_act', 'privilege');
            Tpl::assign('form_op', 'update');
            Tpl::assign('action','edit');
            Tpl::display('privilege_info.htm');
        }
        /**
         * @return 更新管理员信息 Description
         */
        public function update(){
            /* 变量初始化 */
            $admin_id    = !empty($_REQUEST['id']) ? intval($_REQUEST['id'])      : 0;
            $admin_name  = !empty($_REQUEST['user_name']) ? trim($_REQUEST['user_name']) : '';
            $param['user_name'] = $admin_name;
            $ec_salt=rand(1,9999);
            $password = $_POST['new_password']; 
            $admin_into = $this->admin_info;
            /* 查看是否有权限编辑其他管理员的信息 */
            if ($admin_into['admin_id'] != $_REQUEST['id'])
            {
                admin_priv('admin_manage');
            }
            $adminmodel = Model('admin');
            /* 判断管理员是否已经存在 */
            if (!empty($admin_name)){
                $wheres = "user_id <> $admin_id AND user_name = '$admin_name'"; 
                $is_only = $adminmodel->select_admin_info('user_name',$wheres);
                if (!empty($is_only)){
                    showMessage(L('user_name_exist'),'','javascript');
                }
            }
            //如果要修改密码
            if (!empty($password)){
                /* 比较新密码和确认密码是否相同 */
                if ($_POST['new_password'] <> $_POST['pwd_confirm']){
                   $link[] = array('text' => L('go_back'), 'href'=>'javascript:history.back(-1)');
                   showMessage(L('js_languages.password_error'),$link);
                }else{
                    $param['password'] = md5(md5($_POST['new_password']).$ec_salt);
                    $param['ec_salt'] = $ec_salt;
                }
            }
            if (!empty($_POST['select_role'])){
                $wheres1['role_id'] = $_POST['select_role'];
                $role_list = $adminmodel->select_role_info('action_list',$wheres1);
                $param['action_list'] = $role_list['action_list'];
                $param['role_id'] = $_POST['select_role'];
            }
            //更新管理员信息
           $wheres_upate['user_id'] = $admin_id;
           $adminmodel->update_admin_info($param,$wheres_upate);
           /* 记录管理员操作 */
           admin_log($_POST['user_name'], 'edit', 'privilege');
           /* 提示信息 */
           $g_link = 'index.php?act=privilege&op=lists';
           $link[] = array('text' => L('back_admin_list'), 'href'=>$g_link);
           showMessage(L('edit_profile_succeed'), $link);
        }


        /**
         * @return 删除一个对应管理员
         */
        public function remove() {
            /* 检查权限 */
            check_authz_json('admin_manage');
            $id = intval($_GET['id']);
            $adminmodel = Model('admin');
            $user_into = $this->admin_info;
            /* 获得管理员用户名 */
            $field = 'action_list,user_name';
            $whers = "user_id = '$id'";
            $action_list = $adminmodel->select_admin_info($field,$whers);
            /* demo这个管理员不允许删除 */
            if ($action_list == 'all'){
                make_json_error(L('edit_remove_cannot'));
            }
            /* ID为1的不允许删除 */
            if ($id == 1){
                make_json_error(L('remove_cannot'));
            }
            /* 管理员不能删除自己 */
            if ($id == $user_into['user_id']){
                make_json_error(L('remove_self_cannot'));
            }
            $where = " user_id = '$id' ";
            $result = $adminmodel->delete_admin($where);

            if ($result){
                admin_log($action_list['user_name'], 'remove', 'admin');
            }
            $url = 'index.php?op=admin_query&' . str_replace('op=remove', '', $_SERVER['QUERY_STRING']);
            ecs_header("Location: $url\n");
            exit;
        }
        /**
         * @return 为管理员分配权限 Description
         */
        public function allot(){
            admin_priv('allot_priv');
            $admin_id = $this->admin_info['user_id'];
            if ($admin_id == $_GET['id'])
            {
                admin_priv('all');
            }
            $adminModel = Model('admin');
            /* 获得该管理员的权限 */
            $where = "user_id = '$_GET[id]'";
            $priv_strinto = $adminModel->select_admin_info('action_list',$where);
            $priv_str = $priv_strinto['action_list'];

            /* 如果被编辑的管理员拥有了all这个权限，将不能编辑 */
            if ($priv_str == 'all')
            {
               $link[] = array('text' =>L('back_admin_list'), 'href'=>'index.php?act=privilege&op=lists');
               showMessage(L('edit_admininfo_cannot'),$link);
            }
            $priv_arr = get_actions($priv_str);//获取权限的分组数据并处理字段格式
            /* 赋值 */
            Tpl::assign('ur_here',     L('allot_priv') . ' [ '. $_GET['user'] . ' ] ');
            Tpl::assign('action_link', array('href'=>'index.php?act=privilege&op=lists', 'text' =>  L('back_admin_list')));
            Tpl::assign('priv_arr',    $priv_arr);
            Tpl::assign('form_op',    'update_allot');
            Tpl::assign('user_id',     $_GET['id']);
            Tpl::display('privilege_allot.htm'); 

        }
        /**
         * @return 更新管理员的权限  Description
         */
        public function update_allot(){
            admin_priv('allot_priv');
            /* 取得当前管理员用户名 */
            $adminmodel = Model('admin');
            $user_id = $_POST['id'];
            $where = "user_id = '$user_id'";
            $admin_name = $adminmodel->select_admin_info('user_name', $where);
            /* 更新管理员的权限 */
            $act_list = @join(",", $_POST['action_code']);
            $update_info['action_list'] = $act_list;
            $update_info['role_id'] = '';  
            $adminmodel->update_admin_info($update_info,$where);
            /* 记录管理员操作 */
            admin_log(addslashes($admin_name['user_name']), 'edit', 'privilege');
            /* 提示信息 */
            $link[] = array('text' =>L('back_admin_list'), 'href'=>'index.php?act=privilege&op=lists');
            $mags = L('edit') . "&nbsp;" . $admin_name['user_name'] . "&nbsp;" . L('action_succeed');
            showMessage($mags,$link);
        }

    /**
     * @return 获取管理员列表并处理字段格式$admininto
     */ 
    private function get_admin_userlist(){
       $field = 'user_id, user_name, email, add_time, last_login';
       $where = '';
       $order = 'user_id DESC'; 
       $admininto = Model('admin')->get_admin_list($field, $where, $order);
       foreach ($admininto AS $key=>$val){
            $admininto[$key]['add_time']     = local_date(C('time_format'), $val['add_time']);
            $admininto[$key]['last_login']   = local_date(C('time_format'), $val['last_login']);
       } 
       return $admininto;
    }
}
?>

