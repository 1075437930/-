<?php

/**
 * 淘玉php 广告位置数据模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * 广告位置信息增删改查
 * $Id: master.model.php 17217 2015-05-19 06:29:08Z 淘玉 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class ad_positionModel extends Model {

    /**
     * @return 新增广告位
     * @param  array $param 插入数据
     * @return bool
     */
    public function insert_capital_brandarea($param) {
        $result = add_table_info('capital_brandarea', $param);
        return $result;
    }

    /**
     * @return 删除指定条件的广告位
     * @param  string/array $where  限制条件
     * @return string
     */
    public function delete_capital_brandarea($where) {
        $result = delete_table_info('capital_brandarea', $where);
        return $result;
    }

    /**
     * @return 更新广告位信息
     * @param  array $param 更新数据
     * @param  string/array $where 条件
     * @return bool
     */
    public function update_capital_brandarea_info($param,$where) {
        $result = update_table_info('capital_brandarea', $param, $where);
        return $result;
    }

    /**
     * @return  获取单个广告位信息find
     * @param   string $field 显示字段
     * @param   string/array $where 条件
     * @return  array 返回结果
     */
    public function select_capital_brandarea_info($field = '*', $where) {
        $result = get_one_table_list('capital_brandarea', $field, $where, '', '', 'find');
        return $result;
    }

    /**
     * @return 查询广告位个数
     * @param  string/array $where    查询条件
     * @return string
     */
    public function get_capital_brandarea_count($where) {
        $param = array();
        $param['table'] = 'capital_brandarea';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }
}