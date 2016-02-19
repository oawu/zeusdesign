<form action='<?php echo base_url (array ('admin', 'works'));?>' method='post' enctype='multipart/form-data'>
  <table class='table-form'>
    <tbody>

      <tr>
        <th>作者：</th>
        <td>
          <select name='user_id'>
      <?php if ($users = User::all (array ('select' => 'id, name'))) {
              foreach ($users as $user) { ?>
                <option value='<?php echo $user->id;?>'<?php echo (isset ($posts['user_id']) ? $posts['user_id'] : User::current ()->id) == $user->id ? ' selected': '';?>><?php echo $user->name;?></option>
        <?php }
            }?>
          </select>
        </td>
      </tr>

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
        <th class='tst'>分 類：</th>
        <td class='ts'>
    <?php if ($tags = WorkTag::find ('all', array ('include' => array ('tags'), 'conditions' => array ('work_tag_id = ?', 0)))) {
            foreach ($tags as $i => $tag) {?>
              <div class='t'>
                <label class='main'><input type='checkbox' name='tag_ids[]' value='<?php echo $tag->id;?>'<?php echo isset ($posts['tag_ids']) && $posts['tag_ids'] && in_array ($tag->id, $posts['tag_ids']) ? ' checked' : '';?> /> <?php echo $tag->name;?></label>
          <?php if ($tag->tags) {
                  foreach ($tag->tags as $sub_tag) { ?>
                    <label class='sub'><input type='checkbox' class='l' name='tag_ids[]' value='<?php echo $sub_tag->id;?>'<?php echo isset ($posts['tag_ids']) && $posts['tag_ids'] && in_array ($sub_tag->id, $posts['tag_ids']) ? ' checked' : '';?> /> <?php echo $sub_tag->name;?></label>
            <?php }
                } ?>
              </div>
      <?php }
          } else { ?>
            目前尚未新增任何分類。
    <?php } ?>
        </td>
      </tr>

      <tr>
        <th>狀 態：</th>
        <td>
          <select name='is_enabled'>
      <?php foreach (Work::$enableNames as $key => $name) { ?>
              <option value='<?php echo $key;?>'<?php echo (isset ($posts['is_enabled']) ? $posts['is_enabled'] : 1) == $key ? ' selected': '';?>><?php echo $name;?></option>
      <?php } ?>
          </select>
        </td>
      </tr>

      <tr>
        <th></th>
        <td>
          <button type='button' data-i='0' class='icon-plus add_block' data-blocks='<?php echo json_encode ($blocks);?>'>新增說明</button>
        </td>
      </tr>

      <tr>
        <td colspan='2'>
          <a href='<?php echo base_url ('admin', 'works');?>'>回列表</a>
          <button type='reset' class='button'>重填</button>
          <button type='submit' class='button'>確定</button>
        </td>
      </tr>
    </tbody>
  </table>
</form>
