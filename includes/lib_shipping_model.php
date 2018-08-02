<?php

/**
 * 淘玉php 配送方式模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * 配送方式模型
 * $Id: lib_users_model.php 17217 2018-04-29 06:29:08Z 淘玉 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class shippingModel extends Model {

    /**
     * @return 更新支付方式信息
     * @param  array $param 更新数据
     * @param  string/array $where 条件
     * @return bool
     */
    public function update_shipping($param,$where) {
        $result = update_table_info('shipping', $param, $where);
        return $result;
    }

    /**
     * @return  查询配送方式,查多条,select
     * @param   string $field 需要查询字段
     * @param   string/array $where 查询条件
     * @param   string $order 排序参数
     * @param   string $limit  分页参数
     * @return  array
     */
    public function get_shipping_list($field, $where, $order='' , $limit = 0) {
        return get_one_table_list('shipping', $field, $where, $order, $limit, 'select');
    }

    /**
     * @return 查询配送方式,查一条find
     * @param  str $field 搜索字段
     * @param  str/array $where 搜索条件
     * @param  str $order 排序条件
     * @param  str $limit 限制条件shipping_area
     * @return array
     */
    public function select_shipping_info($field, $where, $order = '', $limit = '') {
        $wuliu = get_one_table_list('shipping', $field, $where, $order, $limit, 'find');
        return $wuliu;
    }

    /**
     * @return 删除配送方式
     * @param  array/string  $where 删除条件
     * @return bool
     */
    public function delete_shipping($where) {
        $result = delete_table_info('shipping', $where);
        return $result;
    }    

    /**
     * @return 新增配送方式的配送区域
     * @param  array $param 插入数据
     * @return bool
     */
    public function insert_shipping_area($param) {
        $result = add_table_info('shipping_area', $param);
        return $result;
    }

    /**
     * @return 查询送方式的配送区域,查一条find
     * @param  str $field 搜索字段
     * @param  str/array $where 搜索条件
     * @param  str $order 排序条件
     * @param  str $limit 限制条件shipping_area
     * @return array
     */
    public function select_shipping_area_info($field, $where, $order = '', $limit = '') {
        $wuliu = get_one_table_list('shipping_area', $field, $where, $order, $limit, 'find');
        return $wuliu;
    }

    /**
     * @return  查询配送方式的配送区域和配送费用,查多条,select
     * @param   string $field 需要查询字段
     * @param   string/array $where 查询条件
     * @param   string $order 排序参数
     * @param   string $limit  分页参数
     * @return  array
     */
    public function get_shipping_area_list($field, $where, $order='' , $limit = 0) {
        return get_one_table_list('shipping_area', $field, $where, $order, $limit, 'select');
    }

    /**
     * @return 删除配送方式的配送区域和配送费用
     * @param  array/string  $where 删除条件
     * @return bool
     */
    public function delete_shipping_area($where) {
        $result = delete_table_info('shipping_area', $where);
        return $result;
    }

    /**
     * @return 删除配送方式的配送区域和区域中间表信息
     * @param  array/string  $where 删除条件
     * @return bool
     */
    public function delete_area_region($where) {
        $result = delete_table_info('area_region', $where);
        return $result;
    }

    /**
     * @return 新增配送方式的配送区域中间表信息
     * @param  array $param 插入数据
     * @return bool
     */
    public function insert_area_region($param) {
        $result = add_table_info('area_region', $param);
        return $result;
    }

     /**
     * @return 查询配送方式的配送区域中间表信息,查一条find
     * @param  str $field 搜索字段
     * @param  str/array $where 搜索条件
     * @param  str $order 排序条件
     * @param  str $limit 限制条件shipping_area
     * @return array
     */
    public function select_area_region_info($field, $where, $order = '', $limit = '') {
        $wuliu = get_one_table_list('area_region', $field, $where, $order, $limit, 'find');
        return $wuliu;
    }  

}
