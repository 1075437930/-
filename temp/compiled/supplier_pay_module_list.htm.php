<?php echo self::fetch('pageheader.htm'); ?>

<?php echo self::smarty_insert_scripts(array('files'=>'js/utils.js,js/listtable.js,js/jquery-1.6.2.min.js')); ?>



<?php echo self::fetch('supplier_pay_module_search.htm'); ?>

<form method="post" action="index.php" name="listForm">



<div class="list-div" id="listDiv">

<?php echo self::fetch('supplier_pay_module_div.htm'); ?>

</div>

</form>

<script type="text/javascript">

    listTable.recordCount = <?php echo self::$_var['record_count']; ?>;

    listTable.pageCount = <?php echo self::$_var['page_count']; ?>;

    listTable.act = 'suppaymodule';

    listTable.query = "query";

    <?php $_from = self::$_var['filter']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('key', 'item');if (count($_from)):
    foreach ($_from AS self::$_var['key'] => self::$_var['item']):
?>

    listTable.filter.<?php echo self::$_var['key']; ?> = '<?php echo self::$_var['item']; ?>';

    <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>


    onload = function ()

    {

        listTable.query = "query";

    }

</script>


<?php echo self::fetch('pagefooter.htm'); ?>



