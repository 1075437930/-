<?php

/**
 * 淘玉php 大师数据模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * 大师信息增删改查
 * $Id: master.model.php 17217 2015-05-19 06:29:08Z 淘玉 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class masterModel extends Model {

    /**
     * @return 新增大师
     * @param  array $param 插入数据
     * @return bool
     */
    public function insert_master($param) {
        $result = add_table_info('master', $param);
        return $result;
    }

    /**
     * @return 删除大师
     * @param  array/string $where 删除条件
     * @return bool
     */
    public function delete_master($where) {
        $result = delete_table_info('master', $where);
        return $result;
    }

    /**
     * @return 更新大师信息
     * @param  array $param 更新数据
     * @param  array/string $where 条件
     * @return bool
     */
    public function update_master_info($param,$where) {
        $result = update_table_info('master', $param, $where);
        return $result;
    }

    /**
     * @return 获取大师一条信息find
     * @param  str $field 查询字段
     * @param  array/string $where 条件
     * @return array
     */
    public function select_master_info($field='*',$where='') {
        $result = get_one_table_list('master', $field, $where, '', '', 'find');
        return $result;
    }

    /**
     * @return 获取大师列表
     * @param  str $field 搜索字段
     * @param  array/string $where 搜索条件
     * @param  str $order 排序条件
     * @param  str $limit 限制条件
     * @return array
     */
    public function get_master_list($field='*', $where='', $order='' , $limit = 0) {
        $result = get_one_table_list('master', $field, $where, $order, $limit, 'select');
        return $result;
    }

    /**
     * @return 新增大师分类
     * @param  array $param 插入数据
     * @return bool
     */
    public function insert_master_type($param) {
        $result = add_table_info('master_type', $param);
        return $result;
    }

    /**
     * @return 删除大师分类
     * @param  array/string  $where 删除条件
     * @return bool
     */
    public function delete_master_type($where) {
        $result = delete_table_info('master_type', $where);
        return $result;
    }

    /**
     * @return 更新大师分类
     * @param  array $param 更新数据
     * @param  array/string $where 条件
     * @return bool
     */
    public function update_master_type($param,$where) {
        $result = update_table_info('master_type', $param, $where);
        return $result;
    }

    /**
     * @return 获取大师分类一条信息find
     * @param  str $field 查询字段
     * @param  array/string $where 条件
     * @return array
     */
    public function select_master_type_info($field='*',$where='') {
        $result = get_one_table_list('master_type', $field, $where, '', '', 'find');
        return $result;
    }
   
    /**
     * @return 获取大师分类列表
     * @param str $field 搜索字段
     * @param array/string $where 搜索条件
     * @param str $order 排序条件
     * @param str $limit 限制条件
     * @return array
     */
    public function get_master_type_list($field='*', $where='', $order='' , $limit = 0) {
        $result = get_one_table_list('master_type', $field, $where, $order, $limit, 'select');
        return $result;
    }

    /**
     * 新增大师作品
     * @param array $param 插入数据
     * @return bool
     */
    public function insert_master_goods($param) {
        $result = add_table_info('master_goods', $param);
        return $result;
    }

    /**
     * @return 删除大师作品
     * @param  array/string  $where 删除条件
     * @return bool
     */
    public function delete_master_goods($where) {
        $result = delete_table_info('master_goods', $where);
        return $result;
    }

    /**
     * @return 更新大师作品信息
     * @param  array $param 更新数据
     * @param  array/string $where 条件
     * @return bool
     */
    public function update_master_goods_info($param,$where) {
        $result = update_table_info('master_goods', $param, $where);
        return $result;
    }

    /**
     * @return 获取大师商品一条信息find
     * @param  str $field 查询字段
     * @param  array/string $where 条件
     * @return array
     */
    public function select_master_goods_info($field='*',$where='') {
        $result = get_one_table_list('master_goods', $field, $where, '', '', 'find');
        return $result;
    }
    
    /**
     * @return 获取大师商品列表
     * @param str $field 搜索字段
     * @param array/string $where 搜索条件
     * @param str $order 排序条件
     * @param str $limit 限制条件
     * @return array
     */
    public function get_master_goods_list($field, $where, $order='' , $limit = 0) {
        $result = get_one_table_list('master_goods', $field, $where, $order, $limit, 'select');
        return $result;
    }

    /**
     * 新增大师奖项
     * @param array $param 插入数据
     * @return bool
     */
    public function insert_master_prize($param) {
        $result = add_table_info('master_prize', $param);
        return $result;
    }

    /**
     * @return 删除大师奖项
     * @param  array/string  $where 删除条件
     * @return bool
     */
    public function delete_master_prize($where) {
        $result = delete_table_info('master_prize', $where);
        return $result;
    }

    /**
     * @return 更新大师奖项信息
     * @param  array $param 更新数据
     * @param  array/string $where 条件
     * @return bool
     */
    public function update_master_prize_info($param,$where) {
        $result = update_table_info('master_prize', $param, $where);
        return $result;
    }

    /**
     * @return 获取大师奖项一条信息find
     * @param  str $field 查询字段
     * @param  array/string $where 条件
     * @return array
     */
    public function select_master_prize_info($field='*',$where='') {
        $result = get_one_table_list('master_prize', $field, $where, '', '', 'find');
        return $result;
    }

    /**
     * @return 获取大师奖项列表
     * @param  str $field 搜索字段
     * @param  array/string $where 搜索条件
     * @param  str $order 排序条件
     * @param  str $limit 限制条件
     * @return array
     */
    public function get_master_prize_list($field, $where, $order='' , $limit = 0) {
        $result = get_one_table_list('master_prize', $field, $where, $order, $limit, 'select');
        return $result;
    }
}