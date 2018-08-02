<?php

/**
 * 淘玉php 结款管理
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萧瑟 $
 * 结款管理
 * $Id: sellsuppbalance.php 17217 2018年5月12日16:33:46 萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class sellsuppbalanceControl extends BaseControl {

    /**
     * @return 构造函数方法 Description
     */
    public function __construct() {
        Language::read('sellsuppbalance');
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /**
     * @return 结款列表
     */
    public function lists() {
        /* 模板赋值 */
        TPL::assign('ur_here', L('supplier_pay_list'));
        /* 列表信息 */
        $goods_list = $this->get_supp_order_list();
        TPL::assign('goods_list', $goods_list['logdb']);
        TPL::assign('filter', $goods_list['filter']);
        TPL::assign('record_count', $goods_list['record_count']);
        TPL::assign('page_count', $goods_list['page_count']);
        TPL::assign('full_page', 1);

        /* 排序标记 */
        $sort_flag = sort_flag($goods_list['filter']);
        TPL::assign($sort_flag['tag'], $sort_flag['img']);

        /* 显示商品列表页面 */
        TPL::display('supp_balance_list.htm');
    }

    /**
     * @return 结款列表分页、排序、查询
     */
    public function query() {
        $goods_list = $this->get_supp_order_list();
        TPL::assign('goods_list', $goods_list['logdb']);
        TPL::assign('filter', $goods_list['filter']);
        TPL::assign('record_count', $goods_list['record_count']);
        TPL::assign('page_count', $goods_list['page_count']);
        TPL::assign('full_page', 1);
        make_json_result(TPL::fetch('supp_balance_div.htm'), '', array('filter' => $goods_list['filter'], 'page_count' => $goods_list['page_count']));
    }

    /**
     * @return 移除结款记录到回收站
     */
    public function remove() {
        $supplier_model = Model('supplier');
        $update_arr['is_delete'] = 1;
        $where['sell_id'] = $_REQUEST['id'];
        $query = $supplier_model->update_sell_balance($update_arr, $where);
        $url = 'index.php?act=sellsuppbalance&op=query';
        ecs_header("Location: $url\n");
        exit;
    }

    /**
     * @return 批量操作
     */
    public function batch() {
        /* 取得要操作的商品编号 */
        $sellIds = !empty($_POST['checkboxes']) ? join(',', $_POST['checkboxes']) : 0;
        $supplier_model = Model('supplier');
        $link1[] = array('href' => 'index.php?act=sellsuppbalance&op=lists', 'text' => L('back_supp_list'));
        if($sellIds == 0){
            showMessage(L('choce_not_avilable'), $link1);
        }
        if (isset($_POST['type'])) {
            /* 放入回收站 */
            if ($_POST['type'] == 'balance') {
                /* 清除缓存 */
                clear_cache_files();
                $update_arr['balance_status'] = 2;
                $update_arr['pass_time'] = gmtime();
                $where = " order_id " . db_create_in($sellIds);
                $supplier_model->update_sell_balance($update_arr, $where);                
                showMessage(L('supplier_pay_has_done'), $link1);
            } else if ($_POST['type'] == 'drop') {
                /* 清除缓存 */
                clear_cache_files();
                $update_arr['is_delete'] = 1;
                $where = " order_id " . db_create_in($sellIds);
                $supplier_model->update_sell_balance($update_arr, $where);
                showMessage(L('remove_succ'), $link1);
            }
        }
    }

    /**
     * @return 通过结款审核(发送推送)
     */
    public function passes() {
        $sell_id = $_REQUEST['id'];
        $now_time = gmtime();
        $supplier_model = Model('supplier');
        $update_arr['balance_status'] = 2;
        $update_arr['pass_time'] = $now_time;
        $where['sell_id'] = $sell_id;
        $query = $supplier_model->update_sell_balance($update_arr, $where);
        if($query){
            /*发送推送*/            
            /*获取推送的user_id和商品名称*/
            $wheres['sell_id'] = 1;
            $info = $supplier_model->select_sell_balance_info('supplier_id,goods_name',$wheres);
            $res = $supplier_model->select_supplier_info('user_id','supplier_id='.$info['supplier_id']);
            $magtiles = '产品：' . $info['goods_name'] . ' 结算完成';
            send_jpush_message(1, $res['user_id'], ['title'=>'产品结算','body'=>$magtiles]);
            /*ajax返回*/           
            echo json_encode(array('status'=>1));            
        } else {
            echo json_encode(array('status'=>2));
        }                
    }

    /**
     * @return 获取结款列表
     */
    private function get_supp_order_list() {
        $supplier_model = Model('supplier');
        $payResultArr = array(1 => '未结款', 2 => '已结款');
        $filter['keyword'] = empty($_REQUEST['keyword']) ? '' : trim($_REQUEST['keyword']);
        $logdb = array();
        if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1) {
            $filter['keyword'] = json_str_iconv($filter['keyword']);
        }
        $where = " where sell_balance.sell_id <> 0 AND sell_balance.is_delete = 0";
        if (!empty($filter['keyword'])) {
            $where = " where (sell_balance.goods_name LIKE '%" . mysql_like_quote($filter['keyword']) . "%' OR sell_balance.order_sn LIKE '%" . mysql_like_quote($filter['keyword']) . "%') AND sell_balance.is_delete = 0";
        }

        $filter['record_count'] = $supplier_model->get_sell_balance_count($where);

        $filter = page_and_size($filter);

        $sql = "SELECT sell_balance.*,order_info.fanli_pic FROM " .
                Model()->tablename('sell_balance') . " as sell_balance LEFT JOIN " . Model()->tablename('order_info') . " as order_info ON sell_balance.order_id = order_info.order_id " . $where . " ORDER BY sell_balance.sell_id DESC";
        $query = get_all_page($sql, $filter['page_size'], $filter['start']);
        $i = 0;
        foreach ($query as $rt) {
            /*格式化数据*/
            $rt['add_time'] = local_date(C('time_format'), $rt['add_time']);
            $rt['pass_time'] = local_date(C('time_format'), $rt['pass_time']);
            $rt['o_status'] = $payResultArr[$rt['balance_status']];
            $rt['balance_price'] = $rt['goods_pay_price'] - $rt['fanli_pic']; /*商家收益去除返点金额*/            
            /*获取商品信息*/
            $order_id = $rt['order_id'];
            $goods_list = array();
            $field = "goods.goods_name,goods.original_img AS original_img, goods.goods_number AS storage,goods.goods_sn, IFNULL(brand.brand_name, '') AS brand_name ";
            $where = "sell_balance.order_id = " . $order_id;
            $res = $supplier_model->get_sell_balance_goods_brand_list($field, $where);
            foreach ($res as $row) {                
                $row['goods_thumb'] = get_imgurl_oss($row['original_img'], 60, 60);
                $row['goods_url'] = WEB_PATH.'goods.php?id='.$row['goods_id'];
                $goods_list[] = $row;
            }           
            $rt['goods_list'] = $goods_list;
            $logdb[] = $rt;
        }
        /*返回数据*/
        $arrs = array('logdb' => $logdb, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
        return $arrs;
    }

}
?>

