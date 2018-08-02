<?php

/**
 * 淘玉php 订单公共方法
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * 公共方法
 * $Id: index.php 17217 2015-05-19 06:29:08Z 淘玉 $
 */
defined('TaoyuShop') or exit('Access Invalid!index');
                    
	/**
	 * @return  得到新发货单号
	 * @return  string
	 */
	function get_delivery_sn(){
	    /* 选择一个随机的方案 */
	    mt_srand((double) microtime() * 1000000);
	    return date('YmdHi') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
	}

    /**
     * @return  商品库存增与减，货品(products表)库存增与减
     * @param   int    $good_id         商品ID
     * @param   int    $product_id      货品ID
     * @param   int    $number          增减数量，默认0；
     * @return  bool   true，成功；false，失败；
     */
    function change_goods_storage($good_id, $product_id, $number = 0){
        if ($number == 0){
            return true; // 值为0即不做、增减操作，返回true
        }  
        if (empty($good_id) || empty($number)){
            return false;
        }  
        /* 处理货品库存 */
        $where = "goods_id = '$good_id'";
        $products_query = true;
        if($number > 0){
        	if (!empty($product_id)){
	            $wheres = $where." AND product_id = '$product_id'";
	            $param['product_number'] = $number;        
	            $products_query = update_table_original_field('products', $wheres, $param);
	        }
	        $params['goods_number'] = $number;
	        $query = update_table_original_field('goods', $where, $params);
	        if ($query && $products_query){
	            return true;
	        }else{
	            return false;
	        }
        }else{
        	$number = abs($number);
        	if (!empty($product_id)){
	            $wheres = $where." AND product_id = '$product_id'";
	            $param['product_number'] = $number;        
	            $products_query = update_table_setDec('products', $wheres, $param);
	        }
	        $params['goods_number'] = $number;
	        $query = update_table_setDec('goods', $where, $params);
	        if ($query && $products_query){
	            return true;
	        }else{
	            return false;
	        }
        }
        
    }

    /**
     * @return  改变订单中商品库存
     * @param   int $order_id 订单号
     * @param   bool $is_dec 是否减少库存
     * @param   bool $storage 减库存的时机，1，下订单时；0，发货时；
     * @return  bool   true，成功；false，失败
     */
    function change_order_goods_storage($order_id, $is_dec = true, $storage = 0) {
        /* 查询订单商品信息 */
        $where = "order_id = '$order_id' AND is_real = 1";
        switch ($storage) {
            case 0 :
                $field = "goods_id, SUM(send_number) AS num, MAX(extension_code) AS extension_code, product_id";
                $res = get_one_table_list('order_goods', $field, $where, '','', 'select','goods_id, product_id');
                break;

            case 1 :
                $field = "goods_id, SUM(goods_number) AS num, MAX(extension_code) AS extension_code, product_id";
                $res = get_one_table_list('order_goods', $field, $where, '','', 'select','goods_id, product_id');
                break;
        }

        foreach ($res as $row) {
            if ($is_dec) {
                $result = change_goods_storage($row['goods_id'], $row['product_id'], -$row['num']);
                return $result;
            } else {
                $result = change_goods_storage($row['goods_id'], $row['product_id'], $row['num']);
                return $result;
            }
        }

    }

    
        
    
    

    

    
   

    






