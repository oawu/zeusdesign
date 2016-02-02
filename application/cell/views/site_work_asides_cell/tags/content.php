<?php 
  if ($tags) {
    foreach ($tags as $tag) { ?>
      <a href='<?php echo base_url ('work-tag', $tag->id, 'works');?>' class='m'><?php echo $tag->name;?></a>
<?php foreach ($tag->tags as $tag) { ?>
        <a href='<?php echo base_url ('work-tag', $tag->id, 'works');?>'><?php echo $tag->name;?></a>
<?php }
    }
  } ?>