<?php
/**
 * taoyumall 权限对照表
 * ============================================================================
 * * 版权所有 2005-2012 淘玉商城，并保留所有权利。
 * 网站地址: https://www.taoyumall.com；
 * ----------------------------------------------------------------------------
 * ============================================================================
 * $Author: 萤火虫 $
 * $Id: inc_priv.php 15503 2008-12-24 09:22:45Z 萤火虫 $
 */
if (!defined('TaoyuShop')) {
    die('Hacking attempt');
}

/** 
 * 商品管理权限
 */
$purview['01_goods_list'] = array('goods_manage', 'remove_back');
$purview['02_supplier_goods_list'] = array('s_goods_manage', 's_remove_back');
$purview['03_goods_add'] = 'goods_manage';
$purview['04_category_list'] = array('cat_manage', 'cat_drop'); // 分类添加、分类转移和删除
$purview['05_band_list'] = 'brand_manage';//品牌管理
$purview['08_goods_type'] = 'attr_manage'; // 商品属性
$purview['11_goods_trash'] = array('goods_manage', 'remove_back');
$purview['17_tag_manage'] = 'tag_manage';//标签管理
$purview['19_update_goods'] = 'update_goods';//批量下架/库存
$purview['21_goods_video'] = 'goods_video_list';//商品视频
$purview['20_goods_auto'] = 'goods_auto';//商品自动上下架
$purview['22_pricecut'] = 'pricecut';//商品降价通知
/**
 * 入驻商权限管理
 */
$purview['01_supplier_reg'] = 'supplier_reg';//入驻商申请列表
$purview['05_supplier_rank'] = 'supplier_rank';//入驻商等级
$purview['02_supplier_list'] = array('supplier_list', 'del_supplier', 'supplier_intos'); //入驻商列表
$purview['03_rebate_nopay'] = 'supplier_rebate';//平台交易统计
$purview['04_shop_category'] = 'shop_category';//店铺分类
$purview['08_sell_supp_balance'] = 'sell_supp_balance';//结算款管理
$purview['11_supplier_punish'] = 'supp_punish';//处罚管理
$purview['12_supplier_pay_module'] = 'supplier_pay_module';//付费功能审核



/*典藏管理权限设置*/
$purview['01_dcindex']       = 'dcindex';//典藏首页
$purview['02_dclist']        = 'dclist';//典藏列表
$purview['03_dcadds']        = 'dcadds';//典藏添加
$purview['04_dcorder']        = 'dcorder';//典藏订单
$purview['05_dcbackor']        = 'dcbackor';//典藏退货订单
$purview['06_dcclass']        = 'dcclass';//典藏分类
$purview['07_dctags']        = 'dcclass';//典藏标签
$purview['08_dczudui']        = 'dczudui';//典藏组队小分队
$purview['09_dcseting']        = 'dcseting';//收益设置
$purview['10_dcrank']        = 'dcrank';//典藏等级


/*促销管理权限设置*/
$purview['05_shop_list'] = 'shop_set'; // 实体店设置 
$purview['09_topic'] = array('topic_manage', 'topic_add', 'topic_del'); //专题管理 
$purview['10_auction'] = 'auction';//拍卖活动
$purview['30_vouchers'] = 'vouchers'; // 代金券设置
$purview['26_yaojiang'] = 'yaojiang';//摇奖活动
$purview['27_winning'] = 'winning';//中奖名单

/* 会员管理权限 */
$purview['03_users_list'] = array('users_manage', 'users_drop', 'topic_del'); //会员列表
$purview['05_user_rank_list'] = 'user_rank';//会员等级
$purview['09_user_account'] = 'user_account';//充值和提现申请
$purview['13_users_remind'] = 'users_remind';//会员通知
$purview['09_user_yijian'] = 'user_yijian';//会员建议
$purview['12_user_vip_manage'] = 'user_vip_manage';//邀请人统计


/* 文章管理权限*/
$purview['02_articlecat_list'] = 'article_cat';//文章分类
$purview['03_article_list'] = 'article_manage';//文章列表
$purview['04_article_comment'] = 'article_comment';//文章评论
$purview['05_article_auto'] = 'article_auto';//文章自动发布
$purview['06_articlecat_add'] = 'articlecat_add';//文章分类添加
/* 订单管理权限*/
$purview['01_order_list'] = 'order_view';//订单列表
$purview['02_supplier_order'] = 's_order_view';//入驻商列表
$purview['10_back_order'] = 'back_view';//退款退货
$purview['11_supplier_back_order'] = 's_back_view';//入驻商列表退款退货


/* 短信管理*/
$purview['01_sms_set'] = 'sms_set';//短信设置
$purview['02_sms_setlist'] = 'sms_setlist';//短信模版列表
$purview['03_sms_list'] = 'sms_list';//短信发送列表


/* 分销返点 */
$purview['01_fx_set']       = 'fx_set';//分销返点设置
$purview['02_fx_fandian']   = 'fx_fandian';// 分销返点
$purview['03_fx_logo']      = 'fx_logo';//邀请人注册返点

/**
 * 客服管理
 */
$purview['01_customer'] = 'customer'; // 客服管理
$purview['02_third_customer'] = 'third_customer'; // 三方客服

/**
 * 商店设置权限
 */
$purview['01_shop_config'] = 'shop_config';//商店设置
$purview['02_payment_list'] = 'payment';//支付方式
$purview['03_shipping_list'] = array('ship_manage', 'shiparea_manage');//配送方式
$purview['05_area_list'] = 'area_manage';//地区列表
$purview['08_friendlink_list'] = 'friendlink';//友情链接
$purview['09_logo_list'] = 'logo_list';//合作登录

/**
 * 大师管理权限
 */
$purview['01_master_list'] = 'master_list';// 大师列表
$purview['01_master_type_list'] = 'master_type_list';// 大师分类列表

/**
 * 权限管理权限
 */
$purview['03_admin_logs'] = 'logs_manage';//管理员日志
$purview['01_admin_list'] = 'admin_manage';//管理员列表
$purview['02_admin_role'] = 'role_manage';//角色管理

/**
 * APP管理权限
 */
$purview['01_app_login_img'] = 'app_login_img';//app登录页背景
$purview['02_app_qr_code'] = 'app_qr_code'; //APP二维码下载设置
$purview['03_appbanks'] = 'appbanks';//APP设置银行账号
$purview['04_apptuisong'] = 'apptuisong';//APP推送列表

// 广告管理
$purview['01_ad_position'] = 'ad_position';//广告位置
$purview['02_ad_list'] = 'ad_list';//广告列表


// 问答管理权限设置
$purview['01_asklist']       = 'asklist';// 问答列表
$purview['02_addask']        = 'addask';// 问答添加
$purview['03_askclass']        = 'askclass';// 问答分类
$purview['04_cus_log']        = 'cus_log';// 客服修改商品日志


// 报表统计权限
$purview['01_users_stats'] = 'users_stats'; // 会员统计
$purview['03_keyword'] = 'users_keyword';// 客户搜索记录
$purview['02_money_stats'] = 'money_stats';// 资金统计资金统计


//其他额外数据权限
$purview['check_phone'] = "check_phone";




?>