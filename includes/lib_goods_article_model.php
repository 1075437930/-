<?php
/**
 * 淘玉 商品文章数据关联模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 商品文章数据信息增删改查
 * $Id: lib_goods_article_model.php 17217 2018-04-07  萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!');
class goods_articleModel extends Model{
    /**
     * @return 查询商品文章关联总个数count
     * @param string/Array $where 查询条件
     * @return string
     */
    public function get_goods_article_count($where){
       $param = array();
       $param['table'] = 'goods_article';
       $param['count'] = 'count';
       $param['where'] = $where;
       return DB::select($param);
    }
    
    /**
     * @return 商品文章关联列表select
     * @param $where  str/array 搜索条件
     * @param $limit  str 限制条件
     * @param $order  str 排序条件
     * @param $field  str 搜索字段
     */
    public function get_goods_article_list($field='*',$where,$order='' , $limit = 0) {
        $result = get_one_table_list('goods_article', $field, $where, $order, $limit, 'select');
        return $result;
    }

    /**
     * @return 获取单条商品文章关联信息find
     * @param string $field 需要查询字段
     * @param string/array $where 查询条件
     * @return	array 数组格式的返回结果
     */
    public function select_goods_article_info($field = '*', $where) {
       return get_one_table_list('goods_article', $field, $where, '', '', 'find');
    }

    /**
     * @return 编辑商品文章关联信息boolean
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_goods_article($param,$where) {
        return update_table_info('goods_article', $param, $where);
    }


    /**
     * @param string/array $where 删除的条件
     * @return 删除商品文章关联信息bool
     */
    public function delete_goods_article($where){
        $return = delete_table_info('goods_article', $where);
        return $return;
    }
    
    /**
     * @return  插入文章关联产品表 Description
     * @param   array  $insert 插入条件
     */
    public function insert_goods_article($insert) {
        return add_table_info('goods_article', $insert);
    }
    
     /**
     * @return 取得文章关联商品 Description
     * @param type $article_id 文章id
     * @return type
     */
    public function get_article_goods_list($article_id) {
        $fieldstr = "goods.goods_id,goods.goods_name";
        $where = " goods_article.article_id = '$article_id'";
        $param = array();
        $param['table'] = 'goods_article,goods';
        $param['join_type'] = 'left join';
        $param['field'] = $fieldstr;
        $param['join_on'] = array('goods.goods_id = goods_article.goods_id');
        $param['where'] = $where;
        $goods_list = Db::select($param);
        return $goods_list;
    }

}
