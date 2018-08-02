<?php

/**
 * 淘玉php 会员帐户操作记录模型类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 会员帐户操作记录模型类
 * $Id: lib_automanage_model.php 17217 2018-05-4 :29:08Z 萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class accountlogModel extends Model {

	/**
     * @return 新增用户操作记录
     * @param  array $param 插入数据
     * @return bool
     */
    public function insert_account_log($param) {
        $result = add_table_info('account_log', $param);
        return $result;
    }

    /**
     * @return 删除用户操作记录
     * @param $where  array/string  删除的条件
     * @return bool
     */
    public function delete_account_log($where) {
        $deladdre = delete_table_info('account_log', $where);
        return $deladdre;
    }

    /**
     * @return 查询记录个数
     * @param  string/array $where    查询条件
     * @return string
     */
    public function get_account_log_count($where) {
        $param = array();
        $param['table'] = 'account_log';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }

    /**
     * @return 获取单条用户操作记录信息find
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @return array 数组格式的返回结果
     */
    public function select_account_log_info($field = '*', $where='',$order='' , $limit = 0) {
       $result = get_one_table_list('account_log', $field, $where, $order, $limit, 'find');
       return $result;
    }

    /**
     * @return 获取用户操作记录列表select
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  string $order 排序参数
     * @param  string $limit 分页参数
     * @return array
     */
    public function get_account_log_list($field, $where, $order='' , $limit = 0,$group='') {
        return get_one_table_list('account_log', $field, $where, $order, $limit, 'select',$group);
    }
}