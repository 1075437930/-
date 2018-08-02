<?php
/**
 * 淘玉 商品分类数据模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 商品分类信息增删改查
 * $Id: lib_category_model.php 17217 2018-04-07  萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!');
class categoryModel{
	/**
     * @return 查询商品分类关联总个数count
     * @param string/Array $where 查询条件
     * @return string
     */
    public function get_category_count($where){
       $param = array();
       $param['table'] = 'category';
       $param['count'] = 'count';
       $param['where'] = $where;
       return DB::select($param);
    }
    /**
     * @return 商品分类关联列表select
     * @param $where  str/array 搜索条件
     * @param $limit  str 限制条件
     * @param $order  str 排序条件
     * @param $field  str 搜索字段
     */
    public function get_category_list($field='*',$where,$order='' , $limit = 0) {
        $result = get_one_table_list('category', $field, $where, $order, $limit, 'select');
        return $result;
    }
    /**
     * @return 获取单条商品分类关联信息find
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @return	array 数组格式的返回结果
     */
    public function select_category_info($field = '*', $where) {
       return get_one_table_list('category', $field, $where, '', '', 'find');
    }
    /**
     * @return 编辑商品分类关联信息boolean
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_category($param,$where) {
        return update_table_info('category', $param, $where);
    }
    /**
     * @param string/array $where 删除的条件
     * @return 删除商品分类关联信息bool
     */
    public function delete_category($where){
        $return = delete_table_info('category', $where);
        return $return;
    }
    /**
     * @return  插入分类关联产品表 Description
     * @param   array  $insert 插入条件
     */
    public function insert_category($insert) {
        return add_table_info('category', $insert);
    }
}