<form action='<?php echo base_url (array ('admin', 'banners', $banner->id));?>' method='post' enctype='multipart/form-data'>
  <input type='hidden' name='_method' value='put' />
  <table class='table-form'>
    <tbody>

      <tr>
        <th>標 題：</th>
        <td>
          <input type='text' name='title' value='<?php echo isset ($posts['title']) ? $posts['title'] : $banner->title;?>' placeholder='請輸入標題..' maxlength='200' pattern='.{1,200}' required title='輸入標題!' autofocus />
        </td>
      </tr>

      <tr>
        <th>內 容：</th>
        <td>
          <textarea name='content' class='pure autosize' placeholder='請輸入內容..'><?php echo isset ($posts['content']) ? $posts['content'] : $banner->content;?></textarea>
        </td>
      </tr>

      <tr>
        <th>封 面：</th>
        <td>
          <?php echo img ($banner->cover->url ('100x100c'), false, 'class="cover"');?>
          <input type='file' name='cover' value='' />
        </td>
      </tr>

      <tr>
        <th>鏈 結：</th>
        <td>
          <input type='text' name='link' value='<?php echo isset ($posts['link']) ? $posts['link'] : $banner->link;?>' placeholder='請輸入鏈結..' maxlength='250' pattern='.{1,250}' required title='輸入鏈結!' />
        </td>
      </tr>

      <tr>
        <th>開啟方式：</th>
        <td>
          <select name='target'>
      <?php foreach (Banner::$targetNames as $key => $name) { ?>
              <option value='<?php echo $key;?>'<?php echo (isset ($posts['target']) ? $posts['target'] : $banner->target) == $key ? ' selected': '';?>><?php echo $name;?></option>
      <?php } ?>
          </select>
        </td>
      </tr>

      <tr>
        <th>狀 態：</th>
        <td>
          <select name='is_enabled'>
      <?php foreach (Banner::$enableNames as $key => $name) { ?>
              <option value='<?php echo $key;?>'<?php echo (isset ($posts['is_enabled']) ? $posts['is_enabled'] : $banner->is_enabled) == $key ? ' selected': '';?>><?php echo $name;?></option>
      <?php } ?>
          </select>
        </td>
      </tr>

      <tr>
        <td colspan='2'>
          <a href='<?php echo base_url ('admin', 'banners');?>'>回列表</a>
          <button type='reset' class='button'>重填</button>
          <button type='submit' class='button'>確定</button>
        </td>
      </tr>
    </tbody>
  </table>
</form>
