<?php

/**
 * 淘玉php 拍卖活动
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萧瑟 $
 * 拍卖活动
 * $Id: auction.php 17217 2018年5月4日16:33:46 萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class auctionControl extends BaseControl {

    /**
     * @return 构造函数方法 Description
     */
    public function __construct() {
        Language::read('auction'); //载入语言包

        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /**
     * @return 拍卖活动列表
     */
    function lists() {
        /* 检查权限 */

        admin_priv('auction');

        /* 模板赋值 */

        TPL::assign('full_page', 1);

        TPL::assign('ur_here', L('auction_list'));

        TPL::assign('action_link', array('href' => 'index.php?act=auction&op=add', 'text' => L('add_auction')));

        $list = $this->auction_list();

        TPL::assign('auction_list', $list['item']);

        TPL::assign('filter', $list['filter']);

        TPL::assign('record_count', $list['record_count']);

        TPL::assign('page_count', $list['page_count']);

        $sort_flag = sort_flag($list['filter']);

        TPL::assign($sort_flag['tag'], $sort_flag['img']);

        /* 显示商品列表页面 */

        TPL::display('auction_list.htm');
    }

    /**
     * @return 分页、排序、查询
     */
    public function query() {
        $list = $this->auction_list();
        TPL::assign('auction_list', $list['item']);
        TPL::assign('filter', $list['filter']);
        TPL::assign('record_count', $list['record_count']);
        TPL::assign('page_count', $list['page_count']);
        $sort_flag = sort_flag($list['filter']);
        TPL::assign($sort_flag['tag'], $sort_flag['img']);
        make_json_result(TPL::fetch('auction_list.htm'), '', array('filter' => $list['filter'], 'page_count' => $list['page_count']));
    }

    /*
     * 取得拍卖活动列表
     * @return   array
     */

    function auction_list() {
//        $result = get_filter();
        $auction_model = Model('auction');
//        if ($result === false) {
        /* 过滤条件 */
        $filter['keyword'] = empty($_REQUEST['keyword']) ? '' : trim($_REQUEST['keyword']);
        if (isset($_REQUEST['is_ajax']) && $_REQUEST['is_ajax'] == 1) {
            $filter['keyword'] = json_str_iconv($filter['keyword']);
        }
        $filter['is_going'] = empty($_REQUEST['is_going']) ? 0 : 1;
        $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'act_id' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
        $where = " AND supplier_id=0 ";
        if (!empty($filter['keyword'])) {
            $where .= " AND goods_name LIKE '%" . mysql_like_quote($filter['keyword']) . "%'";
        }
        if ($filter['is_going']) {
            $now = gmtime();
            $where .= " AND is_finished = 0 AND start_time <= '$now' AND end_time >= '$now' ";
        }
        $filter['record_count'] = $auction_model->get_auction_count($where);
        /* 分页大小 */
        $filter = page_and_size($filter);
        /* 查询 */
        $sql = "SELECT * " .
                "FROM " . Model()->tablename('goods_activity') .
                " WHERE act_type = '" . GAT_AUCTION . "' $where " .
                " ORDER BY $filter[sort_by] $filter[sort_order] ";
        $filter['keyword'] = stripslashes($filter['keyword']);
//        set_filter($filter, $sql);
//        } else {
//            $sql = $result['sql'];
//            $filter = $result['filter'];
//        }
        $res = get_all_page($sql, $filter['page_size'], $filter['start']);
        $list = array();
        foreach ($res as $value) {
            $ext_info = unserialize($value['ext_info']);
            $arr = array_merge($value, $ext_info);
            $arr['start_time'] = local_date('Y-m-d H:i', $arr['start_time']);
            $arr['end_time'] = local_date('Y-m-d H:i', $arr['end_time']);
            $arr['is_open_price'] = $arr['is_open_price'] == 1 ? '明拍' : '暗拍';
            $list[] = $arr;
        }
        $arr = array('item' => $list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
        return $arr;
    }

}
?>

