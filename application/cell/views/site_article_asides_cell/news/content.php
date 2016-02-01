<?php
  if (!$articles) return;
?>
<aside>
  <h3>最新文章</h3>
  <ul>
<?php 
    foreach ($articles as $article) { ?>
      <li><a href='<?php echo base_url ('article', $article->site_show_page_last_uri ());?>'><?php echo $article->title;?></a></li>
<?php 
    } ?>
  </ul>
</aside>