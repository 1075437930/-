<?php

/**
 * 淘玉 系统支付记录数据模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 系统支付记录数据信息增删改查count
 * $Id: lib_order_model.php 17217 2018-04-07  萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class pay_logModel extends Model{

	/**
     * @return 获取单条支付记录信息find
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @return  array  数组格式的返回结果
     */
    public function select_pay_log_info($field = '*', $where='',$order='' , $limit = 0) {
      $result = get_one_table_list('pay_log', $field, $where, $order, $limit, 'find');
      return $result;
    }

    /**
     * @return 添加新支付记录
     * @param array $insert 更新数据
     * @return keyid 返回添加成功以后id
     */
    public function insert_pay_log($insert) {
       return add_table_info('pay_log',$insert);
    }

    /**
     * @return 编辑更新支付记录信息
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_pay_log($param,$where) {
        return update_table_info('pay_log', $param, $where);
    }

    /**
     * @return 删除支付记录
     * @param $where  array/string  删除的条件
     * @return bool
     */
    public function delete_pay_log($where) {
        return delete_table_info('pay_log', $where);
    }
}