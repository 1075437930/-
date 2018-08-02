<?php
/**
 * 淘玉 商品属性数据关联模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 商品属性数据信息增删改查
 * $Id: lib_goods_attr_model.php 17217 2018-04-07  萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!');
class goods_attrModel extends Model{
    /**
     * @return 查询商品属性关联总个数count
     * @param string/Array $where 查询条件
     * @return string
     */
    public function get_goods_attr_count($where){
       $param = array();
       $param['table'] = 'goods_attr';
       $param['count'] = 'count';
       $param['where'] = $where;
       return DB::select($param);
    }
    
    /**
     * @return 商品属性关联列表select
     * @param $where  str/array 搜索条件
     * @param $limit  str 限制条件
     * @param $order  str 排序条件
     * @param $field  str 搜索字段
     */
    public function get_goods_attr_list($field='*',$where,$order='' , $limit = 0) {
        $result = get_one_table_list('goods_attr', $field, $where, $order, $limit, 'select');
        return $result;
    }

    /**
     * @return 获取单条商品属性关联信息find
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @return	array 数组格式的返回结果
     */
    public function select_goods_attr_info($field = '*', $where) {
       return get_one_table_list('goods_attr', $field, $where, '', '', 'find');
    }

    /**
     * @return 编辑商品属性关联信息boolean
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_goods_attr($param,$where) {
        return update_table_info('goods_attr', $param, $where);
    }


    /**
     * @param string/array $where 删除的条件
     * @return 删除商品属性关联信息bool
     */
    public function delete_goods_attr($where){
        $return = delete_table_info('goods_attr', $where);
        return $return;
    }
    
    /**
     * @return  插入商品属性关联表 Description
     * @param   array  $insert 插入条件
     */
    public function insert_goods_attr($insert) {
        return add_table_info('goods_attr', $insert);
    }
    
    /**
    * @return 两表链表查询attribute-goodsattr获取产品属性内容返回select
    * @param string $field 需要查询字段
    * @param string/array $where 查询条件
    * @param string $ons 两表关联条件
    * @param string $order 排序参数
    * @param string $limit 分页参数
    * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
    * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
    * inner join(等值连接)      只返回两个表中联结字段相等的行
    */

    
   public function get_attribute_goodsattr_list($field,$where,$ons,$order = '',$limit = ''){
        return $this->table('attribute,goods_attr')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on($ons)
        ->where($where)
        ->order($order)
        ->limit($limit)
        ->select();
   }
   
   /**
    * @return 两表链表查询goodsattr-attribute获取产品属性内容返回find
    * @param string $field 需要查询字段
    * @param string/array $where 查询条件
    * @param string $ons 两表关联条件
    * @param string $order 排序参数
    * @param string $limit 分页参数
    * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
    * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
    * inner join(等值连接)      只返回两个表中联结字段相等的行
    */

    
   public function get_goodsattr_attribute_list($field,$where,$ons){
        return $this->table('goods_attr,attribute')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on($ons)
        ->where($where)
        ->find();
   }
   
   /**
     * @return 属性列表select
     * @param $where  str/array 搜索条件
     * @param $limit  str 限制条件
     * @param $order  str 排序条件
     * @param $field  str 搜索字段
     */
    public function get_attribute_list($field='*',$where,$order='' , $limit = 0) {
        $result = get_one_table_list('attribute', $field, $where, $order, $limit, 'select');
        return $result;
    }

    /**
     * @return 获取单条属性信息find
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @return	array 数组格式的返回结果
     */
    public function select_attribute_info($field = '*', $where) {
       return get_one_table_list('attribute', $field, $where, '', '', 'find');
    }
    
    
  

}
