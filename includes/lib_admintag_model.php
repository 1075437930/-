<?php
/**
 * 淘玉 商品标签数据关联模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 商品标签数据信息增删改查
 * $Id: lib_admintag_model.php 17217 2018-04-07  萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!');
class admintagModel extends Model{
    /**
     * @return 查询商品标签关联总个数count
     * @param string/Array $where 查询条件
     * @return string
     */
    public function get_goods_admintag_count($where){
       $param = array();
       $param['table'] = 'goods_admintag';
       $param['count'] = 'count';
       $param['where'] = $where;
       return DB::select($param);
    }
    
    /**
     * @return 商品标签关联列表select
     * @param $where  str/array 搜索条件
     * @param $limit  str 限制条件
     * @param $order  str 排序条件
     * @param $field  str 搜索字段
     */
    public function get_goods_admintag_list($field='*',$where,$order='' , $limit = 0) {
        $result = get_one_table_list('goods_admintag', $field, $where, $order, $limit, 'select');
        return $result;
    }

    /**
     * @return 获取单条商品标签关联信息find
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @return	array 数组格式的返回结果
     */
    public function select_goods_admintag_info($field = '*', $where) {
       return get_one_table_list('goods_admintag', $field, $where, '', '', 'find');
    }

    /**
     * @return 编辑商品标签关联信息boolean
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_goods_admintag($param,$where) {
        return update_table_info('goods_admintag', $param, $where);
    }


    /**
     * @param string/array $where 删除的条件
     * @return 删除商品标签关联信息bool
     */
    public function delete_goods_admintag($where){
        $return = delete_table_info('goods_admintag', $where);
        return $return;
    }
    
    /**
     * @return  插入产品tags信息表 Description
     * @param   array  $insert 插入条件
     */
    public function insert_goods_admintag($insert) {
        return add_table_info('goods_admintag', $insert);
    }
    
    
    /**
     * @return  插入产品tags信息表多条数据 Description
     * @param   array(array)  $insert 插入数据二维数组
     */
    public function insert_goods_admintag_all($insert) {
        return add_table_all('goods_admintag', $insert);
    }
    /**
     * @return 商品标签分类列表select
     * @param $where  str/array 搜索条件
     * @param $limit  str 限制条件
     * @param $order  str 排序条件
     * @param $field  str 搜索字段
     */
    public function get_admintag_class_list($field='*',$where,$order='' , $limit = 0) {
        $result = get_one_table_list('admintag_class', $field, $where, $order, $limit, 'select');
        return $result;
    }

    /**
     * @return 获取单条分类信息find
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @return	array 数组格式的返回结果
     */
    public function select_admintag_class_info($field = '*', $where) {
        return get_one_table_list('admintag_class', $field, $where, '', '', 'find');
    }

    /**
     * @return 编辑标签分类信息boolean
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_admintag_class($param,$where) {
        return update_table_info('admintag_class', $param, $where);
    }

    /**
     * @param string/array $where 删除的条件
     * @return 删除标签分类信息bool
     */
    public function delete_admintag_class($where){
        return delete_table_info('admintag_class', $where);
    }


    /**
     * @return  插入分类信息 Description
     * @param   array  $insert 插入条件
     */
    public function insert_admintag_class($insert) {
        return add_table_info('admintag_class', $insert);
    }

    /**
     * @return  插入标签信息 Description
     * @param   array  $insert 插入条件
     */
    public function insert_admintag($insert) {
        return add_table_info('admintag', $insert);
    }

    /**
     * @return 获取单条标签信息find
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @return	array 数组格式的返回结果
     */
    public function select_admintag_info($field = '*', $where) {
        return get_one_table_list('admintag', $field, $where, '', '', 'find');
    }

    /**
     * @return 商品标签列表select
     * @param $where  str/array 搜索条件
     * @param $limit  str 限制条件
     * @param $order  str 排序条件
     * @param $field  str 搜索字段
     */
    public function get_admintag_list($field='*',$where,$order='' , $limit = 0){
        $result = get_one_table_list('admintag', $field, $where, $order, $limit, 'select');
        return $result;
    }

    /**
     * @return 编辑标签信息boolean
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_admintag($param,$where) {
        return update_table_info('admintag', $param, $where);
    }

    /**
     * @param string/array $where 删除的条件
     * @return 删除标签信息bool
     */
    public function delete_admintag($where){
        return delete_table_info('admintag', $where);
    }
    
    /**
    * @return 两表链表查询admintag-goods_admintag获取标签内容返回find
    * @param string $field 需要查询字段
    * @param string/array $where 查询条件
    * @param string $order 排序参数
    * @param string $limit 分页参数
    * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
    * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
    * inner join(等值连接)      只返回两个表中联结字段相等的行
    */
   public function select_admintag_goodstag_info($field,$where){
        return $this->table('admintag,goods_admintag')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on('admintag.tag_id=goods_admintag.tag_id')
        ->where($where)
        ->find();
   }
   
   /**
    * @return 两表链表查询admintag-goods_admintag获取标签内容返回select
    * @param string $field 需要查询字段
    * @param string/array $where 查询条件
    * @param string $on 链表条件
    * @param string $order 排序参数
    * @param string $limit 分页参数
    * left join(左联接) 　　　　 返回包括左表中的所有记录和右表中联结字段相等的记录 
    * right join(右联接) 　　　　返回包括右表中的所有记录和左表中联结字段相等的记录
    * inner join(等值连接)      只返回两个表中联结字段相等的行
    */
   public function get_admintag_goodstag_list($field,$where,$on,$order = '',$limit = ''){
        return $this->table('admintag,goods_admintag')
        ->field($field)
        ->join('left')//right 或者inner 
        ->on($on)
        ->where($where)
        ->order($order)
        ->limit($limit)       
        ->select();
   }

}
