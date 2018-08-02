<?php

/**
 * 淘玉php 典藏标签模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * 典藏标签模型
 * $Id: lib_diancang_tagods.model.php 17217 2015-05-19 06:29:08Z 淘玉 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class diancang_tagodsModel extends Model {

    /**********典藏标签**********/

    /**
     * @return 新增典藏标签
     * @param  array $param 插入数据
     * @return bool
     */
    public function insert_capital_tags($param) {
        $result = add_table_info('capital_tags', $param);
        return $result;
    }

    /**
     * @return 删除典藏标签
     * @param  array/string  $where 删除条件
     * @return bool
     */
    public function delete_capital_tags($where) {
        $result = delete_table_info('capital_tags', $where);
        return $result;
    }

    /**
     * @return 更新典藏标签信息
     * @param  array $param 更新数据
     * @param  array/string $where 条件
     * @return bool
     */
    public function update_capital_tags_info($param,$where) {
        $result = update_table_info('capital_tags', $param, $where);
        return $result;
    }

    /**
     * @return  获取单个典藏标签信息find
     * @param   string $field 显示字段
     * @param   array/string $where 条件
     * @return  array 返回结果
     */
    public function select_capital_tags_info($field = '*', $where) {
        $result = get_one_table_list('capital_tags', $field, $where, '', '', 'find');
        return $result;
    }

    /**
     * @return 获取典藏标签信息select
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  str $order 排序条件
     * @param  str $limit 限制条件
     * @return array  数组格式的返回结果
     */
    public function get_capital_tags_list($field = '*', $where='',$order='' , $limit = 0){
        $result = get_one_table_list('capital_tags', $field, $where, $order, $limit, 'select');
        return $result;
    }

    /**
     * @return 查询典藏标签记录个数
     * @param  string/array $where    查询条件
     * @return string
     */
    public function get_capital_tags_count($where) {
        $param = array();
        $param['table'] = 'capital_tags';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }

    /**********典藏商品标签**********/

    /**
     * @return 新增典藏商品标签
     * @param  array $param 插入数据
     * @return bool
     */
    public function insert_capital_tagods($param) {
        $result = add_table_info('capital_tagods', $param);
        return $result;
    }

    /**
     * @return 删除典藏商品标签
     * @param  array/string  $where 删除条件
     * @return bool
     */
    public function delete_capital_tagods($where) {
        $result = delete_table_info('capital_tagods', $where);
        return $result;
    }

    /**
     * @return 更新典藏商品标签信息
     * @param  array $param 更新数据
     * @param  array/string $where 条件
     * @return bool
     */
    public function update_capital_tagods_info($param,$where) {
        $result = update_table_info('capital_tagods', $param, $where);
        return $result;
    }

    /**
     * @return  获取单个典藏商品标签信息find
     * @param   string $field 显示字段
     * @param   array/string $where 条件
     * @return  array 返回结果
     */
    public function select_capital_tagods_info($field = '*', $where) {
        $result = get_one_table_list('capital_tagods', $field, $where, '', '', 'find');
        return $result;
    }

    /**
     * @return 获取典藏商品标签信息select
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  str $order 排序条件
     * @param  str $limit 限制条件
     * @return array  数组格式的返回结果
     */
    public function get_capital_tagods_list($field = '*', $where='',$order='' , $limit = 0){
        $result = get_one_table_list('capital_tagods', $field, $where, $order, $limit, 'select');
        return $result;
    }

    /**
     * @return 查询典藏商品标签记录个数
     * @param  string/array $where    查询条件
     * @return string
     */
    public function get_capital_tagods_count($where) {
        $param = array();
        $param['table'] = 'capital_tagods';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }

}