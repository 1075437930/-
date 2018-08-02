

<?php if (self::$_var['full_page']): ?>

<?php echo self::fetch('pageheader.htm'); ?>

<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>
<script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/listtable.js"></script>

<div class="form-div">

<form method="post" action="areamanage.php" name="theForm" onsubmit="return add_area()">

<?php if (self::$_var['region_type'] == '0'): ?><?php echo self::$_var['lang']['add_country']; ?>:

<?php elseif (self::$_var['region_type'] == '1'): ?><?php echo self::$_var['lang']['add_province']; ?>:

<?php elseif (self::$_var['region_type'] == '2'): ?><?php echo self::$_var['lang']['add_city']; ?>:

<?php elseif (self::$_var['region_type'] == '3'): ?><?php echo self::$_var['lang']['add_cantonal']; ?>: <?php endif; ?>

<input type="text" name="region_name" maxlength="150" size="40" />

<input type="hidden" name="region_type" value="<?php echo self::$_var['region_type']; ?>" />

<input type="hidden" name="parent_id" value="<?php echo self::$_var['parent_id']; ?>" />

<input type="submit" value="<?php echo self::$_var['lang']['button_submit']; ?>" class="button" />

</form>

</div>





<div class="list-div">

<table cellspacing='1' cellpadding='3' id='listTable'>

  <tr>

    <th><?php echo self::$_var['area_here']; ?></th>

  </tr>

</table>

</div>

<div class="list-div" id="listDiv">

<?php endif; ?>



<table cellspacing='1' cellpadding='3' id='listTable'>

  <tr>

    <?php $_from = self::$_var['region_arr']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'list');self::$_foreach['area_name'] = array('total' => count($_from), 'iteration' => 0);
if (self::$_foreach['area_name']['total'] > 0):
    foreach ($_from AS self::$_var['list']):
        self::$_foreach['area_name']['iteration']++;
?>

      <?php if (self::$_foreach['area_name']['iteration'] > 1 && ( self::$_foreach['area_name']['iteration'] - 1 ) % 3 == 0): ?>

      </tr><tr>

      <?php endif; ?>

      <td class="first-cell" align="left">

       <span onclick="listTable.edit(this, 'edit_area_name', '<?php echo self::$_var['list']['region_id']; ?>'); return false;"><?php echo htmlspecialchars(self::$_var['list']['region_name']); ?></span>

       <span class="link-span">

       <?php if (self::$_var['region_type'] < 3): ?>

       <a href="index.php?act=areamanage&op=lists&type=<?php echo self::$_var['list']['region_type+1']; ?>&pid=<?php echo self::$_var['list']['region_id']; ?>" title="<?php echo self::$_var['lang']['manage_area']; ?>">

         <?php echo self::$_var['lang']['manage_area']; ?></a>&nbsp;&nbsp;

       <?php endif; ?>

       <a href="javascript:listTable.remove(<?php echo self::$_var['list']['region_id']; ?>, '<?php echo self::$_var['lang']['area_drop_confirm']; ?>', 'drop_area')" title="<?php echo self::$_var['lang']['drop']; ?>"><?php echo self::$_var['lang']['drop']; ?></a>

       </span>

      </td>

    <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>

  </tr>

</table>



<?php if (self::$_var['full_page']): ?>

</div>





<script language="JavaScript">

<!--

listTable.act = "areamanage";

onload = function() {

  document.forms['theForm'].elements['region_name'].focus();

  // 开始检查订单

  startCheckOrder();

}



/**

 * 新建区域

 */

function add_area()

{

    var region_name = Utils.trim(document.forms['theForm'].elements['region_name'].value);

    var region_type = Utils.trim(document.forms['theForm'].elements['region_type'].value);

    var parent_id   = Utils.trim(document.forms['theForm'].elements['parent_id'].value);



    if (region_name.length == 0)

    {

        alert(region_name_empty);

    }

    else

    {

      Ajax.call('index.php?is_ajax=1&act=areamanage&op=add_area',

        'parent_id=' + parent_id + '&region_name=' + region_name + '&region_type=' + region_type,

        listTable.listCallback, 'POST', 'JSON');

    }



    return false;

}



//-->

</script>





<?php echo self::fetch('pagefooter.htm'); ?>

<?php endif; ?>