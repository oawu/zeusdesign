<form action='<?php echo base_url (array ('admin', 'product_tags', $tag->id, 'products'));?>' method='post' enctype='multipart/form-data'>
  <table class='table-form'>
    <tbody>

      <tr>
        <th>標 題：</th>
        <td>
          <input type='text' name='title' value='<?php echo isset ($posts['title']) ? $posts['title'] : '';?>' placeholder='請輸入標題..' maxlength='200' pattern='.{1,200}' required title='輸入標題!' autofocus />
        </td>
      </tr>

      <tr>
        <th>內 容：</th>
        <td>
          <textarea name='content' class='pure autosize' placeholder='請輸入內容..'><?php echo isset ($posts['content']) ? $posts['content'] : '';?></textarea>
        </td>
      </tr>

      <tr>
        <th>封 面：</th>
        <td>
          <input type='file' name='cover' value='' />
        </td>
      </tr>

      <tr>
        <th>圖 片：</th>
        <td>
          <div class='ps'>
            <button type='button' class='icon-plus' data-i='0'></button>
          </div>
        </td>
      </tr>

      <tr>
        <th>分 類：</th>
        <td><?php echo $tag->name;?></td>
      </tr>

      <tr>
        <th>狀 態：</th>
        <td>
          <select name='is_enabled'>
      <?php foreach (Product::$enableName as $key => $name) { ?>
              <option value='<?php echo $key;?>'<?php echo (isset ($posts['is_enabled']) ? $posts['is_enabled'] : 1) == $key ? ' selected': '';?>><?php echo $name;?></option>
      <?php } ?>
          </select>
        </td>
      </tr>

      <tr>
        <th></th>
        <td>
          <button type='button' data-i='0' class='icon-plus add_block' data-blocks='<?php echo json_encode (isset ($posts['blocks']) ? $posts['blocks'] : array ());?>'>新增說明</button>
        </td>
      </tr>

      <tr>
        <td colspan='2'>
          <a href='<?php echo base_url ('admin', 'products');?>'>回列表</a>
          <button type='reset' class='button'>重填</button>
          <button type='submit' class='button'>確定</button>
        </td>
      </tr>
    </tbody>
  </table>
</form>
