<!DOCTYPE html>
<html lang="zh">
  <head>
    <?php echo isset ($meta_list) ? $meta_list : ''; ?>
    <link rel="chitorch icon" href="<?php echo resource_url ('resource', 'image', 'logo', 'favicon.ico');?>">

    <title><?php echo isset ($title) ? $title : ''; ?></title>

    <link rel="alternate" href="<?php echo current_url ();?>" hreflang="zh-Hant" />
    <meta name="msvalidate.01" content="337867F91709D9322F7258F220946159" />

<?php echo isset ($css_list) ? $css_list : ''; ?>

<?php echo isset ($js_list) ? $js_list : ''; ?>

  </head>
  <body lang="zh-tw">
    <?php echo isset ($hidden_list) ? $hidden_list : ''; ?>
    
    <div id='container'>
      <?php echo isset ($content) ? $content : ''; ?>
    </div>
  </body>
</html>