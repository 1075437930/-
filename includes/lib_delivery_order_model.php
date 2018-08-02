<?php

/**
 * 淘玉php 发货模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * 发货模型
 * $Id: lib_users_model.php 17217 2018-04-29 06:29:08Z 淘玉 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class delivery_orderModel extends Model {

    /**
     * @return  获取单条订单发货单信息find
     * @param   string $field 显示字段
     * @param   string/array $where 条件
     * @return  array 返回结果
     */
    public function select_delivery_order_info($field = '*', $where='') {
        $result = get_one_table_list('delivery_order', $field, $where, '', '', 'find');
        return $result;
    }

    /**
     * @return 编辑更新订单发货单信息
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_delivery_order($param,$where) {
        return update_table_info('delivery_order', $param, $where);
    }

    /**
     * @return  获取多条订单发货单信息select
     * @param   string $field 显示字段
     * @param   string/array $where 条件
     * @return  array 返回结果
     */
    public function get_delivery_order_list($field='*', $where='', $order = '', $limit = 0) {
        $result = get_one_table_list('delivery_order', $field, $where, '', '', 'select');
        return $result;
    }

    /**
     * @return 删除订单发货单信息
     * @param $where  array/string  删除的条件
     * @return bool
     */
    public function delete_delivery_order($where) {
        return delete_table_info('delivery_order', $where);
    }

    /**
     * @return 新增订单发货单
     * @param  array $param 插入数据
     * @return bool
     */
    public function insert_delivery_order($param) {
        $result = add_table_info('delivery_order', $param);
        return $result;
    }

    /**
     * @return 查询发货单总个数count 
     * @param string/array $where    查询条件
     * @return string
     */
    public function get_delivery_order_count($where) {
        $param = array();
        $param['table'] = 'delivery_order';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }
}
