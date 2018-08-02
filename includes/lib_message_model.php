<?php

/**
 * 淘玉php 用户消息模版类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * 用户消息模版类用于用户官方接受消息使用
 * $Id: lib_message_model.php 17217 2015-05-19 06:29:08Z 淘玉 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class messageModel extends Model {


    /**
     * @return 查询短信总个数count
     * @param string/Array $where 查询条件
     * @return string
     */
    public function get_message_count($where){
        $param = array();
        $param['table'] = 'message';
        $param['count'] = 'count';
        $param['where'] = $where;
        return DB::select($param);
    }

    /**
     * @return 系统短信消息列表select
     * @param type $field
     * @param type $where
     * @param type $order
     * @param type $limit
     * @return type
     */
    public function get_message_list($field, $where, $order='' , $limit = 0) {
        $result = get_one_table_list('message', $field, $where, $order, $limit, 'select');
        return $result;
    }

    /**
     * @return 编辑修改系统短信消息boolean
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_message($param,$where) {
        $result = Db::update('message', $param, $where);
        return $result;
    }
    
    /**
     * @return 添加新系统短信消息keyid
     * @param array $insert 更新数据
     * @return keyid 返回添加成功以后id
     */
    public function insert_message($insert) {
       return add_table_info('message',$insert);
    }
   
    
    /**
     * @return 获取单个系统短信消息find
     * @param	string $field 显示字段
     * @param	array $where 管理员条件
     * @return	array 数组格式的返回结果
     */
    public function select_message_info($field = '*', $where) {
        $result = Model()->table('message')->field($field)->where($where)->find();
        return $result;
    }

  
    /**
     * @return 删除指定系统短信消息bool
     * @param $where  array/string  删除的条件
     * @return bool
     */
    function delete_message($where) {
        $deladdre = delete_table_info('message', $where);
        return $deladdre;
    }
    
}
