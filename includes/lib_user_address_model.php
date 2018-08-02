<?php

/**
 * 淘玉php 收货人信息数据模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * 收货人信息增删改查
 * $Id: lib_article_model.php 17217 2015-05-19 06:29:08Z 淘玉 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class user_addressModel extends Model {

	/**
     * @return 多条收货人的信息列表select
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @param string $order 排序参数
     * @param string $limit 分页参数
     * @return array    二维数组
     */
    public function get_user_address_list($field, $where, $order='' , $limit = 0){
        return get_one_table_list('user_address', $field, $where, $order, $limit, 'select');
    }

    /**
     * @return 单条收货人的信息find
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @param string $order 排序参数
     * @param string $limit 分页参数
     * @return array    二维数组
     */
    public function select_user_address_info($field, $where, $order='' , $limit = 0){
        return get_one_table_list('user_address', $field, $where, $order, $limit, 'select');
    }

    /**
     * @return 删除收货人的信息
     * @param $where  array/string  删除的条件
     * @return bool
     */
    public function delete_user_address($where) {
        $deladdre = delete_table_info('user_address', $where);
        return $deladdre;
    }


}