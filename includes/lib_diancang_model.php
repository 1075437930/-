<?php

/**
 * 淘玉php 典藏模块数据模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萧瑟 $
 * 典藏模块数据模型
 * $Id: lib_diancang_model.php 17217 2018-05-5 :29:08Z 萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class diancangModel extends Model {

    /*********典藏产品*********/
    
    /**
     * @return 新增典藏商品
     * @param  array $param 插入数据
     * @return bool
     */
    public function insert_capital_goods($param) {
        $result = add_table_info('capital_goods', $param);
        return $result;
    }

    /**
     * @return 删除典藏商品
     * @param  array/string  $where 删除条件
     * @return bool
     */
    public function delete_capital_goods($where) {
        $result = delete_table_info('capital_goods', $where);
        return $result;
    }

    /**
     * @return 编辑更新典藏商品信息
     * @param  array $param 更新数据
     * @param  array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_capital_goods($param,$where) {
        return update_table_info('capital_goods', $param, $where);
    }

    /**
     * @return  获取单条典藏商品信息find
     * @param   string $field 需要查询字段
     * @param   string/array $where 查询条件
     * @return  array  数组格式的返回结果
     */
    public function select_capital_goods_info($field = '*', $where='',$order='' , $limit = 0) {
      $result = get_one_table_list('capital_goods', $field, $where, $order, $limit, 'find');
      return $result;
    }

    /**
     * @return 查询总典藏商品个数count 
     * @param string/array $where    查询条件
     * @return string
     */
    public function get_capital_goods_count($where) {
        $param = array();
        $param['table'] = 'capital_goods';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }

    /**
     * @return 链表查询典藏商品个数select ,(capital_goods,goods) 
     * @param  string/array $where    查询条件
     * @return string
     */
    public function get_capital_goods_goods_count($where='') {
        $res = $this->table('capital_goods,goods')
        ->field('*')
        ->join('left')//right 或者inner 
        ->on('capital_goods.goods_id=goods.goods_id')
        ->where($where)
        ->select();
        return count($res);
    }

    /**
     * @return 链表查询典藏商品信息select,(capital_goods,goods,capital_class) 
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  string $order 排序参数
     * @param  string $limit 分页参数
     * @return type
     * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
     * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
     * inner join(等值连接)      只返回两个表中联结字段相等的行
     */
    public function get_capital_goods_goods_class_info($field,$where,$order = '',$limit = ''){
        return $this->table('capital_goods,goods,capital_class')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on('capital_goods.goods_id = goods.goods_id,capital_goods.dc_classid = capital_class.dcclass_id')
        ->where($where)
        ->order($order)
        ->limit($limit)
        ->find();
    } 

    /*********典藏产品月份比例*********/
    
    /**
     * @return  获取典藏商品月份比例find
     * @param   string $field 显示字段
     * @param   array/string $where 条件
     * @return  array 返回结果
     */
    public function select_capital_goodsyue_info($field = '*', $where) {
        $result = get_one_table_list('capital_goodsyue', $field, $where, '', '', 'find');
        return $result;
    }

    /**
     * @return 获取典藏商品月份比例select
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @param string $order 排序参数
     * @param string $limit 分页参数
     * @return type
     */
    public function get_capital_goodsyue_list($field, $where='', $order='' , $limit = 0) {
        return get_one_table_list('capital_goodsyue', $field, $where, $order, $limit, 'select');
    }

    /**
     * @return 新增典藏商品月份比例
     * @param  array $param 插入数据
     * @return bool
     */
    public function insert_capital_goodsyue($param) {
        $result = add_table_info('capital_goodsyue', $param);
        return $result;
    }

    /**
     * @return 删除典藏商品月份比例
     * @param  array/string  $where 删除条件
     * @return bool
     */
    public function delete_capital_goodsyue($where) {
        $result = delete_table_info('capital_goodsyue', $where);
        return $result;
    }
    

    /**
     * @return 查询总典藏用户等级个数count 
     * @param string/array $where    查询条件
     * @return string
     */
    public function get_capital_rank_count($where) {
        $param = array();
        $param['table'] = 'capital_rank';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }
    
    /*********典藏代金卷*********/

    /**
     * @return  获取代金卷find
     * @param   string $field 显示字段
     * @param   array/string $where 条件
     * @return  array 返回结果
     */
    public function select_capital_point_info($field = '*', $where) {
        $result = get_one_table_list('capital_point', $field, $where, '', '', 'find');
        return $result;
    }

    /**
     * @return 获取代金卷select
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @param string $order 排序参数
     * @param string $limit 分页参数
     * @return type
     */
    public function get_capital_point_list($field, $where='', $order='' , $limit = 0) {
        return get_one_table_list('capital_point', $field, $where, $order, $limit, 'select');
    }
    
    /**
     * @return 更新代金卷的某个字段直+n等类似的操作
     * @param $where  array   查询条件
     * @param $param   sting/array  需要增加或减少N的字段  默认加1
     * @return bool
     * 备注 ：$param['表字段'] = 5 为 本字段加5  $param = '表字段'  为本字段加1 
     */
    public function update_capital_point_setInc($param,$where) {
        return update_table_original_field('capital_point', $where, $param);
    }

    /**
     * @return 添加用户代金卷发放记录keyid
     * @param string $insert 更新数据
     * @return keyid 返回添加成功以后id
     */
    public function insert_capital_userpoint($insert) {
        return add_table_info('capital_userpoint',$insert);
    }

    /**
     * @return 编辑更新用户代金卷发放记录
     * @param  array $param 更新数据
     * @param  array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_capital_userpoint($param,$where) {
        return update_table_info('capital_userpoint', $param, $where);
    }

    /*********典藏用户*********/

    /**
     * @return 编辑更新典藏用户信息
     * @param  array $param 更新数据
     * @param  array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_capital_user($param,$where) {
        return update_table_info('capital_user', $param, $where);
    }
    
    /**
     * @return 获取典藏用户列表select
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @param string $order 排序参数
     * @param string $limit 分页参数
     * @return type
     */
    public function get_capital_user_list($field, $where, $order='' , $limit = 0) {
        return get_one_table_list('capital_user', $field, $where, $order, $limit, 'select');
    }
    
    /**
     * @return 链表查询用户信息users-capital_user
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @param string $order 排序参数
     * @param string $limit 分页参数
     * @return type
     * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
     * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
     * inner join(等值连接)      只返回两个表中联结字段相等的行
     */
    public function get_users_capital_user_list($field,$where,$order = '',$limit = ''){
        return $this->table('users,capital_user')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on('users.user_id = capital_user.user_id')
        ->where($where)
        ->order($order)
        ->limit($limit)
        ->select();
    }

    /**
     * @return 链表查询用户及等级信息users-capital_user
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @param string $order 排序参数
     * @param string $limit 分页参数
     * @return type
     * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
     * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
     * inner join(等值连接)      只返回两个表中联结字段相等的行
     */
    public function select_capital_user_rank_info($field,$where,$order = '',$limit = ''){
        return $this->table('capital_user,capital_rank')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on('capital_user.capital_rankid = capital_rank.rank_id')
        ->where($where)
        ->order($order)
        ->limit($limit)
        ->find();
    }
    
    /**
     * @return 删除capital_goodsuser
     * @param  array/string  $where 删除条件
     * @return bool
     */
    public function delete_capital_goodsuser($where) {
        $result = delete_table_info('capital_goodsuser', $where);
        return $result;
    }
   
}
