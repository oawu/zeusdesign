<!DOCTYPE html>
<html lang="zh">
  <head>
    <?php echo isset ($meta_list) ? $meta_list : ''; ?>

    <title><?php echo isset ($title) ? $title : ''; ?></title>

<?php echo isset ($css_list) ? $css_list : ''; ?>

<?php echo isset ($js_list) ? $js_list : ''; ?>

  </head>
  <body lang="zh-tw">
    <?php echo isset ($hidden_list) ? $hidden_list : ''; ?>
    
    <div id='container'>
      <?php echo render_cell ('site_frame_cell', 'header', isset ($_method) ? $_method : '');?>
      <?php echo isset ($content) ? $content : ''; ?>
      <?php echo render_cell ('site_frame_cell', 'footer');?>
    </div>
  </body>
</html>