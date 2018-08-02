<?php

/**
 * 淘玉php 品牌处理model
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * 处理品牌表中增删改查
 * $Id: lib_brand_model.php 17217 2015-05-19 06:29:08Z 淘玉 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class brandModel extends Model {
    
    /**
     * @return 获取品牌列表select
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @param string $order 排序参数
     * @param string $limit 分页参数
     * @return type
     */
    public function get_brand_list($field, $where, $order='' , $limit = 0) {
        return get_one_table_list('brand', $field, $where, $order, $limit, 'select');
    }
    
    
    /**
     * @return 获取单条品牌信息find
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @return	array 数组格式的返回结果
     */
    public function select_brand_info($field = '*', $where) {
       return get_one_table_list('brand', $field, $where, '', '', 'find');
    }
    
    
     /**
     * @return 删除对应品牌bool
     * @param $where  array/string  删除的条件
     * @return bool
     */
    function delete_brand($where) {
        return delete_table_info('brand', $where);
    }
    
    
    /**
     * @return 编辑对应品牌信息boolean
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_brand($param,$where) {
       return update_table_info('brand', $param, $where);
    }
    
    /**
     * @return 新添加对应品牌信息keyid
     * @param array $insert 新数据
     * @return keyid 返回添加成功以后id
     */
    public function insert_brand($insert) {
       return add_table_info('brand',$insert);
    }
    
    /**
     * @return 查询总品牌个数count 
     * @param string/array $where    查询条件
     * @return string
     */
    function get_brand_count($where) {
        $param = array();
        $param['table'] = 'brand';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }
    
    

}
