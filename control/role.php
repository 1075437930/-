<?php

/**
 * 淘玉php 管理员角色管理
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 管理员角色管理
 * $Id: role.php 17217 2018年4月23日17:23:46 萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!');
class roleControl extends BaseControl {
    /**
     * @return 构造函数方法 Description
     */
    public function __construct() {
        Language::read('role');//载入语言包
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }
    
    /**
     * @return 管理员角色列表函数 
     */
    public function lists() {
         /* 模板赋值 */
        admin_priv('role_manage');
        $role_list = Model('admin')->get_role_list('*', '');
        Tpl::assign('ur_here',     L('02_admin_role'));
        Tpl::assign('action_link', array('href'=>'index.php?act=role&op=add', 'text' => L('admin_add_role')));
        Tpl::assign('full_page',   1);
        Tpl::assign('admin_list', $role_list);
        Tpl::display('role_list.htm');
    }
    
    
    /**
     * @return 管理员角色列表刷新翻页等更新函数 
     */
    public function role_query() {
       $role_list = Model('admin')->get_role_list('*', '');
       Tpl::assign('admin_list',  $role_list);
       Tpl::assign('full_page', 0);
       make_json_result(Tpl::fetch('role_list.htm'));
    }
    
    /**
     * @return 进入添加管理员角色页面
     */
    public function add() {
        /* 检查权限 */
        admin_priv('role_manage');
        $priv_str = '';
        /* 获取权限的分组数据 */
        $priv_arr = get_actions($priv_str);//获取权限的分组数据并处理字段格式
         /* 模板赋值 */
        Tpl::assign('ur_here',  L('admin_add_role'));
        Tpl::assign('action_link', array('href'=>'index.php?act=role&op=lists', 'text' => L('admin_list_role')));
        Tpl::assign('form_act',    'role');
        Tpl::assign('form_op',    'insert');
        Tpl::assign('action',      'add');
        Tpl::assign('priv_arr',    $priv_arr);
        Tpl::display('role_info.htm');
    }
    
    
    /**
     * @return 插入管理员角色添加数据
     */
    public function insert() {
        /* 检查权限 */
        admin_priv('role_manage');
        $act_list = @join(",", $_POST['action_code']);
        $peam['role_name'] = $_POST['user_name'];
        $peam['action_list'] = $act_list;
        $peam['role_describe'] = trim($_POST['role_describe']);
        $rolemodel = Model('admin');
        $new_id = $rolemodel->insert_role_info($peam);
        /*添加链接*/
        $link[0]['text'] = L('admin_list_role');
        $link[0]['href'] = 'index.php?act=role&op=lists';
        showMessage(L('add') . "&nbsp;" .$_POST['user_name'] . "&nbsp;" . L('action_succeed'), $link);
        /* 记录管理员操作 */
        admin_log($_POST['user_name'], 'add', 'role');
    }

    /**
     * @return 编辑角色信息 Description
     */
    public function edit(){
        admin_priv('role_manage');
        $admin_id = !empty($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
        $adminmodel = Model('admin');
        $where['role_id'] = $admin_id;
        /* 获取角色信息 */
        $user_info = $adminmodel->select_role_info('action_list,role_id, role_name, role_describe', $where);
        $user_into = $this->admin_info;
        /* 查看是否有权限编辑其他管理员的信息 */
        if ($user_into['user_id'] != $admin_id)
        {
            admin_priv('admin_manage');
        }
        /* 获得该管理员的权限 */
        $priv_str = $user_info['action_list'];
        /* 获取权限的分组数据 */
        $priv_arr = get_actions($priv_str);
        /* 模板赋值 */
        Tpl::assign('user',        $user_info);
        Tpl::assign('form_act',    'role');
        Tpl::assign('form_op',    'update');
        Tpl::assign('action',      'edit');
        Tpl::assign('ur_here',     L('admin_edit_role'));
        Tpl::assign('action_link', array('href'=>'index.php?act=role&op=lists', 'text' => L('admin_list_role')));
        Tpl::assign('priv_arr',    $priv_arr);
        Tpl::assign('user_id',     $admin_id);
        Tpl::display('role_info.htm');
    }
    /**
     * @return 更新角色信息 Description
     */
    public function update(){
        /* 更新管理员的权限 */
        $act_list = @join(",", $_POST['action_code']);
        $admin_name = $_POST['user_name'];
        $role_describe = $_POST['role_describe'];
        $adminModel = Model('admin');
        $param['action_list'] = $act_list;
        $param['role_name'] = $admin_name;
        $param['role_describe'] = $role_describe;
        $where['role_id'] = $_POST['id'];
        $adminModel->update_role_info($param,$where);
        
        $param2['action_list'] = $act_list;
        $adminModel->update_admin_info($param2,$where);
        /* 提示信息 */
        $link[] = array('text' => L('back_admin_list'), 'href'=>'index.php?act=role&op=lists');
        showMessage(L('edit') . "&nbsp;" . $_POST['user_name'] . "&nbsp;" . L('action_succeed'),$link);
    }


    /**
     * @return 删除一个对应管理员
     */
    public function remove() {
        /* 检查权限 */
        check_authz_json('admin_drop');
        $adminmodel = Model('admin');
        $role_id = intval($_GET['id']);
        $wheres['role_id'] = $role_id;
        $remove_num = $adminmodel->get_admin_count($wheres);
        if($remove_num > 0){
            make_json_error(l('remove_cannot_user'));
        } else{
            $adminmodel->delete_role($wheres);
            $url = 'index.php?act=role&op=role_query&' . str_replace('op=remove', '', $_SERVER['QUERY_STRING']);
        }
        ecs_header("Location: $url\n");
        exit; 
    }

}
?>

