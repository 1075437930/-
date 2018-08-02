<?php

/**
 * 淘玉php 支付方式数据模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * 支付方式信息增删改查
 * $Id: master.model.php 17217 2015-05-19 06:29:08Z 淘玉 $
 */
defined('TaoyuShop') or exit('Access Invalid!');
class PaymentModel extends Model {
   
    /**
     * @return 更新支付方式信息
     * @param  array $param 更新数据
     * @param  string/array $where 条件
     * @return bool
     */
    public function update_payment($param,$where) {
        $result = update_table_info('payment', $param, $where);
        return $result;
    }

    /**
     * @return  获取支付方式信息find
     * @param   string $field 需要查询字段
     * @param   string/array $where 查询条件
     * @param   string $order 排序参数
     * @param   string $limit 分页参数
     * @return  array 数组格式的返回结果
     */
    public function select_payment_info($field, $where, $order='' , $limit = 0) {
        return get_one_table_list('payment', $field, $where, $order, $limit, 'find');
    }

    /**
     * 获得所有有效的支付方式
     * @param   string $field 需要查询字段
     * @param   string/array $where 查询条件
     * @param   string $order 排序参数
     * @param   string $limit  分页参数
     * @return  array
     */
    public function get_payment_list($field, $where, $order='' , $limit = 0) {
        return get_one_table_list('payment', $field, $where, $order, $limit, 'select');
    }

    /**
     * @return 查询支付方式个数
     * @param  string/array $where    查询条件
     * @return string
     */
    public function get_payment_count($where) {
        $param = array();
        $param['table'] = 'payment';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }

}
