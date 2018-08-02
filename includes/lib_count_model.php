<?php
/**
 * 淘玉 数据统计数据模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萧瑟 $
 * 数据统计相关
 * $Id: lib_statistics_model.php 17217 2018-04-07  萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class countModel{

	/**
     * @return 获取所有符合条件的记录
     * @param  str $field 搜索字段
     * @param  str/array $where 搜索条件
     * @param  str $order 排序条件
     * @param  str $limit 限制条件
     * @return array
     */
    public function get_count_list($field, $where, $order='' , $limit = 0,$group='') {
        $result = get_one_table_list('count', $field, $where, $order, $limit, 'select',$group);
        return $result;
    }
}