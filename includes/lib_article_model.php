<?php

/**
 * 淘玉php 文章数据模型
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * 管理员信息增删改查
 * $Id: lib_article_model.php 17217 2015-05-19 06:29:08Z 淘玉 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

class articleModel extends Model {

    /**
     * @return 文章列表select
     * @param type $field
     * @param type $where
     * @param type $order
     * @param type $limit
     * @return type
     */
    public function get_article_list($field, $where, $order = '', $limit = 0) {
        $result = get_one_table_list('article', $field, $where, $order, $limit, 'select');
        return $result;
    }

    /**
     * @return 编辑修改文章信息boolean
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_article($param, $where) {
        $result = Db::update('article', $param, $where);
        return $result;
    }


    /**
     * @return 编辑修改文章视频信息boolean
     * @param array $param 更新数据
     * @param array/string $where 更新条件
     * @return bool 布尔类型的返回结果
     */
    public function update_article_video($param, $where) {
        $result = Db::update('article_video', $param, $where);
        return $result;
    }

    /**
     * @return 添加新文章信息keyid
     * @param array $insert 更新数据
     * @return keyid 返回添加成功以后id
     */
    public function insert_article($insert) {
        return add_table_info('article', $insert);
    }

    /**
     * @return 获取视频列表select
     * @param type $field
     * @param type $where
     * @param type $order
     * @param type $limit
     * @return type
     */
    public function get_article_video_list($field, $where, $order = '', $limit = 0) {
        $result = get_one_table_list('article_video', $field, $where, $order, $limit, 'select');
        return $result;
    }
    
    /**
     * @return 添加新文章视频信息keyid
     * @param array $insert 更新数据
     * @return keyid 返回添加成功以后id
     */
    public function insert_article_video($insert) {
        return add_table_info('article_video', $insert);
    }

    /**
     * @return 获取单个文章信息find
     * @param	string $field 显示字段
     * @param	array $where 管理员条件
     * @return	array 数组格式的返回结果
     */
    public function select_article_info($field = '*', $where) {
        $result = Model()->table('article')->field($field)->where($where)->find();
        return $result;
    }
    
   /**
     * @return 获取单个文章分类信息find
     * @param	string $field 显示字段
     * @param	array $where 管理员条件
     * @return	array 数组格式的返回结果
     */
    public function select_article_cat_info($field = '*', $where) {
        $result = Model()->table('article_cat')->field($field)->where($where)->find();
        return $result;
    }

    /**
     * @return 文章分类列表select
     * @param type $field
     * @param type $where
     * @param type $order
     * @param type $limit
     * @return type
     */
    public function get_article_cat_list($field, $where, $order = '', $limit = 0) {
        $result = get_one_table_list('article_cat', $field, $where, $order, $limit, 'select');
        return $result;
    }

    /**
     * @return 更新文章分类信息boolean
     * @param array $param 更新数据
     * @return bool 布尔类型的返回结果
     */
    public function update_article_cat($param, $where) {
        $result = Db::update('article_cat', $param, $where);
        return $result;
    }

    /**
     * @return 查询总文章个数count 
     * @param string/array $where    查询条件
     * @return string
     */
    function get_article_count($where) {
        $param = array();
        $param['table'] = 'article';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }
    
    /**
     * @return 查询文章分类对应id的二级分类个数count 
     * @param string/array $where    查询条件
     * @return string
     */
    function get_article_cat_count($where) {
        $param = array();
        $param['table'] = 'article_cat';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }
    
    /**
     * @return 查询文章评论总个数count 
     * @param string/array $where    查询条件
     * @return string
     */
    function get_article_comment_count($where) {
        $param = array();
        $param['table'] = 'article_comment';
        $param['count'] = 'count';
        $param['where'] = $where;
        return Db::select($param);
    }

    /**
     * @return 添加新文章分类信息keyid
     * @param array $insert 更新数据
     * @return keyid 返回添加成功以后id
     */
    public function insert_article_cat($insert) {
        return add_table_info('article_cat', $insert);
    }

    /**
     * @return 删除指定文章信息bool
     * @param $where  array/string  删除的条件
     * @return bool
     */
    function delete_article($where) {
        $deladdre = delete_table_info('article', $where);
        return $deladdre;
    }

    /**
     * @return 删除指定文章分类信息bool
     * @param $where  array/string  删除的条件
     * @return bool
     */
    function delete_article_cat($where) {
        $deladdre = delete_table_info('article_cat', $where);
        return $deladdre;
    }

 

    
    
    /**
     * @return 修改文章对应评论信息bool
     * @param $where  array/string  删除的条件
     * @return bool
     */
    public function update_article_comment($param, $where) {
        $result = Db::update('article_comment', $param, $where);
        return $result;
    }

    /**
     * @return 获取现有文章的最大id值getRow
     * @param   object  $where 过滤条件
     */
    function select_article_maxid() {
        /* 取得数据 */
        $sql = 'SELECT MAX(article_id)+1 AS article_id FROM ' . $this->tablename('article');
        $row = DB::getRow($sql);
        return $row;
    }

   

    /**
     * @return 文章评论列表select
     * @param type $field
     * @param type $where
     * @param type $order
     * @param type $limit
     * @return type
     */
    public function get_article_comment_list($field, $where, $order = '', $limit = 0) {
        $result = get_one_table_list('article_comment', $field, $where, $order, $limit, 'select');
        return $result;
    }

    /**
     * @return 获取单个文章评论信息find
     * @param	string $field 显示字段
     * @param	array $where 管理员条件
     * @return	array 数组格式的返回结果
     */
    public function select_article_comment_info($field = '*', $where) {
        $result = Model()->table('article_comment')->field($field)->where($where)->find();
        return $result;
    }

    /**
     * @return 删除文章所有关注bool
     * @param $where  array/string  删除的条件
     * @return bool
     */
    function delete_article_collection($where) {
        $deladdre = delete_table_info('article_collection', $where);
        return $deladdre;
    }

    /**
     * @return 删除文章所有点赞bool
     * @param $where  array/string  删除的条件
     * @return bool
     */
    function delete_article_zan($where) {
        $deladdre = delete_table_info('article_zan', $where);
        return $deladdre;
    }

    /**
     * @return 删除文章所有评论信息bool
     * @param $where  array/string  删除的条件
     * @return bool
     */
    function delete_article_comment($where) {
        $deladdre = delete_table_info('article_comment', $where);
        return $deladdre;
    }

    /**
     * @return 删除文章所有评论点赞信息信息bool
     * @param $where  array/string  删除的条件
     * @return bool
     */
    function delete_acomment_zan($where) {
        $deladdre = delete_table_info('comment_zan', $where);
        return $deladdre;
    } 
    
    /**
     * @return 链表获取分类下面所有分类和对应文章数据 Description
     */
    function get_article_cat_article_list(){
        $sql = "SELECT c.*, COUNT(s.cat_id) AS has_children, COUNT(a.article_id) AS aricle_num " .
                ' FROM ' . $this->tablename('article_cat') . " AS c" .
                " LEFT JOIN " . $this->tablename('article_cat') . " AS s ON s.parent_id=c.cat_id" .
                " LEFT JOIN " . $this->tablename('article') . " AS a ON a.cat_id=c.cat_id" .
                " GROUP BY c.cat_id " .
                " ORDER BY parent_id, sort_order ASC";
        return DB::getAll($sql);
    }

}
