<?php

/**
 * 淘玉php 典藏组队模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * 典藏组队模型
 * $Id: lib_diancang_team.model.php 17217 2015-05-19 06:29:08Z 淘玉 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class diancang_teamModel extends Model {

    /**
     * @return 新增典藏组队
     * @param  array $param 插入数据
     * @return bool
     */
    public function insert_capital_team_stage($param) {
        $result = add_table_info('capital_team_stage', $param);
        return $result;
    }

    /**
     * @return 删除典藏组队
     * @param  array/string  $where 删除条件
     * @return bool
     */
    public function delete_capital_team_stage($where) {
        $result = delete_table_info('capital_team_stage', $where);
        return $result;
    }

    /**
     * @return 更新典藏组队信息
     * @param  array $param 更新数据
     * @param  array/string $where 条件
     * @return bool
     */
    public function update_capital_team_stage_info($param,$where) {
        $result = update_table_info('capital_team_stage', $param, $where);
        return $result;
    }

    /**
     * @return  获取单个典藏组队信息find
     * @param   string $field 显示字段
     * @param   array/string $where 条件
     * @return  array 返回结果
     */
    public function select_capital_team_stage_info($field = '*', $where) {
        $result = get_one_table_list('capital_team_stage', $field, $where, '', '', 'find');
        return $result;
    }

    /**
     * @return 获取典藏组队信息select
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  str $order 排序条件
     * @param  str $limit 限制条件
     * @return array  数组格式的返回结果
     */
    public function get_capital_team_stage_list($field = '*', $where='',$order='' , $limit = 0){
        $result = get_one_table_list('capital_team_stage', $field, $where, $order, $limit, 'select');
        return $result;
    }

    /**
     * @return 查询记录个数
     * @param  string/array $where    查询条件
     * @return string
     */
    public function get_capital_team_stage_count($where) {
        $param = array();
        $param['table'] = 'capital_team_stage';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }

}