<form action='<?php echo base_url ('admin', 'work_tags', $parent_tag->id, 'tags');?>' method='get' class="search<?php echo $has_search ? ' show' : '';?>">
  <div class='l i1 n1'>
    <input type='text' name='name' value='<?php echo @$columns['name'];?>' placeholder='請輸入 名稱..' />
  </div>
  <button type='submit'>尋找</button>
  <a href='<?php echo base_url ('admin', 'work_tags', $parent_tag->id, 'tags', 'add');?>'>新增</a>
</form>
<button type='button' onClick="if (!$(this).prev ().is (':visible')) $(this).attr ('class', 'icon-chevron-left').prev ().addClass ('show'); else $(this).attr ('class', 'icon-chevron-right').prev ().removeClass ('show');" class='icon-chevron-<?php echo $has_search ? 'left' : 'right';?>'></button>

  <table class='table-list-rwd'>
    <tbody>
<?php if ($tags) {
        foreach ($tags as $tag) { ?>
          <tr>
            <td data-title='名稱' width=''><?php echo $tag->name;?></td>
            <td data-title='作品數量' width='100'><?php echo count ($tag->mappings);?> 個</td>
            <td data-title='編輯' width='120'>
              <a href='<?php echo base_url ('admin', 'work_tags', $tag->id, 'works');?>' class='icon-images'></a>
              <a href='<?php echo base_url ('admin', 'work_tags', $parent_tag->id, 'tags', $tag->id, 'edit');?>' class='icon-pencil2'></a>
              <a href='<?php echo base_url ('admin', 'work_tags', $parent_tag->id, 'tags', $tag->id);?>' data-method='delete' class='icon-bin destroy'></a>
            </td>
            <td data-title='排序' width='50' class='sort'>
              <a href='<?php echo base_url ('admin', 'work_tags', $parent_tag->id, 'tags', $tag->id, 'sort', 'up');?>' data-method='post' class='icon-triangle-up'></a>
              <a href='<?php echo base_url ('admin', 'work_tags', $parent_tag->id, 'tags', $tag->id, 'sort', 'down');?>' data-method='post' class='icon-triangle-down'></a>
            </td>
          </tr>
  <?php }
      } else { ?>
        <tr><td colspan>目前沒有任何資料。</td></tr>
<?php }?>
    </tbody>
  </table>

<?php echo render_cell ('admin_frame_cell', 'pagination', $pagination);?>

