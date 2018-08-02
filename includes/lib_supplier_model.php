<?php

/**
 * 淘玉php 入驻商管理数据模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萧瑟 $
 * 入驻商管理数据模型
 * $Id: lib_supplier_model.php 17217 2018-05-5 :29:08Z 萧瑟 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class supplierModel extends Model {

    /***************入驻商等级操作***************/
    
    /**
     * @return 新添加入驻商等级信息keyid
     * @param array $insert 新数据
     * @return keyid 返回添加成功以后id
     */
    public function insert_supplier_rank($insert) {
        return add_table_info('supplier_rank', $insert);
    }

    /**
     * @return 删除入驻商等级bool
     * @param $where  array/string  删除的条件
     * @return bool
     */
    public function delete_supplier_rank($where) {
        return delete_table_info('supplier_rank', $where);
    }

    /**
     * @return 编辑入驻商等级信息boolean
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_supplier_rank($param, $where) {
        return update_table_info('supplier_rank', $param, $where);
    }

    /**
     * @return 获取单条供应商等级
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @return  array 数组格式的返回结果
     */
    public function select_supplier_rank_info($field = '*', $where) {
        return get_one_table_list('supplier_rank', $field, $where, '', '', 'find');
    }
    
    /**
     * @return 获取供应商等级
     * @param $where string
     * @param $field string
     * @param $order string
     * @param $limit string
     */
    public function get_supplier_rank_list($field, $where = '', $order = '', $limit = '') {
        return get_one_table_list('supplier_rank', $field, $where, $order, $limit, 'select');
    }

    
    /***************入驻商操作***************/

    /**
     * @return 编辑入驻商信息boolean
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_supplier($param, $where) {
        return update_table_info('supplier', $param, $where);
    }

    /**
     * @return 更新表名的某个字段直-n等类似的操作(本方法只能做减法处理)
     * @param $where  array   查询条件
     * @param $param   sting/array  需要增加或减少N的字段  默认减1
     * @return bool
     * 备注 ：$param['表字段'] = 5 为 本字段减5  $param = '表字段'  为本字段减1 
     */
    function update_supplier_setDec($param,$where) {
        return update_table_setDec('supplier', $where, $param);
    }

    /**
     * @return 更新表名的某个字段直+n 或 -n等类似的操作（本字段可以做加减法处理）
     * @param $where  array   查询条件
     * @param $param   sting/array  需要增加或减少N的字段  默认减1
     * @return bool
     * 备注 ：$param['表字段'] = 5 为 本字段加上5  $param['表字段'] = -5 为 本字段减去5 $param = '表字段'  为本字段加1 
     */
    function update_supplier_setInc($param,$where) {
        return update_table_original_field('supplier', $where, $param);
    }

    /**
     * @return 获取单条supplier信息find
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @return	array 数组格式的返回结果
     */
    public function select_supplier_info($field = '*', $where) {
        return $this->table('supplier')->field($field)->where($where)->find();
    }

    /**
     * @return 取得入驻商列表select
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @param string $order 排序参数
     * @param string $limit 分页参数
     * @return array    二维数组
     */
    public function get_supplier_list($field, $where, $order = '', $limit = 0) {
        return get_one_table_list('supplier', $field, $where, $order, $limit, 'select');
    }

    /**
    * @return 两表链表查询入驻商商品信息supplier-goods返回find
    * @param string $field 需要查询字段
    * @param string/array $where 查询条件
    * @param string $order 排序参数
    * @param string $limit 分页参数
    * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
    * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
    * inner join(等值连接)      只返回两个表中联结字段相等的行
    */
    public function select_supplier_goods_info($field,$where){
        return $this->table('supplier,goods')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on('supplier.goods_id=goods.goods_id')
        ->where($where)
        ->find();
    }

    /**
    * @return 两表链表查询入驻商商品信息supplier-goods返回select
    * @param string $field 需要查询字段
    * @param string/array $where 查询条件
    * @param string $order 排序参数
    * @param string $limit 分页参数
    * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
    * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
    * inner join(等值连接)      只返回两个表中联结字段相等的行
    */
    public function get_supplier_goods_list($field,$where,$order = '',$limit = ''){
        return $this->table('supplier,goods')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on('supplier.goods_id=goods.goods_id')
        ->where($where)
        ->order($order)
        ->limit($limit)
        ->select();
    }
   
    /**
     * @return 获取供应商记录数
     * @param $where string
     */
    public function get_supplier_count($where = '') {
        $param = array();
        $param['table'] = 'supplier';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }

    /**
     * @return 两表链表查询供应商信息supplier-users,find
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @param string $order 排序参数
     * @param string $limit 分页参数
     * @return type
     * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
     * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
     * inner join(等值连接)      只返回两个表中联结字段相等的行
     */
    public function get_supplier_users_info($field, $where, $order = '', $limit = '') {
        return $this->table('supplier,users')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on('supplier.user_id = users.user_id')
        ->where($where)
        ->order($order)
        ->limit($limit)
        ->find();
    }

    /***************入驻商管理员操作***************/

    /**
     * @return 新添加驻商管理员信息
     * @param array $insert 新数据
     * @return keyid 返回添加成功以后id
     */
    public function insert_supplier_admin_user($insert) {
        return add_table_info('supplier_admin_user', $insert);
    }

    /**
     * @return 编辑驻商管理员信息boolean
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_supplier_admin_user($param, $where) {
        return update_table_info('supplier_admin_user', $param, $where);
    }

    /**
     * @return 获取入驻商管理员信息
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @param string $order 排序参数
     * @param string $limit 分页参数
     * @return type
     */
    public function get_supplier_admin_user_list($field, $where, $order = '', $limit = 0) {
        return get_one_table_list('supplier_admin_user', $field, $where, $order, $limit, 'select');
    }

    /***************入驻商积分操作***************/

    /**
     * @return 新添加商家积分信息
     * @param array $insert 新数据
     * @return keyid 返回添加成功以后id
     */
    public function insert_supplier_integral($insert) {
        return add_table_info('supplier_integral', $insert);
    }

    /**
     * @return 获取入驻商积分列表select
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @param string $order 排序参数
     * @param string $limit 分页参数
     * @return type
     */
    public function get_supplier_integral_list($field, $where, $order = '', $limit = 0) {
        return $this->table('supplier_integral')->field($field)->where($where)->order($order)->limit($limit)->select();
    }

    /**
     * @return 查询总入驻商积分记录个数count 
     * @param string/array $where    查询条件
     * @return string
     */
    public function get_supplier_integral_count($where) {
        $param = array();
        $param['table'] = 'supplier_integral';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }

    /***************入驻商订单操作***************/

    /**
     * @return  获取单条入驻商订单信息find
     * @param   string $field 显示字段
     * @param   string/array $where 条件
     * @return  array 返回结果
     */
    public function select_supplier_order_info($field = '*', $where='') {
        $result = get_one_table_list('supplier_order', $field, $where, '', '', 'find');
        return $result;
    }

    /**
     * @return 获取入驻商订单列表select
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  string $order 排序参数
     * @param  string $limit 分页参数
     * @param  string $group 分组参数
     * @return array
     */
    public function get_supplier_order_list($field, $where, $order='' , $limit = 0,$group='') {
        return get_one_table_list('supplier_order', $field, $where, $order, $limit, 'select',$group);
    }

    /***************入驻商结算操作***************/

    /**
     * @return 编辑结算信息
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_sell_balance($param, $where) {
        return update_table_info('sell_balance', $param, $where);
    }

    /**
     * @return  获取单条结算信息find
     * @param   string $field 显示字段
     * @param   string/array $where 条件
     * @return  array 返回结果
     */
    public function select_sell_balance_info($field = '*', $where='') {
        $result = get_one_table_list('sell_balance', $field, $where, '', '', 'find');
        return $result;
    }   

    /**
     * @return 三表链表查询结算订单商品信息sell_balance-goods-brand
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @param string $order 排序参数
     * @param string $limit 分页参数
     * @return type
     * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
     * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
     * inner join(等值连接)      只返回两个表中联结字段相等的行
     */
    public function get_sell_balance_goods_brand_list($field, $where, $order = '', $limit = '') {
        return $this->table('sell_balance,goods,brand')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on('sell_balance.goods_id = goods.goods_id,goods.brand_id = brand.brand_id')
        ->where($where)
        ->order($order)
        ->limit($limit)
        ->select();
    }
   
    /**
     * @return 两表链表查询入驻商结算信息sell_balance-supplier 
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @param string $order 排序参数
     * @param string $limit 分页参数
     * @return type
     * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
     * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
     * inner join(等值连接)      只返回两个表中联结字段相等的行
     */
    public function get_sell_balance_supplier_list($field, $where, $order = '', $limit = '') {
        return $this->table('sell_balance,supplier')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on('sell_balance.supplier_id = supplier.supplier_id ')
        ->where($where)
        ->order($order)
        ->limit($limit)
        ->select();
    }

    /**
     * @return 查询结算信息总个数count 
     * @param string/array $where    查询条件
     * @return string
     */
    public function get_sell_balance_count($where) {
        $param = array();
        $param['table'] = 'sell_balance';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }

    /***************入驻商付费功能申请操作***************/

    /**
     * @return 删除入驻商付费功能申请信息bool
     * @param $where  array/string  删除的条件
     * @return bool
     */
    public function delete_supplier_module_apply($where) {
        return delete_table_info('supplier_module_apply', $where);
    }

    /**
     * @return 编辑入驻商付费功能申请信息boolean
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_supplier_module_apply($param, $where) {
        return update_table_info('supplier_module_apply', $param, $where);
    }

    /**
     * @return 获取单条入驻商付费功能申请信息find
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @return  array 数组格式的返回结果
     */
    public function select_supplier_module_apply_info($field = '*', $where) {
        return get_one_table_list('supplier_module_apply', $field, $where, '', '', 'find');
    }

    /**
     * @return 两表链表查询入驻商付费功能申请信息supplier_module_apply-users 
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @param string $order 排序参数
     * @param string $limit 分页参数
     * @return type
     * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
     * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
     * inner join(等值连接)      只返回两个表中联结字段相等的行
     */
    public function get_supplier_module_apply_users_list($field, $where, $order = '', $limit = '') {
        return $this->table('supplier_module_apply,users')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on('supplier_module_apply.user_id = users.user_id')
        ->where($where)
        ->order($order)
        ->limit($limit)
        ->select();
    }

    /**
     * @return 查询总入驻商付费功能申请记录个数count 
     * @param string/array $where    查询条件
     * @return string
     */
    public function get_supplier_module_apply_count($where) {
        $param = array();
        $param['table'] = 'supplier_module_apply';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }

    /***************入驻商标签操作***************/
    
    /**
     * @return 新添加店铺标签信息
     * @param array $insert 新数据
     * @return keyid 返回添加成功以后id
     */
    public function insert_supplier_tag_map($insert) {
        return add_table_info('supplier_tag_map', $insert);
    }

    /**
     * @return 删除店铺标签
     * @param $where  array/string  删除的条件
     * @return bool
     */
    public function delete_supplier_tag_map($where) {
        return delete_table_info('supplier_tag_map', $where);
    }
    
    /**
     * @return 编辑店铺标签信息boolean
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_supplier_tag_map($param, $where) {
        return update_table_info('supplier_tag_map', $param, $where);
    }

    /**
     * @return 获取店铺标签列表select
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @param string $order 排序参数
     * @param string $limit 分页参数
     * @return type
     */
    public function get_supplier_tag_map_list($field, $where, $order = '', $limit = 0) {
        return get_one_table_list('supplier_tag_map', $field, $where, $order, $limit, 'select');
    }

    /**
     * @return 两表链表查询店铺标签信息supplier_tag_map_supplier_tag Description
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @param string $order 排序参数
     * @param string $limit 分页参数
     * @return type
     * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
     * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
     * inner join(等值连接)      只返回两个表中联结字段相等的行
     */
    public function get_supplier_tag_map_supplier_tag_list($field, $where, $order = '', $limit = '') {
        return $this->table('supplier_tag_map,supplier_tag')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on('supplier_tag_map.tag_id=supplier_tag.tag_id')
        ->where($where)
        ->order($order)
        ->limit($limit)
        ->select();
    }

    /**
     * @return 获取店铺标签列表select
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @param string $order 排序参数
     * @param string $limit 分页参数
     * @return type
     */
    public function get_supplier_tag_list($field, $where, $order = '', $limit = 0) {
        return get_one_table_list('supplier_tag', $field, $where, $order, $limit, 'select');
    }


}
