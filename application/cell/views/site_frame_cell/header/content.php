<header>
  <a href='<?php echo base_url ();?>'>
    <img src='<?php echo resource_url ('resource', 'image', 'logo', 'logo.png');?>' alt='<?php echo Cfg::setting ('site', 'site', 'title');?>'>
  </a>

  <nav>
    <a href='<?php echo base_url ();?>' class='a'>Home</a>
    <a href='<?php echo base_url ('abouts');?>'>關於宙思</a>
    <a href='<?php echo base_url ('works', '48');?>'>設計作品</a>
    <a href='<?php echo base_url ('contacts');?>'>聯絡我們</a>
  </nav>

</header>