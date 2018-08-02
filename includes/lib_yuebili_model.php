<?php

/**
 * 淘玉php 月份比例模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * 月份比例模型
 * $Id: lib_yuebili.model.php 17217 2015-05-19 06:29:08Z 淘玉 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class yuebiliModel extends Model {

    /**
     * @return 新增月比例
     * @param  array $param 插入数据
     * @return bool
     */
    public function insert_capital_bili($param) {
        $result = add_table_info('capital_bili', $param);
        return $result;
    }

    /**
     * @return 删除月比例
     * @param  array/string  $where 删除条件
     * @return bool
     */
    public function delete_capital_bili($where) {
        $result = delete_table_info('capital_bili', $where);
        return $result;
    }

    /**
     * @return 更新月比例信息
     * @param  array $param 更新数据
     * @param  array/string $where 条件
     * @return bool
     */
    public function update_capital_bili_info($param,$where) {
        $result = update_table_info('capital_bili', $param, $where);
        return $result;
    }

    /**
     * @return  获取单个月比例信息find
     * @param   string $field 显示字段
     * @param   array/string $where 条件
     * @return  array 返回结果
     */
    public function select_capital_bili_info($field = '*', $where) {
        $result = get_one_table_list('capital_bili', $field, $where, '', '', 'find');
        return $result;
    }

    /**
     * @return 获取月比例信息select
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  str $order 排序条件
     * @param  str $limit 限制条件
     * @return array  数组格式的返回结果
     */
    public function get_capital_bili_list($field = '*', $where='',$order='' , $limit = 0){
        $result = get_one_table_list('capital_bili', $field, $where, $order, $limit, 'select');
        return $result;
    }

    /**
     * @return 查询记录个数
     * @param  string/array $where    查询条件
     * @return string
     */
    public function get_capital_bili_count($where) {
        $param = array();
        $param['table'] = 'capital_bili';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }

}