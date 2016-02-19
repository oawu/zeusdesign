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
