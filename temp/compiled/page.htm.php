
      <script type="text/javascript" src="<?php echo self::$_var['urls_dir']; ?>/js/utils.js"></script>




      <div id="turn-page">

        <?php echo self::$_var['lang']['total_records']; ?> <span id="totalRecords"><?php echo self::$_var['record_count']; ?></span>

        <?php echo self::$_var['lang']['total_pages']; ?> <span id="totalPages"><?php echo self::$_var['page_count']; ?></span>

        <?php echo self::$_var['lang']['page_current']; ?> <span id="pageCurrent"><?php echo self::$_var['filter']['page']; ?></span>

        <?php echo self::$_var['lang']['page_size']; ?> <input type='text' size='3' id='pageSize' value="<?php echo self::$_var['filter']['page_size']; ?>" onkeypress="return listTable.changePageSize(event)" />

        <span id="page-link">
          <a href="javascript:listTable.gotoPageFirst()"><?php echo self::$_var['lang']['page_first']; ?></a>
          <a href="javascript:listTable.gotoPagePrev()"><?php echo self::$_var['lang']['page_prev']; ?></a>
          <a href="javascript:listTable.gotoPageNext()"><?php echo self::$_var['lang']['page_next']; ?></a>
          <a href="javascript:listTable.gotoPageLast()"><?php echo self::$_var['lang']['page_last']; ?></a>  
         跳转到 <input id="pageInput" title="按回车键跳转到指定页数" value="<?php echo self::$_var['filter']['page']; ?>" style="width:25px;"  onkeypress="if(event.keyCode==13) {listTable.gotoPage(this.value);}"   type="text">
          <input class="button" id="pagerBtn" value="GO" onclick="goToPage()" type="button">

            <?php if (self::$_var['mo'] == 1): ?>
               <?php $_from = self::$_var['num']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; self::push_vars('', 'val_0_63531300_1529647637');if (count($_from)):
    foreach ($_from AS self::$_var['val_0_63531300_1529647637']):
?>
                    <?php echo self::$_var['val_0_63531300_1529647637']; ?>
<!--onclick="var val=this.getAttribute('page');alert(val);listTable.gotoPage(val)"><?php echo self::$_var['val_0_63531300_1529647637']; ?></a>-->
               <?php endforeach; endif; unset($_from); ?><?php self::pop_vars(); ?>
            <?php endif; ?>
        </span>
      </div>
<script>

function goToPage(){

	var page=$("#pageInput").val();

	listTable.gotoPage(page);

}

</script>