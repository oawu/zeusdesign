<form action='<?php echo base_url ('admin', 'work_tags', $tag->id, 'works');?>' method='get' class="search<?php echo $has_search = array_filter ($columns) ? ' show' : '';?>">
<?php 
  if ($columns) { ?>
    <div class='l i<?php echo count ($columns);?> n1'>
<?php foreach ($columns as $column) {
        if (isset ($column['select']) && $column['select']) { ?>
          <select name='<?php echo $column['key'];?>'>
            <option value=''>請選擇 <?php echo $column['title'];?>..</option>
      <?php foreach ($column['select'] as $option) { ?>
              <option value='<?php echo $option['value'];?>'<?php echo $option['value'] == $column['value'] ? ' selected' : '';?>><?php echo $option['text'];?></option>
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
    <div class='l i0 n1'></div>
<?php 
  }?>
  <a href='<?php echo base_url ('admin', 'work_tags', $tag->id, 'works', 'add');?>'>新增</a>
</form>
<button type='button' onClick="if (!$(this).prev ().is (':visible')) $(this).attr ('class', 'icon-chevron-left').prev ().addClass ('show'); else $(this).attr ('class', 'icon-chevron-right').prev ().removeClass ('show');" class='icon-chevron-<?php echo $has_search ? 'left' : 'right';?>'></button>

  <table class='table-list-rwd'>
    <tbody>
<?php if ($works) {
        foreach ($works as $work) { ?>
          <tr>
            <td data-title='標題' width='150'><?php echo $work->title;?></td>
            <td data-title='內容' ><?php echo $work->mini_content ();?></td>
            <td data-title='封面' width='50'><?php echo img ($work->cover->url ('100x100c'), false, 'class="i_30"');?></td>
            <td data-title='圖片' width='140' class='pics'><?php echo $work->pictures ? implode ('', array_map (function ($picture) { return img ($picture->name->url ('100x100c'), false, 'class="i_30"'); }, $work->pictures)) : '-';?></td>
            <td data-title='狀態' width='50'<?php echo !$work->is_enabled ? 'class="red"' : '';?>><?php echo Work::$enableName[$work->is_enabled];?></td>

            <td data-title='編輯' width='80'>
              <a href='<?php echo base_url ('admin', 'work_tags', $tag->id, 'works', $work->id, 'edit');?>' class='icon-pencil2'></a>
              <a href='<?php echo base_url ('admin', 'work_tags', $tag->id, 'works', $work->id);?>' data-method='delete' class='icon-bin destroy'></a>
            </td>
          </tr>
  <?php }
      } else { ?>
        <tr><td colspan>目前沒有任何資料。</td></tr>
<?php }?>
    </tbody>
  </table>

<?php echo render_cell ('admin_frame_cell', 'pagination', $pagination);?>

