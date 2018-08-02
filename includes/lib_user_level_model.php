<?php

/**
 * 淘玉 用户等级数据模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 用户等级数据信息增删改查count
 * $Id: lib_order_model.php 17217 2018-04-07  萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class user_levelModel extends Model{

	/**
     * @return 获取单条用户等级信息find
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @return  array  数组格式的返回结果
     */
    public function select_user_level_info($field = '*', $where='',$order='' , $limit = 0) {
      $result = get_one_table_list('user_level', $field, $where, $order, $limit, 'find');
      return $result;
    }

    /**
     * @return 添加新用户等级
     * @param array $insert 更新数据
     * @return keyid 返回添加成功以后id
     */
    public function insert_user_level($insert) {
       return add_table_info('user_level',$insert);
    }

    /**
     * @return 编辑更新用户等级信息
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_user_level($param,$where) {
        return update_table_info('user_level', $param, $where);
    }

    /**
     * @return 删除用户等级
     * @param $where  array/string  删除的条件
     * @return bool
     */
    public function delete_user_level($where) {
        return delete_table_info('user_level', $where);
    }

    
    /**
     * @return 获取用户等级列表select
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  string $order 排序参数
     * @param  string $limit 分页参数
     * @return array
     */
    public function get_user_level_list($field, $where, $order = '', $limit = 0) {
        return get_one_table_list('user_level', $field, $where, $order, $limit, 'select');
    }

    /**
     * @return 查询记录个数
     * @param  string/array $where    查询条件
     * @return string
     */
    public function get_user_level_count($where='') {
        $param = array();
        $param['table'] = 'user_level';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }
}