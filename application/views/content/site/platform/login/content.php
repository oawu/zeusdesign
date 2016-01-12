<div class='login'>
  <div class='logo'>
    <img src='<?php echo base_url ('resource', 'image', 'logo', 'L1024.png');?>' />
  </div>
<?php 
  if (User::current () && !in_array (User::current ()->role, Cfg::setting ('role', 'admins'))) { ?>
    <div class='m'>您已經登入成功，<br/>請管理員為您確認權限！</div>
<?php 
  } else if ($_flash_message = Session::getData ('_flash_message', true)) { ?>
    <div class='m'><?php echo $_flash_message;?></div>
<?php 
  }?>
  <a id='facebook' href='<?php echo Fb::loginUrl ('platform', 'fb_sign_in', 'admin');?>'>facebook 登入</a>
</div>
