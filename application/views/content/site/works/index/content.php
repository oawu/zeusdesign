<div class='_t'>
  <span>設計作品</span>
</div>

<div class='b1'>
  <article>
    <?php
    foreach ($works as $work) { ?>
      <figure>
        <a href='<?php echo base_url ('work', $work->site_show_page_last_uri ());?>'>
          <img src='<?php echo $work->cover->url ('300w');?>' alt='<?php echo $work->title;?>'>
        </a>

        <figcaption>
          <a href='<?php echo base_url ('work', $work->site_show_page_last_uri ());?>'><?php echo $work->title;?></a>
          <p><?php echo $work->content;?></p>
        </figcaption>
      </figure>
    <?php
    } ?>
  </article>
  
  <aside>
    <?php 
    if ($tags = WorkTag::all (array ('order' => 'sort ASC', 'conditions' => array ('work_tag_id = ?', 0)))) {
      foreach ($tags as $tag) { ?>
        <a href='<?php echo base_url ('works', $tag->site_show_page_last_uri ());?>' class='m<?php echo $tag->id == $id ? ' a': '';?>'><?php echo $tag->name;?></a>
  <?php foreach ($tag->tags as $tag) { ?>
          <a href='<?php echo base_url ('works', $tag->site_show_page_last_uri ());?>'<?php echo $tag->id == $id ? ' class="a"': '';?>><?php echo $tag->name;?></a>
  <?php }
      }
    } ?>
  </aside>
</div>