<?php
/**
 * 淘玉 商品属性数据模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 陈洋 $
 * 商品属性信息增删改查
 * $Id: lib_goods_type_model.php 17217 2018-06-13  陈洋 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class attrModel extends Model{


    /**
     * @return 编辑商品属性boolean
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_attr($param,$where) {
        return update_table_info('attribute', $param, $where);
    }


    /**
     * @param string/array $where 删除的条件
     * @return 删除商品属性信息bool
     */
    public function delete_attr($where){
        $return = delete_table_info('attribute', $where);
        return $return;
    }

    /**
     * @return  添加属性 Description
     * @param   array  $insert 插入条件
     */
    public function insert_attr($insert) {
        return add_table_info('attribute', $insert);
    }
}