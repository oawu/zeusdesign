<header class='_h'>
  <a href='<?php echo base_url ();?>'>
    <img src='<?php echo resource_url ('resource', 'image', 'logo', 'logo.png');?>' alt='<?php echo Cfg::setting ('site', 'site', 'title');?>'>
  </a>

  <nav>
    <a href='<?php echo base_url ();?>'<?php echo $key == 'index' ? 'class="a"': '';?>>Home</a>
    <a href='<?php echo base_url ('abouts');?>'<?php echo $key == 'abouts' ? 'class="a"': '';?>>關於宙思</a>
    <a href='<?php echo base_url ('works');?>'<?php echo $key == 'works' ? 'class="a"': '';?>>設計作品</a>
    <a href='<?php echo base_url ('contacts');?>'<?php echo $key == 'contacts' ? 'class="a"': '';?>>聯絡我們</a>
  </nav>
</header>