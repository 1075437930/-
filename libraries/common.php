<?php

/**
 * taoyuapp 公用函数库
 * ============================================================================
 * 版权所有 2005-2012 淘玉商城，并保留所有权利。
 * 网站地址: http://www.taoyumall.com；
 * ----------------------------------------------------------------------------
 * ============================================================================
 * $Author: taoyu $
 * $Id: lib_common_new.php 2017-09-13 wubo $
 */
defined('TaoyuShop') or exit('Access Invalid!route');

/**
 * @return 数据库过滤函数 Description
 * @param array $sql_str
 */
function inject_check($sql_str = 0) {
    //合并$_POST 和 $_GET
    $get = array();
    $post = array();
    foreach ($_GET as $get_key => $get_var) {
        $get[strtolower($get_key)] = $get_var;
    }
    /* 过滤所有POST过来的变量 */
    foreach ($_POST as $post_key => $post_var) {
        $post[strtolower($post_key)] = $post_var;
    }

    //需要过滤的数据
    if ($sql_str == 0) {
        $GetPost = '/select|insert|update|delete|union|into|load_file|outfile/i';
    } else {
        $GetPost = '/select|insert|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile|\(|\)|\<|\>|chr|char/i';
    }
    foreach ($post as $post_key => $sql_str) {
        $check = preg_match($GetPost, $sql_str); // 进行过滤

        if ($check) {
//            $this->error('输入内容不合法，请重新输入！');
//            exit();
            showMessage('参数不合法get');
//            output_data(false, ['mags' => '参数不合法get']);
        }
    }
    foreach ($get as $post_key => $sql_str) {
        $check = preg_match($GetPost, $sql_str); // 进行过滤
        if ($check) {
            showMessage('参数不合法get');
//            output_data(false, ['mags' => '参数不合法post']);
//            $this->error('输入内容不合法，请重新输入！');
//            exit();
        }
    }
}

/**
 * @return 取得上次的过滤条件
 * @param   string  $param_str  参数字符串，由list函数的参数组成
 * @return  如果有，返回array('filter' => $filter, 'sql' => $sql)；否则返回false
 */
function get_filter($param_str = '') {
    $filterfile = $_REQUEST['act'] . '_' . $_REQUEST['op'];
    if ($param_str) {
        $filterfile .= $param_str;
    }
    if (isset($_GET['uselastfilter']) && isset($_COOKIE['lastfilterfile']) && $_COOKIE['lastfilterfile'] == sprintf('%X', crc32($filterfile))) {
        return array(
            'filter' => unserialize(urldecode($_COOKIE['lastfilter'])),
            'sql' => base64_decode($_COOKIE['lastfiltersql'])
        );
    } else {
        return false;
    }
}

/**
 * @return 保存过滤条件
 * @param   array   $filter     过滤条件
 * @param   string  $sql        查询语句
 * @param   string  $param_str  参数字符串，由list函数的参数组成
 */
function set_filter($filter, $sql, $param_str = '') {
    $filterfile = $_REQUEST['act'] . '_' . $_REQUEST['op'];
    if ($param_str) {
        $filterfile .= $param_str;
    }
    setNcCookie('lastfilterfile', sprintf('%X', crc32($filterfile)), time() + 600);
    setNcCookie('lastfilter', urlencode(serialize($filter)), time() + 600);
    setNcCookie('lastfiltersq', base64_encode($sql), time() + 600);
}

/**
 * @return 分页的信息加入条件的数组
 *
 * @access  public
 * @return  array
 */
function page_and_size($filter) {
    if (isset($_REQUEST['page_size']) && intval($_REQUEST['page_size']) > 0) {
        $filter['page_size'] = intval($_REQUEST['page_size']);
    } elseif (isset($_COOKIE['page_size']) && intval($_COOKIE['page_size']) > 0) {
        $filter['page_size'] = intval($_COOKIE['page_size']);
    } else {
        $filter['page_size'] = 15;
    }

    /* 每页显示 */
    $filter['page'] = (empty($_REQUEST['page']) || intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);

    /* page 总数 */
    $filter['page_count'] = (!empty($filter['record_count']) && $filter['record_count'] > 0) ? ceil($filter['record_count'] / $filter['page_size']) : 1;

    /* 边界处理 */
    if ($filter['page'] > $filter['page_count']) {
        $filter['page'] = $filter['page_count'];
    }

    $filter['start'] = ($filter['page'] - 1) * $filter['page_size'];

    return $filter;
}

/**
 * @return 获得指定分类下的子分类的数组
 *
 * @access  public
 * @param   int     $cat_id     分类的ID
 * @param   int     $selected   当前选中分类的ID
 * @param   boolean $re_type    返回的类型: 值为真时返回下拉列表,否则返回数组
 * @param   int     $level      限定返回的级数。为0时返回所有级数
 * @param   int     $is_show_all 如果为true显示所有分类，如果为false隐藏不可见分类。
 * @return  mix
 */
function cat_list($cat_id = 0, $selected = 0, $re_type = true, $level = 0, $is_show_all = true) {
    static $res = NULL;

    if ($res === NULL) {
        $sql = "SELECT c.cat_id, c.cat_name,c.cat_img,c.measure_unit, c.parent_id, c.is_show, c.show_in_nav, c.grade, c.sort_order, COUNT(s.cat_id) AS has_children " .
                "FROM ecs_category AS c " .
                "LEFT JOIN ecs_category AS s ON s.parent_id=c.cat_id " .
                "where c.is_virtual= '0' and c.is_new_cat = 1 " .
                "GROUP BY c.cat_id " .
                'ORDER BY c.parent_id, c.sort_order ASC';
        $res = Db::getAll($sql);

        $sql = "SELECT cat_id, COUNT(*) AS goods_num " .
                " FROM ecs_goods" .
                " WHERE is_delete = 0  " .
                " GROUP BY cat_id";
        $res2 = Db::getAll($sql);

        $sql = "SELECT gc.cat_id, COUNT(*) AS goods_num " .
                " FROM  ecs_goods_cat AS gc , ecs_goods AS g " .
                " WHERE g.goods_id = gc.goods_id AND g.is_delete = 0  " .
                " GROUP BY gc.cat_id";
        $res3 = Db::getAll($sql);

        $newres = array();
        foreach ($res2 as $k => $v) {
            $newres[$v['cat_id']] = $v['goods_num'];
            foreach ($res3 as $ks => $vs) {
                if ($v['cat_id'] == $vs['cat_id']) {
                    $newres[$v['cat_id']] = $v['goods_num'] + $vs['goods_num'];
                }
            }
        }

        foreach ($res as $k => $v) {
            $res[$k]['goods_num'] = !empty($newres[$v['cat_id']]) ? $newres[$v['cat_id']] : 0;
        }
    }

    if (empty($res) == true) {
        return $re_type ? '' : array();
    }

    $options = cat_options($cat_id, $res,'cats'); // 获得指定分类下的子分类的数组

    $children_level = 99999; //大于这个分类的将被删除
    if ($is_show_all == false) {
        foreach ($options as $key => $val) {
            if ($val['level'] > $children_level) {
                unset($options[$key]);
            } else {
                if ($val['is_show'] == 0) {
                    unset($options[$key]);
                    if ($children_level > $val['level']) {
                        $children_level = $val['level']; //标记一下，这样子分类也能删除
                    }
                } else {
                    $children_level = 99999; //恢复初始值
                }
            }
        }
    }

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

    if ($re_type == true) {
        $select = '';
        foreach ($options AS $var) {
            $select .= '<option value="' . $var['cat_id'] . '" ';
            $select .= ($selected == $var['cat_id']) ? "selected='ture'" : '';
            $select .= '>';
            if ($var['level'] > 0) {
                $select .= str_repeat('&nbsp;', $var['level'] * 4);
            }
            $select .= htmlspecialchars(addslashes($var['cat_name']), ENT_QUOTES) . '</option>';
        }

        return $select;
    } else {
        foreach ($options AS $key => $value) {
            $options[$key]['url'] = build_uri('category', array('cid' => $value['cat_id']), $value['cat_name']);
        }

        return $options;
    }
}

/**
 * @return 过滤和排序所有分类，返回一个带有缩进级别的数组
 *
 * @access  private
 * @param   int     $cat_id     上级分类ID
 * @param   array   $arr        含有所有分类的数组
 * @param   int     $level      级别
 * @return  void
 */
function cat_options($spec_cat_id, $arr,$types = '') {
    static $cat_options = array();
    if (isset($cat_options[$spec_cat_id])) {
        return $cat_options[$spec_cat_id];
    }
    if (!isset($cat_options[0])) {
        $level = $last_cat_id = 0;
        $options = $cat_id_array = $level_array = array();
        $data = read_static_cache('cat_option_static'.$types);
        if ($data === false) {
            while (!empty($arr)) {
                foreach ($arr AS $key => $value) {
                    $cat_id = $value['cat_id'];
                    if ($level == 0 && $last_cat_id == 0) {
                        if ($value['parent_id'] > 0) {
                            break;

                        }

                        $options[$cat_id] = $value;
                        $options[$cat_id]['level'] = $level;
                        $options[$cat_id]['id'] = $cat_id;
                        $options[$cat_id]['name'] = $value['cat_name'];
                        unset($arr[$key]);

                        if ($value['has_children'] == 0) {
                            continue;
                        }
                        $last_cat_id = $cat_id;
                        $cat_id_array = array($cat_id);
                        $level_array[$last_cat_id] = ++$level;
                        continue;
                    }

                    if ($value['parent_id'] == $last_cat_id) {
                        $options[$cat_id] = $value;
                        $options[$cat_id]['level'] = $level;
                        $options[$cat_id]['id'] = $cat_id;
                        $options[$cat_id]['name'] = $value['cat_name'];
                        unset($arr[$key]);

                        if ($value['has_children'] > 0) {
                            if (end($cat_id_array) != $last_cat_id) {
                                $cat_id_array[] = $last_cat_id;
                            }
                            $last_cat_id = $cat_id;
                            $cat_id_array[] = $cat_id;
                            $level_array[$last_cat_id] = ++$level;
                        }
                    } elseif ($value['parent_id'] > $last_cat_id) {
                        break;
                    }
                }

                $count = count($cat_id_array);
                if ($count > 1) {
                    $last_cat_id = array_pop($cat_id_array);
                } elseif ($count == 1) {
                    if ($last_cat_id != end($cat_id_array)) {
                        $last_cat_id = end($cat_id_array);
                    } else {
                        $level = 0;
                        $last_cat_id = 0;
                        $cat_id_array = array();
                        continue;
                    }
                }

                if ($last_cat_id && isset($level_array[$last_cat_id])) {
                    $level = $level_array[$last_cat_id];
                } else {
                    $level = 0;
                }
            }
            //如果数组过大，不采用静态缓存方式
            if (count($options) <= 2000) {
                write_static_cache('cat_option_static'.$types, $options);
            }
        } else {
            $options = $data;
        }
        $cat_options[0] = $options;
    } else {
        $options = $cat_options[0];
    }

    if (!$spec_cat_id) {
        return $options;
    } else {
        if (empty($options[$spec_cat_id])) {
            return array();
        }

        $spec_cat_id_level = $options[$spec_cat_id]['level'];

        foreach ($options AS $key => $value) {
            if ($key != $spec_cat_id) {
                unset($options[$key]);
            } else {
                break;
            }
        }

        $spec_cat_id_array = array();
        foreach ($options AS $key => $value) {
            if (($spec_cat_id_level == $value['level'] && $value['cat_id'] != $spec_cat_id) ||
                    ($spec_cat_id_level > $value['level'])) {
                break;
            } else {
                $spec_cat_id_array[$key] = $value;
            }
        }
        $cat_options[$spec_cat_id] = $spec_cat_id_array;

        return $spec_cat_id_array;
    }
}

/**
 * @return 所有页面分页列表数据库函数
 * @param type $sql
 * @param type $pagecount
 * @param type $pagesstart
 * @return type
 */
function get_all_page($sql, $num, $start) {
    if ($start == 0) {
        $sql .= ' LIMIT ' . $num;
    } else {
        $sql .= ' LIMIT ' . $start . ', ' . $num;
    }
    $row = DB::getAll($sql);
    return $row;
}

/**
 * 重写 URL 地址
 *
 * @access  public
 * @param   string  $app        执行程序
 * @param   array   $params     参数数组
 * @param   string  $append     附加字串
 * @param   integer $page       页数
 * @param   string  $keywords   搜索关键词字符串
 * @return  void
 */
function build_uri($app, $params, $append = '', $page = 0, $keywords = '', $size = 0) {
    static $rewrite = NULL;

    if ($rewrite === NULL) {
        $rewrite = intval($GLOBALS['_CFG']['rewrite']);
    }

    $args = array('go' => '',
        'suppid' => 0,
        'cid' => 0,
        'gid' => 0,
        'bid' => 0,
        'acid' => 0,
        'aid' => 0,
        'sid' => 0,
        'gbid' => 0,
        'auid' => 0,
        'sort' => '',
        'order' => '',
    );

    extract(array_merge($args, $params));

    $uri = '';
    switch ($app) {
        case 'supplier':
            $go = empty($go) ? 'index' : $go;
            if ($go == 'category' || $go == 'index') {
                if ($rewrite) {
                    /* $uri = $app.'-'.$go.'-'.$suppid.'-' . $cid;
                      if (isset($bid))
                      {
                      $uri .= '-b' . $bid;
                      }
                      if (isset($price_min))
                      {
                      $uri .= '-min'.$price_min;
                      }
                      if (isset($price_max))
                      {
                      $uri .= '-max'.$price_max;
                      }
                      if (isset($filter_attr))
                      {
                      $uri .= '-attr' . $filter_attr;
                      }
                      if (!empty($page))
                      {
                      $uri .= '-' . $page;
                      }
                      if (!empty($sort))
                      {
                      $uri .= '-' . $sort;
                      }
                      if (!empty($order))
                      {
                      $uri .= '-' . $order;
                      } */
                    $uri = $app . '.php?go=' . $go . '&amp;suppId=' . $suppid . '&amp;id=' . $cid;
                    if (!empty($bid)) {
                        $uri .= '&amp;brand=' . $bid;
                    }
                    if (isset($price_min)) {
                        $uri .= '&amp;price_min=' . $price_min;
                    }
                    if (isset($price_max)) {
                        $uri .= '&amp;price_max=' . $price_max;
                    }
                    if (!empty($filter_attr)) {
                        $uri .= '&amp;filter_attr=' . $filter_attr;
                    }

                    if (!empty($page)) {
                        $uri .= '&amp;page=' . $page;
                    }
                    if (!empty($sort)) {
                        $uri .= '&amp;sort=' . $sort;
                    }
                    if (!empty($order)) {
                        $uri .= '&amp;order=' . $order;
                    }
                } else {
                    $uri = $app . '.php?go=' . $go . '&amp;suppId=' . $suppid . '&amp;id=' . $cid;
                    if (!empty($bid)) {
                        $uri .= '&amp;brand=' . $bid;
                    }
                    if (isset($price_min)) {
                        $uri .= '&amp;price_min=' . $price_min;
                    }
                    if (isset($price_max)) {
                        $uri .= '&amp;price_max=' . $price_max;
                    }
                    if (!empty($filter_attr)) {
                        $uri .= '&amp;filter_attr=' . $filter_attr;
                    }

                    if (!empty($page)) {
                        $uri .= '&amp;page=' . $page;
                    }
                    if (!empty($sort)) {
                        $uri .= '&amp;sort=' . $sort;
                    }
                    if (!empty($order)) {
                        $uri .= '&amp;order=' . $order;
                    }
                }
            } elseif ($go == 'article') {
                //$uri = $rewrite ? $app.'-article-'.$suppid.'-' . $aid : $app.'.php?go=article&suppId='.$suppid.'&id=' . $aid;
                $uri = $rewrite ? $app . '.php?go=article&suppId=' . $suppid . '&id=' . $aid : $app . '.php?go=article&suppId=' . $suppid . '&id=' . $aid;
            } elseif ($go == 'search') {
                if ($rewrite) {
                    /* $uri = $app.'-'.$go.'-'.$suppid;
                      if (isset($cid))
                      {
                      $uri .= '-c' . $cid;
                      }
                      if (isset($bid))
                      {
                      $uri .= '-b' . $bid;
                      }
                      if (isset($price_min))
                      {
                      $uri .= '-min'.$price_min;
                      }
                      if (isset($price_max))
                      {
                      $uri .= '-max'.$price_max;
                      }
                      if (isset($filter_attr))
                      {
                      $uri .= '-attr' . $filter_attr;
                      }
                      if (!empty($page))
                      {
                      $uri .= '-' . $page;
                      }
                      if (!empty($sort))
                      {
                      $uri .= '-' . $sort;
                      }
                      if (!empty($order))
                      {
                      $uri .= '-' . $order;
                      }
                      if (!empty($keywords))
                      {
                      $uri .= '-' . $keywords;
                      } */
                    $uri = $app . '.php?go=' . $go . '&amp;suppId=' . $suppid;
                    if (!empty($cid)) {
                        $uri .= '&amp;cid=' . $cid;
                    }
                    if (!empty($bid)) {
                        $uri .= '&amp;brand=' . $bid;
                    }
                    if (isset($price_min)) {
                        $uri .= '&amp;price_min=' . $price_min;
                    }
                    if (isset($price_max)) {
                        $uri .= '&amp;price_max=' . $price_max;
                    }
                    if (!empty($filter_attr)) {
                        $uri .= '&amp;filter_attr=' . $filter_attr;
                    }

                    if (!empty($page)) {
                        $uri .= '&amp;page=' . $page;
                    }
                    if (!empty($sort)) {
                        $uri .= '&amp;sort=' . $sort;
                    }
                    if (!empty($order)) {
                        $uri .= '&amp;order=' . $order;
                    }
                    if (!empty($keywords)) {
                        $uri .= '&amp;keywords=' . $keywords;
                    }
                } else {
                    $uri = $app . '.php?go=' . $go . '&amp;suppId=' . $suppid;
                    if (!empty($cid)) {
                        $uri .= '&amp;cid=' . $cid;
                    }
                    if (!empty($bid)) {
                        $uri .= '&amp;brand=' . $bid;
                    }
                    if (isset($price_min)) {
                        $uri .= '&amp;price_min=' . $price_min;
                    }
                    if (isset($price_max)) {
                        $uri .= '&amp;price_max=' . $price_max;
                    }
                    if (!empty($filter_attr)) {
                        $uri .= '&amp;filter_attr=' . $filter_attr;
                    }

                    if (!empty($page)) {
                        $uri .= '&amp;page=' . $page;
                    }
                    if (!empty($sort)) {
                        $uri .= '&amp;sort=' . $sort;
                    }
                    if (!empty($order)) {
                        $uri .= '&amp;order=' . $order;
                    }
                    if (!empty($keywords)) {
                        $uri .= '&amp;keywords=' . $keywords;
                    }
                }
            }

            break;
        case 'stores':
            if (empty($cid)) {
                return false;
            } else {
                if ($rewrite) {
                    $uri = 'stores-' . $cid;
                    if (!empty($page)) {
                        $uri .= '-' . $page;
                    }
                } else {
                    $uri = 'stores.php?id=' . $cid;
                    if (!empty($page)) {
                        $uri .= '&amp;page=' . $page;
                    }
                }
            }

            break;
        case 'category':
            if (empty($cid)) {
                return false;
            } else {
                if ($rewrite) {
                    $uri = 'category-' . $cid;
                    if (isset($bid)) {
                        $uri .= '-b' . $bid;
                    }
                    if (isset($price_min)) {
                        $uri .= '-min' . $price_min;
                    }
                    if (isset($price_max)) {
                        $uri .= '-max' . $price_max;
                    }
                    if (isset($filter)) {
                        $uri .= '-fil' . $filter;
                    }
                    if (isset($filter_attr)) {
                        $uri .= '-attr' . $filter_attr;
                    }
                    if (!empty($page)) {
                        $uri .= '-' . $page;
                    }
                    if (!empty($sort)) {
                        $uri .= '-' . $sort;
                    }
                    if (!empty($order)) {
                        $uri .= '-' . $order;
                    }
                    //$uri = get_dir('category', $cid). '/'.$uri;
                    //$uri = 'category.php?id=' . $cid;
//                    if (!empty($bid))
//                    {
//                        $uri .= '&amp;brand=' . $bid;
//                    }
//                    if (isset($price_min))
//                    {
//                        $uri .= '&amp;price_min=' . $price_min;
//                    }
//                    if (isset($price_max))
//                    {
//                        $uri .= '&amp;price_max=' . $price_max;
//                    }
//                	if (isset($filter))
//                    {
//                        $uri .= '&amp;filter=' . $filter;
//                    }
//                    if (!empty($filter_attr))
//                    {
//                        $uri .='&amp;filter_attr=' . $filter_attr;
//                    }
//
//                    if (!empty($page))
//                    {
//                        $uri .= '&amp;page=' . $page;
//                    }
//                    if (!empty($sort))
//                    {
//                        $uri .= '&amp;sort=' . $sort;
//                    }
//                    if (!empty($order))
//                    {
//                        $uri .= '&amp;order=' . $order;
//                    }
                } else {
                    $uri = 'category.php?id=' . $cid;
                    if (!empty($bid)) {
                        $uri .= '&amp;brand=' . $bid;
                    }
                    if (isset($price_min)) {
                        $uri .= '&amp;price_min=' . $price_min;
                    }
                    if (isset($price_max)) {
                        $uri .= '&amp;price_max=' . $price_max;
                    }
                    if (isset($filter)) {
                        $uri .= '&amp;filter=' . $filter;
                    }
                    if (!empty($filter_attr)) {
                        $uri .= '&amp;filter_attr=' . $filter_attr;
                    }

                    if (!empty($page)) {
                        $uri .= '&amp;page=' . $page;
                    }
                    if (!empty($sort)) {
                        $uri .= '&amp;sort=' . $sort;
                    }
                    if (!empty($order)) {
                        $uri .= '&amp;order=' . $order;
                    }
                }
            }

            break;

        case 'goods':
            if (empty($gid)) {
                return false;
            } else {
                if ($rewrite) {
                    $uri = 'goods-' . $gid;
                    /* $pathrow = $GLOBALS['db']->getRow("select c.path_name,c.cat_id from ". $GLOBALS['ecs']->table('goods')." AS g left join ". $GLOBALS['ecs']->table('category') ." AS c on g.cat_id=c.cat_id where g.goods_id='$gid'" );
                      $pathrow['path_name'] = $pathrow['path_name'] ? $pathrow['path_name'] : ("cat".$pathrow['cat_id']);
                      $pathrow['path_name'] = PREFIX_CATEGORY ."-".$pathrow['path_name'];
                      $uri = $pathrow['path_name']. '/'.$uri; */
                } else {
                    $uri = 'goods.php?id=' . $gid;
                }
            }

            break;
        case 'pre_sale':
            if (empty($pre_sale_id)) {
                return false;
            } else {
                if ($rewrite) {
                    $uri = 'pre_sale-' . $pre_sale_id;
                } else {
                    $uri = 'pre_sale.php?id=' . $pre_sale_id;
                }
            }

            break;
        case 'brand':
            if (empty($bid)) {
                return false;
            } else {
                if ($rewrite) {
                    $uri = 'brand-' . $bid;
                    if (isset($cid)) {
                        $uri .= '-c' . $cid;
                    }
                    if (!empty($page)) {
                        $uri .= '-' . $page;
                    }
                    if (!empty($sort)) {
                        $uri .= '-' . $sort;
                    }
                    if (!empty($order)) {
                        $uri .= '-' . $order;
                    }
                } else {
                    $uri = 'brand.php?id=' . $bid;
                    if (!empty($cid)) {
                        $uri .= '&amp;cat=' . $cid;
                    }
                    if (!empty($page)) {
                        $uri .= '&amp;page=' . $page;
                    }
                    if (!empty($sort)) {
                        $uri .= '&amp;sort=' . $sort;
                    }
                    if (!empty($order)) {
                        $uri .= '&amp;order=' . $order;
                    }
                }
            }

            break;
        case 'article_cat':
            if (empty($acid)) {
                return false;
            } else {
                if ($rewrite) {
                    $uri = 'article_cat-' . $acid;
                    if (!empty($page)) {
                        $uri .= '-' . $page;
                    }
                    if (!empty($sort)) {
                        $uri .= '-' . $sort;
                    }
                    if (!empty($order)) {
                        $uri .= '-' . $order;
                    }
                    if (!empty($keywords)) {
                        $uri .= '-' . $keywords;
                    }
                    //$uri = get_dir('article_cat', $acid). '/'.$uri;
                } else {
                    $uri = 'article_cat.php?id=' . $acid;
                    if (!empty($page)) {
                        $uri .= '&amp;page=' . $page;
                    }
                    if (!empty($sort)) {
                        $uri .= '&amp;sort=' . $sort;
                    }
                    if (!empty($order)) {
                        $uri .= '&amp;order=' . $order;
                    }
                    if (!empty($keywords)) {
                        $uri .= '&amp;keywords=' . $keywords;
                    }
                }
            }

            break;
        case 'article':
            if (empty($aid)) {
                return false;
            } else {
                if ($rewrite) {
                    $uri = 'article-' . $aid;
                    /* $pathrow = $GLOBALS['db']->getRow("select c.path_name,c.cat_id from ". $GLOBALS['ecs']->table('article')." AS a left join ". $GLOBALS['ecs']->table('article_cat') ." AS c on a.cat_id=c.cat_id where a.article_id='$aid'" );
                      $pathrow['path_name'] = $pathrow['path_name'] ? $pathrow['path_name'] : ("cat". $pathrow['cat_id']);
                      $pathrow['path_name'] = PREFIX_ARTICLECAT ."-".$pathrow['path_name'];
                      $uri = $pathrow['path_name']. '/'.$uri; */
                } else {
                    $uri = 'article.php?id=' . $aid;
                }
            }

            break;
        case 'group_buy':
            if (empty($gbid)) {
                return false;
            } else {
                $uri = $rewrite ? 'group_buy-' . $gbid : 'group_buy.php?act=view&amp;id=' . $gbid;
            }

            break;
        case 'auction':
            if (empty($auid)) {
                return false;
            } else {
                $uri = $rewrite ? 'auction-' . $auid : 'auction.php?act=view&amp;id=' . $auid;
            }

            break;
        case 'snatch':
            if (empty($sid)) {
                return false;
            } else {
                $uri = $rewrite ? 'snatch-' . $sid : 'snatch.php?id=' . $sid;
            }

            break;
        case 'pro_search':
            break;
        case 'search':
            break;
        case 'exchange':
            if ($rewrite) {
                $uri = 'exchange-' . $cid;
                if (isset($price_min)) {
                    $uri .= '-min' . $price_min;
                }
                if (isset($price_max)) {
                    $uri .= '-max' . $price_max;
                }
                if (!empty($page)) {
                    $uri .= '-' . $page;
                }
                if (!empty($sort)) {
                    $uri .= '-' . $sort;
                }
                if (!empty($order)) {
                    $uri .= '-' . $order;
                }
            } else {
                $uri = 'exchange.php?cat_id=' . $cid;
                if (isset($price_min)) {
                    $uri .= '&amp;integral_min=' . $price_min;
                }
                if (isset($price_max)) {
                    $uri .= '&amp;integral_max=' . $price_max;
                }

                if (!empty($page)) {
                    $uri .= '&amp;page=' . $page;
                }
                if (!empty($sort)) {
                    $uri .= '&amp;sort=' . $sort;
                }
                if (!empty($order)) {
                    $uri .= '&amp;order=' . $order;
                }
            }

            break;
        case 'exchange_goods':
            if (empty($gid)) {
                return false;
            } else {
                $uri = $rewrite ? 'exchange-id' . $gid : 'exchange.php?id=' . $gid . '&amp;act=view';
            }

            break;
        default:
            return false;
            break;
    }

    if ($rewrite) {
        if ($rewrite == 2 && !empty($append)) {
            $uri .= '-' . urlencode(preg_replace('/[\.|\/|\?|&|\+|\\\|\'|"|,]+/', '', $append));
        }
        if ($app != 'supplier') {
            $uri .= '.html';
        }
    }
    if (($rewrite == 2) && (strpos(strtolower(EC_CHARSET), 'utf') !== 0)) {
        $uri = urlencode($uri);
    }
    return $uri;
}

/**
 * 获得所有模块的名称以及链接地址
 *
 * @access      public
 * @param       string      $directory      插件存放的目录
 * @return      array
 */
function read_modules($directory = '.') {
    global $_LANG;

    $dir = @opendir($directory);
    $set_modules = true;
    $modules = array();
    $fileds = @readdir($dir);

    while (false !== ($file = @readdir($dir))) {
        if (preg_match("/^.*?\.php$/", $file)) {
            include_once($directory . '/' . $file);
        }
    }
    @closedir($dir);
    unset($set_modules);

    foreach ($modules AS $key => $value) {
        ksort($modules[$key]);
    }
    ksort($modules);

    return $modules;
}
