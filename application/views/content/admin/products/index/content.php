<form action='<?php echo base_url ('admin', 'products');?>' method='get' class="search<?php echo $has_search ? ' show' : '';?>">
  <div class='l i2 n1'>
    <input type='text' name='title' value='<?php echo @$columns['title'];?>' placeholder='請輸入 標題..' />
    <input type='text' name='content' value='<?php echo @$columns['content'];?>' placeholder='請輸入 內容..' />
  </div>
  <button type='submit'>尋找</button>
  <a href='<?php echo base_url ('admin', 'products', 'add');?>'>新增</a>
</form>
<button type='button' onClick="if (!$(this).prev ().is (':visible')) $(this).attr ('class', 'icon-chevron-left').prev ().addClass ('show'); else $(this).attr ('class', 'icon-chevron-right').prev ().removeClass ('show');" class='icon-chevron-<?php echo $has_search ? 'left' : 'right';?>'></button>

  <table class='table-list-rwd'>
    <tbody>
<?php if ($products) {
        foreach ($products as $product) { ?>
          <tr>
            <td data-title='標題' width='50'><?php echo $product->title;?></td>
            <td data-title='編輯' width='150'>
              <!-- <a href='<?php echo base_url ('admin', 'products', $product->id, 'products');?>' class='icon-images'></a> -->
              <a href='<?php echo base_url ('admin', 'products', $product->id, 'products');?>' class='icon-products a'></a>
              <a href='<?php echo base_url ('admin', 'products', $product->id, 'edit');?>' class='icon-pencil2'></a>
              <a href='<?php echo base_url ('admin', 'products', $product->id);?>' data-method='delete' class='icon-bin destroy'></a>
            </td>
          </tr>
  <?php }
      } else { ?>
        <tr><td colspan>目前沒有任何資料。</td></tr>
<?php }?>
    </tbody>
  </table>

<?php echo render_cell ('frame_cell', 'pagination', $pagination);?>

