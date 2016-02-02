<nav>
  <span><a href='<?php echo base_url ('articles');?>'>知識文章</a> » <?php echo $article['title'];?></span>
</nav>

<div class='b1'>
  <article>
    <header>
      <h1><a href='<?php echo base_url ('article', $article['site_show_page_last_uri']);?>'><?php echo $article['title'];?></a></h1>
      <div class="fb-like" data-href="<?php echo base_url ('article', $article['id']);?>" data-send="false" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
    </header>        
    
    <section>
<?php echo $article['content'];?>
    </section>
    
<?php
    if ($tags) { ?>
      <ul class='t'>
  <?php foreach ($tags as $tag) { ?>
          <li><a data-id='<?php echo $tag['id'];?>' href='<?php echo base_url ('article-tag', $tag['id'], 'articles');?>' target='_blank'><?php echo $tag['name'];?></a></li>
  <?php } ?>
      </ul>
<?php 
    }
    if ($sources) { ?>
      <ul class='s'>
  <?php foreach ($sources as $source) { ?>
          <li>
        <?php if ($source['title']) { ?>
                <a href='<?php echo $source['href'];?>' target='_blank'><?php echo $source['title'];?></a><span><a href='<?php echo $source['href'];?>' target='_blank'><?php echo $source['mini_href'];?></a></span>
        <?php } else { ?>
                <a href='<?php echo $source['href'];?>' target='_blank'><?php echo $source['mini_href'];?></a>
        <?php } ?>
          </li>
  <?php } ?>
      </ul>
    <?php
    } ?>

    <footer>
      <div><span>張貼者：</span><a href='<?php echo $user['facebook_link'];?>' target='_blank'><?php echo $user['name'];?></a>於<time datetime='<?php echo $article['created_at']['Y-m-d H:i:s'];?>'><?php echo $article['created_at']['Y-m-d H:i:s'];?></time>發佈。</div>
      <div>瀏覽人數：<?php echo $article['pv'];?> 人</div>
    </footer>
  </article>
  
  <section>
<?php 
  echo render_cell ('site_article_asides_cell', 'tags');
  echo render_cell ('site_article_asides_cell', 'hots');
  echo render_cell ('site_article_asides_cell', 'news'); ?>
  </section>

</div>

