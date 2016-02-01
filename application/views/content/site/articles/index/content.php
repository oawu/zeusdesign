<nav>
  <?php
  if (isset ($tag)) {?>
    <span><a href='<?php echo base_url ('articles');?>'>知識文章</a> » <?php echo $tag->name;?></span>
  <?php
  } else { ?>
    <h1>知識文章</h1>
  <?php
  } ?>
</nav>

<div class='b1'>
  <section>
<?php
    if (isset ($tag)) { ?>
      <h1><a href='<?php echo base_url ('article-tags', $tag->id, 'articles');?>'><?php echo $tag->name;?></a></h1>
<?php
    }
    if ($articles) {
      foreach ($articles as $article) {?>
        <article>
          <header><h2><a href='<?php echo base_url ('article', $article->site_show_page_last_uri ());?>'><?php echo $article->title;?></a></h2></header>
          <div><figure><a href='<?php echo base_url ('article', $article->site_show_page_last_uri ());?>'><img src='<?php echo $article->cover->url ('450x180c');?>' alt='<?php echo $article->title;?>'></a><figcaption><a href='<?php echo base_url ('article', $article->site_show_page_last_uri ());?>'><?php echo $article->title;?></a><p><?php echo $article->mini_content ();?></p></figcaption></figure><div><p><?php echo $article->mini_content ();?></p><a href='<?php echo base_url ('article', $article->site_show_page_last_uri ());?>'>閱讀更多</a><time datetime='<?php echo $article->created_at ? $article->created_at->format ('Y-m-d H:i:s') : date ('Y-m-d H:i:s');?>' data-time='<?php echo $article->created_at ? $article->created_at->format ('Y-m-d H:i:s') : date ('Y-m-d H:i:s');?>'><?php echo $article->created_at ? $article->created_at->format ('Y-m-d H:i:s') : date ('Y-m-d H:i:s');?></time></div></div>
        </article>        
<?php }
    } else { ?>
      <div>目前尚未有相關文章。</div>
<?php
    } 
    echo render_cell ('site_frame_cell', 'pagination', $pagination);?>
  </section>
  
  <section>

<?php 
  echo render_cell ('site_article_asides_cell', 'tags');
  echo render_cell ('site_article_asides_cell', 'hots');
  echo render_cell ('site_article_asides_cell', 'news');
  echo render_cell ('site_frame_cell', 'pagination', $pagination);?>
  </section>

</div>

