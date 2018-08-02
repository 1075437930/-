<?php

/**
 * 淘玉php 后台文章管理类
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 萤火虫 $
 * 后台文章管理类
 * $Id: article.php 17217 2018年4月23日17:23:46 萤火虫 $
 */
defined('TaoyuShop') or exit('Access Invalid!');
class articleControl extends BaseControl {
    
    /**
     * @return 构造函数方法 Description
     */
    public function __construct() {
        Language::read('article');//载入语言包
        parent::__construct();
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");
    }
    
    /**
     * @return 文章列表函数 
     */
    public function lists() {
        /* 权限的判断 */
        admin_priv('article_manage');

        /* 文章分类选择 */
        $cat_select = article_cat_list(0);
        
        $filter = array();
        Tpl::assign('cat_select',  $cat_select);
        Tpl::assign('ur_here',      L('03_article_list'));
        Tpl::assign('action_link',  array('text' => L('article_manage_add'), 'href' => 'index.php?act=article&op=article_add'));
        Tpl::assign('full_page',    1);
        $article_list = $this->get_articleslist();
        Tpl::assign('article_list',    $article_list['arr']);
        Tpl::assign('filter',          $article_list['filter']);
        Tpl::assign('record_count',    $article_list['record_count']);
        Tpl::assign('page_count',      $article_list['page_count']);
        $sort_flag  = sort_flag($article_list['filter']);
        Tpl::assign($sort_flag['tag'], $sort_flag['img']);
        Tpl::display('article_list.htm');
    }
    
    /**
     * @return 文章列表排序、分页、查询
     */
    public function article_query() {
        $article_list = $this->get_articleslist();
        Tpl::assign('article_list',    $article_list['arr']);
        Tpl::assign('filter',          $article_list['filter']);
        Tpl::assign('record_count',    $article_list['record_count']);
        Tpl::assign('page_count',      $article_list['page_count']);
        $sort_flag  = sort_flag($article_list['filter']);
        Tpl::assign($sort_flag['tag'], $sort_flag['img']);
        make_json_result(Tpl::fetch('article_list.htm'), '',
            array('filter' => $article_list['filter'], 'page_count' => $article_list['page_count']));
    }
    
    /**
     * @return 进入添加文章页面 Description
     */
     public function article_add() {
         /* 权限判断 */
        admin_priv('article_manage_add');
        /* 创建 html editor */
        create_html_editor('FCKeditor1');
        /*初始化*/
        $article = array();
        $article['is_open'] = 1;
        /* 取得分类、品牌 */
        /* 清理关联商品 */
        Model('goods_article')->delete_goods_article('article_id = 0');
        if (isset($_GET['id'])){
            Tpl::assign('cur_id',  $_GET['id']);
        }
        Tpl::assign('article',     $article);
        Tpl::assign('cat_select',  article_cat_list(0));
        Tpl::assign('ur_here',     L('article_manage_add'));
        Tpl::assign('action_link', array('text' => L('03_article_list'), 'href' => 'index.php?act=article&op=lists'));
        Tpl::assign('form_act', 'article');
        Tpl::assign('form_op', 'insert');
        Tpl::display('article_info.htm');
     }
     
     /**
      * @return 插入文章到数据库 Description 
      */
     public function insert(){
            /* 权限判断 */
            admin_priv('article_manage_add');
            /* 允许上传的文件类型 */
            $allow_file_types = '|BMP|SWF|DOC|XLS|PPT|MID|WAV|ZIP|RAR|PDF|CHM|RM|TXT|MP4';
            /* 允许上传的图片类型 */
            $allow_img_types = '|GIF|JPG|PNG|';
            /*检查是否重复*/
            $articlemodel = Model('article');
            $article_title = $_POST['title'];
            $wheres['cat_id'] = $_POST['article_cat'];//文章分类
            $wheres['title'] = $article_title;
            $is_only = $articlemodel->select_article_info('title', $where);
            if (!$is_only){
                showMessage(sprintf(L('title_exist'), stripslashes($article_title)));
             }
            /* 取得视频文件地址 */
            $file_url = '';           
            if ((isset($_FILES['file']['error']) && $_FILES['file']['error'] == 0) || (!isset($_FILES['file']['error']) && isset($_FILES['file']['tmp_name']) && $_FILES['file']['tmp_name'] != 'none')){
                // 上传文件到oss
                $path = 'bdimages/article/'.date('Ymd');
                $sizes = $_FILES['file']['size'];
                $res = upload_oss_files($_FILES['file'], $path, 0);
                if ($res['status']){
                    $file_url = $res['url'];
                    $insert['article_type'] = 2;//判断是系统1还是普通文章
                }
            }
            if ($file_url == ''){
                $file_url = $_POST['file_url'];
                $insert['article_type'] = $_POST['article_type'];//判断是系统1还是普通文章
            }
            
            /* 取得图片地址 */
            $img_url = '';
            if ((isset($_FILES['img']['error']) && $_FILES['img']['error'] == 0) || (!isset($_FILES['img']['error']) && isset($_FILES['img']['tmp_name']) && $_FILES['img']['tmp_name'] != 'none')){
                // 检查图片格式
                if (!check_file_type($_FILES['img']['tmp_name'], $_FILES['img']['name'], $allow_img_types)){
                    showMessage(L('invalid_file'));
                }
                // 复制图片
                $path = 'bdimages/article/'.date('Ymd');
                $res = upload_oss_img($_FILES['img'], $path, 0);
                if ($res['status']){
                    $img_url = $res['url'];
                }
            }
            if ($img_url == ''){
                $img_url = $_POST['img_url'];
            }
            
            /*插入数据*/
            $add_time = gmtime();
            if (empty($_POST['cat_id'])){
                $_POST['cat_id'] = 0;
            }
            /*推送$_POST['article_cat'] == '30' 为公告*/
            if($_POST['article_cat'] == '30' && $_POST['article_jt'] == 1 && $_POST['article_type'] == 1){
                if(C('sys_debug')){
                    showMessage('测试数据不发送公告推送');
                }else{
                    $send_content = $_POST['description'];
                    if(strlen($article_title)<1 || strlen($article_title)>48){
                        showMessage(L('title_yichang'));
                    }
                    if(strlen($send_content)<1 || strlen($send_content)>500){
                        showMessage(L('neirong_yichang'));
                    }
                    $platform = ['ios','android'];
                    $limit=0;
                    $i=0;
                    while($i<200){ //这个判断是假设网站有效会员小于5万，防止程序死循环
                        $userintos = array();
                        $field = 'user_id,user_name,user_ajpushid';
                        $w = 'user_ajpushid != "" ';
                        $userintos = get_one_table_list('users',$field,$w,'',"$limit,500",'select');
                        if(empty($userintos)){
                            break;
                        }
                        $userids = array();
                        $user_names = array();

                        foreach($userintos as $key=>$value){
                            $userids[] = $value['user_id'];
                            $user_names[] = $value['user_name'];
                        }

                        if (!empty($userintos)) {
                            $extras = [
                                'types'=>'gonggao',
                                'id'=>''
                            ];
                            $audience = array('regid' => $user_names);
                            $content = [
                                'title'=>$article_title,
                                'body'=>$send_content
                            ];
                            $zhangdsd = send_jpush_message(1, '', $content, $audience, $extras, $platform);
                        }
                        $limit+= 500;
                        $i++;

                    } 
                    $message['to_member_id'] = 'all';
                    $message['message_title'] = $article_title;
                    $message['message_body'] = $send_content;
                    $message['message_time'] = gmtime();
                    $message['article_type'] = 1;
                    $message['message_type'] = 1;
                    $message['tuisong_type'] = 1;
                    $messageid = Model('message')->insert_message($message);
                    if(empty($zhangdsd) || empty($messageid)){
                        showMessage(L('tuisong_yichang'));
                    }
                }
            }
            $insert['vidoe_times'] = $_POST['vidoe_times'];
            $insert['title'] = $article_title;
            $insert['cat_id'] = $_POST['article_cat'];
            $insert['is_open'] = $_POST['is_open'];
            $insert['author'] = '淘玉';
            $insert['article_hot'] = $_POST['article_hot'];
            $insert['article_news'] = $_POST['article_news'];
            $insert['article_jt'] = $_POST['article_jt'];
            $insert['keywords'] = $_POST['keywords'];
            $insert['content'] = $_POST['FCKeditor1'];
            $insert['media_type'] = $_POST['media_type'];
            $insert['add_time'] = $add_time;
            $insert['file_url'] = $file_url;
            $insert['img_url'] = $img_url;
            $insert['description'] = $_POST['description'];
            /*代码修改 by guo  end*/
            $article_ids = $articlemodel->insert_article($insert);
            if($insert['article_type'] == 2){
                $insert_video['video_img'] = $img_url;
                $insert_video['video_url'] = $file_url;
                $insert_video['article_id'] = $article_ids;
                $insert_video['oidev_size'] = $sizes;
                $insert_video['is_selos'] = 1;
                $insert_video['user_id'] = 0;
                $insert_video['shang_idtime'] = gmtime();
                Model('article')->insert_article_video($insert_video);
            }
            
            $param['article_id'] = $article_ids;
            $wheresa['article_id'] = 0;
            //修改产品对应文章
            Model('goods_article')->update_goods_article($param,$wheresa);
            //修改产品对应分类更新
            $wherecat['cat_id'] = $_POST['article_cat'];
            $param['update_time'] = $add_time;
            $articlemodel->update_article_cat($param,$wheresa);
            $link[1]['text'] = L('continue_add');
            $link[1]['href'] = 'index.php?act=article&op=article_add';
            $link[0]['text'] = L('back_list');
            $link[0]['href'] = 'index.php?act=article&op=lists';
            admin_log($article_title,'add','article');
            showMessage(L('articleadd_succeed'),$link);
     }
     
      /**
     * @return 编辑文章页面 Description
     */
     public function article_edit() {
         /* 权限判断 */
        admin_priv('article_manage_add');
        $aerticleidsd = $_REQUEST['id'];
        $articlemodel = Model('article');       
        /* 取文章数据 */
        $whers['article_id'] = $aerticleidsd;
        $article = $articlemodel->select_article_info('*',$whers);
        /* 创建 html editor */
        create_html_editor('FCKeditor1',$article['content']); /* 修改 by www.taoyumall.com 百度编辑器 */
        /* 取得关联商品 */
        $goods_list = Model('goods_article')->get_article_goods_list($aerticleidsd);
        Tpl::assign('goods_list', $goods_list);
        Tpl::assign('article', $article);
        Tpl::assign('cat_select',  article_cat_list(0, $article['cat_id']));
        Tpl::assign('ur_here',     L('article_edit'));
        Tpl::assign('action_link', array('text' => L('03_article_list'), 'href' => 'index.php?act=article&op=lists'));
        Tpl::assign('form_act', 'article');
        Tpl::assign('form_op', 'article_update');
        Tpl::display('article_info.htm');
     }
     
     
     /**
      * @return 修改文章内容到数据库 Description
      */
     
     public function article_update(){
         /* 权限判断 */
        admin_priv('article_manage_add');
        /*检查文章名是否相同*/
        $articlemodel = Model('article');
        $article_ids = $_POST['id'];
        $article_title = $_POST['title'];
        $wheres['cat_id'] = $_POST['article_cat'];//文章分类
        $wheres['title'] = $article_title;
        $is_only = $articlemodel->select_article_info('title', $where);
        if (!$is_only){
            showMessage(sprintf(L('title_exist'), stripslashes($article_title)));
         }
        if (empty($_POST['cat_id'])){
            $_POST['cat_id'] = 0;
        }
        /* 取得视频文件地址 */
        $file_url = '';          
        if ((isset($_FILES['file']['error']) && $_FILES['file']['error'] == 0) || (!isset($_FILES['file']['error']) && isset($_FILES['file']['tmp_name']) && $_FILES['file']['tmp_name'] != 'none')){
            // 上传文件到oss
            $path = 'bdimages/article/'.date('Ymd');
            $sizes = $_FILES['file']['size'];
            $res = upload_oss_files($_FILES['file'], $path, 0);
            if ($res['status']){
                $file_url = $res['url'];
                $paramupa['article_type'] = 2;//判断是系统1还是普通文章
            }
        }
        if ($file_url == ''){
            $file_url = $_POST['file_url'];
            $paramupa['article_type'] = $_POST['article_type'];
        }
        
        /* 如果 file_url 跟以前不一样，且原来的文件是本地文件，删除原来的文件 */
        $wherefule['article_id'] = $article_ids;
        $old_urlinto = $articlemodel->select_article_info('file_url', $wherefule);
        if ($old_urlinto['file_url'] != '' && $old_urlinto['file_url'] != $file_url && strpos($old_urlinto['file_url'], 'http://') === false && strpos($old_urlinto['file_url'], 'https://') === false){
            ossDeleteFileObject($old_urlinto['file_url']);
        }
        /* 取得图片地址 */
        $img_url = '';
        if ((isset($_FILES['img']['error']) && $_FILES['img']['error'] == 0) || (!isset($_FILES['img']['error']) && isset($_FILES['img']['tmp_name']) && $_FILES['img']['tmp_name'] != 'none')){
            // 检查图片格式
             if (!check_file_type($_FILES['img']['tmp_name'], $_FILES['img']['name'], $allow_img_types)){
                showMessage(L('invalid_file'));
            }
            // 复制图片
            $path = 'bdimages/article/'.date('Ymd');
            $res = upload_oss_img($_FILES['img'], $path, 0);
            if ($res['status']){
                $img_url = $res['url'];
            }
        }
        if ($img_url == ''){
            $img_url = $_POST['img_url'];
        }
        $old_imgsinto = $articlemodel->select_article_info('img_url', $wherefule);
        if ($old_imgsinto['img_url'] != '' && $old_imgsinto['img_url'] != $img_url && strpos($old_imgsinto['img_url'], 'http') === false ){
            ossDeleteFileObject($old_imgsinto['img_url']);
        }
        $paramupa['vidoe_times'] = $_POST['vidoe_times'];
        $paramupa['title'] = $article_title;
        $paramupa['cat_id'] = $_POST['article_cat'];
        $paramupa['img_url'] = $img_url;
        $paramupa['is_open'] = $_POST['is_open'];
        $paramupa['article_hot'] = $_POST['article_hot'];
        $paramupa['article_jt'] = $_POST['article_jt'];
        $paramupa['article_news'] = $_POST['article_news'];
        $paramupa['author'] = '淘玉';
        $paramupa['keywords'] = $_POST['keywords'];
        $paramupa['file_url'] = $file_url;
        $paramupa['media_type'] = $_POST['media_type'];
        $paramupa['content'] = $_POST['FCKeditor1'];
        $paramupa['description'] = $_POST['description'];
        $updat_reules = $articlemodel->update_article($paramupa,$wherefule);
        if($paramupa['article_type'] == 2){
            $insert_video['video_img'] = $img_url;
            $insert_video['video_url'] = $file_url;
            $insert_video['oidev_size'] = $sizes;
            $insert_video['is_selos'] = 1;
            Model('article')->update_article_video($insert_video,$wherefule);
         }
        if(!empty($updat_reules)){
            $link[0]['text'] = L('back_list');
            $link[0]['href'] = 'index.php?act=article&op=lists' ;
            $note = sprintf(L('articleedit_succeed'), stripslashes($article_title));
            admin_log($article_title, 'edit', 'article');
            showMessage($note, $link);
        }else{
            showMessage(L('articleedit_fail'));
        }
     }


    /**
     * @return 删除对应文章 
     * article_comment//文章评论
     * article_collection//文章关注
     * article_zan//文章点赞
     * comment_zan//评论点赞
     * article_video//文章视频
     * article//文章表
     */
    public function article_del(){
        check_authz_json('article_manage_del');
        $id = intval($_GET['id']);
        $articlemodel = Model('article');
        $where['article_id'] = $id;
        $artintos = $articlemodel->select_article_info('file_url,article_type,img_url,title', $where);
        /* 删除原来的文件 */
        $old_url = $artintos['file_url'];
        $old_img_url = $artintos['img_url'];
        if ($old_url != '' && strpos($old_url, 'http://') === false && strpos($old_url, 'https://') === false){
            ossDeleteFileObject($old_url);
        }
        if ($old_img_url != '' && strpos($old_img_url, 'http://') === false && strpos($old_img_url, 'https://') === false){
            ossDeleteFileObject($old_img_url);
        }
        $articlemodel->delete_article($where);//删除文章
        Model('goods_article')->delete_goods_article($where);//删除文章关联商品
        
        //删除文章所有评论和 对应评论点赞
        $art_comment = $articlemodel->get_article_comment_list('id,content,type',$where);
        if(!empty($art_comment)){
                if($art_comment['type'] == 2){
                        if(!empty($art_comment['content'])){
                                $urlarrya2 = explode(',', $art_comment['content']);
                                foreach ($urlarrya2 as $key2 => $value2) {
                                        ossDeleteFileObject($value2);
                                }
                        }
                }
                $ping_id = $art_comment['id'];
                $comtwhere['comment_id'] =$ping_id;
                $articlemodel->delete_acomment_zan($comtwhere);//删除对应评论的点赞信息
                $articlemodel->delete_article_comment($where);//删除对应评论信息
        }


        //删除文章所有关注
        $articlemodel->delete_article_collection($where);
        //删除文章所有点赞
        $articlemodel->delete_article_zan($where);
        admin_log($artintos['title'],'remove','article');
        $url = 'index.php?act=article&op=article_query&' . str_replace('op=article_del', '', $_SERVER['QUERY_STRING']);
        ecs_header("Location: $url\n");
        exit;
    }

    /**
     * @return 切换是否前台显示文章 Description
     */
    public function toggle_show(){
        check_authz_json('article_manage');
        $id     = intval($_POST['id']);
        $val    = intval($_POST['val']);
        $param['is_open'] = $val;
        $where['article_id'] = $id;
        Model('article')->update_article($param,$where);
        make_json_result($val);
    }
    
    /**
     * @return 切换是否前台显示文章 Description
     */
    public function article_hot(){
        check_authz_json('article_manage');
        $id     = intval($_POST['id']);
        $val    = intval($_POST['val']);
        $param['article_hot'] = $val;
        $where['article_id'] = $id;
        Model('article')->update_article($param,$where);
        make_json_result($val);
    }
    
   /**
     * @return 切换文章最新文章 Description
     */
    public function article_news(){
        check_authz_json('article_manage');
        $id     = intval($_POST['id']);
        $val    = intval($_POST['val']);
        $param['article_news'] = $val;
        $where['article_id'] = $id;
        Model('article')->update_article($param,$where);
        make_json_result($val);
    }
    
   /**
     * @return 切换文章推荐文章 Description
     */
    public function article_jt(){
        check_authz_json('article_manage');
        $id     = intval($_POST['id']);
        $val    = intval($_POST['val']);
        $param['article_jt'] = $val;
        $where['article_id'] = $id;
        Model('article')->update_article($param,$where);
        make_json_result($val);
    }


    /**
      * @return 获取文章关联产品搜索列表 Description
      */
     public function get_artOrgoods_list(){
        $keywored = $_GET['keyword'];
        $wheres = "goods_name LIKE '%" . $keywored . "%' OR goods_sn LIKE '%" . $keywored . "%' OR goods_id LIKE '%" . $keywored . "%' " ;
        $arr = Model('goods')->get_goods_list('goods_id, goods_name, shop_price',$wheres,'',50);
        $opt = array();
        foreach ($arr AS $key => $val){
            $opt[] = array('value' => $val['goods_id'],'text' => $val['goods_name'],'data' => $val['shop_price']);
        }
        make_json_result($opt);
    }
    
    /**
     * @return  将商品插入文章关联 Description
     */
    public function add_link_goods(){
        check_authz_json('article_manage');
        $add_ids = $_GET['add_ids'];
        $article_id = $_GET['article_id'];
        $articlemodel = Model('article');
        if(empty($article_id)){
            $article_into = $articlemodel->select_article_maxid();
            $article_id = $article_into['article_id'];
        }
        $insert['article_id'] = $article_id;
        $insert['goods_id'] = $add_ids;
        Model('goods_article')->insert_goods_article($insert);
        /* 重新载入 */
        $arr = Model('goods_article')->get_article_goods_list($article_id);
        $opt = array();
        foreach ($arr AS $key => $val){
            $opt[] = array('value'  => $val['goods_id'],
                            'text'  => $val['goods_name'],
                            'data'  => '');
        }
        make_json_result($opt);
    }
    
    /**
     * @return 将商品删除关联 Description
     */
    public function drop_link_goods(){
        check_authz_json('article_manage');
        $drop_goods     = $_GET['drop_ids'];
        $articlemodel = Model('article');
        $article_id = $_GET['article_id'];
        if(empty($article_id)){
            $article_into = $articlemodel->select_article_maxid();
            $article_id = $article_into['article_id'];
        }
        $wheres = " article_id = '$article_id' AND goods_id " .db_create_in($drop_goods);
        Model('goods_article')->delete_goods_article($wheres);
        /* 重新载入 */
        $arr = Model('goods_article')->get_article_goods_list($article_id);
        $opt = array();
        foreach ($arr AS $key => $val){
            $opt[] = array('value'  => $val['goods_id'],
                            'text'  => $val['goods_name'],
                            'data'  => '');
        }
        make_json_result($opt);
    }
    
    /**
     * @return 文章评论列表 Description
     */
    public function article_comment(){
        $filter = array();
        Tpl::assign('full_page', 1);
        Tpl::assign('filter',  '');
        $comment_list = $this->get_commentlist();
        Tpl::assign('comment_list',    $comment_list['arr']);
        Tpl::assign('filter',          $comment_list['filter']);
        Tpl::assign('record_count',    $comment_list['record_count']);
        Tpl::assign('page_count',      $comment_list['page_count']);
        $sort_flag  = sort_flag($comment_list['filter']);
        Tpl::assign($sort_flag['tag'], $sort_flag['img']);
        Tpl::display('article_comment.htm');
    }

    /**
     * @return 文章评论排序、分页、查询
     */
    public function comment_query() {
        $comment_list = $this->get_commentlist();
        Tpl::assign('comment_list',    $comment_list['arr']);
        Tpl::assign('filter',          $comment_list['filter']);
        Tpl::assign('record_count',    $comment_list['record_count']);
        Tpl::assign('page_count',      $comment_list['page_count']);
        $sort_flag  = sort_flag($comment_list['filter']);
        Tpl::assign($sort_flag['tag'], $sort_flag['img']);
        make_json_result(Tpl::fetch('article_comment.htm'), '',
            array('filter' => $comment_list['filter'], 'page_count' => $comment_list['page_count']));
    }
    
    /**
     * @return 文章评论的二级评论列表 Description
     */
    public function comment_lock(){
        $filter = array();
        $comment_id = $_REQUEST['comment_id'];
        Tpl::assign('full_page', 1);
        $comment_list = $this->get_commentlist();
        Tpl::assign('comment_list',    $comment_list['arr']);
        Tpl::assign('filter',          $comment_list['filter']);
        Tpl::assign('comment_id',      $comment_id);
        Tpl::assign('record_count',    $comment_list['record_count']);
        Tpl::assign('page_count',      $comment_list['page_count']);
        $sort_flag  = sort_flag($comment_list['filter']);
        Tpl::assign($sort_flag['tag'], $sort_flag['img']);
        Tpl::display('article_comment_list.htm');
    }
    
     /**
     * @return 文章二级评论排序、分页、查询
     */
    public function comment_list_query() {
        $comment_id = $_REQUEST['comment_id'];
        $comment_list = $this->get_commentlist();
        Tpl::assign('comment_list',    $comment_list['arr']);
        Tpl::assign('filter',          $comment_list['filter']);
        Tpl::assign('record_count',    $comment_list['record_count']);
        Tpl::assign('comment_id',      $comment_id);
        Tpl::assign('page_count',      $comment_list['page_count']);
        $sort_flag  = sort_flag($comment_list['filter']);
        Tpl::assign($sort_flag['tag'], $sort_flag['img']);
        make_json_result(Tpl::fetch('article_comment_list.htm'), '',
            array('filter' => $comment_list['filter'], 'page_count' => $comment_list['page_count']));
    }
    
    
    /**
     * @return 评论前台是否显示修改函数 Description
     */
    public function comment_show_type(){
        $comment_id = intval($_REQUEST['id']);
        $val = intval($_POST['val']);
        $articlemodel = Model('article');
        $where['id'] = $comment_id;
        $param['show_type'] = $val;
        $result = $articlemodel->update_article_comment($param, $where);
        if($result){
            make_json_result($val);
        }else{
            make_json_error(L('edit_fail'));
        }
    }
    
    
    /**
     * @return 删除评论函数 Description
     */
    public function comment_del(){
        $comment_id = intval($_REQUEST['id']);
        $articlemodel = Model('article');
        $where['id'] = $comment_id;
        $result = $articlemodel->delete_article_comment($where);
        if($result){
            admin_log($comment_id,'remove','articlecomment');
            $url = 'index.php?act=article&op=comment_query';
            ecs_header("Location: $url\n");
            exit;
        }
    }
    

    /**
     * @return 获得文章列表 
     */
    private function get_articleslist(){
        $result = get_filter();
        if ($result === false){
            $filter = array();
            $articlemodel = Model('article');
            $filter['keyword']    = empty($_REQUEST['keyword']) ? '' : trim($_REQUEST['keyword']);
            $filter['cat_id'] = empty($_REQUEST['cat_id']) ? 0 : intval($_REQUEST['cat_id']);
            $filter['sort_by']    = empty($_REQUEST['sort_by']) ? 'article.article_id' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
            $where = '';
            if (!empty($filter['keyword'])){
                $where .= " AND article.title LIKE '%" . $filter['keyword'] . "%'";
            }
            if ($filter['cat_id']){
                $where .= " AND article." . get_article_children($filter['cat_id']);
            }
            /* 文章总数 */
            $filter['record_count'] = $articlemodel->get_article_count($where);
            $filter = page_and_size($filter);
            /* 获取文章数据 */
            $sql = 'SELECT article.* , article_cat.cat_name '.
                   'FROM ' .Model()->tablename('article'). ' AS article '.
                   'LEFT JOIN ' .Model()->tablename('article_cat'). ' AS article_cat ON article_cat.cat_id = article.cat_id '.
                   'WHERE 1 ' .$where. ' ORDER by '.$filter['sort_by'].' '.$filter['sort_order'];
            $filter['keyword'] = stripslashes($filter['keyword']);
            set_filter($filter, $sql);
        }else{
            $sql    = $result['sql'];
            $filter = $result['filter'];
        }
        $arr = array();
        $res = get_all_page($sql, $filter['page_size'], $filter['start']);

        foreach ($res as $key => $value) {
            $res[$key]['date'] = local_date(C('time_format'), $value['add_time']);
            if($value['media_type'] == 0){
                $res[$key]['media_names'] = '全部';
            }else if($value['media_type'] == 1){
                $res[$key]['media_names'] = 'app手机端';
            }else if($value['media_type'] == 2){
                $res[$key]['media_names'] = '电脑端';
            }else{
                $res[$key]['media_names'] = '第三方端';
            }
            $list[] = $res[$key];
        }
        return array('arr' => $list, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
    }
    
    
    /**
     * @return 获得文章评论列表 
     */
    private function get_commentlist(){
        $result = get_filter();
        if ($result === false){
            $filter = array();
            $articlemodel = Model('article');
            $comment_id = $_REQUEST['comment_id'];
            $filter['keyword']    = empty($_REQUEST['keyword']) ? '' : trim($_REQUEST['keyword']);
            $filter['sort_by']    = empty($_REQUEST['sort_by']) ? 'article_comment.id' : trim($_REQUEST['sort_by']);
            $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);
            $where = '';
            if (!empty($filter['keyword'])){
                $where .= " AND article_comment.content LIKE '%" . $filter['keyword'] . "%'";
            }  
            if(!empty($comment_id)){
                $where .= " AND to_id = '" .$comment_id . "'";
                $filter['comment_id'] = $comment_id;
            }else{
                $where .= " AND to_id = '0' ";
            }
            /* 文章总数 */
            $filter['record_count'] = $articlemodel->get_article_comment_count($where);
            $filter = page_and_size($filter);
            /* 获取文章数据 */
            $sql = 'SELECT article_comment.* , article.title,users.alias '.
                   'FROM ' .Model()->tablename('article_comment'). ' AS article_comment '.
                   'LEFT JOIN ' .Model()->tablename('article'). ' AS article ON article_comment.article_id = article.article_id '.
                   'LEFT JOIN ' .Model()->tablename('users'). ' AS users ON article_comment.user_id = users.user_id '.
                   'WHERE 1 ' .$where. ' ORDER by '.$filter['sort_by'].' '.$filter['sort_order'];
            set_filter($filter, $sql);
        }else{
            $sql    = $result['sql'];
            $filter = $result['filter'];
        }
        $arr = array();
 
        $res = get_all_page($sql, $filter['page_size'], $filter['start']);
        foreach ($res as $key => $value) {
            $res[$key]['date'] = local_date(C('time_format'), $value['add_time']);
            if($value['type'] == 1){
               $res[$key]['type_name'] =  '文字';
            }else if($value['type'] == 2){
               $res[$key]['type_name'] =  '图片';
               $img_urls = explode(',',$value['content']);
               foreach ($img_urls as $value1) {
                   $urls = get_imgurl_oss($value1, 50, 50);
                   $imgs .= "<img  src='".$urls."' style='margin-right:5px;width:25px;height:25px'>";
               }
               $res[$key]['content'] = $imgs;
            }else{
                $res[$key]['type_name'] =  '视频';
            }
            $where1['to_id'] = $value['id'];
            if(!empty($where1['to_id'])){
                $res[$key]['to_commnet'] = $articlemodel->get_article_comment_count($where1);
            }else{
                $res[$key]['to_commnet'] = 0;
            }
            if(!empty($value['to_userid'])){
                $where2['user_id'] = $value['to_userid'];
                $to_user_name = Model('users')->select_users_info('alias',$where2);
                $res[$key]['to_user_name'] = $to_user_name['alias'];
            }
            
           
        }
        return array('arr' => $res, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

    }

}
?>

