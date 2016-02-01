<nav>
  <span><a href='<?php echo base_url ('works');?>'>設計作品</a> » <?php echo $work->title;?></span>
</nav>

<div class='b1'>

  <article> 
    <div>
<?php foreach (WorkBlock::find ('all', array ('include' => array ('items'), 'conditions' => array ('work_id = ?', $work->id))) as $block) { ?>
        <section>
          <h2><?php echo $block->title;?></h2>
    <?php foreach ($block->items as $item) { ?>
            <p>
        <?php if ($item->link) { ?>
                <a href='<?php echo $item->link;?>' target='_blank'><?php echo $item->title;?></a>
                <i><?php echo $item->link;?></i>  
        <?php } else { ?>
                <?php echo $item->title;?>
        <?php }?>
            </p>
    <?php } ?>
        </section>
<?php } ?>
    </div>
  </article>

  <article>
    <header>
      <h1><a href='<?php echo base_url ('work', $work->site_show_page_last_uri ());?>'><?php echo $work->title;?></a></h1>
      <div class="fb-like" data-href="<?php echo base_url ('work', $work->id);?>" data-send="false" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>
    </header> 

    <section><p><?php echo $work->content;?></p></section>

    <?php 
    foreach ($work->pictures as $picture) { ?>
      <figure>
        <a>
          <img src='<?php echo $picture->name->url ('800w');?>' alt='<?php echo $work->title;?>'>
        </a>
        <figcaption>
        </figcaption>
      </figure>
    <?php 
    } ?>
  </article>
</div>