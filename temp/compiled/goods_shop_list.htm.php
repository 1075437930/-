

<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script>


<div class="form-div">
    <form action="javascript:searchGoods()" name="searchForm">
        <img src="templates/default/images/icon_search.gif" width="26" height="22" border="0" alt="SEARCH" />
        

        <select id="cat_id" name="cat_id">
            <option value="0"><?php echo self::$_var['lang']['all_cat']; ?></option>
            <?php echo self::$_var['cat_list']; ?>
        </select>
        
        <select  class="chzn-select" id="brand_id" name="brand_id"><option value="0"><?php echo self::$_var['lang']['goods_brand']; ?></option><?php echo self::html_options(array('options'=>self::$_var['brand_list'])); ?></select>
         
        <select  class="chzn-select" id="supplier_id" name="supplier_id"><option value="0"><?php echo self::$_var['lang']['goods_supplier']; ?></option><?php echo self::html_options(array('options'=>self::$_var['supplie_list'])); ?></select>
         
        <select  class="chzn-select" id="supplier_status" name="supplier_status">
            <option value=""><?php echo self::$_var['lang']['supplier_status']; ?></option>
            <option value="0">未审核</option>
            <option value="1">审核通过</option>
            <option value="-1">审核未通过</option>
        </select>
        
        关键字 <input type="text" name="keyword" size="15" />
        <input type="submit" value="<?php echo self::$_var['lang']['button_search']; ?>" class="button" />
    </form>
</div>
<script language="JavaScript">
 
    function searchGoods(){
        listTable.filter['cat_id'] = document.forms['searchForm'].elements['cat_id'].value;
        listTable.filter['brand_id'] = document.forms['searchForm'].elements['brand_id'].value;
        listTable.filter['supplier_id'] = document.forms['searchForm'].elements['supplier_id'].value;
        listTable.filter['keyword'] = Utils.trim(document.forms['searchForm'].elements['keyword'].value);
        listTable.filter['page'] = 1;
        listTable.loadList();
    }
</script>

<form method="post" action="index.php" name="listForm" onsubmit="return confirmSubmit(this)">
  
  <div class="list-div" id="listDiv">
<?php endif; ?>
<table cellpadding="3" cellspacing="1">
  <tr>
    <th>
      <input onclick='listTable.selectAll(this, "checkboxes")' type="checkbox" />
      <a href="javascript:listTable.sort('goods_id'); "><?php echo self::$_var['lang']['record_id']; ?></a><?php echo self::$_var['sort_goods_id']; ?>
    </th>
    <th>图片</th>
    <th><a href="javascript:listTable.sort('supplier_name'); ">供货商家</a><?php echo self::$_var['sort_supplier_name']; ?></th>
    <th><?php echo self::$_var['lang']['goods_name']; ?></th>
    <th><?php echo self::$_var['lang']['goods_sn']; ?></th>
    <th><a href="javascript:listTable.sort('shop_price'); ">商城价格</a><?php echo self::$_var['sort_shop_price']; ?></th>
    <th><a href="javascript:listTable.sort('add_time'); ">上架时间</a><?php echo self::$_var['sort_add_time']; ?></th>
    <th><a href="javascript:listTable.sort('last_update'); ">修改时间</a><?php echo self::$_var['sort_last_update']; ?></th>
    <th><a href="javascript:listTable.sort('is_on_sale'); "><?php echo self::$_var['lang']['is_on_sale']; ?></a><?php echo self::$_var['sort_is_on_sale']; ?></th>
    <th><a href="javascript:listTable.sort('is_best'); "><?php echo self::$_var['lang']['is_best']; ?></a><?php echo self::$_var['sort_is_best']; ?></th>
    <th><a href="javascript:listTable.sort('is_new'); "><?php echo self::$_var['lang']['is_new']; ?></a><?php echo self::$_var['sort_is_new']; ?></th>
    <th><a href="javascript:listTable.sort('is_hot'); "><?php echo self::$_var['lang']['is_hot']; ?></a><?php echo self::$_var['sort_is_hot']; ?></th>
    <th><a href="javascript:listTable.sort('is_tiejia'); "><?php echo self::$_var['lang']['is_tiejia']; ?></a><?php echo self::$_var['sort_is_tiejia']; ?></th>
    <th><a href="javascript:listTable.sort('sort_order'); "><?php echo self::$_var['lang']['sort_order']; ?></a><?php echo self::$_var['sort_sort_order']; ?></th>
    <th>审核状态</th>
    <th><a href="javascript:listTable.sort('goods_number'); "><?php echo self::$_var['lang']['goods_number']; ?></a><?php echo self::$_var['sort_goods_number']; ?></th>
    <th><?php echo self::$_var['lang']['handler']; ?></th>
  <tr>
  <?php $_from = self::$_var['goods_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'goods');if (count($_from)):
    foreach ($_from AS self::$_var['goods']):
?>
  <tr>
    <td><input type="checkbox" name="checkboxes[]" value="<?php echo self::$_var['goods']['goods_id']; ?>" /><?php echo self::$_var['goods']['goods_id']; ?></td>
    <td><img src="<?php echo self::$_var['goods']['goods_thumb']; ?>" width="40" height="40" border="0" /></td>
    <td><span><?php echo self::$_var['goods']['supplier_name']; ?></span></td>
    <td class="first-cell" style="<?php if (self::$_var['goods']['is_promote']): ?>color:red;<?php endif; ?>"><span><?php echo self::$_var['goods']['goods_name']; ?></span></td>
    <td><span onclick="listTable.edit(this, 'edit_goods_sn', <?php echo self::$_var['goods']['goods_id']; ?>)"><?php echo self::$_var['goods']['goods_sn']; ?></span></td>
    <td align="right"><span><?php echo self::$_var['goods']['shop_price']; ?></span></td>
    <td align="right"><span><?php echo self::$_var['goods']['add_time']; ?></span></td>
    <td align="right"><span><?php echo self::$_var['goods']['last_update']; ?></span></td>
    <td align="center"><img src="templates/default/images/<?php if (self::$_var['goods']['is_on_sale']): ?>yes<?php else: ?>no<?php endif; ?>.gif" onclick="listTable.toggle(this, 'toggle_on_sale', <?php echo self::$_var['goods']['goods_id']; ?>)" /></td>
    <td align="center"><img src="templates/default/images/<?php if (self::$_var['goods']['is_best']): ?>yes<?php else: ?>no<?php endif; ?>.gif" onclick="listTable.toggle(this, 'toggle_best', <?php echo self::$_var['goods']['goods_id']; ?>)" /></td>
    <td align="center"><img src="templates/default/images/<?php if (self::$_var['goods']['is_new']): ?>yes<?php else: ?>no<?php endif; ?>.gif" onclick="listTable.toggle(this, 'toggle_new', <?php echo self::$_var['goods']['goods_id']; ?>)" /></td>
    <td align="center"><img src="templates/default/images/<?php if (self::$_var['goods']['is_hot']): ?>yes<?php else: ?>no<?php endif; ?>.gif" onclick="listTable.toggle(this, 'toggle_hot', <?php echo self::$_var['goods']['goods_id']; ?>)" /></td>
    <td align="center"><img src="templates/default/images/<?php if (self::$_var['goods']['is_tiejia']): ?>yes<?php else: ?>no<?php endif; ?>.gif" onclick="listTable.toggle(this, 'toggle_tiejia', <?php echo self::$_var['goods']['goods_id']; ?>)" /></td>
    <td align="center"><span onclick="listTable.edit(this, 'edit_sort_order', <?php echo self::$_var['goods']['goods_id']; ?>)"><?php echo self::$_var['goods']['sort_order']; ?></span></td>
    
    <td align="center">
        <?php if (self::$_var['goods']['supplier_status'] == '-1'): ?>
            审核未通过
        <?php else: ?>
            <img src="templates/default/images/<?php if (self::$_var['goods']['supplier_status'] == '1'): ?>yes<?php elseif (self::$_var['goods']['supplier_status'] == '0'): ?>no<?php endif; ?>.gif" onclick="listTable.toggle(this, 'toggle_status', <?php echo self::$_var['goods']['goods_id']; ?>)" />
        <?php endif; ?>
    </td>
    <td align="right"><span onclick="listTable.edit(this, 'edit_goods_number', <?php echo self::$_var['goods']['goods_id']; ?>)"><?php echo self::$_var['goods']['goods_number']; ?></span></td>
    <td align="center">
      <a href="index.php?act=shop&op=shop_edit&goods_id=<?php echo self::$_var['goods']['goods_id']; ?>" title="<?php echo self::$_var['lang']['edit']; ?>"><img src="templates/default/images/icon_edit.gif" width="16" height="16" border="0" /></a>
      <a href="javascript:;" onclick="listTable.remove(<?php echo self::$_var['goods']['goods_id']; ?>, '<?php echo self::$_var['lang']['trash_goods_confirm']; ?>','shop_remove')" title="<?php echo self::$_var['lang']['trash']; ?>"><img src="templates/default/images/icon_trash.gif" width="16" height="16" border="0" /></a>
    </td>
  </tr>
  <?php endforeach; else: ?>
  <tr><td class="no-records" colspan="17"><?php echo self::$_var['lang']['no_records']; ?></td></tr>
  <?php endif; unset($_from); ?><?php self::pop_vars(); ?>
</table>



<table id="page-table" cellspacing="0">
  <tr>
    <td align="right" nowrap="true">
    <?php echo self::fetch('page.htm'); ?>
    </td>
  </tr>
</table>
<?php if (self::$_var['full_page']): ?>
</div>

<div>
   操作：
  <input type="hidden" name="act" value="shop" />
  <input type="hidden" name="op" value="shop_batch" />
  <select name="type" id="selAction" onchange="changeAction()">
    <option value=""><?php echo self::$_var['lang']['select_please']; ?></option>
    <option value="trash"><?php echo self::$_var['lang']['trash']; ?></option>
    <option value="on_sale"><?php echo self::$_var['lang']['on_sale']; ?></option>
    <option value="not_on_sale"><?php echo self::$_var['lang']['not_on_sale']; ?></option>
    <option value="best"><?php echo self::$_var['lang']['best']; ?></option>
    <option value="not_best"><?php echo self::$_var['lang']['not_best']; ?></option>
    <option value="new"><?php echo self::$_var['lang']['new']; ?></option>
    <option value="not_new"><?php echo self::$_var['lang']['not_new']; ?></option>
    <option value="hot"><?php echo self::$_var['lang']['hot']; ?></option>
    <option value="not_hot"><?php echo self::$_var['lang']['not_hot']; ?></option>
  </select>
  <input type="submit" value="<?php echo self::$_var['lang']['button_submit']; ?>" id="btnSubmit" name="btnSubmit" class="button"/>
</div>
</form>

<script type="text/javascript">
  listTable.recordCount = <?php echo self::$_var['record_count']; ?>;
  listTable.pageCount = <?php echo self::$_var['page_count']; ?>;
  listTable.act = "shop";
  listTable.query = "shop_query";
  <?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>
    listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

  


  
  /**
   * @param: bool ext 其他条件：用于转移分类
   */
  function confirmSubmit(frm, ext)
  {
      if (frm.elements['type'].value == 'trash')
      {
          return confirm('您确实要把选中的商品放入回收站吗？');
      }
      else if (frm.elements['type'].value == 'not_on_sale')
      {
          return confirm('您确实要将选定的商品下架吗？');
      }
      else if (frm.elements['type'].value == '')
      {
          return false;
      }
      else
      {
          return true;
      }
  }

  function changeAction(){	
      var frm = document.forms['listForm'];
      if (!document.getElementById('btnSubmit').disabled && confirmSubmit(frm))
      {
          frm.submit();
      }
  }

</script>
<?php echo self::fetch('pagefooter.htm'); ?>
<?php endif; ?>