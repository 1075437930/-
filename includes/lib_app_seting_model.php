<?php

/**
 * 淘玉php app管理数据模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 *
 * $Id: admin.model.php 17217 2015-05-19 06:29:08Z 淘玉 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class app_setingModel{
    
    /**
     * @return 获取单条登录背景图信息find
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  str $order 排序条件
     * @param  str $limit 限制条件
     * @return array  数组格式的返回结果
     */
    public function select_background_img_info($field = '*', $where='',$order='' , $limit = 0){
        $result = get_one_table_list('login_background_img', $field, $where, $order, $limit, 'find');
        return $result;
    }

    /**
     * @return 删除登录背景图片
     * @param  $where array/string  删除的条件
     * @return bool
     */
    public function delete_background_img($where) {
        return delete_table_info('login_background_img', $where);
    }
   
    /**
     * @return 查询背景图的数量count 
     * @param string/array $where    查询条件
     * @return string
     */
    public function get_background_img_count($where='') {
        $param = array();
        $param['table'] = 'login_background_img';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }

    /**
     * @return 新增银行卡
     * @param  array $param 插入数据
     * @return bool
     */
    public function insert_background_img($param) {
        $result = add_table_info('login_background_img', $param);
        return $result;
    }

    /**
     * @return 获取单条登录背景图信息find
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  str $order 排序条件
     * @param  str $limit 限制条件
     * @return array  数组格式的返回结果
     */
    public function select_yinghang_info($field = '*', $where='',$order='' , $limit = 0){
        $result = get_one_table_list('app_yinghang', $field, $where, $order, $limit, 'find');
        return $result;
    }

    /**
     * @return 获取银行卡信息find
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  str $order 排序条件
     * @param  str $limit 限制条件
     * @return array  数组格式的返回结果
     */
    public function get_yinghang_list($field = '*', $where='',$order='' , $limit = 0){
        $result = get_one_table_list('app_yinghang', $field, $where, $order, $limit, 'select');
        return $result;
    }

    /**
     * @return 编辑银行卡信息
     * @param  array $param 更新数据
     * @param  array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_app_yinghang($param,$where) {
        return update_table_info('app_yinghang', $param, $where);
    }

    /**
     * @return 新增银行卡
     * @param  array $param 插入数据
     * @return bool
     */
    public function insert_app_yinghang($param) {
        $result = add_table_info('app_yinghang', $param);
        return $result;
    }

    /**
     * @return 删除银行卡
     * @param  array/string  $where 删除条件
     * @return bool
     */
    public function delete_app_yinghang($where) {
        $result = delete_table_info('app_yinghang', $where);
        return $result;
    }

    /**
     * @return 获取单条app_config信息find
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  str $order 排序条件
     * @param  str $limit 限制条件
     * @return array  数组格式的返回结果
     */
    public function select_app_config_info($field = '*', $where='',$order='' , $limit = 0){
        $result = get_one_table_list('app_config', $field, $where, $order, $limit, 'find');
        return $result;
    }

    /**
     * @return 编辑app_config信息
     * @param  array $param 更新数据
     * @param  array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_app_config($param,$where) {
        return update_table_info('app_config', $param, $where);
    }

    /**
     * @return 新增app_config记录
     * @param  array $param 插入数据
     * @return bool
     */
    public function insert_app_config($param) {
        $result = add_table_info('app_config', $param);
        return $result;
    }


    
}