<?php

/**
 * 淘玉php 用户模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * 用户模型
 * $Id: lib_users_model.php 17217 2018-04-29 06:29:08Z 淘玉 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class usersModel extends Model {

    /**
     * @return 查询记录个数
     * @param  string/array $where    查询条件
     * @return string
     */
    public function get_users_count($where) {
        $param = array();
        $param['table'] = 'users';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }

    /**
     * @return 获取用户等级列表select
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  string $order 排序参数
     * @param  string $limit 分页参数
     * @return array
     */
    public function get_user_rank_list($field, $where, $order = '', $limit = 0) {
        return get_one_table_list('user_rank', $field, $where, $order, $limit, 'select');
    }

    /**
     * @return 获取注册扩展字段列表select
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  string $order 排序参数
     * @param  string $limit 分页参数
     * @return array
     */
    public function get_reg_fields_list($field, $where, $order = '', $limit = 0) {
        return get_one_table_list('reg_fields', $field, $where, $order, $limit, 'select');
    }

    
    /**
     * @return 删除指定用户等级信息bool
     * @param  $where  array/string  删除的条件
     * @return bool
     */
    public function delete_user_rank($where) {
        $deladdre = delete_table_info('user_rank', $where);
        return $deladdre;
    }

    /**
     * @return 获取单条用户等级信息find
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @return	array 数组格式的返回结果
     */
    public function select_user_rank_info($field = '*', $where) {
        return get_one_table_list('user_rank', $field, $where, '', '', 'find');
    }

    /**
     * @return 更新user_rank表
     * @param  $field  array/string  条件
     * @param  $rank_id  array/string  条件
     * @return bool
     */
    public function update_user_rank($field, $where) {
        $res = update_table_info('user_rank', $field, $where);
        return $res;
    }

    /**
     * @return 获取单条用户信息find
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @return array 数组格式的返回结果
     */
    public function select_users_info($field = '*', $where) {
        return $this->table('users')->field($field)->where($where)->find();
    }
    
    /**
     * @return 获取单条用户账户变动信息find
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @return array 数组格式的返回结果
     */
    public function select_user_account_info($field = '*', $where) {
        return get_one_table_list('user_account', $field, $where, '', '', 'find');
    }

    /**
     * @return 更新user_account数据
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_user_account_info($param, $where) {
        $result = update_table_info('user_account', $param, $where);
        return $result;
    }
   
    /**
     * @return 链表查询用户账户信息find,(user_account，users)
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  string $order 排序参数
     * @param  string $limit 分页参数
     * @return type
     * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
     * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
     * inner join(等值连接)      只返回两个表中联结字段相等的行
     */
    public function get_user_account_users_info($field,$where,$order = '',$limit = ''){
        return $this->table('user_account,users')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on('user_account.user_id = users.user_id')
        ->where($where)
        ->order($order)
        ->limit($limit)
        ->find();
    }

    /**
     * @return 链表查询用户账户信息find,(users，user_level)
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  string $order 排序参数
     * @param  string $limit 分页参数
     * @return type
     * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
     * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
     * inner join(等值连接)      只返回两个表中联结字段相等的行
     */
    public function get_users_user_level_info($field,$where,$order = '',$limit = ''){
        return $this->table('users,user_level')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on('users.level_id = user_level.level_id')
        ->where($where)
        ->order($order)
        ->limit($limit)
        ->find();
    }

    /**
     * @return 删除指定用户余额信息bool
     * @param  $where  array/string  删除的条件
     * @return bool
     */
    public function delete_user_account($where) {
        $deladdre = delete_table_info('user_account', $where);
        return $deladdre;
    }

    /**
     * @return 添加新订单操作记录keyid
     * @param  array $param 新数据
     * @return keyid 返回添加成功以后id
     */
    public function insert_user_account($param) {
        return add_table_info('user_account', $param);
    }

    /**
     * @return 链表查询记录个数（user_account,users）
     * @param  string/array $where    查询条件
     * @return string
     */
    public function get_user_account_count($where) {
        $res = $this->table('user_account,users')
        ->field('*')
        ->join('left')
        ->on('user_account.user_id=users.user_id')
        ->where($where)
        ->select();
        return count($res);
    }

    /**
     * @return 获取会员建议记录数
     * @param  $where  array/string  
     * @return int
     */
    public function get_yijian_count($where) {
        $param = array();
        $param['table'] = 'yijian';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }

    /**
     * @return 获取单条会员建议信息find
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @return array 数组格式的返回结果
     */
    public function select_yijian_info($field = '*', $where) {
        return get_one_table_list('yijian', $field, $where, '', '', 'find');
    }

    /**
     * @return 删除会员建议
     * @param  $where  array/string  删除的条件
     * @return bool
     */
    public function delete_suggestion($where) {
        $deladdre = delete_table_info('yijian', $where);
        return $deladdre;
    }

    /**
     * @return 编辑会员建议
     * @param  array $param 更新数据
     * @param  array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_suggestion($param,$where) {
       return update_table_info('yijian', $param, $where);
    }

    /**
     * @return 编辑用户信息boolean
     * @param  array $param 更新数据
     * @param   array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_users($param,$where) {
       return update_table_info('users', $param, $where);
    }

    /**
     * @return 删除指定用户bool
     * @param  $where  array/string  删除的条件
     * @return bool
     */
    public function delete_users($where) {
        $deladdre = delete_table_info('users', $where);
        return $deladdre;
    }

    /**
     * @return 删除缺货登记
     * @param  $where  array/string  删除的条件
     * @return bool
     */
    public function delete_booking_goods($where) {
        $deladdre = delete_table_info('booking_goods', $where);
        return $deladdre;
    }

    /**
     * @return 删除会员收藏商品
     * @param  $where  array/string  删除的条件
     * @return bool
     */
    public function delete_collect_goods($where) {
        $deladdre = delete_table_info('collect_goods', $where);
        return $deladdre;
    }

    /**
     * @return 删除会员反馈、留言
     * @param  $where  array/string  删除的条件
     * @return bool
     */
    public function delete_feedback($where) {
        $deladdre = delete_table_info('feedback', $where);
        return $deladdre;
    }

    /**
     * @return 删除会员红包
     * @param  $where  array/string  删除的条件
     * @return bool
     */
    public function delete_user_bonus($where) {
        $deladdre = delete_table_info('user_bonus', $where);
        return $deladdre;
    }

    /**
     * @return 删除用户标记信息
     * @param  $where  array/string  删除的条件
     * @return bool
     */
    public function delete_tag($where) {
        $deladdre = delete_table_info('tag', $where);
        return $deladdre;
    }
    
    /**
     * @return 更新users表名的某个字段直+n等类似的操作
     * @param  $where  array   查询条件
     * @param  $param   sting/array  需要增加或减少N的字段  默认加1
     * @return bool
     * 备注 ：$param['表字段'] = 5 为 本字段加5  $param = '表字段'  为本字段加1 
     */
    public function update_users_setInc($param,$where) {
        return update_table_original_field('users', $where, $param);
    }
       
    /**
     * @return 新添加用户信息keyid
     * @param  array $insert 新数据
     * @return keyid 返回添加成功以后id
     */
    public function insert_users($insert) {
       return add_table_info('users',$insert);
    }
     /**
     * @return 新添加用户注册扩展信息keyid
     * @param  array $insert 新数据
     * @return keyid 返回添加成功以后id
     */
    public function insert_ureg_extend_info($insert) {
       return add_table_info('ureg_extend_info',$insert);
    }
        
    /**
     * @return 获取reg_extend_info列表select
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  string $order 排序参数
     * @param  string $limit 分页参数
     * @return array
     */
    public function get_reg_extend_info_list($field, $where, $order='' , $limit = 0) {
        return get_one_table_list('reg_extend_info', $field, $where, $order, $limit, 'select');
    }

    /**
     * @return 获取用户列表select
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  string $order 排序参数
     * @param  string $limit 分页参数
     * @return array
     */
    public function get_users_list($field, $where, $order='' , $limit = 0,$group='') {
        return get_one_table_list('users', $field, $where, $order, $limit, 'select',$group);
    }
    
    /**
     * @return 获取单条reg_extend_info信息find
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @return array 数组格式的返回结果
     */
    public function select_reg_extend_info_info($field = '*', $where) {
       return get_one_table_list('reg_extend_info', $field, $where, '', '', 'find');
    }

    /**
     * @return 编辑reg_extend_info信息boolean
     * @param  array $param 更新数据
     * @param  array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_reg_extend_info($param,$where) {
       return update_table_info('reg_extend_info', $param, $where);
    }
    
    /**
     * @return 新添加reg_extend_info信息keyid
     * @param  array $insert 新数据
     * @return keyid 返回添加成功以后id
     */
    public function insert_reg_extend_info($insert) {
       return add_table_info('reg_extend_info',$insert);
    }

    
}
