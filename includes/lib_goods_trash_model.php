<?php
/**
 * 淘玉 商品回收模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 陈洋 $
 * 商品回收信息增删改查
 * $Id: lib_goods_type_model.php 17217 2018-06-14  陈洋 $
 */

defined('TaoyuShop') or exit('Access Invalid!');

class goods_trashModel extends Model{
    /**
     * @return  插入商品回收表 Description
     * @param   array  $insert 插入条件
     */
    public function insert_goods_trash($insert) {
        return add_table_info('goods_trash', $insert);
    }

    /**
     * @return 商品回收表select
     * @param $field  str 搜索字段
     * @param $where  str/array 搜索条件
     * @param $order  str 排序条件
     * @param $limit  str 限制条件
     */
    public function get_goods_trash_list($field='*',$where,$order='',$limit=0){
        return get_one_table_list('goods_trash',$field, $where, $order, $limit, 'select');
    }

    /**
     * @return 获取单条类型信息find
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @return	array 数组格式的返回结果
     */
    public function select_goods_trash_info($field = '*', $where){
        return get_one_table_list('goods_trash', $field, $where, '', '', 'find');
    }

    /**
     * @param string/array $where 删除的条件
     * @return 删除商品回收bool
     */
    public function delete_goods_trash($where){
        $return = delete_table_info('goods_trash', $where);
        return $return;
    }
}