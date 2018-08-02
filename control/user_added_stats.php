<?php

/**
 * 淘玉php 后台新增会员统计类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 后台报表统计类
 * $Id: article.php 17217 2018年4月23日17:23:46 萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!cache');

class user_added_statsControl extends BaseControl {
    
    /**
     * @return 构造函数方法
     */
    public function __construct() {
        Language::read('statistic,calendar,param');//载入语言包
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }
    
    /**
     * @return 新增会员统计
     */
    public function lists() {
    	admin_priv('users_stats');
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
		/*当日新增会员数*/
	    $field = "FLOOR((reg_time - $start_date) / (24 * 3600)) reg_time, COUNT(*) user_count";
	    $where = 'reg_time >=' . $start_date . ' AND reg_time <='. $end_date;
	    $groupby = "FLOOR((reg_time - $start_date) / (24 * 3600))";
	    $orderby = 'reg_time';
	    $user_added_today = Model('users')->get_users_list($field, $where, $orderby,0,$groupby);
	    foreach ($user_added_today as $key => $value) {
	        $user_added_today[$key]['reg_time'] = date('Ymd', $start_date + $value['reg_time'] * 86400);
	    }

	    /*前日新增会员数*/	    
	    $fields = "FLOOR((reg_time + 86400 - $start_date) / (24 * 3600)) reg_time, COUNT(*) user_count";
	    $wheres = 'reg_time >=' . ($start_date- 86399) . ' AND reg_time <='. ($end_date - 86399);
	    $groupbys = "FLOOR((reg_time + 86400 - $start_date) / (24 * 3600))";
	    $orderbys = 'reg_time';
	    $user_added_yesterday = Model('users')->get_users_list($fields, $wheres, $orderbys,0,$groupbys);
	    /* echo '<pre/>';
	    var_dump($user_added_yesterday);
	    var_dump($re);*/
	    foreach ($user_added_yesterday as $key => $value) {
	        $user_added_yesterday[$key]['reg_time'] = date('Ymd', $start_date + $value['reg_time'] * 86400);
	    }

	    /*时间轴字符串*/
	    $time_today_arr = 0;
	    $time_yesterday_arr = 0;
	    /*按日统计*/
	    if (isset($stats_type) && $stats_type == 0) {
	        $time_today_arr = date('Ymd');
	        $time_yesterday_arr = date('Ymd', strtotime('-1 day'));
	    } else {
	    	/*按周、月统计*/
	        /*取得日期、赋初始值*/
	        $time_arr = $this->get_date_arr($start_date, $end_date);
	        foreach ($user_added_today as $value) {
	            $time_arr[$value['reg_time']] = $value['user_count'];
	        }
	        foreach ($time_arr as $key => $value) {
	            $user_reg_time .= "'" . $key . "',";
	            $user_today_count .= "'" . $value . "',";
	        }
	        /*前日*/
	        $time_arr = $this->get_date_arr($start_date, $end_date);
	        foreach ($user_added_yesterday as $value) {
	            $time_arr[$value['reg_time']] = $value['user_count'];
	        }
	        foreach ($time_arr as $key => $value) {
	            $user_yesterday_count .= "'" . $value . "',";
	        }
	    }

	    Tpl::assign('ur_here', '会员统计');
	    /*日期字符串*/
	    Tpl::assign('user_reg_time', $user_reg_time);
	    /*当日新增会员数字符串*/
	    Tpl::assign('user_today_count', $user_today_count);
	    /*前日新增会员数字符串*/
	    Tpl::assign('user_yesterday_count', $user_yesterday_count);		
    	Tpl::display('user_added_stats.htm');
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