<?php

/**
 * 淘玉php 计划任务产品或文章自动更新model类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 计划任务产品或文章自动更新model类
 * $Id: lib_automanage_model.php 17217 2018-05-4 :29:08Z 萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class automanageModel extends Model {

    /**
     * @return 获取自动发布文章或者产品记录数 
     * @param string/array $where    查询条件
     * @return string
     */
    function get_auto_manage_count($where) {
        $param = array();
        $param['table'] = 'auto_manage';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }

    /**
     * @return 自动发布文章或者产品记录数 列表select
     * @param $where  str/array 搜索条件
     * @param $limit  str 限制条件
     * @param $order  str 排序条件
     * @param $field  str 搜索字段
     */
    public function get_auto_manage_list($field='*',$where,$order='' , $limit = 0){
        $result = get_one_table_list('auto_manage', $field, $where, $order, $limit, 'select');
        return $result;
    }


    /**
     * @return 获取自动发布文章或者产品单条数据
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @return	array 数组格式的返回结果
     */
    public function select_auto_manage_info($field = '*', $where) {
        return get_one_table_list('auto_manage', $field, $where, '', '', 'find');
    }

    /**
     * @return 删除指定自动发布文章或者产品信息bool
     * @param $where  array/string  删除的条件
     * @return bool
     */
    function delete_auto_manage($where) {
        $deladdre = delete_table_info('auto_manage', $where);
        return $deladdre;
    }

     /**
     * @return 新增自动发布文章或者产品
     * @param  array $param 插入数据
     * @return bool
     */
    public function insert_auto_manage($param) {
        $result = add_table_info('auto_manage', $param);
        return $result;
    }
    
    /**
     * @return 更新自动发布文章或者产品信息
     * @param  array $param 更新数据
     * @param  array/string $where 条件
     * @return bool
     */
    public function update_auto_manage($param,$where) {
        $result = update_table_info('auto_manage', $param, $where);
        return $result;
    }

}
