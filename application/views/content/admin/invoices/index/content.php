<form action='<?php echo base_url ('admin', 'invoices');?>' method='get' class="search<?php echo $has_search = array_filter ($columns) ? ' show' : '';?>">
<?php 
  if ($columns) { ?>
    <div class='l i<?php echo count ($columns);?> n2'>
<?php foreach ($columns as $column) {
        if (isset ($column['select']) && $column['select']) { ?>
          <select name='<?php echo $column['key'];?>'>
            <option value=''>請選擇 <?php echo $column['title'];?>..</option>
      <?php foreach ($column['select'] as $option) { ?>
              <option value='<?php echo $option['value'];?>'<?php echo $option['value'] === $column['value'] ? ' selected' : '';?>><?php echo $option['text'];?></option>
      <?php } ?>
          </select>
  <?php } else { ?>
          <input type='text' name='<?php echo $column['key'];?>' value='<?php echo $column['value'];?>' placeholder='請輸入 <?php echo $column['title'];?>..' />
<?php   }
      }?>
    </div>
    <button type='submit' href='<?php echo base_url ('admin', 'invoices');?>'>尋找</button>
<?php 
  } else { ?>
    <div class='l i0 n2'></div>
<?php 
  }?>
  <a id='export' href='<?php echo base_url ('admin', 'invoices', 'export');?>'>匯出</a>
  <a href='<?php echo base_url ('admin', 'invoices', 'add');?>'>新增</a>
</form>
<button type='button' onClick="if (!$(this).prev ().is (':visible')) $(this).attr ('class', 'icon-chevron-left').prev ().addClass ('show'); else $(this).attr ('class', 'icon-chevron-right').prev ().removeClass ('show');" class='icon-chevron-<?php echo $has_search ? 'left' : 'right';?>'></button>

  <table class='table-list-rwd'>
    <tbody>
<?php if ($invoices) {
        foreach ($invoices as $invoice) { ?>
          <tr>
            <td data-title='封面' width='50'><?php echo img ($invoice->cover->url ('100x100c'), false, 'class="i_30"');?></td>
            <td data-title='名稱' width='100'><?php echo $invoice->name;?></td>
            <td data-title='負責人' width='100'><?php echo $invoice->user->name;?></td>
            <td data-title='窗口' width='100'><?php echo $invoice->contact;?></td>
            <td data-title='金額' width='100'>NT$<?php echo $invoice->money;?></td>
            <td data-title='圖片' width='100' class='pics'><?php echo $invoice->pictures ? implode ('', array_map (function ($picture) { return img ($picture->name->url ('100x100c'), false, 'class="i_30"'); }, $invoice->pictures)) : '-';?></td>
            <td data-title='分類' width='80'><?php echo $invoice->tag ? $invoice->tag->name : '其他';?></td>
            <td data-title='結案日期' width='125'><?php echo $invoice->closing_at ? $invoice->closing_at->format ('Y-m-d') : '-';?></td>
            <td data-title='備註' ><?php echo $invoice->mini_memo ();?></td>
            <td data-title='是否請款' width='90'>
              <label class='checkbox'>
                <input type='checkbox' data-id='<?php echo $invoice->id;?>'<?php echo $invoice->is_finished ? ' checked' : '';?>>
                <span></span><div><?php echo Invoice::$finishName[$invoice->is_finished];?></div>
              </label>
            </td>

            <td data-title='編輯' width='70'>
              <a href='<?php echo base_url ('admin', 'invoices', $invoice->id, 'edit');?>' class='icon-pencil2'></a>
              <a href='<?php echo base_url ('admin', 'invoices', $invoice->id);?>' data-method='delete' class='icon-bin destroy'></a>
            </td>
          </tr>
  <?php }
      } else { ?>
        <tr><td colspan>目前沒有任何資料。</td></tr>
<?php }?>
    </tbody>
  </table>

<?php echo render_cell ('admin_frame_cell', 'pagination', $pagination);?>

