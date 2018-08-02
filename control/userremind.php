<?php

/**
 * 淘玉php 会员通知
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萧瑟 $
 * 会员通知
 * $Id: userremind.php 17217 2018年5月3日16:33:46 萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class userremindControl extends BaseControl {

    /**
     * @return 构造函数方法 Description
     */
    public function __construct() {
//        Language::read('users_remind'); //载入语言包

        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /**
     * @return 用户等级列表
     */
    function index() {
        admin_priv('users_remind');
        TPL::display('notice_add.htm');
    }

    /**
     * @return 提交表单
     */
    public function submit() {
        $content = trim($_POST['content1']); //信息内容
        $send_type = intval($_POST['send_type']);
        $memberid_list = array();
        $user_model = Model('users');
        //整理发送列表
        //指定会员
        if ($send_type == 1) {
            $tmp = explode("\n", $_POST['user_name']);
            $username = implode(',', $tmp);
            $count = substr_count($username, ',');
            if (!empty($tmp)) {
                foreach ($tmp as $k => $v) {
                    $tmp[$k] = trim($v);
                }
                foreach ($tmp as $k => $v) {
                    $member_list[$k] = $user_model->select_users_info('*'," user_name='$v'");
                }
                foreach ($member_list as $k => $val) {
                    $memberlist = $val[0];
                    $userid[$k] = $val[0]['user_id'];
                }
            }
        }

        foreach ($userid as $v) {
            $userid1 .= $v . ',';
        }
        $userid1 = ',' . $userid1;
        $insert_arr = array();
        $insert_arr['send_mode'] = 1;
        $insert_arr['users_name'] = $username;
        $insert_arr['content'] = $content;
        $insert_arr['from_member_id'] = $this->admin_info['admin_id'];
        $insert_arr['from_member_name'] = $this->admin_info['admin_name'];
        $insert_arr['msg_content'] = $content;
        $insert_arr['message_type'] = $send_type;

        if ($send_type == 1) {
            if ($count == 0) {
                $insert_arr['message_ismore'] = 0;
            } else {
                $insert_arr['message_ismore'] = 1;
            }
            $insert_arr['user_id'] = $userid1;
        } else {
            $insert_arr['user_id'] = 'all';
            $insert_arr['message_ismore'] = 1;
        }

        $this->saveMessage($insert_arr);
        $links = array(
            array('href' => 'index.php?act=userremind&op=index')
        );
        showMessage(L('attradd_succed'), $links);
    }

    /**
     * 保存
     * @param type $param
     * @return boolean
     */
    function saveMessage($param) {

        if ($param['user_id'] == '') {
            return false;
        }
        $array = array();

        $array['message_parent_id'] = $param['message_parent_id'] ? $param['message_parent_id'] : '0';

        $array['from_member_id'] = $param['from_member_id'] ? $param['from_member_id'] : '0';

        $array['from_member_name'] = $param['from_member_name'] ? $param['from_member_name'] : '';

        $array['to_member_id'] = $param['user_id'];

        $array['to_member_name'] = $param['users_name'] ? $param['users_name'] : '';

        $array['message_body'] = trim($param['msg_content']);

        $array['message_time'] = time();

        $array['message_update_time'] = time();

        $array['message_type'] = $param['message_type'] ? $param['message_type'] : '0';

        $array['message_ismore'] = $param['message_ismore'] ? $param['message_ismore'] : '0';

        $array['read_member_id'] = $param['read_member_id'] ? $param['read_member_id'] : '';

        $array['del_member_id'] = $param['del_member_id'] ? $param['del_member_id'] : '';
        
        add_table_info('message',$array);
    }

}
?>

