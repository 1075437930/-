<?php

/**
 * 淘玉php 会员
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萧瑟 $
 * 会员
 * $Id: users.php 17217 2018年4月29日16:33:46 萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class usersControl extends BaseControl {

    /**
     * @return 构造函数方法 Description
     */
    public function __construct() {
        Language::read('users'); //载入语言包

        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /**
     * @return 会员帐号列表
     */
    public function lists() {
        $user_model = Model('users');
        /* 检查权限 */
        admin_priv('users_manage');
        $rs = Model('user_level')->get_user_level_list('level_id,level_name', '', " level_id ASC");
        /*处理用户vip等级名称*/
        foreach ($rs as $ke => $val) {
            if($val['level_name']=='vip'){
                $rs[$ke]['level_name'] = $val['level_name'].$val['level_id'];
            }
        }
        $ranks = array();
        foreach ($rs as $value) {
            $ranks[$value['level_id']] = $value['level_name'];
        }
        TPL::assign('user_ranks', $ranks);
        TPL::assign('ur_here', L('03_users_list'));
        TPL::assign('action_link', array(
            'text' => L('04_users_add'), 'href' => 'index.php?act=users&op=add'
        ));

        $user_list = $this->user_list();
        foreach ($user_list['user_list'] as $k => $v) {
            $user_list['user_list'][$k]['mobile_phone1'] = $v['mobile_phone'];
            $user_list['user_list'][$k]['mobile_phone'] = jiaMiPhone($v['mobile_phone']);
        }
        TPL::assign('user_list', $user_list['user_list']);
        TPL::assign('filter', $user_list['filter']);
        TPL::assign('record_count', $user_list['record_count']);
        TPL::assign('page_count', $user_list['page_count']);
        TPL::assign('full_page', 1);
        TPL::assign('sort_user_id', '<img src="templates/default/images/sort_desc.gif">');
        TPL::display('user_list.htm');
    }

    /**
     * @return 会员帐号列表排序、分页、查询
     */
    public function query() {
        $user_list = $this->user_list();
        TPL::assign('user_list', $user_list['user_list']);
        TPL::assign('filter', $user_list['filter']);
        TPL::assign('record_count', $user_list['record_count']);
        TPL::assign('page_count', $user_list['page_count']);

        $sort_flag = sort_flag($user_list['filter']);
        TPL::assign($sort_flag['tag'], $sort_flag['img']);

        make_json_result(TPL::fetch('user_list.htm'), '', array(
            'filter' => $user_list['filter'], 'page_count' => $user_list['page_count']
        ));
    }

    /**
     * @return 添加会员帐号
     */
    public function add() {

        /* 检查权限 */
        admin_priv('users_manage');
        $user_model = Model('users');
        $shop_model = Model('systemset');
        $user = array(
            'rank_points' => C('register_points'), 'pay_points' => C('register_points'), 'sex' => 0, 'credit_line' => 0
        );
        /* 取出注册扩展字段 */
        $extend_info_list = $user_model->get_reg_fields_list('*', 'type < 2 AND display = 1 AND id != 6', 'dis_order, id', '');
        TPL::assign('extend_info_list', $extend_info_list);

        TPL::assign('ur_here', L('04_users_add'));
        TPL::assign('action_link', array(
            'text' => L('03_users_list'), 'href' => 'index.php?act=users&op=lists'
        ));
        TPL::assign('form_op', 'insert');
        TPL::assign('form_act', 'users');
        TPL::assign('user', $user);

        $special_ranks = $user_model->get_user_rank_list('*', 'special_rank = 1', 'min_points');
        foreach ($special_ranks as $value) {
            $rank_list[$value['rank_id']] = $value['rank_name'];
        }
        TPL::assign('special_ranks', $rank_list);
        TPL::assign('country_list', Model('region')->get_regoin_list());       
        /*assign_query_info();*/
        TPL::display('user_info.htm');
    }

    /**
     * @return 添加会员帐号
     */
    public function insert() {
        // 全局变量
        $user_id = $this->admin_info['user_id'];
        $users_model = Model('users');
        /* 检查权限 */
        admin_priv('users_manage');
        $username = empty($_POST['username']) ? '' : trim($_POST['username']);
        $password = empty($_POST['password']) ? '' : trim($_POST['password']);
        $mobile_phone = empty($_POST['mobile_phone']) ? '' : trim($_POST['mobile_phone']);
        $sex = empty($_POST['sex']) ? 0 : intval($_POST['sex']);
        $sex = in_array($sex, array(0, 1, 2)) ? $sex : 0;
        $birthday = $_POST['birthdayYear'] . '-' . $_POST['birthdayMonth'] . '-' . $_POST['birthdayDay'];
        $rank = empty($_POST['user_rank']) ? 0 : intval($_POST['user_rank']);
        $credit_line = empty($_POST['credit_line']) ? 0 : floatval($_POST['credit_line']);
        $real_name = empty($_POST['real_name']) ? '' : trim($_POST['real_name']);
        $card = empty($_POST['card']) ? '' : trim($_POST['card']);
        $country = $_POST['country'];
        $province = $_POST['province'];
        $city = $_POST['city'];
        $district = $_POST['district'];
        $address = empty($_POST['address']) ? '' : trim($_POST['address']);
        $status = $_POST['status'];
        $insert_arr['user_name'] = $username;
        $insert_arr['password'] = $password;
        $link[] = array('text' => L('back_user_list'), 'href' => 'index.php?act=users&op=lists');
        $whe['user_name'] = $username;
        $users_info_user_name = $users_model->select_users_info(' * ', $whe);
        $wher['mobile_phone'] = $mobile_phone;
        $users_info_user_mobile = $users_model->select_users_info(' * ', 'mobile_phone = ' . $mobile_phone);

        if (!empty($users_info_user_name)) {
            showMessage(L('username_exists'), $link);
        }
        if (!empty($users_info_user_mobile)) {
            showMessage(L('mobile_phone_exists'), $link);
        }

        $result = $users_model->insert_users($insert_arr);
        if (empty($result)) {
            $link[] = array('text' => L('back_user_list'), 'href' => 'index.php?act=users&op=lists');
            showMessage(L('username_error_or_mobile_phone_error'), $link);
        }
        /* 注册送积分 */
        $points_register = C('register_points');
        if (!empty($points_register)) {
            log_account_change($user_id, 0, 0, C('register_points'), C('register_points'), L('register_points'));
        }

        /* 把新注册会员的扩展信息插入数据库 */

        $fields_arr = $users_model->get_reg_fields_list('id', 'type = 0 AND display = 1', 'dis_order, id');
        /*生成扩展字段的内容字符串*/
        $extend_field_str = ''; 
        $user_id_arr = $users_info_user_name;
        foreach ($fields_arr as $val) {
            $extend_field_index = 'extend_field' . $val['id'];
            if (!empty($_POST[$extend_field_index])) {
                $temp_field_content = strlen($_POST[$extend_field_index]) > 100 ? mb_substr($_POST[$extend_field_index], 0, 99) : $_POST[$extend_field_index];
                $extend_field_str .= " ('" . $user_id_arr['user_id'] . "', '" . $val['id'] . "', '" . $temp_field_content . "'),";
            }
        }
        $extend_field_str = substr($extend_field_str, 0, - 1);
        /*插入注册扩展数据*/
        if ($extend_field_str) {
            $insert_arr0['user_id'] = $user_id_arr[0];
            $insert_arr0['reg_field_id'] = $user_id_arr[1];
            $insert_arr0['content'] = $user_id_arr[2];
            $result0 = $users_model->insert_ureg_extend_info($insert_arr0);
        }

        /* 更新会员的其它信息 */
        $other = array();
        $other['credit_line'] = $credit_line;
        $other['user_rank'] = $rank;
        $other['sex'] = $sex;
        $other['birthday'] = $birthday;
        $other['reg_time'] = local_strtotime(local_date('Y-m-d H:i:s'));

        $other['msn'] = isset($_POST['extend_field1']) ? htmlspecialchars(trim($_POST['extend_field1'])) : '';
        $other['qq'] = isset($_POST['extend_field2']) ? htmlspecialchars(trim($_POST['extend_field2'])) : '';
        $other['office_phone'] = isset($_POST['extend_field3']) ? htmlspecialchars(trim($_POST['extend_field3'])) : '';
        $other['home_phone'] = isset($_POST['extend_field4']) ? htmlspecialchars(trim($_POST['extend_field4'])) : '';
        $other['mobile_phone'] = isset($_POST['extend_field5']) ? htmlspecialchars(trim($_POST['extend_field5'])) : '';
        $where['user_name'] = $username;
        $result1 = $users_model->update_users($other, $where);
        $uploaddir = 'data/users/' . date('Ym');
        if (isset($_FILES['face_card']) && $_FILES['face_card']['tmp_name'] != '') {
            $face_card = upload_oss_img($_FILES['face_card'], $uploaddir);
            if ($face_card === false) {
                showMessage(L('card_face_upload_fail'), $link);
            }
        }
        if (isset($_FILES['back_card']) && $_FILES['back_card']['tmp_name'] != '') {
            $back_card = upload_oss_img($_FILES['face_card'], $uploaddir);
            if ($back_card === false) {
                showMessage(L('card_back_upload_fail'), $link);
            }
        }
        $up['mobile_phone'] = $mobile_phone;
        $up['real_name'] = $real_name;
        $up['card'] = $card;
        $up['country'] = $country;
        $up['province'] = $province;
        $up['city'] = $city;
        $up['district'] = $district;
        $up['address'] = $address;
        $up['status'] = $status;
        $w['user_name'] = $username;
        $users_model->update_users($up, $w);

        if ($face_card != '') {
            $up0['face_card'] = $face_card;
            $users_model->update_users($up0, $w);
        }
        if ($back_card != '') {
            $up1['face_card'] = $back_card;
            $users_model->update_users($up1, $w);
        }
        /* 记录管理员操作 */
        admin_log($_POST['username'], 'add', 'users');

        /* 提示信息 */
        showMessage(sprintf(L('add_success'), htmlspecialchars(stripslashes($_POST['username']))), $link);
    }

    /**
     * @return 删除会员帐号
     */
    public function remove() {
        /* 检查权限 */
        admin_priv('users_drop');
        $users = Model('users');
        /* 如果会员已申请或正在申请入驻商家，不能删除会员 */
        $issupplier = Model('supplier')->get_supplier_count("user_id='" . $_GET['id'] . "'");
        $link = array('text' => L('go_back'), 'href' => 'index.php?act=users&op=lists');
        if($issupplier>0){            
            /* 提示信息 */
            showMessage('该会员已申请或正在申请入驻商，不能删除！', $link);            
        }else{
            $username = $users->select_users_info('user_name',"user_id = '" . $_GET['id'] . "'")['user_name'];
            /* 删除会员所有数据 */
            $this->remove_user($_GET['id']);
            
            /* 记录管理员操作 */
            admin_log(addslashes($username), 'remove', 'users');
            
            /* 提示信息 */
            showMessage(sprintf(L('remove_success'),$username), $link);
        }
    }

    /**
     * @return 批量删除会员帐号
     */
    public function batch_remove() {
        /* 检查权限 */
        admin_priv('users_drop');
        $users = Model('users');
        $link = array('text' => L('go_back'), 'href' => 'index.php?act=users&op=lists');
        if(isset($_POST['checkboxes'])) {
            $col = $users->get_users_list('user_name',db_create_in($_POST['checkboxes'],'user_id'));  
            foreach ($col as $key => $value) {
                $result[] = $value['user_name'];
            }    
            $usernames = implode(',', addslashes_deep($result));
            $count = count($result);
            /* 删除会员所有数据 */
            $this->remove_user($_POST['checkboxes']);
            admin_log($usernames, 'batch_remove', 'users');
            /* 提示信息 */
            showMessage(sprintf(L('batch_remove_success'),$count), $link);
        } else {
            /* 提示信息 */
            showMessage(L('no_select_user'), $link);
        }
    }

    /**
     * @return 编辑会员帐号
     */
    public function edit() {
        // 全局变量
        $user_id = $this->admin_info['user_id'];
        $users_model = Model('users');
        /* 检查权限 */
        admin_priv('users_manage');
        $we = 'user_id='.addslashes($_GET['id']);
        $field = 'user_id,user_name,alias,sex, birthday, pay_points, rank_points, user_rank , user_money, frozen_money, credit_line, parent_id,qq, msn,
        office_phone, home_phone, mobile_phone,real_name,card,face_card,back_card,country,province,city,district,address,status';
        $row = $users_model->select_users_info($field, $we);
        $parent = $users_model->select_users_info('user_name', 'user_id='.$row['parent_id'])['user_name'];
        $row['parent_username'] = $parent;
        if ($row) {
            $user['user_id'] = $row['user_id'];
            $user['alias'] = $row['alias'];
            $user['sex'] = $row['sex'];
            $user['birthday'] = date($row['birthday']);
            $user['pay_points'] = $row['pay_points'];
            $user['rank_points'] = $row['rank_points'];
            $user['user_rank'] = $row['user_rank'];
            $user['user_money'] = $row['user_money'];
            $user['frozen_money'] = $row['frozen_money'];
            $user['credit_line'] = $row['credit_line'];
            $user['formated_user_money'] = price_format($row['user_money']);
            $user['formated_frozen_money'] = price_format($row['frozen_money']);
            $user['parent_id'] = $row['parent_id'];
            $user['parent_username'] = $row['parent_username'];
            $user['qq'] = $row['qq'];
            $user['msn'] = $row['msn'];
            $user['office_phone'] = $row['office_phone'];
            $user['home_phone'] = $row['home_phone'];
            $user['mobile_phone'] = $row['mobile_phone'];
            $user['mobile_phone_mi'] = jiaMiPhone($row['mobile_phone']);
            /* 代码增加2014-12-23 by www.taoyumall.com _star */
            $user['real_name'] = $row['real_name'];
            $user['card'] = $row['card'];
            $user['face_card'] = $row['face_card'];
            $user['back_card'] = $row['back_card'];
            $user['country'] = $row['country'];
            $user['province'] = $row['province'];
            $user['city'] = $row['city'];
            $user['district'] = $row['district'];
            $user['address'] = $row['address'];
            $user['status'] = $row['status'];
            /* 代码增加2014-12-23 by www.taoyumall.com _end */
        } else {
            $link[] = array(
                'text' => L('go_back'), 'href' => 'index.php?act=users&op=lists'
            );
            showMessage(L('username_invalid'), $link);
        }
        /* 取出注册扩展字段 */
        $extend_info_list = $users_model->get_reg_fields_list('*', 'type < 2 AND display = 1 AND id != 6', 'dis_order,id');
        $where['user_id'] = $user['user_id'];
        $extend_info_arr = $users_model->get_reg_extend_info_list('reg_field_id, content ', $where);
        $temp_arr = array();
        foreach ($extend_info_arr as $val) {
            $temp_arr[$val['reg_field_id']] = $val['content'];
        }

        foreach ($extend_info_list as $key => $val) {
            switch ($val['id']) {
                case 1:
                    $extend_info_list[$key]['content'] = $user['msn'];
                    break;
                case 2:
                    $extend_info_list[$key]['content'] = $user['qq'];
                    break;
                case 3:
                    $extend_info_list[$key]['content'] = $user['office_phone'];
                    break;
                case 4:
                    $extend_info_list[$key]['content'] = $user['home_phone'];
                    break;
                case 5:
                    $extend_info_list[$key]['content'] = $user['mobile_phone'];
                    break;
                default:
                    $extend_info_list[$key]['content'] = empty($temp_arr[$val['id']]) ? '' : $temp_arr[$val['id']];
            }
        }

        TPL::assign('extend_info_list', $extend_info_list);

        /* 当前会员推荐信息 */
        $affiliate = unserialize(C('affiliate'));
        TPL::assign('affiliate', $affiliate);

        empty($affiliate) && $affiliate = array();

        if (empty($affiliate['config']['separate_by'])) {
            // 推荐注册分成
            $affdb = array();
            $num = count($affiliate['item']);
            $up_uid = "'$_GET[id]'";
            for ($i = 1; $i <= $num; $i ++) {
                $count = 0;
                if ($up_uid) {
                    $query = $users_model->get_users_list('user_id', " parent_id IN($up_uid)");

                    $up_uid = '';
                    foreach ($query as $rt) {
                        $up_uid .= $up_uid ? ",'$rt[user_id]'" : "'$rt[user_id]'";
                        $count ++;
                    }
                }
                $affdb[$i]['num'] = $count;
            }
            if ($affdb[1]['num'] > 0) {
                TPL::assign('affdb', $affdb);
            }
        }
        /* 代码增加2014-12-23 by www.taoyumall.com _star */
        TPL::assign('country_list', Model('region')->get_regoin_list());
        TPL::assign('province_list', $province_list);
        TPL::assign('city_list', $city_list);
        TPL::assign('district_list', $district_list);
        /* 代码增加2014-12-23 by www.taoyumall.com _end */
        TPL::assign('ur_here', L('users_edit'));
        TPL::assign('action_link', array(
            'text' => L('03_users_list'), 'href' => 'index.php?act=users&op=lists'
        ));
        $user['user_name'] = $row['user_name'];
        TPL::assign('user', $user);
        TPL::assign('form_op', 'update');
        TPL::assign('form_act', 'users');
        $special_ranks = $users_model->get_user_rank_list('*', 'special_rank = 1', 'min_points');
        foreach ($special_ranks as $value) {
            $rank_list[$value['rank_id']] = $value['rank_name'];
        }
        TPL::assign('special_ranks', $rank_list);
        TPL::display('user_info.htm');
    }

    /**
     * @return 更新会员帐号
     */
    public function update() {
        // 全局变量
        $user_id = $this->admin_info['user_id'];
        $users_model = Model('users');

        /* 检查权限 */
        admin_priv('users_manage');
        $username = empty($_POST['username']) ? '' : trim($_POST['username']);
        $password = empty($_POST['password']) ? '' : trim($_POST['password']);
        $mobile_phone = empty($_POST['mobile_phone']) ? '' : trim($_POST['mobile_phone']);
        $sex = empty($_POST['sex']) ? 0 : intval($_POST['sex']);
        $sex = in_array($sex, array(0, 1, 2)) ? $sex : 0;
        $birthday = $_POST['birthdayYear'] . '-' . $_POST['birthdayMonth'] . '-' . $_POST['birthdayDay'];
        $rank = empty($_POST['user_rank']) ? 0 : intval($_POST['user_rank']);
        $credit_line = empty($_POST['credit_line']) ? 0 : floatval($_POST['credit_line']);
        /* 代码增加2014-12-23 by www.taoyumall.com _star */
        $real_name = empty($_POST['real_name']) ? '' : trim($_POST['real_name']);
        $card = empty($_POST['card']) ? '' : trim($_POST['card']);
        $country = $_POST['country'];
        $province = $_POST['province'];
        $city = $_POST['city'];
        $district = $_POST['district'];
        $address = empty($_POST['address']) ? '' : trim($_POST['address']);
        $status = $_POST['status'];
        /* 代码增加2014-12-23 by www.taoyumall.com _end */

        // 获取会员邮箱和手机号已经验证信息,如果手机号、邮箱变更则需验证，如果未变化则沿用原来的验证结果
        $where['user_name'] = $username;
        $user = $users_model->select_users_info('user_id, user_name, sex, reg_time', $where);
        $profile = array(
            'username' => $username, 'password' => $password, 'mobile_phone' => $mobile_phone, 'gender' => $sex, 'bday' => $birthday
        );

        if ($user['mobile_phone'] != $mobile_phone) {
            $profile['mobile_validated'] = 0;
        } else {
            $profile['mobile_validated'] = $user['mobile_validated'];
        }
        $w['user_id'] = $user['user_id'];
        $result = $users_model->update_users($profile, $w);

        if (!$result) {
            $msg = L('edit_user_failed');
            showMessage($msg, array('text' => L('03_users_list'), 'href' => 'index.php?act=users&op=lists'));
        }
        if (!empty($password)) {
            $wh['user_name'] = $username;
            $update_arr['ec_salt'] = '0';
            $users_model->update_users($update_arr, $wh);
        }
        /* 代码增加2014-12-23 by www.taoyumall.com _star */
        $uploaddir = 'data/users/' . date('Ym');
        if (isset($_FILES['face_card']) && $_FILES['face_card']['tmp_name'] != '') {
            $face_card = upload_oss_img($_FILES['face_card'], $uploaddir);
            if ($face_card === false) {
                $link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
                showMessage(L('card_face_upload_fail'), $link);
            }
        }
        if (isset($_FILES['back_card']) && $_FILES['back_card']['tmp_name'] != '') {
            $back_card = upload_oss_img($_FILES['face_card'], $uploaddir);
            if ($back_card === false) {
                $link = array('text' => L('go_back',''), 'href' => 'javascript:history.back(-1)');
                showMessage(L('card_back_upload_fail'), $link);
            }
        }
        $whe['user_name'] = $username;
        $up_arr['real_name'] = $real_name;
        $up_arr['card'] = $card;
        $up_arr['country'] = $country;
        $up_arr['province'] = $province;
        $up_arr['city'] = $city;
        $up_arr['district'] = $district;
        $up_arr['address'] = $address;
        $up_arr['status'] = $status;
        $users_model->update_users($up_arr, $whe);
        if ($face_card != '') {
            $up_arr0['face_card'] = $face_card;
            $users_model->update_users($up_arr0, $whe);
        }
        if ($back_card != '') {
            $up_arr1['back_card'] = $back_card;
            $users_model->update_users($up_arr1, $whe);
        }
        /* 代码增加2014-12-23 by www.taoyumall.com _end */
        /* 更新会员扩展字段的数据 */
        // 读出所有扩展字段的id
        $fields_arr = $users_model->get_reg_fields_list('id', 'type = 0 AND display = 1', 'dis_order, id');
        $user_id_arr = $users_model->select_users_info('user_id, user_name, sex, reg_time', $whe);
        $user_id = $user_id_arr['user_id'];
        /*循环更新扩展会员信息*/
        foreach ($fields_arr as $val) {
            $extend_field_index = 'extend_field' . $val['id'];
            if (isset($_POST[$extend_field_index])) {
                $temp_field_content = strlen($_POST[$extend_field_index]) > 100 ? mb_substr($_POST[$extend_field_index], 0, 99) : $_POST[$extend_field_index];
                $where_extend['reg_field_id'] = $val[id];
                $where_extend['user_id'] = $user_id;
                $result0 = $users_model->select_reg_extend_info_info('*', $where_extend);
                /*如果之前没有记录，则插入*/
                if ($result0) {
                    $update_extend_arr['content'] = $temp_field_content;
                    $users_model->update_reg_extend_info($update_extend_arr, $where_extend);
                } else {
                    $insert_extend_arr['user_id'] = $user_id;
                    $insert_extend_arr['reg_field_id'] = $val[id];
                    $insert_extend_arr['content'] = $temp_field_content;
                    $users_model->insert_reg_extend_info($insert_extend_arr);
                }
            }
        }

        /* 更新会员的其它信息 */
        $other = array();
        $other['credit_line'] = $credit_line;
        $other['user_rank'] = $rank;

        $other['msn'] = isset($_POST['extend_field1']) ? htmlspecialchars(trim($_POST['extend_field1'])) : '';
        $other['qq'] = isset($_POST['extend_field2']) ? htmlspecialchars(trim($_POST['extend_field2'])) : '';
        $other['office_phone'] = isset($_POST['extend_field3']) ? htmlspecialchars(trim($_POST['extend_field3'])) : '';
        $other['home_phone'] = isset($_POST['extend_field4']) ? htmlspecialchars(trim($_POST['extend_field4'])) : '';
        /*$other['mobile_phone'] = isset($_POST['extend_field5']) ?
        htmlspecialchars(trim($_POST['extend_field5'])) : '';
        dqy add start 2015-1-6
        去掉此处，此处会将手机号码设置为未验证
        $sql = "select mobile_phone from " . $GLOBALS['ecs']->table('users') . " where user_name = '$username'";
        if($GLOBALS['db']->getOne($sql) != $other['mobile_phone'])
        {
        $sql = "UPDATE " . $GLOBALS['ecs']->table('users') . " SET validated = 0 where user_name = '$username'";
        $GLOBALS['db']->query($sql);
        }
         
        dqy add end 2015-1-6*/

        $users_model->update_reg_extend_info($other, $whe);
        /* 记录管理员操作 */
        admin_log($username, 'edit', 'users');

        /* 提示信息 */
        $links[0]['text'] = L('goto_list');
        $links[0]['href'] = 'users.php?act=users&op=lists';
        $links[1]['text'] = L('go_back');
        $links[1]['href'] = 'javascript:history.back()';

        showMessage(L('update_success'), $links);
    }

    /**
     * @return 会员地址列表
     */
    public function address_list() {
        $address = $this->get_user_address_list();                        
        Tpl::assign('address', $address);
        Tpl::assign('ur_here', L('address_list'));
        Tpl::assign('action_link', array(
            'text' => L('03_users_list'), 'href' => 'index.php?act=users&op=lists' 
        ));
        Tpl::display('user_address_list.htm');
    }

    /**
     * @return 返回会员列表数据
     */
    private function user_list() {
        $result = get_filter();
        if ($result === false) {
            /* 过滤条件 */
            $filter['keywords'] = empty($_REQUEST['keywords']) ? '' : trim($_REQUEST['keywords']);
            if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1) {
                $filter['keywords'] = json_str_iconv($filter['keywords']);
            }
            $filter['rank'] = empty($_REQUEST['rank']) ? 0 : $_REQUEST['rank'];
            $filter['pay_points_gt'] = empty($_REQUEST['pay_points_gt']) ? 0 : intval($_REQUEST['pay_points_gt']);
            $filter['pay_points_lt'] = empty($_REQUEST['pay_points_lt']) ? 0 : intval($_REQUEST['pay_points_lt']);

            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'user_id' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

            $ex_where = '';
            if ($filter['keywords']) {
                $ex_where .= " AND user_name LIKE '%" . mysql_like_quote($filter['keywords']) . "%' or alias like  '%" . mysql_like_quote($filter['keywords']) . "%' or mobile_phone like  '%" . mysql_like_quote($filter['keywords']) . "%' ";
            }
            if ($filter['rank']) {
                $ex_where .= " AND  level_id =  ".$filter['rank'];
            }
            if ($filter['pay_points_gt']) {
                $ex_where .= " AND pay_points >= '$filter[pay_points_gt]' ";
            }
            if ($filter['pay_points_lt']) {
                $ex_where .= " AND pay_points < '$filter[pay_points_lt]' ";
            }
            $filter['record_count'] = Model('users')->get_users_count($ex_where);
            /* 分页大小 */
            $filter = page_and_size($filter);
            $sql = "SELECT user_email_bind,level_id,user_mobile_bind,user_id, user_name, alias, email, mobile_phone, is_validated, validated, user_money, frozen_money, rank_points, pay_points,taoyu_money, status, reg_time, froms " .
                    " FROM " . Model()->tablename('users') .'WHERE 1'. $ex_where . " ORDER by " . $filter['sort_by'] . ' ' . $filter['sort_order'];
            set_filter($filter, $sql);
        } else {
            $sql = $result['sql'];
            $filter = $result['filter'];
        }

        $user_list = get_all_page($sql, $filter['page_size'], $filter['start']);        
        $rank_list = Model('users')->get_user_rank_list('*','1');        
        $count = count($user_list);
        for ($i = 0; $i < $count; $i ++) {
            $user_list[$i]['reg_time'] = local_date(C('date_format'), $user_list[$i]['reg_time']);

            for ($j = 0; $j < count($rank_list); $j ++) {
                $rank_id = $rank_list[$j]['rank_id'];

                $rank_points = $user_list[$i]['rank_points'];
                $min_point = $rank_list[$j]['min_points'];
                $max_point = $rank_list[$j]['max_points'];

                if ($rank_points <= $max_point && $rank_points >= $min_point) {
                    $user_list[$i]['rank_name'] = $rank_list[$j]['rank_name'];
                }
            }
        }

        /*获取用户vip等级*/
        $user_level = Model('user_level')->get_user_level_list('level_id,level_name','1');
        /*处理用户vip等级名称*/
        foreach ($user_level as $ke => $val) {
            if($val['level_name']=='vip'){
                $user_level[$ke]['level_name'] = $val['level_name'].$val['level_id'];
            }
        }
        if(!empty($user_list)){
            foreach ($user_list as $key => $value) {            
                foreach ($user_level as $k => $v) {
                    if($value['level_id'] == $v['level_id']){
                        $user_list[$key]['level_name'] = $v['level_name'];
                    }    
                }            
            }
        }
        $arr = array(
            'user_list' => $user_list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']
        );

        return $arr;
    }

    /**
     * @return 返回会员地址列表
     */
    private function get_user_address_list() {
        $region = Model('region');
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $where = "user_id='$id'";
        $address = Model('user_address')->get_user_address_list('*',$where);
        foreach ($address as $key => $value) {
            $address[$key]['country_name'] = $region->select_region_info('region_name','region_id = '.$value['country'])['region_name'];
            $address[$key]['province_name'] = $region->select_region_info('region_name','region_id = '.$value['province'])['region_name'];
            $address[$key]['city_name'] = $region->select_region_info('region_name','region_id = '.$value['city'])['region_name'];
            $address[$key]['district_name'] = $region->select_region_info('region_name','region_id = '.$value['district'])['region_name'];
            $address[$key]['mobile'] = jiaMiPhone($value['mobile']);
        }
        return $address;
    }

    /**
     * @return 删除会员帐号
     */
    private function remove_user($id) {
        $model = Model('users');
        $order_model = Model('order');
        if (is_array($id)) {   
            $where = db_create_in($id, 'user_id');
            $col = $model->get_users_list('user_id',$where);
            foreach ($col as $val) {
                $temp[] = $val['user_id'];
            }
            $col = $temp;
        } else {
            $where = "user_id= " . $id;
            $col = $model->select_users_info('user_id',$where);
        }
        if ($col) {
            /*将当前会员推荐的会员的parent_id改为0 */
            $data = array('parent_id'=>0);
            $res = $model->update_users($data,db_create_in($col, 'parent_id'));
            /*删除当前会员*/
            $res = $model->delete_users(db_create_in($col, 'user_id'));

            /* 删除会员订单 */
            $col_order_ids = $order_model->get_order_info_list('order_id',db_create_in($col, 'user_id'));
            foreach ($col_order_ids as $val) {
                $col_order_id[] = $val['order_id'];
            }
            if ($col_order_id) {
                $res = $order_model->delete_order_info(db_create_in($col_order_id, 'order_id'));
                $res = $order_model->delete_order_goods(db_create_in($col_order_id, 'order_id'));
            }
            $wheres = db_create_in($col, 'user_id');
            /*删除缺货登记相关信息*/
            $model->delete_booking_goods($wheres);
            /*删除会员收藏商品信息*/
            $model->delete_collect_goods($wheres);
            /*删除会员反馈信息*/
            $model->delete_feedback($wheres);
            /*删除会员收货地址*/
            Model('user_address')->delete_user_address($wheres);
            /*删除会员红包信息*/
            $model->delete_user_bonus($wheres);
            /*删除会员资金流动信息*/
            $model->delete_user_account($wheres);
            /*删除会员标记信息*/
            $model->delete_tag($wheres);
            /*删除会员日志*/
            Model('accountlog')->delete_account_log($wheres);
        }
        if (is_array($id)) {
            $wherei = db_create_in($id, 'user_id');
        } else {
            $wherei = "user_id=" . $id;
        }
        $res = $model->delete_users($wherei);
    }
}
?>

