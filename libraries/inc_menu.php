<?php

/**
 * 淘玉mall 管理中心菜单数组
 * ============================================================================
 * * 版权所有 2005-2012 淘玉商城，并保留所有权利。
 * 网站地址: https://www.taoyumall.com；
 * ----------------------------------------------------------------------------
 * ============================================================================
 * $Author: 萤火虫 $
 * $Id: menu.php 251230 2015-03-19 06:29:08Z taoyu $
 */
if (!defined('TaoyuShop')) {
    die('Hacking attempt');
}

/**
 * 商品列表导航 
 */
$modules['02_cat_and_goods']['01_goods_list'] = 'index.php?act=goods&op=lists'; // 商品列表
$modules['02_cat_and_goods']['02_supplier_goods_list'] = 'index.php?act=shop&op=shop_lists'; // 供货商商品列表
$modules['02_cat_and_goods']['04_category_list'] = 'index.php?act=category&op=cat_lists';//商品分类
$modules['02_cat_and_goods']['05_band_list'] = 'index.php?act=brand&op=brand_lists';//商品品牌
$modules['02_cat_and_goods']['08_goods_type'] = 'index.php?act=goods_type&op=manage';//商品类型
$modules['02_cat_and_goods']['11_goods_trash'] = 'index.php?act=goods&op=trash'; // 商品回收站
$modules['02_cat_and_goods']['17_tag_manage'] = 'index.php?act=tag_manage&op=class_list';//商品标签管理
$modules['02_cat_and_goods']['19_update_goods'] = 'index.php?act=update_goods&op=indexset'; // 商品批量操作库存和上架下
$modules['02_cat_and_goods']['20_goods_auto'] = 'index.php?act=goods_auto&op=lists'; // 商品自动上下架
$modules['02_cat_and_goods']['21_goods_video'] = 'index.php?act=goods_video&op=lists';//商品视频管理
$modules['02_cat_and_goods']['22_pricecut'] = 'index.php?act=pricecut&op=lists';//降价通知列表

/**
 * 典藏管理列表导航 
 */
$modules['10_diancang']['01_dcindex'] = 'index.php?act=diancang&op=dcindex'; // 典藏首页
$modules['10_diancang']['02_dclist'] = 'index.php?act=diancang&op=dclist'; // 典藏列表
$modules['10_diancang']['03_dcadds'] = 'index.php?act=diancang&op=dcadd'; // 典藏添加
$modules['10_diancang']['04_dcorder'] = 'index.php?act=dcorder&op=lists'; // 典藏订单
$modules['10_diancang']['05_dcbackor'] = 'index.php?act=dcback&op=lists'; // 典藏退款订单
$modules['10_diancang']['06_dcclass'] = 'index.php?act=dcclass&op=lists'; // 典藏分类
$modules['10_diancang']['07_dctags'] = 'index.php?act=dctags&op=lists'; // 典藏标签
$modules['10_diancang']['08_dczudui'] = 'index.php?act=dcgroup&op=lists'; // 典藏组队小分队数据
$modules['10_diancang']['09_dcseting'] = 'index.php?act=dcyueset&op=lists'; // 典藏设置
$modules['10_diancang']['10_dcrank'] = 'index.php?act=dcrank&op=lists'; // 典藏用户等级


/**
 * 入住商管理二级导航
 */
$modules['02_supplier']['01_supplier_reg'] = 'index.php?act=supplier&op=lists';//入驻商申请列表
$modules['02_supplier']['02_supplier_list'] = 'index.php?act=supplier&op=lists&status=1';//入驻商列表
$modules['02_supplier']['03_rebate_nopay'] = 'index.php?act=suprebate&op=lists';//平台交易统计
$modules['02_supplier']['04_shop_category'] = 'index.php?act=supstreetcat&op=lists';//店铺分类
$modules['02_supplier']['05_supplier_rank'] = 'index.php?act=suprank&op=lists';//入驻商等级
$modules['02_supplier']['08_sell_supp_balance'] = 'index.php?act=sellsuppbalance&op=lists';//结款管理
$modules['02_supplier']['11_supplier_punish'] = 'index.php?act=supintegral&op=punish_list';//处罚分类
$modules['02_supplier']['12_supplier_pay_module'] = 'index.php?act=suppaymodule&op=lists';//付费功能审核

/**
 * 促销管理二级导航
 */
$modules['03_promotion']['05_shop_list'] = 'index.php?act=offlineshop&op=lists';//线下酒店二维码生成
$modules['03_promotion']['09_topic'] = 'index.php?act=topic&op=lists';//专题管理
$modules['03_promotion']['10_auction'] = 'index.php?act=auction&op=lists';//拍卖活动
$modules['03_promotion']['30_vouchers'] = 'index.php?act=vouchers&op=lists'; // 代金券管理
//$modules['03_promotion']['26_yaojiang'] = 'index.php?act=yaojiang&op=lists'; // 摇奖 
//$modules['03_promotion']['27_winning'] = 'index.php?act=winning&op=lists'; // 中奖名单 

/**
 * 会员管理二级导航
 */
$modules['08_members']['03_users_list'] = 'index.php?act=users&op=lists';//会员列表
$modules['08_members']['05_user_rank_list'] = 'index.php?act=userrank&op=lists';//会员等级
$modules['08_members']['09_user_account'] = 'index.php?act=useraccount&op=lists';//充值提现
$modules['08_members']['13_users_remind'] = 'index.php?act=userremind&op=index';//会员通知
$modules['08_members']['09_user_yijian'] = 'index.php?act=suggestion&op=lists';//会员建议
$modules['08_members']['12_user_vip_manage'] = 'index.php?act=inviter&op=lists';//会员邀请人列表

/**
 * 文章管理二级导航
 */
$modules['07_content']['03_article_list'] = 'index.php?act=article&op=lists';//文章列表
$modules['07_content']['02_articlecat_list'] = 'index.php?act=articlecat&op=lists';//文章分类
$modules['07_content']['04_article_comment'] = 'index.php?act=article&op=article_comment';//文章评论
$modules['07_content']['05_article_auto'] = 'index.php?act=articleauto&op=lists';//文章自动发布
/**
 * 订单管理二级导航
 */
$modules['04_order']['01_order_list'] = 'index.php?act=order&op=lists';//订单列表
$modules['04_order']['02_supplier_order'] = 'index.php?act=order&op=lists&supp=1';//入驻商订单列表
$modules['04_order']['10_back_order'] = 'index.php?act=back&op=back_list'; // 退款退货
$modules['04_order']['11_supplier_back_order'] = 'index.php?act=back&op=back_list&supp=1'; // 入住商退款退货


/**
 * 短信管理二级导航
 */
$modules['14_sms']['01_sms_set'] = 'index.php?act=mail_templates&op=lists';//短信设置
$modules['14_sms']['02_sms_setlist'] = 'index.php?act=sms&op=lists';//短信模版列表
//$modules['14_sms']['03_sms_list'] = 'index.php?act=sms&op=display_send_ui';//短信发送列表

/**
 * 分销管理二级导航
 */
$modules['25_fenxiao']['01_fx_set']       = 'index.php?act=fxusers&op=lists';// 分销返点设置
$modules['25_fenxiao']['02_fx_fandian']   = 'index.php?act=fenxiao&op=lists';// 分销返点
$modules['25_fenxiao']['03_fx_logo']      = 'index.php?act=fenxiao&op=yqfanli_edit';// 邀请人注册返点

/**
 * 即时通讯管理二级导航
 */
$modules['20_chat']['01_customer'] = 'index.php?act=customer&op=lists'; // 客服管理
$modules['20_chat']['02_third_customer'] = 'index.php?act=third_customer&op=lists'; // 三方客服


/**
 * 系统设置管理二级导航
 */
$modules['11_system']['01_shop_config'] = 'index.php?act=shopconfig&op=edit';//商店设置
$modules['11_system']['02_payment_list'] = 'index.php?act=payment&op=lists';//支付方式
$modules['11_system']['03_shipping_list'] = 'index.php?act=shipping&op=lists';//配送方式
$modules['11_system']['05_area_list'] = 'index.php?act=areamanage&op=lists';//地区列表
$modules['11_system']['08_friendlink_list'] = 'index.php?act=friendlink&op=lists';//友情链接
$modules['11_system']['09_logo_list'] = 'index.php?act=website&op=lists';//合作登录管理

/**
 * 大师管理二级导航
 */
$modules['19_master']['01_master_list'] = 'index.php?act=master&op=lists'; // 大师列表
$modules['19_master']['01_master_type_list'] = 'index.php?act=master&op=master_type_list'; // 大师分类列表


/**
 * 权限管理二级导航
 */
$modules['10_priv_admin']['03_admin_logs'] = 'index.php?act=adminlogs&op=lists';//管理员日志
$modules['10_priv_admin']['01_admin_list'] = 'index.php?act=privilege&op=lists';//管理员列表
$modules['10_priv_admin']['02_admin_role'] = 'index.php?act=role&op=lists';//角色管理

/**
 * app管理二级导航
 */
$modules['21_app']['01_app_login_img'] = 'index.php?act=app_seting&op=login_img_list'; //APP设置登录页背景
$modules['21_app']['02_app_qr_code'] = 'index.php?act=app_seting&op=qr_code'; //APP二维码设置
$modules['21_app']['03_appbanks'] = 'index.php?act=app_banks&op=lists'; //APP设置银行账号
$modules['21_app']['04_apptuisong'] = 'index.php?act=app_tuisong&op=lists'; //APP推送列表
$modules['21_app']['05_app_update'] = 'index.php?act=app_seting&op=app_update'; //APP更新设置

/**
 * 广告管理二级导航
 */
$modules['05_banner']['01_ad_position'] = 'index.php?act=ad_position&op=lists';//广告位置
$modules['05_banner']['02_ad_list'] = 'index.php?act=ads&op=lists';//广告列表

/** 
 * 问答管理二级导航
 */
$modules['10_ask']['01_asklist'] = 'index.php?act=askset&op=ask_list'; // 问答列表
$modules['10_ask']['02_addask'] = 'index.php?act=askset&op=ask_add'; // 问答添加
$modules['10_ask']['03_askclass'] = 'index.php?act=askset&op=ask_class_list'; // 问答分类
$modules['10_ask']['04_cus_log'] = 'index.php?act=askset&op=cus_log_list'; // 客服修改商品日志

/**
 *  数据统计
 */
$modules['06_stats']['01_users_stats'] = 'index.php?act=user_added_stats&op=lists'; // 会员统计
$modules['06_stats']['02_money_stats'] = 'index.php?act=capital_statistics&op=lists'; // 资金统计
//$modules['06_stats']['03_keyword'] = 'index.php?act=keyword&op=lists'; // 客户搜索记录


?>
