<?php
  if (!$tags) return;
?>
<aside class='f'>
  <h2>標籤分類</h2>
  <ul>
<?php 
    foreach ($tags as $tag) { ?>
      <li><a href='<?php echo base_url ('article-tags', $tag->id, 'articles');?>'><?php echo $tag->name;?></a></li>
<?php 
    } ?>
  </ul>
</aside>