<nav>
  <?php
  if (isset ($tag)) {?>
    <span><a href='<?php echo base_url ('works');?>'>設計作品</a> » <?php echo $tag->name;?></span>
  <?php
  } else { ?>
    <h1>設計作品</h1>
  <?php
  } ?>
</nav>


<div class='b1'>
  <article>
<?php
    if (isset ($tag)) { ?>
      <h1><a data-id='<?php echo $tag->id;?>' href='<?php echo base_url ('work-tag', $tag->id, 'works');?>'><?php echo $tag->name;?></a></h1>
<?php
    } ?>
    <div>
<?php foreach ($works as $work) { ?>
        <figure>
          <a href='<?php echo base_url ('work', $work->site_show_page_last_uri ());?>'>
            <img src='<?php echo $work->cover->url ('300w');?>' alt='<?php echo $work->title;?>'>
          </a>

          <figcaption>
            <a href='<?php echo base_url ('work', $work->site_show_page_last_uri ());?>'><?php echo $work->title;?></a>
            <p><?php echo $work->content;?></p>
          </figcaption>
        </figure>
<?php } 
      echo render_cell ('site_frame_cell', 'pagination', $pagination); ?>
    </div>
  </article>
  
  <aside>
<?php echo render_cell ('site_work_asides_cell', 'tags');?>
  </aside>
</div>