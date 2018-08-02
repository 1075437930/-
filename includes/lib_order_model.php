<?php

/**
 * 淘玉 订单数据模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 订单信息增删改查count
 * $Id: lib_order_model.php 17217 2018-04-07  萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class orderModel extends Model{
   
    /**
     * @return 查询总订单个数count 
     * @param  string/array $where    查询条件
     * @return string
     */
    public function get_order_info_count($where) {
        $param = array();
        $param['table'] = 'order_info';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }

    /**
     * @return 查询用户总订单个数count 
     * @param  string/array $where    查询条件
     * @return string
     */
    public function get_order_info_users_count($where='') {
        $res = $this->table('order_info,users')
        ->field('*')
        ->join('left')//right 或者inner 
        ->on('order_info.user_id=users.user_id')
        ->where($where)
        ->select();
        return count($res);
    }

    /**
     * @return 获取订单列表select
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  string $order 排序参数
     * @param  string $limit 分页参数
     * @return array
     */
    public function get_order_info_list($field, $where='', $order='' , $limit = 0,$group='') {
        return get_one_table_list('order_info', $field, $where, $order, $limit, 'select',$group);
    }
    
    
    /**
     * @return 获取单条订单信息find
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @return array 数组格式的返回结果
     */
    public function select_order_info_info($field = '*', $where) {
        return $this->table('order_info')->field($field)->where($where)->find();
    }
    
    /**
     * @return 删除订单bool
     * @param $where  array/string  删除的条件
     * @return bool
     */
    public function delete_order_info($where) {
        return delete_table_info('order_info', $where);
    }

    /**
     * @return 编辑订单信息boolean
     * @param  array $param 更新数据
     * @param  array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_order_info($param,$where) {
        return update_table_info('order_info', $param, $where);
    }

    /**
     * @return 更新order_info表的某个字段直+n等类似的操作
     * @param $where  array   查询条件
     * @param $param   sting/array  需要增加或减少N的字段  默认加1
     * @return bool
     * 备注 ：$param['表字段'] = 5 为 本字段加5  $param = '表字段'  为本字段加1 
     */
    public function update_order_info_setInc($where, $param) {
        return update_table_original_field('order_info', $where, $param);
    }

    /**
     * @return 更新order_info的某个字段直-n等类似的操作
     * @param $where  array   查询条件
     * @param $param   sting/array  需要增加或减少N的字段  默认减1
     * @return bool
     * 备注 ：$param['表字段'] = 5 为 本字段减5  $param = '表字段'  为本字段减1 
     */
    public function update_order_info_setDec($where, $param) {
        return update_table_setDec('order_info', $where, $param);
    }
    
    /**
     * @return 取得所有办事处select
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  string $order 排序参数
     * @param  string $limit 分页参数
     * @return array
     */
    public function get_agency_list($field, $where='', $order='' , $limit = 0) {
        return get_one_table_list('agency', $field, $where, $order, $limit, 'select');
    }

    /**
     * @return 链表查询单个订单区域名
     * @param  string $param 参数  
     * @param  string $where 查询条件
     * @param  string $order 排序参数
     * @param  string $limit 分页参数
     * @return array
     */
    public function get_order_region_list($param,$where='',$order='',$limit=0){
        return $this->table('order_info,region')
        ->field('region_name')
        ->join('left')//right 或者inner 
        ->on("order_info.".$param."=region.region_id")
        ->where($where)
        ->order($order)
        ->limit($limit)
        ->find();
    }   
   
    /**
     * @return 链表查询订单商品及货品select,(order_goods,products,goods,brand) 
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  string $order 排序参数
     * @param  string $limit 分页参数
     * @return type
     * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
     * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
     * inner join(等值连接)      只返回两个表中联结字段相等的行
     */
    public function get_order_goods_products_goods_brand_list($field,$where='',$order = '',$limit = ''){
        return $this->table('order_goods,products,goods,brand')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on('order_goods.product_id=products.product_id,order_goods.goods_id=goods.goods_id,goods.brand_id=brand.brand_id')
        ->where($where)
        ->order($order)
        ->limit($limit)
        ->select();
    } 
    

    /**
     * @return 链表查询订单信息，包括订单的自提点信息find,(order_info，pickup_point)
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  string $order 排序参数
     * @param  string $limit 分页参数
     * @return type
     * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
     * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
     * inner join(等值连接)      只返回两个表中联结字段相等的行
     */
    public function get_order_pickup_info($field='*',$where='',$order = '',$limit = ''){
        return $this->table('order_info,pickup_point')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on('order_info.pickup_point = pickup_point.id')
        ->where($where)
        ->order($order)
        ->limit($limit)
        ->find();
    }

    /**
     * @return 链表查询订单商品select,(order_goods、products、 goods、 brand、 goods_attr) 
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  string $order 排序参数
     * @param  string $limit 分页参数
     * @return type
     * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
     * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
     * inner join(等值连接)      只返回两个表中联结字段相等的行
     */
    public function get_order_goods($field,$where='',$order = '',$limit = ''){
        return $this->table('order_goods,products,goods,brand,goods_attr')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on('products.product_id = order_goods.product_id,order_goods.goods_id = goods.goods_id,goods.brand_id = brand.brand_id,order_goods.goods_attr_id = goods_attr.goods_attr_id')
        ->where($where)
        ->order($order)
        ->limit($limit)
        ->select();
    }

    /**
     * @return 链表查询订单商品信息find,(order_goods、order_goods) 
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @param string $order 排序参数
     * @param string $limit 分页参数
     * @return type
     * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
     * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
     * inner join(等值连接)      只返回两个表中联结字段相等的行
     */
    public function get_order_goods_goods_list($field,$where='',$order = '',$limit = ''){
        return $this->table('order_goods,goods')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on('order_goods.goods_id=goods.goods_id')
        ->where($where)
        ->order($order)
        ->limit($limit)
        ->find();
    }

    /**
     * @return 链表查询订单商品信息find,(order_goods、order_info) 
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @param  string $order 排序参数
     * @param  string $limit 分页参数
     * @return type
     * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
     * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
     * inner join(等值连接)      只返回两个表中联结字段相等的行
     */
    public function get_order_goods_order_info_list($field='*',$where='',$order = '',$limit = ''){
        return $this->table('order_info,order_goods')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on('order_info.order_id=order_goods.order_id')
        ->where($where)
        ->order($order)
        ->limit($limit)
        ->select();
    }
   
    /**
     * @return 获取单条订单快递信息find
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @return array 数组格式的返回结果
     */
    public function select_kuaidi_order_info($field = '*', $where) {
        return $this->table('kuaidi_order')->field($field)->where($where)->find();
    }

    /**
     * @return 查询订单商品总个数count 
     * @param  string/array $where    查询条件
     * @return string
     */
    public function get_order_goods_count($where) {
        $param = array();
        $param['table'] = 'order_goods';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }

    /**
     * @return 获取多条订单商品信息select
     * @param  str $field 搜索字段
     * @param  str/array $where 搜索条件
     * @param  str $order 排序条件
     * @param  str $limit 限制条件
     * @return array
     */
    public function get_order_goods_list($field = '*', $where='',$order='',$limit=0) {
        return get_one_table_list('order_goods', $field, $where, $order, $limit, 'select');
    }

    /**
     * @return 获取一条订单商品信息find
     * @param  str $field 搜索字段
     * @param  str/array $where 搜索条件
     * @param  str $order 排序条件
     * @param  str $limit 限制条件
     * @return array
     */
    public function select_order_goods_info($field = '*', $where='',$order='',$limit='') {
        return get_one_table_list('order_goods', $field, $where, $order, $limit, 'find');
    }


    /**
     * @return 编辑更新订单商品信息boolean
     * @param  array $param 更新数据
     * @param  array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_order_goods($param,$where) {
        return update_table_info('order_goods', $param, $where);
    }

    /**
     * @return 删除订单商品
     * @param  $where  array/string  删除的条件
     * @return bool
     */
    public function delete_order_goods($where) {
        return delete_table_info('order_goods', $where);
    }

    /**
     * @return 更新order_goods表的某个字段直+n等类似的操作
     * @param $where  array   查询条件
     * @param $param   sting/array  需要增加或减少N的字段  默认加1
     * @return bool
     * 备注 ：$param['表字段'] = 5 为 本字段加5  $param = '表字段'  为本字段加1 
     */
    public function update_order_goods_setInc($where, $param) {
        return update_table_original_field('order_goods', $where, $param);
    }

    /**
     * @return 更新order_goods的某个字段直-n等类似的操作
     * @param $where  array   查询条件
     * @param $param   sting/array  需要增加或减少N的字段  默认减1
     * @return bool
     * 备注 ：$param['表字段'] = 5 为 本字段减5  $param = '表字段'  为本字段减1 
     */
    public function update_order_goods_setDec($where, $param) {
        return update_table_setDec('order_goods', $where, $param);
    }

    /**
     * @return 获取订单快递状态信息列表
     * @param  string $field 需要查询字段
     * @param  string/array $where 查询条件
     * @return array 数组格式的返回结果
     */
    public function select_kuaidi_order_status_list($field, $where='', $order='' , $limit = 0) {
        return get_one_table_list('kuaidi_order_status', '*', $where, $order, '', 'select');
    }

     /**
     * @return 取得一条订单操作记录select
     * @param  str $field 查询字段
     * @param  str/array $where 条件
     * @return array
     */
    public function select_order_action_info($field, $where='', $order='' , $limit = 0) {
        $result = get_one_table_list('order_action', $field, $where, $order, $limit, 'find');
        return $result;
    }

    /**
     * @return 取得多条订单操作记录select
     * @param  str $field 查询字段
     * @param  str/array $where 条件
     * @return array
     */
    public function get_order_action_list($field, $where='', $order='' , $limit = 0) {
        $result = get_one_table_list('order_action', $field, $where, $order, $limit, 'select');
        return $result;
    }

    /**
     * @return 添加新订单操作记录keyid
     * @param  array $param 新数据
     * @return keyid 返回添加成功以后id
     */
    public function insert_order_action($param) {
        return add_table_info('order_action', $param);
    }

    /**
     * @return 删除订单操作信息
     * @param  $where  array/string  删除的条件
     * @return bool
     */
    public function delete_order_action($where) {
        return delete_table_info('order_action', $where);
    }

    /**
     * @return 取得订单自提点信息find
     * @param  str $field 查询字段
     * @param  str/array $where 条件
     * @return array
     */
    public function get_order_pickup_point($field,$where='') {
        $result = Model()->table('pickup_point')->field($field)->where($where)->find();
        return $result;
    }

    /**
     * @return 取得订单购买返点，邀请人返点
     * @param  str $sql 查询语句
     * @return mix
     * 备注：非特殊用途勿用
     */
    public function get_return_point($sql) {
        return Db::getRow($sql);
    }                

   
}

