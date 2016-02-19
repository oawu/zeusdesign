<form action='<?php echo base_url ('admin', 'works', 'all');?>' method='get' class="search<?php echo $has_search = array_filter (column_array ($columns, 'value')) ? ' show' : '';?>">
<?php 
  if ($columns) { ?>
    <div class='l i<?php echo count ($columns);?> n1'>
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
    <div class='l i0 n1'></div>
<?php 
  }?>
  <a href='<?php echo base_url ('admin', 'works', 'add');?>'>新增</a>
</form>
<button type='button' onClick="if (!$(this).prev ().is (':visible')) $(this).attr ('class', 'icon-chevron-left').prev ().addClass ('show'); else $(this).attr ('class', 'icon-chevron-right').prev ().removeClass ('show');" class='icon-chevron-<?php echo $has_search ? 'left' : 'right';?>'></button>

<?php
  foreach ($works as $work) { ?>
    <div class='work'>
      <h3><?php echo $work->title;?></h3>

      <div class='ps'>
  <?php if ((String) $work->cover) { ?>
          <div href='<?php echo $work->cover->url ();?>' data-fancybox-group='fancybox_group_<?php echo $work->id;?>'>
            <img src='<?php echo $work->cover->url ();?>' />
            <div>封面</div>
            <a href='<?php echo $work->cover->url ();?>' download='<?php echo $work->cover;?>' class='icon-in'>下載</a>
            <a href='<?php echo $work->cover->url ();?>' class='icon-link-external'>檢視</a>
          </div>  
  <?php }
        if ($work->pictures) {
          foreach ($work->pictures as $picture) { ?>
            <div href='<?php echo $picture->name->url ();?>' data-fancybox-group='fancybox_group_<?php echo $work->id;?>'>
              <img src='<?php echo $picture->name->url ();?>' />
              <!-- <div>封面</div> -->
              <a href='<?php echo $picture->name->url ();?>' download='<?php echo $work->cover;?>' class='icon-in'>下載</a>
              <a href='<?php echo $picture->name->url ();?>' class='icon-link-external'>檢視</a>
            </div>  
    <?php }
        } ?>


      </div>
    </div>
<?php
  }
?>
