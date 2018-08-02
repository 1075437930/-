<?php
/**
 * 淘玉php 管理中心共用语言文件
 * ============================================================================
 * * 版权所有 2015-2025 淘玉商城，并保留所有权利。 * 网站地址: http://www.taoyumall.com；发布。
 * ============================================================================
 * $Author: 淘玉 $
 * $Id: common.php 17217 2015-05-19 06:29:08Z 淘玉 $
 */
defined('TaoyuShop') or exit('Access Invalid!');

//后台登录语言参数
$_LANG['nc_login'] = '登录';
$_LANG['nc_succ'] = '成功';
$_LANG['nc_fail'] = '失败';
$_LANG['login_index_title_01']					= '';
$_LANG['login_index_title_02']					= '平台管理中心';
$_LANG['login_index_title_03']					= '商城&nbsp;&nbsp;|&nbsp;&nbsp;资讯&nbsp;&nbsp;|&nbsp;&nbsp;圈子&nbsp;&nbsp;|&nbsp;&nbsp;微商城&nbsp;&nbsp;|&nbsp;&nbsp;统计';
$_LANG['login_index_username_null']				= '用户名不能为空';
$_LANG['login_index_password_null']				= '密码不能为空';
$_LANG['login_index_checkcode_null']				= '验证码不能为空';
$_LANG['login_index_checkcode_wrong']			= '验证码输入错误，请重新输入';
$_LANG['login_index_not_admin']					= '您不是管理员，不能进入后台';
$_LANG['login_index_username_password_wrong']	= '账号密码错误';
$_LANG['login_index_need_login']					= '您需要登录后才可以使用本功能';
$_LANG['login_index_username']					= '账号';
$_LANG['login_index_password']					= '密码';
$_LANG['login_index_password_pattern']			= '密码不少于6个字符';
$_LANG['login_index_checkcode']					= '输入验证';
$_LANG['login_index_checkcode_pattern']			= '验证码为4个字符';
$_LANG['login_index_close_checkcode']			= '关闭';
$_LANG['login_index_change_checkcode']			= '看不清,点击更换验证码';
$_LANG['login_index_button_login']				= '登录';
$_LANG['login_index_taoyushop']						= '淘玉商城';

//分配查看电话号码权限  开始  代码增加  by 吴博  2017-07-25 16.16
$_LANG['check_phone'] = '电话号码查看';
$_LANG['check_look_phone'] = "可以查看";
//分配查看电话号码权限  结束  代码增加  by 吴博   2017-07-25 16.16
//分配大师权限配置  结束  代码增加  by wubo 2017-07-24 08.55
$_LANG['app_name'] = 'ECSHOP';
$_LANG['cp_home'] = '淘玉商城 管理中心';
$_LANG['copyright'] = '版权所有 &copy; 2015-2025 淘玉商城，并保留所有权利。';
$_LANG['query_info'] = '共执行 %d 个查询，用时 %s 秒';
$_LANG['memory_info'] = '，内存占用 %0.3f MB';
$_LANG['gzip_enabled'] = '，Gzip 已启用';
$_LANG['gzip_disabled'] = '，Gzip 已禁用';
$_LANG['loading'] = '正在处理您的请求...';
$_LANG['js_languages']['process_request'] = '正在处理您的请求...';
$_LANG['js_languages']['todolist_caption'] = '记事本';
$_LANG['js_languages']['todolist_autosave'] = '自动保存';
$_LANG['js_languages']['todolist_save'] = '保存';
$_LANG['js_languages']['todolist_clear'] = '清除';
$_LANG['js_languages']['todolist_confirm_save'] = '是否将更改保存到记事本？';
$_LANG['js_languages']['todolist_confirm_clear'] = '是否清空内容？';
$_LANG['auto_redirection'] = '如果您不做出选择，将在 <span id="spanSeconds">3</span> 秒后跳转到第一个链接地址。';
$_LANG['password_rule'] = '密码应只包含英文字符、数字.长度在6--16位之间';
$_LANG['username_rule'] = '用户名应为汉字、英文字符、数字组合，3到15位';
$_LANG['plugins_not_found'] = '插件 %s 无法定位';
$_LANG['no_records'] = '没有找到任何记录';
$_LANG['role_describe'] = '角色描述';

$_LANG['require_field'] = '<span class="require-field">*</span>';
$_LANG['yes'] = '是';
$_LANG['no'] = '否';
$_LANG['start_date'] = '开始日期';
$_LANG['end_date'] = '结束日期';
$_LANG['nians'] = '年';
$_LANG['yues'] = '月';
$_LANG['dates'] = '日';
$_LANG['maohao'] = ':';
$_LANG['leixing'] = '类型';
$_LANG['leixing1'] = '下架';
$_LANG['leixing2'] = '库存为0';
$_LANG['xiugaitishi'] = '操作提示：本功能只适用于自营市场上货的产品。';
$_LANG['goodsbh'] = '产品编号首字母';
$_LANG['end_date'] = '结束日期';
$_LANG['record_id'] = '编号';
$_LANG['handler'] = '操作';
$_LANG['install'] = '安装';
$_LANG['uninstall'] = '卸载';
$_LANG['isdefaultshow'] = '设为默认快递';
$_LANG['defaultshow'] = '默认快递';
$_LANG['list'] = '列表';
$_LANG['add'] = '添加';
$_LANG['edit'] = '编辑';
$_LANG['view'] = '查看';
$_LANG['remove'] = '移除';
$_LANG['drop'] = '删除';
$_LANG['confirm_delete'] = '您确定要删除吗？';
$_LANG['disabled'] = '禁用';
$_LANG['enabled'] = '启用';
$_LANG['setup'] = '设置';
$_LANG['success'] = '成功';
$_LANG['sort_order'] = '排序';
$_LANG['trash'] = '回收站';
$_LANG['restore'] = '还原';
$_LANG['close_window'] = '关闭窗口';
$_LANG['btn_select'] = '选择';
$_LANG['operator'] = '操作人';
$_LANG['cancel'] = '取消';
$_LANG['all_cat'] = '全部分类';


$_LANG['empty'] = '不能为空';
$_LANG['repeat'] = '已存在';
$_LANG['is_int'] = '应该为整数';

// yyy添加start
$_LANG['button_new'] = ' 新建 ';
$_LANG['top_huanying'] = ' ，欢迎您！ ';
// yyy添加end
/* 代码增加_start By www.taoyumall.com */
$_LANG['17_pickup_point_manage'] = '自提点管理';
$_LANG['pickup_point_list'] = '自提点列表';
$_LANG['pickup_point_add'] = '添加自提点';
$_LANG['pickup_point_batch_add'] = '自提点批量上传';
/* 代码增加_end By www.taoyumall.com */
$_LANG['button_submit'] = ' 确定 ';
$_LANG['button_save'] = ' 保存 ';
$_LANG['button_reset'] = ' 重置 ';
$_LANG['button_search'] = ' 搜索 ';

$_LANG['priv_error'] = '对不起,您没有执行此项操作的权限!';
$_LANG['drop_confirm'] = '您确认要删除这条记录吗?';
$_LANG['form_notice'] = '点击此处查看提示信息';
$_LANG['upfile_type_error'] = '上传文件的类型不正确!';
$_LANG['upfile_error'] = '上传文件失败!';
$_LANG['no_operation'] = '您没有选择任何操作';

$_LANG['go_back'] = '返回上一页';
$_LANG['back'] = '返回';
$_LANG['continue'] = '继续';
$_LANG['system_message'] = '系统信息';
$_LANG['check_all'] = '全选';
$_LANG['select_please'] = '请选择...';
$_LANG['all_category'] = '所有分类';
$_LANG['all_brand'] = '所有品牌';
$_LANG['refresh'] = '刷新';
$_LANG['update_sort'] = '更新排序';
$_LANG['modify_failure'] = '修改失败!';
$_LANG['attradd_succed'] = '操作成功!';
$_LANG['todolist'] = '记事本';
$_LANG['n_a'] = 'N/A';

/* 提示 */
$_LANG['sys']['wrong'] = '错误：';

/* 编码 */
$_LANG['charset']['utf8'] = '国际化编码（utf8）';
$_LANG['charset']['zh_cn'] = '简体中文';
$_LANG['charset']['zh_tw'] = '繁体中文';
$_LANG['charset']['en_us'] = '美国英语';
$_LANG['charset']['en_uk'] = '英文';

/* 新订单通知 */
$_LANG['order_notify'] = '新订单通知';
$_LANG['new_order_1'] = '您有 ';
$_LANG['new_order_2'] = ' 个新订单以及 ';
$_LANG['new_order_3'] = ' 个新付款的订单';
$_LANG['new_order_link'] = '点击查看新订单';

/* 语言项 */
$_LANG['chinese_simplified'] = '简体中文';
$_LANG['english'] = '英文';

/* 分页 */
$_LANG['total_records'] = '总计 ';
$_LANG['total_pages'] = '个记录分为';
$_LANG['page_size'] = '页，每页';
$_LANG['page_current'] = '页当前第';
$_LANG['page_first'] = '第一页';
$_LANG['page_prev'] = '上一页';
$_LANG['page_next'] = '下一页';
$_LANG['page_last'] = '最末页';
$_LANG['admin_home'] = '起始页';

/* 重量 */
$_LANG['gram'] = '克';
$_LANG['kilogram'] = '千克';

/* 权限管理需要语言*/
$_LANG['admin_list_role'] = '角色列表';
$_LANG['admin_add'] = '添加管理员';
$_LANG['admin_add_role'] = '添加角色';
$_LANG['admin_edit_role'] = '修改角色';

/* cls_image类的语言项 */
$_LANG['directory_readonly'] = '目录 % 不存在或不可写';
$_LANG['invalid_upload_image_type'] = '不是允许的图片格式';
$_LANG['upload_failure'] = '文件 %s 上传失败。';
$_LANG['missing_gd'] = '没有安装GD库';
$_LANG['missing_orgin_image'] = '找不到原始图片 %s ';
$_LANG['nonsupport_type'] = '不支持该图像格式 %s ';
$_LANG['creating_failure'] = '创建图片失败';
$_LANG['writting_failure'] = '图片写入失败';
$_LANG['empty_watermark'] = '水印文件参数不能为空';
$_LANG['missing_watermark'] = '找不到水印文件%s';
$_LANG['create_watermark_res'] = '创建水印图片资源失败。水印图片类型为%s';
$_LANG['create_origin_image_res'] = '创建原始图片资源失败，原始图片类型%s';
$_LANG['invalid_image_type'] = '无法识别水印图片 %s ';
$_LANG['file_unavailable'] = '文件 %s 不存在或不可读';


/* 代码增加_start By supplier.68ecshop.com */
$_LANG['cfg_name']['company_type'] = '入驻商企业类型';
$_LANG['cfg_desc']['company_type'] = '入驻商申请时会用到，填写时注意每行一个企业类型';
$_LANG['cfg_name']['supplier_privilege'] = '入驻商设置';
$_LANG['cfg_name']['supplier_addbest'] = '加入推荐权限';
$_LANG['cfg_name']['supplier_editgoods'] = '更改商品信息权限';
$_LANG['cfg_name']['supplier_secondadd'] = '审核未通过商品再次提交';
// 代码增加
$_LANG['cfg_name']['supplier_comment'] = '能否控制订单评论';
$_LANG['cfg_range']['supplier_comment']['1'] = '开启';
$_LANG['cfg_range']['supplier_comment']['0'] = '关闭';
$_LANG['cfg_desc']['supplier_comment'] = '开启时，商家可禁止公开订单评论';
// 代码增加

/* 能否控制显示和隐藏评论 */
$_LANG['cfg_name']['supplier_commentshow'] = '能否控制显示和隐藏评论';
$_LANG['cfg_range']['supplier_commentshow']['1'] = '开启';
$_LANG['cfg_range']['supplier_commentshow']['0'] = '关闭';
/* 能否控制显示和隐藏评论 */
$_LANG['cfg_range']['supplier_addbest']['1'] = '开启';
$_LANG['cfg_range']['supplier_addbest']['0'] = '关闭';
$_LANG['cfg_range']['supplier_editgoods']['1'] = '开启';
$_LANG['cfg_range']['supplier_editgoods']['0'] = '关闭';
$_LANG['cfg_desc']['supplier_editgoods'] = '注意：这里的商品指的是 “审核通过后的商品”';
$_LANG['cfg_range']['supplier_secondadd']['1'] = '开启';
$_LANG['cfg_range']['supplier_secondadd']['0'] = '关闭';
$_LANG['cfg_name']['supplier_notice'] = '入驻商公告';
$_LANG['cfg_name']['supplier_rebate_paytype'] = '佣金支付方式';

$_LANG['01_goods_list_pass1'] = '审核通过商品';
$_LANG['01_goods_list_pass2'] = '未审核商品';
$_LANG['01_goods_list_pass3'] = '审核未通过商品';
$_LANG['02_rebate_manage'] = '佣金管理';
$_LANG['03_rebate_nopay'] = '平台交易统计';//本期待结';
$_LANG['03_rebate_pay'] = '往期结算';
$_LANG['05_dianpu_manage'] = '店铺系统设置';
$_LANG['01_base'] = '店铺基本设置';
$_LANG['02_menu'] = '店铺导航栏';
$_LANG['03_guanggao'] = '店铺主广告';
$_LANG['04_article'] = '店铺文章';
$_LANG['05_header'] = '店铺头部自定义';
$_LANG['06_templates'] = '店铺模板选择';
$_LANG['07_street'] = '店铺街信息设置';
$_LANG['08_shipping_list'] = '配送方式';





?>