<?php

/**
 * 淘玉php 入驻商店铺街数据模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 入驻商店铺街数据模型
 * $Id: lib_street_category_model.php 17217 2018-05-5 :29:08Z 萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class street_categoryModel extends Model {

    /**
     * @return 新添加店铺分类信息keyid
     * @param array $insert 新数据
     * @return keyid 返回添加成功以后id
     */
    public function insert_street_category($insert) {
        return add_table_info('street_category', $insert);
    }
    
    /**
     * @return 获取店铺分类
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @return	array 数组格式的返回结果
     */
    public function select_street_category_info($field = '*', $where) {
        return get_one_table_list('street_category', $field, $where, '', '', 'find');
    }

    /**
     * @return 编辑店铺分类信息boolean
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_street_category($param, $where) {
        return update_table_info('street_category', $param, $where);
    }

    

    /**
     * @return 删除店铺街分类bool
     * @param $where  array/string  删除的条件
     * @return bool
     */
    public function delete_street_category($where) {
        return delete_table_info('street_category', $where);
    }
    
    /**
     * @return 获取店铺分类列表select
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @param string $order 排序参数
     * @param string $limit 分页参数
     * @return type
     */
    public function get_street_category_list($field, $where, $order = '', $limit = 0) {
        return $this->table('street_category')->field($field)->where($where)->order($order)->limit($limit)->select();
    }
    
    /**
     * @return 获取店铺街列表select
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @param string $order 排序参数
     * @param string $limit 分页参数
     * @return type
     */
    public function get_supplier_street_list($field, $where, $order = '', $limit = 0) {
        return $this->table('supplier_street')->field($field)->where($where)->order($order)->limit($limit)->select();
    }
    
    /**
     * @return 编辑店铺街信息boolean
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_supplier_street($param, $where) {
        return update_table_info('supplier_street', $param, $where);
    }

    /**
     * @return 删除店铺街信息
     * @param $where  array/string  删除的条件
     * @return bool
     */
    function delete_supplier_street($where) {
        return delete_table_info('supplier_street', $where);
    }
    
      /**
     * @return 查询总店铺街个数count 
     * @param string/array $where    查询条件
     * @return string
     */
    function get_supplier_street_count($where) {
        $param = array();
        $param['table'] = 'supplier_street';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }
    
    /**
     * @return 获取单条店铺街信息find
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @return	array 数组格式的返回结果
     */
    public function select_supplier_street_info($field = '*', $where) {
        return get_one_table_list('supplier_street', $field, $where, '', '', 'find');
    }

}
