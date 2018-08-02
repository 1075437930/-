<?php
/**
 * 淘玉 商品购物车数据模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 商品购物车信息增删改查
 * $Id: lib_goods_model.php 17217 2018-04-07  萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!');
class cartModel extends Model{
    /**
     * @return 查询商品购物车总个数count
     * @param string/Array $where 查询条件
     * @return string
     */
    public function get_cart_count($where){
       $param = array();
       $param['table'] = 'cart';
       $param['count'] = 'count';
       $param['where'] = $where;
       return DB::select($param);
    }
    
    /**
     * @return 商品购物车列表select
     * @param $field  str 搜索字段
     * @param $where  str/array 搜索条件
     * @param $order  str 排序条件
     * @param $limit  str 限制条件
     */
    public function get_cart_list($field='*',$where,$order='' , $limit = 0) {
        $result = get_one_table_list('cart', $field, $where, $order, $limit, 'select');
        return $result;
    }

    /**
     * @return 获取单条商品购物车信息find
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @return	array 数组格式的返回结果
     */
    public function select_cart_info($field = '*', $where) {
       return get_one_table_list('cart', $field, $where, '', '', 'find');
    }
    
    
    /**
     * @return  插入商品购物车表 Description
     * @param   array  $insert 插入条件
     */
    public function insert_cart($insert) {
        return add_table_info('cart', $insert);
    }
    
    /**
     * @return 编辑商品购物车信息boolean
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_cart($param,$where) {
        return update_table_info('cart', $param, $where);
    }



    /**
     * @param string/array $where 删除的条件
     * @return 删除商品购物车信息bool
     */
    public function delete_cart($where){
        $result = delete_table_info('cart', $where);
        return $result;
    }

    
    /**
    * @return 两表链表查询goods_gallery-goods返回select
    * @param string $field 需要查询字段
    * @param string/array $where 查询条件
    * @param string $order 排序参数
    * @param string $limit 分页参数
    * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
    * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
    * inner join(等值连接)      只返回两个表中联结字段相等的行
    */
   public function get_cart_goods_list($field,$where,$order = '',$limit = ''){
        return $this->table('cart,goods')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on('cart.goods_id=goods.goods_id')
        ->where($where)
        ->order($order)
        ->limit($limit)
        ->select();
   }
   
    

}
