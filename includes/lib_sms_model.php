<?php

/**
 * 淘玉php 短信模版类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 陈洋$
 * 短信模版类用于用户接受短信使用
 * $Id: lib_sms_model.php 17217 2015-06-21 09:29:08Z 淘玉 陈洋$
 */
defined('TaoyuShop') or exit('Access Invalid!');

class smsModel extends Model {


    /**
     * @return 查询短信总个数count
     * @param string/Array $where 查询条件
     * @return string
     */
    public function get_sms_count($where){
        $param = array();
        $param['table'] = 'sms';
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
    public function get_sms_list($field, $where, $order='' , $limit = 0) {
        $result = get_one_table_list('sms', $field, $where, $order, $limit, 'select');
        return $result;
    }

    /**
     * @return 编辑修改系统短信消息boolean
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_sms($param,$where) {
        $result = Db::update('sms', $param, $where);
        return $result;
    }
    
    /**
     * @return 添加新系统短信消息keyid
     * @param array $insert 更新数据
     * @return keyid 返回添加成功以后id
     */
    public function insert_sms($insert) {
       return add_table_info('sms',$insert);
    }
   
    
    /**
     * @return 获取单个系统短信消息find
     * @param	string $field 显示字段
     * @param	array $where 管理员条件
     * @return	array 数组格式的返回结果
     */
    public function select_sms_info($field = '*', $where) {
        $result = Model()->table('sms')->field($field)->where($where)->find();
        return $result;
    }

  
    /**
     * @return 删除指定系统短信消息bool
     * @param $where  array/string  删除的条件
     * @return bool
     */
    function delete_sms($where) {
        $deladdre = delete_table_info('sms', $where);
        return $deladdre;
    }
    
}
