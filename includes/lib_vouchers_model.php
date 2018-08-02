<?php

/**
 * 淘玉php 代金券管理数据模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萧瑟 $
 * 代金券管理数据模型
 * $Id: lib_vouchers_model.php 17217 2018-05-5 :29:08Z 萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class vouchersModel extends Model {

    /**
     * @return 获取代金券记录数
     * @param string/array $where    查询条件
     * @return string
     */
    public function get_capital_point_count($where) {
        $param = array();
        $param['table'] = 'capital_point';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }

    /**
     * @return 获取对应代金券
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @return	array 数组格式的返回结果
     */
    public function select_capital_point_info($field = '*', $where) {
        return get_one_table_list('capital_point', $field, $where, '', '', 'find');
    }

    /**
     * @return 获取所有代金券
     * @return array
     */
    public function get_capital_point_list($field = '*', $where = '', $order = '', $limit = 0) {
        return get_one_table_list('capital_point', $field, $where, '', '', 'select');
    }

    /**
     * @return 
     * @param $types  string  
     * @return array
     */
    public function get_capital_canshu_list($field = '*', $where = '', $order = '', $limit = 0) {
        return get_one_table_list('capital_canshu', $field, $where, '', '', 'select');
    }

    /**
     * @return 获取固定参数键值对
     * @param $where  string  
     * @return array
     */
    public function select_capital_canshu_info($field = '*', $where) {
        return get_one_table_list('capital_canshu', $field, $where, '', '', 'find');
    }

}
