<?php

/**
 * 淘玉php 全站配置信息模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * 全站配置信息模型
 * $Id: lib_shop_config_model.php 17217 2018-04-29 06:29:08Z 淘玉 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class shop_configModel extends Model {

	/**
     * @return 更新配置信息
     * @param  $param  array 更新数据
     * @param  $where  条件
     * @return bool
     */
    public function update_shop_config($param, $where) {
        $res = update_table_info('shop_config', $param, $where);
        return $res;
    }

    /**
     * @return 获取单条配置信息find
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @return array 数组格式的返回结果
     */
    public function select_shop_config_info($field = '*', $where) {
        return get_one_table_list('shop_config', $field, $where, '', '', 'find');
    }

    /**
     * @return 获取多条配置信息
     * @param  str $field 搜索字段
     * @param  array/string $where 搜索条件
     * @param  str $order 排序条件
     * @param  str $limit 限制条件
     * @return array
     */
    public function get_shop_config_list($field, $where, $order='' , $limit = 0) {
        $result = get_one_table_list('shop_config', $field, $where, $order, $limit, 'select');
        return $result;
    }
}
