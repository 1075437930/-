<?php

/**
 * 淘玉php 文章公共方法
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * 文章公共方法
 * $Id: article.php 17217 2015-05-19 06:29:08Z 淘玉 $
 */
defined('TaoyuShop') or exit('Access Invalid!index');
    
    /**
     * @return 获得指定分类下的子分类的数组
     * @access  public
     * @param   int     $cat_id     分类的ID
     * @param   int     $selected   当前选中分类的ID
     * @param   boolean $re_type    返回的类型: 值为真时返回下拉列表,否则返回数组
     * @param   int     $level      限定返回的级数。为0时返回所有级数
     * @return  mix
     */
    function article_cat_list($cat_id = 0, $selected = 0, $re_type = true, $level = 0) {
        $res = Model('article')->get_article_cat_article_list();   
        if (empty($res) == true) {
            return $re_type ? '' : array();
        }
        $options = cat_options($cat_id, $res,'article'); // 获得指定分类下的子分类的数组
        /* 截取到指定的缩减级别 */
        if ($level > 0) {
            if ($cat_id == 0) {
                $end_level = $level;
            } else {
                $first_item = reset($options); // 获取第一个元素
                $end_level = $first_item['level'] + $level;
            }

            /* 保留level小于end_level的部分 */
            foreach ($options AS $key => $val) {
                if ($val['level'] >= $end_level) {
                    unset($options[$key]);
                }
            }
        }

        $pre_key = 0;
        foreach ($options AS $key => $value) {
            $options[$key]['has_children'] = 1;
            if ($pre_key > 0) {
                if ($options[$pre_key]['cat_id'] == $options[$key]['parent_id']) {
                    $options[$pre_key]['has_children'] = 1;
                }
            }
            $pre_key = $key;
        }

        if ($re_type == true) {
            $select = '';
            foreach ($options AS $var) {
                $select .= '<option value="' . $var['cat_id'] . '" ';
                $select .= ' cat_type="' . $var['cat_type'] . '" ';
                $select .= ($selected == $var['cat_id']) ? "selected='ture'" : '';
                $select .= '>';
                if ($var['level'] > 0) {
                    $select .= str_repeat('&nbsp;', $var['level'] * 4);
                }
                $select .= htmlspecialchars(addslashes($var['cat_name'])) . '</option>';
            }

            return $select;
        } else {
            foreach ($options AS $key => $value) {
                $options[$key]['url'] = build_uri('article_cat', array('acid' => $value['cat_id']), $value['cat_name']);
            }
            return $options;
        }
    }

    /**
     * @return 获得指定文章分类下所有底层分类的ID
     *
     * @access  public
     * @param   integer     $cat        指定的分类ID
     *
     * @return void
     */
    function get_article_children($cat = 0) {
        return db_create_in(array_unique(array_merge(array($cat), array_keys(article_cat_list($cat, 0, false)))), 'cat_id');
    }