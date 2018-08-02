<?php

/**
 * 淘玉php 平台交易统计
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萧瑟 $
 * 平台交易统计
 * $Id: suprebate.php 17217 2018年5月8日16:33:46 萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class suprebateControl extends BaseControl {

    /**
     * @return 构造函数方法 Description
     */
    public function __construct() {
        Language::read('suprebate,calendar'); //载入语言包

        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }

    /**
     * @return 平台佣金列表
     */
    public function lists() {
        /* 检查权限 */
        admin_priv('supplier_rebate');
        $supplier_model = Model('supplier');
        /*模板赋值*/ 
        $ur_here_lang = L('commision_info');
        $msg = L('down_sale_commision');
        TPL::assign('msg', $msg);
        TPL::assign('ur_here', $ur_here_lang);
        $main_money_info = Model('suprebate')->select_supplier_rebate_log_info('sum(all_money) as all_money,sum(rebate_money) as rebate_money,sum(result_money) as result_money','1');
        TPL::assign('main_info', $main_money_info);
        $supplier_info = Model('suprebate')->get_supplier_rebate_log_supplier_list('supplier_rebate_log.supplier_id, supplier.supplier_name','','supplier_rebate_log.supplier_id');
        TPL::assign('supplier_info', $supplier_info);
        $result = $this->supplier_rebate_list();
        TPL::assign('full_page', 1);
        TPL::assign('supplier_list', $result['result']);
        TPL::assign('filter', $result['filter']);
        TPL::assign('record_count', $result['record_count']);
        TPL::assign('page_count', $result['page_count']);
        TPL::assign('sort_suppliers_id', '<img src="images/sort_desc.gif">');
        /*显示模板*/ 
        TPL::display('supplier_rebate_list.htm');
    }

    /**
     * @return 平台佣金列表排序、分页、查询
     */
    public function query() {
        //check_authz_json('supplier_rebate');

        $result = $this->supplier_rebate_list();

        TPL::assign('supplier_list', $result['result']);
        TPL::assign('filter', $result['filter']);
        TPL::assign('record_count', $result['record_count']);
        TPL::assign('page_count', $result['page_count']);

        /* 排序标记 */
        $sort_flag = sort_flag($result['filter']);
        TPL::assign($sort_flag['tag'], $sort_flag['img']);

        make_json_result(TPL::fetch('supplier_rebate_list.htm'), '', array('filter' => $result['filter'], 'page_count' => $result['page_count']));
    }

    /**
     * @return 平台佣金信息批量导出
     */
    public function export_supps() {
        admin_priv('supplier_rebate');
        header("Content-type: application/vnd.ms-excel; charset=gbk");
        header("Content-Disposition: attachment; filename=rebate_list.xls");

        $export = "<table border='1'><tr><td colspan='2'>商家名称</td><td colspan='2'>订单收入总额（元）</td><td colspan='2'>佣金抽成总额（元）</td><td colspan='2'>商家实际收入总额（元）</td></tr>";

        $result = $this->supplier_rebate_list();
        foreach ($result['result'] as $key => $val) {
            $export .= "<tr><td colspan='2'>" . $val['supplier_name'] . "</td><td colspan='2'>" . $val['all_money'] . "</td><td colspan='2'>-" . $val['rebate_money'] . "</td><td colspan='2'>" . $val['result_money'] . "</td></tr>";
        }
        $export .= "</table>";
        if (EC_CHARSET != 'gbk') {
            echo ecs_iconv(EC_CHARSET, 'gbk', $export) . "\t";
        } else {
            echo $export . "\t";
        }
    }

    /**
     * @return 商家佣金列表
     */
    public function view() {
        admin_priv('supplier_rebate');
        $supplier_model = Model('supplier');
        $ur_here_lang = L('supplier_commision_log');
        TPL::assign('ur_here', $ur_here_lang); // 当前导航
        $suppid = intval($_REQUEST['suppid']);
        $w['supplier_id'] = $suppid;
        $supplier_info = $supplier_model->select_supplier_info('*',$w);
        $supplier_name = $supplier_info['supplier_name'];                
        TPL::assign('supplier_name', $supplier_name);
        $rebate_pay = Model('suprebate')->get_supplier_rebate_log_list(' DISTINCT pay_id,pay_name ',$w);
        $result = $this->supplier_rebate_view_list();
        $today['start'] = local_date('Y-m-d 00:00');
        $today['ends'] = local_date('Y-m-d 00:00', local_strtotime("+1 day"));
        $yestoday['start'] = local_date('Y-m-d 00:00', local_strtotime("-1 day"));
        $yestoday['ends'] = local_date('Y-m-d 00:00', local_strtotime("+1 day"));
        $week['start'] = local_date('Y-m-d 00:00', local_strtotime("-7 day"));
        $week['ends'] = local_date('Y-m-d 00:00', local_strtotime("+1 day"));
        $month['start'] = local_date('Y-m-d 00:00', local_strtotime("-1 month"));
        $month['ends'] = local_date('Y-m-d 00:00', local_strtotime("+1 day"));

        TPL::assign('supplier_list', $result['result']);
        TPL::assign('filter', $result['filter']);
        TPL::assign('record_count', $result['record_count']);
        TPL::assign('page_count', $result['page_count']);

        TPL::assign('full_page', 1); // 翻页参数
        TPL::assign('payinfo', $rebate_pay);
        TPL::assign('today', $today);
        TPL::assign('yestoday', $yestoday);
        TPL::assign('week', $week);
        TPL::assign('month', $month);
        $where['supplier_id'] = $suppid;
        $supplier_order = Model('suprebate')->get_supplier_rebate_log_list('order_id,order_sn', $where);
        TPL::assign('supplier_order', $supplier_order);
        TPL::display('supplier_rebate_info.htm');
    }

    /**
     * @return 商家佣金列表排序、分页、查询
     */
    public function search_supp_query() {
        check_authz_json('supplier_rebate');

        $result = $this->supplier_rebate_view_list();

        TPL::assign('supplier_list', $result['result']);
        TPL::assign('filter', $result['filter']);
        TPL::assign('record_count', $result['record_count']);
        TPL::assign('page_count', $result['page_count']);

        /* 排序标记 */
        $sort_flag = sort_flag($result['filter']);
        TPL::assign($sort_flag['tag'], $sort_flag['img']);

        make_json_result(TPL::fetch('supplier_rebate_info.htm'), '', array('filter' => $result['filter'], 'page_count' => $result['page_count']));
    }

    /**
     * @return 商家佣金信息批量导出
     */
    public function export_goods() {
        admin_priv('supplier_rebate');
        header("Content-type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=rebate_list.xls");

        $export = "<table border='1'><tr><td colspan='2'>商家名称</td><td colspan='2'>账户变动时间</td><td colspan='2'>订单号</td><td colspan='2'>订单金额（元）</td><td colspan='2'>平台扣除佣金（元）</td><td colspan='2'>商家实际收入金额（元）</td><td colspan='2'>支付方式</td><td colspan='2'>备注</td></tr>";

        $result = $this->supplier_rebate_view_list();
        foreach ($result['result'] as $key => $val) {
            $export .= "<tr><td colspan='2'>" . $_GET['supplier_name'] . "</td><td colspan='2'>" . $val['add_time'] . "</td><td colspan='2' style='vnd.ms-excel.numberformat:@'>" . $val['order_sn'] . "</td><td colspan='2'>" . $val['all_money'] . "</td><td colspan='2'>-" . $val['rebate_money'] . "</td><td colspan='2'>" . $val['result_money'] . "</td><td colspan='2'>" . $val['pay_name'] . "</td><td colspan='2'>" . $val['texts'] . "</td></tr>";
        }
        $export .= "</table>";
        if (EC_CHARSET != 'utf-8') {
            echo ecs_iconv(EC_CHARSET, 'utf-8', $export) . "\t";
        } else {
            echo $export . "\t";
        }
    }

    /**
     * @return 平台下架佣金列表
     */
    public function xiajia() {
        $msg = L('deal_sale_commision');
        $supplier_model = Model('supplier');
        TPL::assign('msg', $msg);
        $xiajia_list = $this->xiajia_list();
        /*取得商家列表*/
        $supplier_ids = $supplier_model->get_supplier_order_list('supplier_id', 'onsale_type = 1','',0,'supplier_id');
        $supplier_id =array();
        foreach ($supplier_ids as $key => $value) {
            $supplier_id[] = $value['supplier_id'];
        }
        $str = implode(',', $supplier_id);
        $supplier_info = $supplier_model->get_supplier_list('supplier_id,supplier_name',"supplier_id in ($str)");
        
        TPL::assign('tot', $xiajia_list['tot']);
        TPL::assign('money', $xiajia_list['list']);
        TPL::assign('supplier_info', $supplier_info);
        Tpl::assign('filter', $xiajia_list['filter']);
        Tpl::assign('record_count', $xiajia_list['record_count']);
        Tpl::assign('page_count',   $xiajia_list['page_count']);
        TPL::assign('full_page', 1); // 翻页参数
        TPL::assign('ur_here', L('xiajia_commision_info'));
        TPL::display('supplier_xiajia_list.htm');
    }

    /**
     * @return 平台下架佣金列表排序、分页、查询
     */
    public function xiajia_query(){
        $xiajia_list = $this->xiajia_list();
        TPL::assign('money', $xiajia_list['list']);
        Tpl::assign('filter', $xiajia_list['filter']);
        Tpl::assign('record_count', $xiajia_list['record_count']);
        Tpl::assign('page_count', $xiajia_list['page_count']);
        make_json_result(Tpl::fetch('supplier_xiajia_list.htm'), '', 
            array('filter' => $xiajia_list['filter'], 'page_count' => $xiajia_list['page_count']));
    }

    /**
     * @return 平台下架佣金信息批量导出
     */
    public function export_supps_xiajia() {
        admin_priv('supplier_rebate');
        header("Content-type: application/vnd.ms-excel; charset=gbk");
        header("Content-Disposition: attachment; filename=rebate_list.xls");

        $export = "<table border='1'><tr><td colspan='2'>商家名称</td><td colspan='2'>下架商品总额（元）</td><td colspan='2'>下架佣金抽成总额（元）</td></tr>";

        $result = $this->xiajia_list();
        foreach ($result['list'] as $key => $val) {
            $export .= "<tr><td colspan='2'>" . $val['supplier_name'] . "</td><td colspan='2'>" . $val['fenxiao_price'] . "</td><td colspan='2'>-" . $val['pay_money'] . "</tr>";
        }
        $export .= "</table>";
        if (EC_CHARSET != 'gbk') {
            echo ecs_iconv(EC_CHARSET, 'gbk', $export) . "\t";
        } else {
            echo $export . "\t";
        }
    }

    /**
     * @return 商家下架佣金列表
     */
    public function xiajia_view() {
        /*商品变更时间*/
        $supplier_model = Model('supplier');
        $today['start'] = local_date('Y-m-d 00:00');
        $today['ends'] = local_date('Y-m-d 00:00', local_strtotime("+1 day"));
        $yestoday['start'] = local_date('Y-m-d 00:00', local_strtotime("-1 day"));
        $yestoday['ends'] = local_date('Y-m-d 00:00', local_strtotime("+1 day"));
        $week['start'] = local_date('Y-m-d 00:00', local_strtotime("-7 day"));
        $week['ends'] = local_date('Y-m-d 00:00', local_strtotime("+1 day"));
        $month['start'] = local_date('Y-m-d 00:00', local_strtotime("-1 month"));
        $month['ends'] = local_date('Y-m-d 00:00', local_strtotime("+1 day"));
        TPL::assign('today', $today);
        TPL::assign('yestoday', $yestoday);
        TPL::assign('week', $week);
        TPL::assign('month', $month);
        /*下架商品佣金详细记录列表*/
        $supplier_id = $_GET['suppid'];
        $res = $this->xiajia_view_list();
        TPL::assign('id', $supplier_id);
        Tpl::assign('filter',       $res['filter']);
        Tpl::assign('record_count', $res['record_count']);
        Tpl::assign('page_count',   $res['page_count']);
        $w['supplier_id'] = $supplier_id;
        $supplier_info = $supplier_model->select_supplier_info('*',$w);
        $supplier_name = $supplier_info['supplier_name'];                
        TPL::assign('supplier_name', $supplier_name);
        TPL::assign("res", $res['list']);
        TPL::assign('full_page', 1);
        TPL::assign('ur_here', '商家下架佣金信息记录'); 
        /*取得所有支付方式*/
        $pay_type = $supplier_model->get_supplier_order_list('pay_type', 'supplier_id = ' . $supplier_id, '', '');
        $str = '';
        foreach ($pay_type as $key => $value){
            if($key==0){
                $str.= $value['pay_type'];
            }else{
                $str.= ','.$value['pay_type'];
            }
        }
        $payment = Model('payment')->get_payment_list('*'," enabled = 1 AND pay_id in($str)",'pay_order');
        TPL::assign('payment', $payment);
        /*取得所有订单号*/
        $where5 = "supplier_id =  $supplier_id  ";
        $res5 = $supplier_model->get_supplier_order_list('distinct(o_sn)', $where5);
        TPL::assign('sn', $res5);
        TPL::display('supplier_xiajia_info.htm');
    }

    /**
     * @return 商家下架佣金列表排序、分页、查询
     */
    public function xiajia_view_query() {
        $res = $this->xiajia_view_list();
        TPL::assign('res', $res['list']);
        Tpl::assign('filter', $res['filter']);
        Tpl::assign('record_count', $res['record_count']);
        Tpl::assign('page_count', $res['page_count']);
        make_json_result(Tpl::fetch('supplier_xiajia_info.htm'), '', 
            array('filter' => $res['filter'], 'page_count' => $res['page_count']));
    }

    /**
     * @return 商家下架佣金信息批量导出
     */
    public function export_goods_xiajia() {
        admin_priv('supplier_rebate');
        header("Content-type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=rebate_list.xls");

        $export = "<table border='1'><tr><td colspan='2'>商家名称</td><td colspan='2'>账户变动时间</td><td colspan='2'>订单号</td><td colspan='2'>订单金额（元）</td><td colspan='2'>平台扣除佣金（元）</td><td colspan='2'>商家实际收入金额（元）</td><td colspan='2'>支付方式</td></tr>";

        $result = $this->xiajia_view_list();
        foreach ($result['list'] as $key => $val) {
            $export .= "<tr><td colspan='2'>" . $_GET['supplier_name'] . "</td><td colspan='2'>" . $val['add_time'] . "</td><td colspan='2' style='vnd.ms-excel.numberformat:@'>" . $val['o_sn'] . "</td><td colspan='2'>" . $val['fenxiao_price'] . "</td><td colspan='2'>-" . $val['pay_money'] . "</td><td colspan='2'>" . $val['add_money'] . "</td><td colspan='2'>" . $val['pay_type'] . "</td></tr>";
        }
        $export .= "</table>";
        if (EC_CHARSET != 'utf-8') {
            echo ecs_iconv(EC_CHARSET, 'utf-8', $export) . "\t";
        } else {
            echo $export . "\t";
        }
    }

    /**
     * @return 获取入驻商佣金列表信息
     * @return array
     */
    private function supplier_rebate_list() {
        $result = get_filter();
        $supplier_model = Model('supplier');
        $result = get_filter();
        if ($result === false) {
            $filter['suppid'] = (isset($_REQUEST['suppid']) && intval($_REQUEST['suppid']) > 0) ? intval($_REQUEST['suppid']) : 0;
            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'supplier_id' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'ASC' : trim($_REQUEST['sort_order']);
            $where = 'WHERE 1 ';
            $where0 = ' 1 ';
            if ($filter['suppid']) {
                $where .= ' and sr.supplier_id=' . $filter['suppid'];
                $where0 .= ' and supplier_id=' . $filter['suppid'];
            }
            $where.= ' GROUP BY sr.supplier_id';
            $where0.= ' GROUP BY supplier_id';
            /* 记录总数 */
            $supp_type = Model('suprebate')->get_supplier_rebate_log_list('COUNT(supplier_id)',$where0);
            $filter['record_count'] = count($supp_type);
            /* 分页大小 */
            $filter = page_and_size($filter);
            /* 查询 */
            $sql = "SELECT sum(sr.all_money) as all_money, sum(sr.rebate_money) as rebate_money, sum(sr.result_money) as result_money, sr.supplier_id, s.supplier_name, s.supplier_rebate " .
                    "FROM " . Model()->tablename("supplier_rebate_log") . " AS  sr left join " . Model()->tablename("supplier") . " AS s on sr.supplier_id=s.supplier_id 
                $where	 
                ORDER BY " . $filter['sort_by'] . " " . $filter['sort_order'];
            set_filter($filter, $sql);
        } else {
            $sql = $result['sql'];
            $filter = $result['filter'];
        }
        $list = array();
        $res = get_all_page($sql, $filter['page_size'], $filter['start']);
        foreach ($res as $row) {
            $list[] = $row;
        }
        $arr = array('result' => $list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

        return $arr;
    }

    /**
     * @return 入驻商详细佣金日志列表
     * @return array
     */
    private function supplier_rebate_view_list() {
        $result = get_filter();
        $supplier_model = Model('supplier');
        if ($result === false) {
            $filter['suppid'] = intval($_REQUEST['suppid']);

            $filter['start_time'] = empty($_REQUEST['start_time']) ? '' : (strpos($_REQUEST['start_time'], '-') > 0 ? local_strtotime($_REQUEST['start_time']) : $_REQUEST['start_time']);
            $filter['end_time'] = empty($_REQUEST['end_time']) ? '' : (strpos($_REQUEST['end_time'], '-') > 0 ? local_strtotime($_REQUEST['end_time']) : $_REQUEST['end_time']);

            $filter['payid'] = isset($_REQUEST['payid']) ? intval($_REQUEST['payid']) : -1;
            $filter['orderid'] = intval($_REQUEST['orderid']) > 0 ? intval($_REQUEST['orderid']) : 0;
            $filter['sort_by'] = empty($_REQUEST['sort_by']) ? ' sr.add_time' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? ' ASC' : trim($_REQUEST['sort_order']);
            $where = 'WHERE sr.supplier_id = ' . $filter['suppid'];
            $where0 = 'WHERE supplier_id = ' . $filter['suppid'];
            if ($filter['start_time']) {
                $where .= " and sr.add_time >= '" . $filter['start_time'] . "' ";
                $where0 .= " and add_time >= '" . $filter['start_time'] . "' ";
            }

            if ($filter['end_time']) {
                $where .= " and sr.add_time <= '" . $filter['end_time'] . "' ";
                $where0 .= " and add_time <= '" . $filter['end_time'] . "' ";
            }

            if ($filter['payid'] !== -1) {
                $where .= " and sr.pay_id = " . $filter['payid'];
                $where0 .= " and pay_id = " . $filter['payid'];
            }

            if ($filter['orderid']) {
                $where .= " and sr.order_id = " . $filter['orderid'];
                $where0 .= " and order_id = " . $filter['orderid'];
            }

            /* 记录总数 */
            $filter['record_count'] = Model('suprebate')->get_supplier_rebate_log_count($where0);
            /* 分页大小 */
            $filter = page_and_size($filter);
            /* 查询 */
            $sql = "SELECT sr.*, s.supplier_name, s.supplier_rebate " .
                    "FROM " . Model()->tablename("supplier_rebate_log") . " AS  sr left join " . Model()->tablename("supplier") . " AS s on sr.supplier_id=s.supplier_id 
                $where
                ORDER BY " . $filter['sort_by'] . " " . $filter['sort_order'];
           set_filter($filter, $sql);
        } else {
           $sql = $result['sql'];
           $filter = $result['filter'];
        }
        $list = array();
        $res = get_all_page($sql, $filter['page_size'], $filter['start']);
        foreach ($res as $row) {
            $row['add_time'] = local_date('Y-m-d H:i:s', $row['add_time']);
            $list[] = $row;
        }
        $arr = array('result' => $list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

        return $arr;
    }

    /**
     * @return 下架佣金统计列表
     * @return  array
     */
    private function xiajia_list() {
        $supplier_model = Model('supplier');        
        $result = get_filter();
        if ($result === false){
            $filter = array();
            $where = '';
            $filter['suppid'] = empty($_REQUEST['suppid']) ? '' : trim($_REQUEST['suppid']);
            if(!empty($filter['suppid'])){
                $where .= ' and supplier_id=' . $filter['suppid'];
            }
            $all = $supplier_model->get_supplier_order_list('supplier_id', 'onsale_type = 1'.$where,'',0,'supplier_id');
            $filter['record_count'] = count($all);
            $filter = page_and_size($filter);
            $sql = 'SELECT supplier_id '.'FROM ' .Model()->tablename('supplier_order'). ' WHERE 1 AND onsale_type = 1 '.$where;
            $filter['suppid'] = stripslashes($filter['suppid']);
            set_filter($filter, $sql);
        }else{
            $sql    = $result['sql'];
            $filter = $result['filter'];
        }
        $res = get_all_page($sql, $filter['page_size'], $filter['start']);
        /*查询入驻商名称*/
        foreach ($res as $k => $v) {
            $res1[$k]['supplier_id'] = $v['supplier_id'];
            $w['supplier_id'] = $v['supplier_id'];
            $r = $supplier_model->select_supplier_info('*',$w);
            $res1[$k]['supplier_name'] = $r['supplier_name'];
        }
        /*查询下架商品id*/
        foreach ($res1 as $k => $v1) {
            $id = $v1['supplier_id'];
            $res2 = $supplier_model->get_supplier_order_list('goods_id', 'supplier_id =' . $id);
            for ($i=0; $i < count($res2); $i++) { 
               $res1[$k]['goods_id'][] = $res2[$i]['goods_id']; 
            }
        }
        /*查询入驻商下架商品总金额和下架应付总佣金*/
        foreach ($res1 as $k => $v2) {
            $fenxiao_price = 0;
            $pay_money = 0;
            $str = implode(',',$v2['goods_id']);
            $res3 = $supplier_model->get_supplier_order_list('fenxiao_price,pay_money', 'goods_id in (' . $str . ') and o_status = 2');
            foreach ($res3 as $keys => $values) {
                $fenxiao_price += $values['fenxiao_price'];
                $pay_money += $values['pay_money'];
                $res1[$k]['fenxiao_price'] = $fenxiao_price;
                $res1[$k]['pay_money'] = $pay_money;                
            }                                  
        }
        /*查询下架商品总金额和下架应付总佣金*/
        $tot = array();
        foreach ($res1 as $k => $v2) {
            $tot['tot_fenxiao_price'] += $v2['fenxiao_price'];
            $tot['tot_pay_money'] += $v2['pay_money'];
        }
        $arr = array('list' => $res1,'tot'=>$tot, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
        return $arr;
       
    }

    /**
     * @return 下架佣金统计列表
     * @return  array
     */
    private function xiajia_view_list() {
        $supplier_model = Model('supplier');        
        $result = get_filter();
        if ($result === false){
            $filter = array();
            $where = '';
            $filter['suppid'] = empty($_REQUEST['suppid']) ? '' : trim($_REQUEST['suppid']);
            $filter['start_time'] = empty($_REQUEST['start_time']) ? '' : trim($_REQUEST['start_time']);
            $filter['end_time'] = empty($_REQUEST['end_time']) ? '' : trim($_REQUEST['end_time']);
            $filter['payid'] = isset($_POST['payid']) ? $_POST['payid'] : '';
            $filter['sn'] = isset($_REQUEST['sn']) ? $_REQUEST['sn'] : '';
            if ($filter['start_time'] != "") {
                $st = strtotime($filter['start_time']);
                if ($filter['end_time'] == "") {
                    $en = time();
                    $where .= "and  add_time between $st and $en";
                } else {
                    $en = strtotime($filter['end_time']);
                    $where .= "and  add_time between $st and $en";
                }
            } else {
                $where .= '';
            }            
            if ($filter['payid'] != '' && $filter['payid'] != 0) {
                $where .= "and pay_type = ".$filter['payid'];
            } else {
                $where .= '';
            }
            if ($filter['sn']!= '' && $filter['sn'] != 0) {
                $where .= "and o_sn = ".$filter['sn'];
            } else {
                $where .= '';
            }            
            $field = 'o_sn , add_time ,fenxiao_price , pay_money ,pay_type';
            $wheres = "supplier_id = ".$filter['suppid'] ." AND o_status = 2 $where";
            $all = $supplier_model->get_supplier_order_list($field, $wheres);
            $filter['record_count'] = count($all);
            $filter = page_and_size($filter);
            $sql = 'SELECT o_sn , add_time ,fenxiao_price , pay_money ,pay_type FROM ' .Model()->tablename('supplier_order'). " WHERE 1 AND $wheres ";
            set_filter($filter, $sql);
        }else{
            $sql    = $result['sql'];
            $filter = $result['filter'];
        }
        $res = get_all_page($sql, $filter['page_size'], $filter['start']);
        foreach ($res as $k => $v) {
            $res[$k]['add_time'] = date("Y-m-d H:i:s", $v['add_time']);
            $res1 = Model('payment')->get_payment_list('*','enabled = 1','pay_order');
            if ($res[$k]['pay_type'] == 0) {
                $res[$k]['pay_type'] = "无支付方式";
            }
            foreach ($res1 as $v1) {
                if ($v['pay_type'] == $v1['pay_id']) {
                    $res[$k]['pay_type'] = $v1['pay_name'];
                }
            }
        }
        foreach ($res as $k => $v2) {
            $fenxiao_price = $v2['fenxiao_price'];
            $pay_money = $v2['pay_money'];
            $add_money = $fenxiao_price - $pay_money;
            $res[$k]['add_money'] = $add_money;
        }
        $arr = array('list' => $res, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
        return $arr; 
    }
    
}


?>

