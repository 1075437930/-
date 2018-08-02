
<?php echo self::fetch('pageheader.htm'); ?>
<div class="list-div">
  <table width="100%" cellpadding="3" cellspacing="1">
     <tr>
      <th><?php echo self::$_var['lang']['consignee']; ?></th>
      <th><?php echo self::$_var['lang']['address']; ?></th>
      <th><?php echo self::$_var['lang']['link']; ?></th>
      <th><?php echo self::$_var['lang']['other']; ?></th>
    </tr>
  <?php $_from = self::$_var['address']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('Key', 'val');if (count($_from)):
    foreach ($_from AS self::$_var['Key'] => self::$_var['val']):
?>
    <tr>
      <td><?php echo htmlspecialchars(self::$_var['val']['consignee']); ?></td>
      <td><?php echo self::$_var['val']['country_name']; ?>&nbsp;&nbsp;<?php echo self::$_var['val']['province_name']; ?>&nbsp;&nbsp;<?php echo self::$_var['val']['city_name']; ?>&nbsp;&nbsp;<?php echo self::$_var['val']['district_name']; ?><br />
      <?php echo htmlspecialchars(self::$_var['val']['address']); ?><?php if (self::$_var['val']['zipcode']): ?>[<?php echo htmlspecialchars(self::$_var['val']['zipcode']); ?>]<?php endif; ?></td>
      <td><?php echo self::$_var['lang']['tel']; ?>：<?php echo self::$_var['val']['tel']; ?><br /><?php echo self::$_var['lang']['mobile']; ?>：<?php echo self::$_var['val']['mobile']; ?><br/>email: <?php echo self::$_var['val']['email']; ?></td>
      <td><?php echo self::$_var['lang']['best_time']; ?>:<?php echo htmlspecialchars(self::$_var['val']['best_time']); ?><br/><?php echo self::$_var['lang']['sign_building']; ?>:<?php echo htmlspecialchars(self::$_var['val']['sign_building']); ?></td>
    </tr>
  <?php endforeach; else: ?>
    <tr><td class="no-records" colspan="4"><?php echo self::$_var['lang']['no_records']; ?></td></tr>
  <?php endif; unset($_from); ?><?php self::pop_vars(); ?>
  </table>
</div>
<?php echo self::fetch('pagefooter.htm'); ?>