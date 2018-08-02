<?php

/**
 * 淘玉php 客服模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * 客服模型
 * $Id: lib_customer_model.php 17217 2018-04-29 06:29:08Z 淘玉 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class customerModel extends Model {

    /**
     * @return  获取单条客服信息find
     * @param   string $field 显示字段
     * @param   string/array $where 条件
     * @return  array 返回结果
     */
    public function select_chat_customer_info($field = '*', $where='') {
        $result = get_one_table_list('chat_customer', $field, $where, '', '', 'find');
        return $result;
    }

    /**
     * @return 编辑更新客服信息
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_chat_customer($param,$where) {
        return update_table_info('chat_customer', $param, $where);
    }

    /**
     * @return  获取多条客服信息select
     * @param   string $field 显示字段
     * @param   string/array $where 条件
     * @return  array 返回结果
     */
    public function get_chat_customer_list($field='*', $where='', $order = '', $limit = 0) {
        $result = get_one_table_list('chat_customer', $field, $where, '', '', 'select');
        return $result;
    }

    /**
     * @return 删除客服信息
     * @param $where  array/string  删除的条件
     * @return bool
     */
    public function delete_chat_customer($where) {
        return delete_table_info('chat_customer', $where);
    }

    /**
     * @return 新增客服
     * @param  array $param 插入数据
     * @return bool
     */
    public function insert_chat_customer($param) {
        $result = add_table_info('chat_customer', $param);
        return $result;
    }

    /**
     * @return 查询发货单总个数count 
     * @param string/array $where    查询条件
     * @return string
     */
    public function get_chat_customer_count($where) {
        $param = array();
        $param['table'] = 'chat_customer';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }
}