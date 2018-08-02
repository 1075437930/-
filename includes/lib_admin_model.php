<?php

/**
 * 淘玉php 管理员数据模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * 管理员信息增删改查
 * $Id: admin.model.php 17217 2015-05-19 06:29:08Z 淘玉 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class adminModel extends Model {
    
    /**
     * @return 管理员列表select
     * @param type $field
     * @param type $where
     * @param type $order
     * @param type $limit
     * @return type
     */
    public function get_admin_list($field, $where, $order='' , $limit = 0) {
        $result = get_one_table_list('admin_user', $field, $where, $order, $limit, 'select');
        return $result;
    }
    
    /**
     * @return 管理员日志select
     * @param type $field
     * @param type $where
     * @param type $order
     * @param type $limit
     * @return type
     */
    public function get_admin_log_list($field, $where, $order='' , $limit = 0) {
        $result = get_one_table_list('admin_log', $field, $where, $order, $limit, 'select');
        return $result;
    }
    
    /**
     * @return 获取角色列表select
     * @param type $field
     * @param type $where
     * @return type
     */
    function get_role_list($field, $where){
        $param = array();
        $param['table'] = 'role';
        $param['field'] = $field;
        $param['where'] = $where;
        $list = DB::select($param);
        return $list;
    }
    
    
    /**
     * @return 更新管理员角色信息boolean
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_role_info($param,$where) {
        $result = Db::update('role', $param, $where);
        return $result;
    }
    
    /**
     * @return 添加新管理员角色信息keyid
     * @param array $insert 更新数据
     * @return keyid 返回添加成功以后id
     */
    public function insert_role_info($insert) {
       return add_table_info('role',$insert);
    }
   
    
    /**
     * @return 获取角色信息find
     * @param	string $field 显示字段
     * @param	array $where 管理员条件
     * @return	array 数组格式的返回结果
     */
    public function select_role_info($field = '*', $where) {
        $result = Model()->table('role')->field($field)->where($where)->find();
        return $result;
    }
    
   /**
     * @return 获取权限的分组数据select
     * @param type $field
     * @param type $where
     * @return type
     */
    function get_action_list($field, $where){
        $param = array();
        $param['table'] = 'admin_action';
        $param['field'] = $field;
        $param['where'] = $where;
        $list = DB::select($param);
        return $list;
    }
    
    
    /**
     * @return 获取管理员信息find
     * @param	string $field 显示字段
     * @param	array $where 管理员条件
     * @return	array 数组格式的返回结果
     */
    public function select_admin_info($field = '*', $where) {
        $result = Model()->table('admin_user')->field($field)->where($where)->find();
        return $result;
    }
    
    
    /**
     * @return 更新管理员信息boolean
     * @param array $param 更新数据
     * @return bool 布尔类型的返回结果
     */
    public function update_admin_info($param,$where) {
        $result = Db::update('admin_user', $param, $where);
        return $result;
    }
    
     /**
     * @return 查询管理员个数count 
     * @param string/array $where    查询条件
     * @return string
     */
    function get_admin_count($where) {
        $param = array();
        $param['table'] = 'admin_user';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }
    
    /**
     * @return 查询管理员日志总个数count 
     * @param string/array $where    查询条件
     * @return string
     */
    function get_admin_log_count($where) {
        $param = array();
        $param['table'] = 'admin_log';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }
    
   /**
     * @return 添加新管理员信息keyid
     * @param array $insert 更新数据
     * @return keyid 返回添加成功以后id
     */
    public function insert_admin_info($insert) {
       return add_table_info('admin_user',$insert);
    }
    
    /**
     * @return 删除指定管理员信息bool
     * @param $where  array/string  删除的条件
     * @return bool
     */
    function delete_admin($where) {
        $deladdre = delete_table_info('admin_user', $where);
        return $deladdre;
    }
    
    /**
     * @return 删除指定角色信息bool
     * @param $where  array/string  删除的条件
     * @return bool
     */
    function delete_role($where) {
        $deladdre = delete_table_info('role', $where);
        return $deladdre;
    }
    
    /**
     * @return 删除指定管理员日志bool
     * @param $where  array/string  删除的条件
     * @return bool
     */
    function delete_admin_log($where) {
        $deladdre = delete_table_info('admin_log', $where);
        return $deladdre;
    }
    
 
}
