
<?php if (self::$_var['full_page']): ?>
<?php echo self::fetch('pageheader.htm'); ?>
<?php echo self::smarty_insert_scripts(array('files'=>'js/utils.js,js/listtable.js')); ?>

<form method="post" action="" name="listForm">

<div class="list-div" id="listDiv">
<?php endif; ?>

<table width="100%" cellspacing="1" cellpadding="2" id="list-table">
  <tr>
    <th><?php echo self::$_var['lang']['cat_name']; ?></th>
    <th><?php echo self::$_var['lang']['type']; ?></th>
    <th><?php echo self::$_var['lang']['cat_desc']; ?></th>
    <th><?php echo self::$_var['lang']['sort_order']; ?></th>
    <th><?php echo self::$_var['lang']['show_in_nav']; ?></th>
    <th><?php echo self::$_var['lang']['img_url']; ?></th>
    <th><?php echo self::$_var['lang']['handler']; ?></th>
  </tr>
  <?php $_from = self::$_var['articlecat']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'cat');if (count($_from)):
    foreach ($_from AS self::$_var['cat']):
?>
  <tr align="center" class="<?php echo self::$_var['cat']['level']; ?>" id="<?php echo self::$_var['cat']['level']; ?>_<?php echo self::$_var['cat']['cat_id']; ?>">
    <td align="left" class="first-cell nowrap" valign="top" >
      <?php if (self::$_var['cat']['is_leaf'] != 1): ?>
      <img src="templates/default/images/menu_minus.gif" id="icon_<?php echo self::$_var['cat']['level']; ?>_<?php echo self::$_var['cat']['cat_id']; ?>" width="9" height="9" border="0" style="margin-left:<?php echo self::$_var['cat']['level']; ?>em" onclick="rowClicked(this)" />
      <?php else: ?>
      <img src="templates/default/images/menu_arrow.gif" width="9" height="9" border="0" style="margin-left:<?php echo self::$_var['cat']['level']; ?>em" />
      <?php endif; ?>
      <span><a href="index.php?act=article&op=lists&cat_id=<?php echo self::$_var['cat']['cat_id']; ?>"><?php echo htmlspecialchars(self::$_var['cat']['cat_name']); ?></a></span>
    </td>
    <td class="nowrap" valign="top">
      <?php echo self::$_var['cat']['type_name']; ?>
    </td>
    <td align="left" valign="top">
      <?php echo self::$_var['cat']['cat_desc']; ?>
    </td>
    <td width="10%" align="right" class="nowrap" valign="top"><span onclick="listTable.edit(this, 'edit_sort_order', <?php echo self::$_var['cat']['cat_id']; ?>)"><?php echo self::$_var['cat']['sort_order']; ?></span></td>
    <td width="10%" class="nowrap" valign="top"><img src="templates/default/images/<?php if (self::$_var['cat']['show_in_nav'] == '1'): ?>yes<?php else: ?>no<?php endif; ?>.gif" onclick="listTable.toggle(this, 'toggle_show_in_nav', <?php echo self::$_var['cat']['cat_id']; ?>)" /></td>
    <td align="center" >
      <img src="<?php echo self::$_var['cat']['img_urls']; ?>" width="30" height="30" />
    </td>
    <td width="24%" align="right" class="nowrap" valign="top">
      <a href="index.php?act=articlecat&op=edit&cat_id=<?php echo self::$_var['cat']['cat_id']; ?>"><?php echo self::$_var['lang']['edit']; ?></a>
      <?php if (self::$_var['cat']['cat_type'] != 2 && self::$_var['cat']['cat_type'] != 3 && self::$_var['cat']['cat_type'] != 4): ?>|
      <a href="javascript:;" onclick="listTable.remove(<?php echo self::$_var['cat']['cat_id']; ?>, '<?php echo self::$_var['lang']['drop_confirm']; ?>')" title="<?php echo self::$_var['lang']['remove']; ?>"><?php echo self::$_var['lang']['remove']; ?></a>
      <?php endif; ?>
    </td>
  </tr>
  <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
</table>

<?php if (self::$_var['full_page']): ?>
</div>
</form>


<script language="JavaScript">
listTable.act = "articlecat";
var imgPlus = new Image();
imgPlus.src = "templates/default/images/menu_plus.gif";

//折叠分类列表

function rowClicked(obj)
{
   // 当前图像
  img = obj;
  // 取得上二级tr>td>img对象
  obj = obj.parentNode.parentNode;
  // 整个分类列表表格
  var tbl = document.getElementById("list-table");
  // 当前分类级别
  var lvl = parseInt(obj.className);
  // 是否找到元素
  var fnd = false;
  var sub_display = img.src.indexOf('menu_minus.gif') > 0 ? 'none' : (Browser.isIE) ? 'block' : 'table-row' ;
  // 遍历所有的分类
  for (i = 0; i < tbl.rows.length; i++)
  {
      var row = tbl.rows[i];
      if (row == obj)
      {
          // 找到当前行
          fnd = true;
          //document.getElementById('result').innerHTML += 'Find row at ' + i +"<br/>";
      }
      else
      {
          if (fnd == true)
          {
              var cur = parseInt(row.className);
              var icon = 'icon_' + row.id;
              if (cur > lvl)
              {
                  row.style.display = sub_display;
                  if (sub_display != 'none')
                  {
                      var iconimg = document.getElementById(icon);
                      iconimg.src = iconimg.src.replace('plus.gif', 'minus.gif');
                  }
              }
              else
              {
                  fnd = false;
                  break;
              }
          }
      }
  }

  for (i = 0; i < obj.cells[0].childNodes.length; i++)
  {
      var imgObj = obj.cells[0].childNodes[i];
      if (imgObj.tagName == "IMG" && imgObj.src != 'images/menu_arrow.gif')
      {
          imgObj.src = (imgObj.src == imgPlus.src) ? 'images/menu_minus.gif' : imgPlus.src;
      }
  }
}

</script>


<?php echo self::fetch('pagefooter.htm'); ?>
<?php endif; ?>