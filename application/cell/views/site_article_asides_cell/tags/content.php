<?php
  if (!$tags) return; ?>

  <aside class='f'>
    <h3>標籤分類</h3>
    <ul>
<?php foreach ($tags as $tag) { ?>
        <li><a data-id='<?php echo $tag->id;?>' href='<?php echo base_url ('article-tag', $tag->id, 'articles');?>'><?php echo $tag->name;?></a></li>
<?php } ?>
    </ul>
  </aside>