<?php 
  foreach ($pokmons as $pokmon) { ?>
    <figure>
      <a href='<?php echo $pokmon->pic->url ();?>' class='i_c' title='<?php echo $pokmon->name;?>' data-fancybox-group='group_img'>
        <img width='250' src='<?php echo $pokmon->pic->url ('500h');?>' alt='<?php echo $pokmon->name;?>' />
      </a>

      <figcaption><?php echo $pokmon->uid;?> - <?php echo $pokmon->name;?></figcaption>
    </figure>
<?php 
  }
