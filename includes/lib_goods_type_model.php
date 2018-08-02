<?php
/**
 * 淘玉 商品数据模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 陈洋 $
 * 商品类型信息增删改查
 * $Id: lib_goods_type_model.php 17217 2018-06-13  陈洋 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class goods_typeModel extends Model{
    /**
     * @return 商品类型列表select
     * @param $field  str 搜索字段
     * @param $where  str/array 搜索条件
     * @param $order  str 排序条件
     * @param $limit  str 限制条件
     */

    public function get_goods_type_list($field='*',$where,$order='',$limit=0){

        return get_one_table_list('goods_type',$field, $where, $order, $limit, 'select');
    }

    /**
     * @return 获取单条类型信息find
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @return	array 数组格式的返回结果
     */
    public function select_goods_type_info($field = '*', $where) {
        return get_one_table_list('goods_type', $field, $where, '', '', 'find');
    }



    /**
     * @return 更新商品类型信息boolean
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_goods_type($param,$where) {
        return update_table_info('goods_type', $param, $where);
    }

    /**
     * @param string/array $where 删除的条件
     * @return 删除商品信息bool
     */
    public function delete_goods_type($where){
        return delete_table_info('goods_type', $where);
    }

    /**
     * @return  插入商品表 Description
     * @param   array  $insert 插入条件
     */
    public function insert_goods_type($insert) {
        return add_table_info('goods_type',$insert);
    }
}