<?php

/**
 * 淘玉php 广告数据模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * 广告信息增删改查
 * $Id: lib_ads_model.php 17217 2015-05-19 06:29:08Z 淘玉 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class adsModel extends Model {

    /**
     * @return 新增广告
     * @param  array $param 插入数据
     * @return bool
     */
    public function insert_capital_brand($param) {
        $result = add_table_info('capital_brand', $param);
        return $result;
    }

    /**
     * @return 删除广告
     * @param  array/string  $where 删除条件
     * @return bool
     */
    public function delete_capital_brand($where) {
        $result = delete_table_info('capital_brand', $where);
        return $result;
    }

    /**
     * @return 更新广告信息
     * @param  array $param 更新数据
     * @param  array/string $where 条件
     * @return bool
     */
    public function update_capital_brand_info($param,$where) {
        $result = update_table_info('capital_brand', $param, $where);
        return $result;
    }
    /**
     * @return 广告列表select
     * @param $field  str 搜索字段
     * @param $where  str/array 搜索条件
     * @param $order  str 排序条件
     * @param $limit  str 限制条件
     */
    public function get_capital_brand_list($field='*',$where,$order='' , $limit = 0) {
        $result = get_one_table_list('capital_brand', $field, $where, $order, $limit, 'select');
        return $result;
    }
    /**
     * @return  获取单个广告信息find
     * @param   string $field 显示字段
     * @param   array/string $where 条件
     * @return  array 返回结果
     */
    public function select_capital_brand_info($field = '*', $where='') {
        $result = get_one_table_list('capital_brand', $field, $where, '', '', 'find');
        return $result;
    }

    /**
     * @return 查询记录个数
     * @param  string/array $where    查询条件
     * @return string
     */
    public function get_capital_brand_count($where='') {
        $param = array();
        $param['table'] = 'capital_brand';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }

    /**
     * @return 获取所有广告位
     * @param  str $field 搜索字段
     * @param  str/array $where 搜索条件
     * @param  str $order 排序条件
     * @param  str $limit 限制条件
     * @return array
     */
    public function get_capital_brandarea_list($field, $where='', $order='' , $limit = 0) {
        $result = get_one_table_list('capital_brandarea', $field, $where, $order, $limit, 'select');
        return $result;
    }
}