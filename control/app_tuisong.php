<?php

/**
 * 淘玉 app管理中app推送功能
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http:www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 整理 吴博 $
 * 后台app管理中设置银行卡功能
 * $Id: app_tuisong.php  2018-04-07   萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!cache');

class app_tuisongControl extends BaseControl{
    public function __construct() {
        parent::__construct();
        Language::read('app_seting');
        $lang = Language::getLangContent();
        Tpl::assign('lang', $lang);
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /**
     * @return 推送列表
     */
    public function lists(){
    	/* 权限的判断 */
        admin_priv('apptuisong');
        Tpl::display('app_tuisong_list.htm');
    }

    /**
     * @return 推送商品搜索
     */
    public function search_goods(){
    	$condition = isset($_REQUEST['goods_name']) ? $_REQUEST['goods_name'] : '';
    	$model = Model('goods');
    	$field = 'goods_id,goods_name,goods_sn,goods_brief';
    	$where = "goods_name like '%" . $condition . "%' or goods_id like '%". $condition . "%' or goods_sn like '%" . $condition . "%' AND is_on_sale=1 AND is_delete=0 AND fenxiao = 1 AND goods_verify = 1";
    	$result = $model->get_goods_list($field,$where,'add_time DESC',20);
	    die(json_encode($result));
    }

    /**
     * @return 推送用户搜索
     */
    public function search_user(){
    	$condition = isset($_REQUEST['user_name']) ? $_REQUEST['user_name'] : '';
	    $model = Model('users');
    	$field = 'user_id,user_name,alias';
    	$where = "user_name like '%" . $condition . "%' or user_id like '%". $condition . "%' or alias like '%" . $condition . "%'";
    	$result = $model->get_users_list($field,$where,'',0,'');
	    die(json_encode($result));
    }


    /**
     * @return 单品推送（商品推送）
     */
    public function tuisong_goods(){
    	$send_content = $_POST['send_content'];
	    $is_android = $_POST['is_android'];
	    $is_ios = $_POST['is_ios'];
	    $vip_check = $_POST['vip_check'];
	    $one_goods_id = $_POST['one_goods_id'];
	    $goods_name = $_POST['goods_name'];
	    
	    $platform = array();
	    
	    if (strlen($send_content) < 1 || strlen($send_content) > 1000 || empty($one_goods_id)) {
	        $resArr = array('datas' => false, 'msgs' => '推送内容异常');
	        die(json_encode($resArr));
	    }
	    if ($is_android == 'false' && $is_ios == 'false') {
	        $resArr = array('datas' => false, 'msgs' => '未选择推送设备');
	        die(json_encode($resArr));
	    }
	    
	    if ($is_android == 'true') {
	        $platform[] = "android";
	    }
	    if ($is_ios == 'true') {
	        $platform[] = "ios";
	    }
	    
	    /*选择要发送的对象群体*/
	    if($vip_check == 8){
	        /*全部*/
	        $where = '';
	    }else if($vip_check == 7){
	        /*普通*/
	        $where = ' and level_id < 10';
	    }else if($vip_check == 6){
	        /*全部VIp*/
	        $where = ' and level_id in (1,2,3,4,5,6)';
	    }else if($vip_check < 6){
	        /*单独VIP*/
	        $where = ' and level_id = '.$vip_check;
	    }else{
	        $where = "";
	    }
	    
	    /*由于推送一次最多20个，需要要做处理*/
	    $limit = 0;
	    $i = 0;
	    while ($i < 2) { 
	    	/*这个判断是假设网站有效会员小于10万，防止程序死循环*/
	        $field = 'user_id';
	        $w = 'user_ajpushid != "" '.$where;
	        $userintos = Model('users')->get_users_list($field,$w,'',"$limit,5");
	        
	        /*未查到数据，退出循环*/
	        if (empty($userintos)) {
	            $err_msg = '未搜索到用户';
	            break;
	        }	        
	        foreach ($userintos as $key => $value) {
	            $message['to_member_id'] = ',' . $value['user_id'] . ',';
		        $message['message_title'] = '商品推送';
		        $message['message_body'] = $send_content;
		        $message['message_time'] = gmtime();
		        $message['message_type'] = 1;
		        $message['tuisong_type'] = 1;
		        $messageid = Model('message')->insert_message($message);
		        if (!empty($messageid)) {
		            $extras = [
		                'types'=>'goods',
		                'id'=>$one_goods_id
		            ];
		            $content = [
		                'title'=>$goods_name,
		                'body'=>$send_content
		            ];
		            $zhangdsd = send_jpush_message(1, '', $content, $value['user_id'], $extras, $platform);
		        }
	        }	        
	        $limit+= 5;
	        $i++;
	    }
	    
	    if (!empty($zhangdsd)) {
	        $resArr = array('datas' => true, 'msgs' => '发送成功');
	        die(json_encode($resArr));
	    } else {
	        $err_msg = empty($err_msg) ? '发送失败': $err_msg;
	        $resArr = array('datas' => false, 'msgs' => $err_msg);
	        die(json_encode($resArr));
	    }
    }

    /**
     * @return 单人推送（单人商品和系统推送）
     */
    public function tuisong_person(){
    	$send_content = $_POST['send_content'];
	    $user_id = $_POST['user_id'];
	    $push_types = $_POST['push_types'];
	    $one_goods_id = $_POST['one_goods_id'];
	    $tuisong_title = $_POST['tuisong_title'];
	    if ($push_types == 2 || $push_types == '2' || $push_types == 3 || $push_types == '3') {
	        if (strlen($tuisong_title) < 1 || strlen($tuisong_title) > 48) {
	            $resArr = array('datas' => false, 'msgs' => '推送标题异常');
	            die(json_encode($resArr));
	        }
	    }
	    if (strlen($send_content) < 1 || strlen($send_content) > 1000) {
	        $resArr = array('datas' => false, 'msgs' => '推送内容异常');
	        die(json_encode($resArr));
	    }
	    if (strlen($user_id) < 1 || strlen($user_id) > 10) {
	        $resArr = array('datas' => false, 'msgs' => '推送用户异常');
	        die(json_encode($resArr));
	    }
	    if (!in_array($push_types, array(1, 2, 3, 4, '1', '2', '3', '4'))) {
	        $resArr = array('datas' => false, 'msgs' => '未选择推送类型');
	        die(json_encode($resArr));
	    }
	    if (in_array($push_types, array(1, '1')) && empty($one_goods_id)) {
	        $resArr = array('datas' => false, 'msgs' => '商品信息异常');
	        die(json_encode($resArr));
	    }
	    $push_types_arr = array(1 => 'goods', 2 => 'gonggao', 3 => 'xitong', 4 => 'gongdan');
	    $userintos = Model('users')->select_users_info('user_name,user_ajpushid',"user_id = '" . trim($user_id) . "'");
	    $user_names = array();
	    if (empty($userintos['user_ajpushid'])) {
	        $resArr = array('datas' => false, 'msgs' => '接收人信息异常');
	        die(json_encode($resArr));
	    }
	    $user_names[] = $userintos['user_ajpushid'];
	    if (!empty($userintos['user_ajpushid'])) {
	        /*提交推送 查看是否符合条件推送*/
	        if (!empty($user_names)) {
	            $insert_msg = array();
	            if ($push_types == 2 || $push_types == '2') { //公告消息
	                $insert_msg['cat_id'] = 30;
	                $insert_msg['title'] = $tuisong_title;
	                $insert_msg['content'] = $send_content;
	                $insert_msg['author'] = '淘玉商城';
	                $insert_msg['keywords'] = $tuisong_title;
	                $insert_msg['article_type'] = 1;
	                $insert_msg['add_time'] = gmtime();
	                $insert_msg['description'] = '';
	                $messageid = Model('message')->insert_message($insert_msg);
	            } else if ($push_types == 3 || $push_types == '3') { //系统消息
	                $insert_msg['to_member_id'] = ',' . $user_id . ',';
	                $insert_msg['message_title'] = $tuisong_title;
	                $insert_msg['message_body'] = $send_content;
	                $insert_msg['message_time'] = gmtime();
	                $insert_msg['message_update_time'] = gmtime();
	                $insert_msg['message_state'] = 0;
	                $insert_msg['message_type'] = 1;
	                $insert_msg['tuisong_type'] = 1;
	                $messageid = Model('message')->insert_message($insert_msg);
	            } else if ($push_types == 1 || $push_types == '1') { //商品
	                $messageid = $one_goods_id;
	            }
	            $extras = [
	                'types'=>'goods',
	                'id'=>$one_goods_id
	            ];
	            $content = [
	                'title'=>$tuisong_title,
	                'body'=>$send_content
	            ];
	            $zhangdsd = send_jpush_message(1, '', $content, $user_id, '', '');             
	            if (empty($zhangdsd)) {
	                $msgs = '此条推送失败';
	                $resArr = array('datas' => false, 'msgs' => $msgs);
	                die(json_encode($resArr));
	            }
	        }
	    }
	    if (!empty($zhangdsd)) {
	        $resArr = array('datas' => true, 'msgs' => '发送成功');
	        die(json_encode($resArr));
	    } else {
	        $resArr = array('datas' => false, 'msgs' => '发送失败');
	        die(json_encode($resArr));
	    }
    }

    /**
     * @return 属性推送
     */
    public function tuisong_attr(){
    	$send_content = $_POST['send_content'];
	    $vip_check = $_POST['vip_check'];
	    $tuisong_title = $_POST['tuisong_title'];
	    
	    if (strlen($send_content) < 1 || strlen($send_content) > 1000) {
	        $resArr = array('datas' => false, 'msgs' => '推送内容异常');
	        die(json_encode($resArr));
	    }
	    
	    /*上传图片*/
	    $uploaddir ='data/article/thumb'.date('Ym');
 		$res = upload_oss_img($_FILES['fu_UploadFile'],$uploaddir);
 		$img_url = $res['url'];
	    $push_types_arr = array(1 => 'goods', 2 => 'gonggao', 3 => 'xitong', 4 => 'gongdan');
	    $platform = ['ios','android'];
	    $push_types = 2;
	    
	    /*选择要发送的对象群体*/
	    if($vip_check == 8){
	        /*全部*/
	        $where = '';
	    }else if($vip_check == 7){
	        /*普通*/
	        $where = ' and level_id < 10';
	    }else if($vip_check == 6){
	        /*全部VIP*/
	        $where = ' and level_id in (1,2,3,4,5,6)';
	    }else if($vip_check < 6){
	        /*单独VIP*/
	        $where = ' and level_id = '.$vip_check;
	    }else{
	        $where = "";
	    }
	    
	    $limit = 0;
	    $i = 0;
	    while ($i < 2) { //这个判断是假设网站有效会员小于10万，防止程序死循环
	        $field = 'user_id';
	        $w = 'user_ajpushid != "" '.$where;
	        $userintos = Model('users')->get_users_list($field,$w,'',"$limit,5");
	        
	        /*未查到数据，退出循环*/
	        if (empty($userintos)) {
	            $err_msg = '未搜索到用户';
	            break;
	        }	        
	        	        
	        foreach ($userintos as $key => $value) {
	            if (!empty($userintos)) {
		            $extras = [
		                'types'=>'gonggao',
		                'id'=>''
		            ];
		            $content = [
		                'title'=>$tuisong_title,
		                'body'=>$send_content
		            ];
		            $zhangdsd = send_jpush_message(1, '', $content, $value['user_id'], $extras, $platform);
		            
		        }
	        }	       	       	        
	        $limit+= 5;
	        $i++;
	    }
	    if (!empty($zhangdsd)) {
	        $insert_msg = array();
	        if ($push_types == 2 || $push_types == '2') {
	        	/*公告消息*/
	            $insert_msg['cat_id'] = 30;
	            $insert_msg['title'] = $tuisong_title;
	            $insert_msg['content'] = $send_content;
	            $insert_msg['author'] = '淘玉商城';
	            $insert_msg['keywords'] = $tuisong_title;
	            $insert_msg['article_type'] = 1;
	            $insert_msg['add_time'] = gmtime();
	            $insert_msg['description'] = '';
	            $insert_msg['img_url'] = $img_url;
	            Model('article')->insert_article($insert_msg);
	        } else if ($push_types == 3 || $push_types == '3') {
	        	/*系统消息*/
	            $insert_msg['to_member_id'] = 'all';
	            $insert_msg['message_title'] = $tuisong_title;
	            $insert_msg['message_body'] = $send_content;
	            $insert_msg['message_time'] = gmtime();
	            $insert_msg['message_update_time'] = gmtime();
	            $insert_msg['message_state'] = 0;
	            $insert_msg['message_type'] = 1;
	            $insert_msg['read_member_id'] = '';
	            $insert_msg['del_member_id'] = '';
	            $insert_msg['from_member_name'] = '';
	            $insert_msg['from_member_name'] = '';
	            Model('message')->insert_message($insert_msg);
	        }
	        $resArr = array('datas' => true, 'msgs' => '发送成功');
	        die(json_encode($resArr));
	    } else {
	        $resArr = array('datas' => false, 'msgs' => '发送失败');
	        die(json_encode($resArr));
	    }
    }


























}