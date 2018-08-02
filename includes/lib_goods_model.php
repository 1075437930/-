<?php
/**
 * 淘玉 商品数据模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 商品信息增删改查
 * $Id: lib_goods_model.php 17217 2018-04-07  萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!');
class goodsModel extends Model{
    /**
     * @return 查询商品总个数count
     * @param string/Array $where 查询条件
     * @return string
     */
    public function get_goods_count($where){
       $param = array();
       $param['table'] = 'goods';
       $param['count'] = 'count';
       $param['where'] = $where;
       return DB::select($param);
    }
    
    /**
     * @return 商品列表select
     * @param $field  str 搜索字段
     * @param $where  str/array 搜索条件
     * @param $order  str 排序条件
     * @param $limit  str 限制条件
     */
    public function get_goods_list($field='*',$where,$order='' , $limit = 0) {
        $result = get_one_table_list('goods', $field, $where, $order, $limit, 'select');
        return $result;
    }

    /**
     * @return 获取单条商品信息find
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @param $order  str 排序条件
     * @return	array 数组格式的返回结果
     */
    public function select_goods_info($field = '*', $where='',$order='') {
       return get_one_table_list('goods', $field, $where, $order, '', 'find');
    }

    /**
     * @return 编辑商品信息boolean
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_goods($param,$where) {
        return update_table_info('goods', $param, $where);
    }

    /**
     * @return 更新goods表的某个字段直+n等类似的操作boolean
     * @param $where  sting/array   查询条件
     * @param $param   sting/array  需要增加或减少N的字段  默认加1
     * 备注 ：$param['表字段'] = 5 为 本字段加5  $param = '表字段'  为本字段加1 
     */
    public function update_goods_setInc($where, $param) {
        return update_table_original_field('goods', $where, $param);
    }

    /**
     * @return 更新goods的某个字段直-n等类似的操作boolean
     * @param $where  array   查询条件
     * @param $param   sting/array  需要增加或减少N的字段  默认减1
     * 备注 ：$param['表字段'] = 5 为 本字段减5  $param = '表字段'  为本字段减1 
     */
    public function update_goods_setDec($where, $param) {
        return update_table_setDec('goods', $where, $param);
    }

    /**
     * @param string/array $where 删除的条件
     * @return 删除商品信息bool
     */
    public function delete_goods($where){
        $return = delete_table_info('goods', $where);
        return $return;
    }
    
    /**
     * @return  插入商品表 Description
     * @param   array  $insert 插入条件
     */
    public function insert_goods($insert) {
        return add_table_info('goods', $insert);
    }
    
  
    /**
     * @return 获取商品属性列表select
     * @param $field  str 搜索字段
     * @param $where  str/array 搜索条件
     * @param $order  str 排序条件
     * @param $limit  str 限制条件
     */
    public function get_goods_type_list($field='*',$where,$order='' , $limit = 0) {
        $result = get_one_table_list('goods_type', $field, $where, $order, $limit, 'select');
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
   public function get_goods_gallery_goods_list($field,$where,$order = '',$limit = ''){
        return $this->table('goods_gallery,goods')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on('goods_gallery.goods_id=goods.goods_id')
        ->where($where)
        ->order($order)
        ->limit($limit)
        ->select();
   }
   
    /**
     * @param string/array $where 删除的条件
     * @return 删除商品正方形图片关联信息bool
     */
    public function delete_goods_gallery($where){
        $return = delete_table_info('goods_gallery', $where);
        return $return;
    }
    
    /**
     * @return  插入商品正方形图片表 Description
     * @param   array  $insert 插入条件
     */
    public function insert_goods_gallery($insert) {
        return add_table_info('goods_gallery', $insert);
    }
    
    /**
     * @return 商品正方形图片列表select
     * @param $field  str 搜索字段
     * @param $where  str/array 搜索条件
     * @param $order  str 排序条件
     * @param $limit  str 限制条件
     */
    public function get_goods_gallery_list($field='*',$where,$order='' , $limit = 0) {
        $result = get_one_table_list('goods_gallery', $field, $where, $order, $limit, 'select');
        return $result;
    }
    
    /**
     * @return 获取单条商品正方形图片信息find
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @return	array 数组格式的返回结果
     */
    public function select_goods_gallery_info($field = '*', $where) {
       return get_one_table_list('goods_gallery', $field, $where, '', '', 'find');
    }
    
    /**
     * @return 编辑商品正方形图片信息boolean
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_goods_gallery($param,$where) {
        return update_table_info('goods_gallery', $param, $where);
    }
    
    
    
    /* 下面是编辑长图信息的model*/
    
    
     /**
    * @return 两表链表查询goods_figure-goods返回select
    * @param string $field 需要查询字段
    * @param string/array $where 查询条件
    * @param string $order 排序参数
    * @param string $limit 分页参数
    * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
    * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
    * inner join(等值连接)      只返回两个表中联结字段相等的行
    */
   public function get_goods_figure_goods_list($field,$where,$order = '',$limit = ''){
        return $this->table('goods_figure,goods')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on('goods_figure.goods_id=goods.goods_id')
        ->where($where)
        ->order($order)
        ->limit($limit)
        ->select();
   }
    
    
    /**
     * @param string/array $where 删除的条件
     * @return 删除商品长方形图片关联信息bool
     */
    public function delete_goods_figure($where){
        $return = delete_table_info('goods_figure', $where);
        return $return;
    }
    
    /**
     * @return  插入商品长方形图片表 Description
     * @param   array  $insert 插入条件
     */
    public function insert_goods_figure($insert) {
        return add_table_info('goods_figure', $insert);
    }
    
    /**
     * @return 商品长方形图片列表select
     * @param $field  str 搜索字段
     * @param $where  str/array 搜索条件
     * @param $order  str 排序条件
     * @param $limit  str 限制条件
     */
    public function get_goods_figure_list($field='*',$where,$order='' , $limit = 0) {
        $result = get_one_table_list('goods_figure', $field, $where, $order, $limit, 'select');
        return $result;
    }
    
    /**
     * @return 获取单条商品长方形图片信息find
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @return	array 数组格式的返回结果
     */
    public function select_goods_figure_info($field = '*', $where) {
       return get_one_table_list('goods_figure', $field, $where, '', '', 'find');
    }
    
    /**
     * @return 编辑商品长方形图片信息boolean
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_goods_figure($param,$where) {
        return update_table_info('goods_figure', $param, $where);
    }
    
    
     /**
    * @return 两表链表查询goods-supplier返回select
    * @param string $field 需要查询字段
    * @param string/array $where 查询条件
    * @param string $order 排序参数
    * @param string $limit 分页参数
    * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
    * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
    * inner join(等值连接)      只返回两个表中联结字段相等的行
    */
   public function get_goods_supplier_list($field,$where,$order = '',$limit = ''){
        return $this->table('goods,supplier')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on('goods.goods_id=supplier.goods_id')
        ->where($where)
        ->order($order)
        ->limit($limit)
        ->select();
   }
   
   /**
    * @return 两表链表查询goods-supplier返回find
    * @param string $field 需要查询字段
    * @param string/array $where 查询条件
    * @param string $order 排序参数
    * @param string $limit 分页参数
    * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
    * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
    * inner join(等值连接)      只返回两个表中联结字段相等的行
    */
   public function select_goods_supplier_info($field,$where){
        return $this->table('goods,supplier')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on('goods.goods_id=supplier.goods_id')
        ->where($where)
        ->find();
   }

}
