<?php

/**
 * 淘玉php 后台会员区域分布统计类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 后台报表统计类
 * $Id: article.php 17217 2018年4月23日17:23:46 萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!cache');

class user_area_statsControl extends BaseControl {

	/**
     * @return 构造函数方法
     */
    public function __construct() {
        Language::read('statistic,calendar,param');//载入语言包
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
        $this->area_type = isset($_REQUEST['area_type']) ? $_REQUEST['area_type'] : 0;
        $this->province = isset($_REQUEST['province']) ? $_REQUEST['province'] : 0;
        $this->city = isset($_REQUEST['city']) ? $_REQUEST['city'] : 0;
    }

	/**
     * @return 会员区域分布列表
     */
    public function lists() {
    	admin_priv('users_stats');

	    // 地域下拉框选项
	    $province_list = Model('region')->get_regoin_list('*','parent_id=' . C('shop_country'));
	    Tpl::assign('ur_here', '会员统计');
	    Tpl::assign('full_page', 1);
	    Tpl::assign('province_list', $province_list);

	    $result = $this->get_result_list($this->area_type, $this->province, $this->city);
	    Tpl::assign('result_list', $result['item']);
	    Tpl::assign('filter', $result['filter']);
	    Tpl::assign('record_count', $result['record_count']);
	    Tpl::assign('page_count', $result['page_count']);

	    Tpl::display('user_area_stats.htm');
    }

    /**
     * @return 会员区域分布列表排序、分页、查询
     */
    public function query() {
    	admin_priv('users_stats');
		$result_list = $this->get_result_list($this->area_type, $this->province, $this->city);;
	    Tpl::assign('result_list', $result_list['item']);
	    Tpl::assign('filter', $result_list['filter']);
	    Tpl::assign('record_count', $result_list['record_count']);
	    Tpl::assign('page_count', $result_list['page_count']);
	    $sort_flag  = sort_flag($result_list['filter']);
	    Tpl::assign($sort_flag['tag'], $sort_flag['img']);
	    make_json_result(Tpl::fetch('user_area_stats.htm'), '',
	        array('filter' => $result_list['filter'], 'page_count' => $result_list['page_count']));	    
    }

    /**
     * @return 批量导出数据
     */
    public function export() {
    	admin_priv('users_stats');
	    // 查询条件
	    $where = ' (o.pay_id = 6 AND o.shipping_status = 2) OR (o.pay_id <> 6 AND o.pay_status = 2) and o.province <>"" and o.province <>0';
	    
	    if ($this->area_type == 0) {
	    	/*按省统计*/
	    	$where = ' (pay_id = 6 AND shipping_status = 2) OR (pay_id <> 6 AND pay_status = 2) and province <>"" and province <>0';
	    	$result = Model('order')->get_order_info_list('COUNT(*) order_count, SUM(goods_amount) goods_amount, province, 0 city, 0 district',$where,'order_count DESC',0,'province');
	    	foreach ($result as $key => $value) {
	    		$result[$key]['province_name'] = Model('region')->select_region_info('region_name','region_id='.$value['province'])['region_name'];
	    	}	        
	    } elseif ($this->area_type == 1) {
	    	/*按市统计*/
	    	$where = ' (pay_id = 6 AND shipping_status = 2) OR (pay_id <> 6 AND pay_status = 2) and province = ' . $this->province;
	    	$result = Model('order')->get_order_info_list('COUNT(*) order_count, SUM(goods_amount) goods_amount, province,city, 0 district',$where,'order_count DESC',0,'province,city');
	    	foreach ($result as $key => $value) {
	    		$result[$key]['province_name'] = Model('region')->select_region_info('region_name','region_id='.$value['province'])['region_name'];
	    		$result[$key]['city_name'] = Model('region')->select_region_info('region_name','region_id='.$value['city'])['region_name'];
	    	}	     	        
	    } 	    
	    /*查询下单会员数*/
	    foreach ($result as $key => $value) {
	        if ($filter['area_type'] == 0) {
	        	/*按省统计*/
	        	$where = ' (pay_id = 6 AND shipping_status = 2) OR (pay_id <> 6 AND pay_status = 2) and province <>"" and province <>0';
	        	$res = Model('order')->get_order_info_list('DISTINCT user_id',$where . ' AND province = ' . $value['province']);
	            $user_count = count($res);
	        } elseif ($filter['area_type'] == 1) {
	        	/*按市统计*/
	        	$where = ' (pay_id = 6 AND shipping_status = 2) OR (pay_id <> 6 AND pay_status = 2)';
	        	$res = Model('order')->get_order_info_list('DISTINCT user_id',$where . ' AND city = ' . $value['city']);
	            $user_count = count($res);	           
	        } else {
	        	/*按区统计*/
	        	$where = ' (pay_id = 6 AND shipping_status = 2) OR (pay_id <> 6 AND pay_status = 2)';
	        	$res = Model('order')->get_order_info_list('DISTINCT user_id',$where . ' AND district = ' . $value['district']);	            
	        }

	        $result[$key]['user_count'] = $user_count;
	    }
	    /*导出到表格*/
	    $title = array(
	    	'省',
			'市',
			'区/县',
			'下单会员数',
			'下单金额',
			'下单量'
		);
		$tmp = array();
		foreach ($result as $key => $value) {
			$tmp[$key]['province_name'] = $value['province_name'];
			$tmp[$key]['city_name'] = $value['city_name'];
			$tmp[$key]['district_name'] = $value['district_name'];
			$tmp[$key]['user_count'] = $value['user_count'];
			$tmp[$key]['goods_amount'] = $value['goods_amount'];
			$tmp[$key]['order_count'] = $value['order_count'];
		}
	    exportExcel($tmp,$title,'区域分布');	    
    }    

    /**
	 * 分页获取区域分布列表
	 *
	 * @return array
	 */
	function get_result_list($area_type, $province, $city){
	    $result = get_filter();
	    if ($result === false) {
	        $filter = array();
	        $filter['area_type'] = empty($_REQUEST['area_type']) ? $area_type : $_REQUEST['area_type'];
	        $filter['province'] = empty($_REQUEST['province']) ? $province : $_REQUEST['province'];
	        $filter['city'] = empty($_REQUEST['city']) ? $city : $_REQUEST['city'];

	        $where = ' (pay_id = 6 AND shipping_status = 2) OR (pay_id <> 6 AND pay_status = 2) and province <>"" and province <>0';	        
	        if ($filter['area_type'] == 0) {
	        	/*按省统计*/
	            $where .= " ";
	            $groupby = 'province';
	        } elseif ($filter['area_type'] == 1) {
	        	/*按市统计*/
	            $where .= "  AND province = " . $filter['province'];
				$groupby = 'province, city';
	        } else {
	        	/*按区统计*/
	            $where .= "   AND province = " . $filter['province']. " AND city = " . $filter['city'] ;
				$groupby = 'province, city, district';
	        }
	        $results = Model('order')->get_order_info_list('province',$where,'',0,$groupby);
	        $filter['record_count'] = count($results);

	        /*分页大小*/
	        $filter = page_and_size($filter);

	        
	        if ($filter['area_type'] == 0) {
	        	/*按省统计*/
	        	$wheres = ' (o.pay_id = 6 AND o.shipping_status = 2) OR (o.pay_id <> 6 AND o.pay_status = 2) and o.province <>"" and o.province <>0';
	            $sql = "SELECT COUNT(*) order_count, SUM(o.goods_amount) goods_amount, o.province, 0 city, 0 district, (SELECT r.region_name FROM "
	                . Model()->tablename('region') . ' r WHERE r.region_id = o.province) province_name FROM '
	                . Model()->tablename('order_info') . ' o WHERE' . $wheres . ' GROUP BY o.province ORDER BY order_count DESC';
	        } elseif ($filter['area_type'] == 1) {
	        	/*按市统计*/
	        	$wheres = ' (o.pay_id = 6 AND o.shipping_status = 2) OR (o.pay_id <> 6 AND o.pay_status = 2)';
	            $sql = "SELECT COUNT(*) order_count, SUM(o.goods_amount) goods_amount, o.province, o.city, 0 district, (SELECT r.region_name FROM "
	                . Model()->tablename('region') . ' r WHERE r.region_id = o.province) province_name, (SELECT r.region_name FROM '
	                . Model()->tablename('region') . ' r WHERE r.region_id = o.city) city_name FROM '
	                . Model()->tablename('order_info') . ' o WHERE' . $wheres
	                . ' AND o.province = ' . $filter['province'] . ' GROUP BY o.province, o.city ORDER BY order_count DESC';
	        } 
	        set_filter($filter, $sql);
	    } else {
	        $sql = $result['sql'];
	        $filter = $result['filter'];
	    }
	    $list = get_all_page($sql, $filter['page_size'], $filter['start']);
	    /*下单会员数*/
	    foreach ($list as $key => $value) {	       
	        if ($filter['area_type'] == 0) {
	        	/*按省统计*/
	        	$res = Model('order')->get_order_info_list('DISTINCT user_id',$where . ' AND province = ' . $value['province']);
	            $user_count = count($res);
	        } elseif ($filter['area_type'] == 1) {
	        	/*按市统计*/
	        	$where = ' (pay_id = 6 AND shipping_status = 2) OR (pay_id <> 6 AND pay_status = 2)';
	        	$res = Model('order')->get_order_info_list('DISTINCT user_id',$where . ' AND city = ' . $value['city']);
	            $user_count = count($res);	           
	        } else {
	        	/*按区统计*/
	        	$where = ' (pay_id = 6 AND shipping_status = 2) OR (pay_id <> 6 AND pay_status = 2)';
	        	$region_id = $value['district'];
	        	if($region_id){
	        		$res = Model('order')->get_order_info_list('DISTINCT user_id',$where . ' AND district = ' . $region_id);
     				$user_count = count($res);
	        	}
     				            
	        }

	        $list[$key]['user_count'] = $user_count;
	    }

	    $arr = array(
	        'item' => $list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']
	    );
	    return $arr;
	}























}