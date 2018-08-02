<?php

/**
 * 淘玉php 典藏会员用户模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * 典藏会员用户模型
 * $Id: lib_diancang_users.model.php 17217 2015-05-19 06:29:08Z 淘玉 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class diancang_usersModel extends Model {

    /**
     * @return 新增典藏会员用户
     * @param  array $param 插入数据
     * @return bool
     */
    public function insert_capital_user($param) {
        $result = add_table_info('capital_user', $param);
        return $result;
    }

    /**
     * @return 删除典藏会员用户
     * @param  array/string  $where 删除条件
     * @return bool
     */
    public function delete_capital_user($where) {
        $result = delete_table_info('capital_user', $where);
        return $result;
    }

    /**
     * @return 更新典藏会员用户信息
     * @param  array $param 更新数据
     * @param  array/string $where 条件
     * @return bool
     */
    public function update_capital_user_info($param,$where) {
        $result = update_table_info('capital_user', $param, $where);
        return $result;
    }

    /**
     * @return  获取单个典藏会员用户信息find
     * @param   string $field 显示字段
     * @param   array/string $where 条件
     * @return  array 返回结果
     */
    public function select_capital_user_info($field = '*', $where='') {
        $result = get_one_table_list('capital_user', $field, $where, '', '', 'find');
        return $result;
    }

    /**
     * @return 获取典藏会员用户信息select
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  str $order 排序条件
     * @param  str $limit 限制条件
     * @return array  数组格式的返回结果
     */
    public function get_capital_user_list($field = '*', $where='',$order='' , $limit = 0){
        $result = get_one_table_list('capital_user', $field, $where, $order, $limit, 'select');
        return $result;
    }

    /**
     * @return 查询记录个数
     * @param  string/array $where    查询条件
     * @return string
     */
    public function get_capital_user_count($where='') {
        $param = array();
        $param['table'] = 'capital_user';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }

    /**
     * @return 链表查询典藏会员信息find,(capital_user,users)
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  string $order 排序参数
     * @param  string $limit 分页参数
     * @return type
     * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
     * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
     * inner join(等值连接)      只返回两个表中联结字段相等的行
     */
    public function select_capital_user_users_info($field,$where,$order = '',$limit = ''){
        return $this->table('capital_user,users')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on('capital_user.user_id = users.user_id')
        ->where($where)
        ->order($order)
        ->limit($limit)
        ->find();
    }

}