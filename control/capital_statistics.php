<?php

/**
 * 淘玉php 后台资金统计类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 后台资金统计类
 * $Id: capital_statistics.php 17217 2018年4月23日17:23:46 萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!cache');

class capital_statisticsControl extends BaseControl {

	/**
     * @return 构造函数方法
     */
    public function __construct() {
        Language::read('statistic,calendar,param');
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");       
    }

	/**
     * @return 资金统计
     */
    public function lists() {
        admin_priv('money_stats');
        if (isset($_REQUEST['stats_type'])) {
            if (isset($_REQUEST['stats_type'])) {
                $stats_type = $_REQUEST['stats_type'];
            }
            if ($stats_type == 0) {
                $start_date = strtotime($_REQUEST['date']);
                $end_date = strtotime($_REQUEST['date']);
                Tpl::assign('date', $_REQUEST['date']);
                Tpl::assign('week_num', '0');
                Tpl::assign('stats_type', '0');
            } elseif ($stats_type == 1) {
                $dropweek = $_REQUEST['dropweek'];
                $dropweek_arr = explode(' ', $dropweek);
                $start_date = strtotime($dropweek_arr[0]);
                $end_date = strtotime($dropweek_arr[1]);
                $week_num = $dropweek_arr[2];
                Tpl::assign('date', $_REQUEST['date']);
                Tpl::assign('week_num', $week_num);
                Tpl::assign('stats_type', '1');
            } else {
                $year = $_REQUEST['year'];
                $month = $_REQUEST['month'];
                $allday = date('t', strtotime("$year-$month"));
                $start_date = strtotime($year . '-' . $month . '-1');
                $end_date = strtotime($year . '-' . $month . '-' . $allday);
                Tpl::assign('date', $_REQUEST['date']);
                Tpl::assign('week_num', '0');
                Tpl::assign('stats_type', '2');
            }
            Tpl::assign('year', $_REQUEST['year']);
            Tpl::assign('month', $_REQUEST['month']);
        } else {
            /*默认按月统计*/
            $year = date('Y');
            $month = date('m');
            $allday = date('t');
            $start_date = strtotime($year . '-' . $month . '-1');
            $end_date = strtotime($year . '-' . $month . '-' . $allday);
            Tpl::assign('year', $year);
            Tpl::assign('month', $month);
            Tpl::assign('date', date('Y-m-d'));
            Tpl::assign('week_num', '0');
            Tpl::assign('stats_type', '2');
        }
        /*设置结束时间*/
        $end_date += 86399;
        /*获取数据*/
        $field = "FLOOR((count_time - $start_date) / (24 * 3600)) count_time, count_money ,count_taoyubi";
        $where = ' count_time >=' . $start_date . ' AND count_time <='. $end_date;
        $groupby = "FLOOR((count_time - $start_date) / (24 * 3600))";
        $orderby = 'count_time';
        $count = Model('count')->get_count_list($field, $where, $orderby, 0,$groupby);
        foreach ($count as $key => $value) {
            $count[$key]['count_time'] = date('Ymd', $start_date + $value['count_time'] * 86400);
        }
        $user_model = Model('users');
        $wheree = "yuangong=0";
        $total_money = $user_model->select_users_info('sum(user_money) as total_money',$wheree)['total_money'];
        $total_taoyumoney = $user_model->select_users_info('sum(taoyu_money) as total_taoyumoney',$wheree)['total_taoyumoney']/10;
        $total_capital = $total_money + ($total_taoyumoney);
        /*时间轴字符串*/
        $time_today_arr = 0;
        $time_yesterday_arr = 0;
        /*按周、月统计*/
        /*余额*/
        $time_arr = $this->get_date_arr($start_date, $end_date);
        foreach ($count as $value) {
            $time_arr[$value['count_time']] = $value['count_money'];
        }
        foreach ($time_arr as $key => $value) {
            $user_reg_time .= "'" . $key . "',";
            $user_money_count .= "'" . $value . "',";
        }
        /*淘玉币*/
        $time_arr = $this->get_date_arr($start_date, $end_date);
        foreach ($count as $value) {
            $time_arr[$value['count_time']] = $value['count_taoyubi'] / 10;
        }
        foreach ($time_arr as $key => $value) {
            $taoyu_money_count .= "'" . $value . "',";
        }               
        Tpl::assign('ur_here', '资金统计');
        Tpl::assign('user_reg_time', $user_reg_time);
        Tpl::assign('user_money_count', $user_money_count);
        Tpl::assign('taoyu_money_count', $taoyu_money_count);
        Tpl::assign('total_money', $total_money);
        Tpl::assign('total_taoyumoney', $total_taoyumoney);
        Tpl::assign('total_capital', $total_capital);
        Tpl::display('capital_statistics.htm');
    }

    /**
     * @return 取得搜索范围内的日期并赋初始值
     * @param  str $dt_end 开始日期
     * @param  str $dt_end 截止日期
     */
    private function get_date_arr($dt_start, $dt_end){
        $date_arr = array();
        do {
            // 将 Timestamp 转成 ISO Date 输出
            $date_arr[date('Ymd', $dt_start)] = 0;
            // 重复 Timestamp + 1 天(86400), 直至大于结束日期中止
        } while (($dt_start += 86400) <= $dt_end);
        return $date_arr;
    }

}