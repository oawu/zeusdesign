<form action='<?php echo base_url ('admin', 'contacts');?>' method='get' class="search<?php echo $has_search = array_filter (column_array ($columns, 'value')) ? ' show' : '';?>">
<?php 
  if ($columns) { ?>
    <div class='l i<?php echo count ($columns);?> n0'>
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
    <button type='submit'>尋找</button>
<?php 
  } else { ?>
    <div class='l i0 n0'></div>
<?php 
  }?>
</form>
<button type='button' onClick="if (!$(this).prev ().is (':visible')) $(this).attr ('class', 'icon-chevron-left').prev ().addClass ('show'); else $(this).attr ('class', 'icon-chevron-right').prev ().removeClass ('show');" class='icon-chevron-<?php echo $has_search ? 'left' : 'right';?>'></button>

  <table class='table-list-rwd'>
    <tbody>
<?php if ($contacts) {
        foreach ($contacts as $contact) { ?>
          <tr>
            <td data-title='是否回覆' width='90'>
              <label class='checkbox'>
                <input type='checkbox' data-id='<?php echo $contact->id;?>'<?php echo $contact->is_replied ? ' checked' : '';?>>
                <span></span><div><?php echo Contact::$replyName[$contact->is_replied];?></div>
              </label>
            </td>

            <td data-title='稱呼' width='100'><?php echo $contact->name;?></td>
            <td data-title='E-Mail' width='180'><?php echo $contact->email;?></td>
            <td data-title='留言內容'><?php echo $contact->message;?></td>
            <td data-title='IP' width='150'><?php echo $contact->ip;?></td>

            <td data-title='留言時間' width='130' class='timeago' data-time='<?php echo $contact->created_at->format ('Y-m-d H:i:s');?>'><?php echo $contact->created_at->format ('Y-m-d H:i:s');?></td>

            <td data-title='隱藏' width='50'>
              <a href='<?php echo base_url ('admin', 'contacts', 'no_visibled', $contact->id);?>' data-method='post' class='icon-eye-blocked'></a>
            </td>
          </tr>
  <?php }
      } else { ?>
        <tr><td colspan>目前沒有任何資料。</td></tr>
<?php }?>
    </tbody>
  </table>

<?php echo render_cell ('admin_frame_cell', 'pagination', $pagination);?>

