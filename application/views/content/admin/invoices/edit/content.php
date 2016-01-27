<form action='<?php echo base_url (array ('admin', 'invoices', $invoice->id));?>' method='post' enctype='multipart/form-data'>
  <input type='hidden' name='_method' value='put' />
  <table class='table-form'>
    <tbody>

      <tr>
        <th>類 別：</th>
        <td>
            <select name='invoice_tag_id'>
              <option value='0'<?php echo (isset ($posts['invoice_tag_id']) ? $posts['invoice_tag_id'] : $invoice->invoice_tag_id) == 0 ? ' selected': '';?>>其他</option>
        <?php if ($tags = InvoiceTag::all ()) {
                foreach ($tags as $tag) { ?>
                  <option value='<?php echo $tag->id;?>'<?php echo (isset ($posts['invoice_tag_id']) ? $posts['invoice_tag_id'] : $invoice->invoice_tag_id) == $tag->id ? ' selected': '';?>><?php echo $tag->name;?></option>
          <?php }
              }?>
            </select>
        </td>
      </tr>

      <tr>
        <th>負責人：</th>
        <td>
          <select name='user_id'>
      <?php if ($users = User::all (array ('select' => 'id, name'))) {
              foreach ($users as $user) { ?>
                <option value='<?php echo $user->id;?>'<?php echo (isset ($posts['user_id']) ? $posts['user_id'] : $invoice->user_id) == $user->id ? ' selected': '';?>><?php echo $user->name;?></option>
        <?php }
            }?>
          </select>
        </td>
      </tr>
      
      <tr>
        <th>名 稱：</th>
        <td>
          <input type='text' name='name' value='<?php echo isset ($posts['name']) ? $posts['name'] : $invoice->name;?>' placeholder='請輸入標題..' maxlength='200' pattern='.{1,200}' required title='輸入標題!' autofocus />
        </td>
      </tr>

      <tr>
        <th>窗 口：</th>
        <td>
          <input type='text' name='contact' value='<?php echo isset ($posts['contact']) ? $posts['contact'] : $invoice->contact;?>' placeholder='請輸入窗口..' maxlength='200' pattern='.{1,200}' required title='輸入窗口!' />
        </td>
      </tr>

      <tr>
        <th>金 額：</th>
        <td>
          <input type='number' name='money' value='<?php echo isset ($posts['money']) ? $posts['money'] : $invoice->money;?>' placeholder='請輸入金額..'/>
        </td>
      </tr>

      <tr>
        <th>封 面：</th>
        <td>
          <?php echo img ($invoice->cover->url ('100x100c'), false, 'class="cover"');?>
          <input type='file' name='cover' value='' />
        </td>
      </tr>

      <tr>
        <th>圖 片：</th>
        <td>
    <?php if ($invoice->pictures) { ?>
            <div class='pics'>
        <?php foreach ($invoice->pictures as $i => $picture) {  ?>
                <div class='pic imgLiquid_center'>
                  <img src='<?php echo $picture->name->url ('100x100c');?>'>
                  <input type='hidden' name='pic_ids[]' value='<?php echo $picture->id;?>' />
                  <div class='icon-bin del'></div>
                </div>
        <?php }?>
            </div>
    <?php } ?>
          <div class='ps'>
            <button type='button' class='icon-plus' data-i='0'></button>
          </div>
        </td>
      </tr>

      <tr>
        <th>結案日期：</th>
        <td>
          <input type='text' name='closing_at' value='<?php echo isset ($posts['closing_at']) ? $posts['closing_at'] : ($invoice->closing_at ? $invoice->closing_at->format ('Y-m-d') : date ('Y-m-d'));?>' placeholder='請選擇結案日期..' maxlength='200' pattern='.{1,200}' required title='輸入窗口!'/>
        </td>
      </tr>

      <tr>
        <th>是否完成：</th>
        <td>
          <div class='checkbox'>
            <input type='checkbox' id='is_finished' name='is_finished'<?php echo (isset ($posts['is_finished']) && $posts['is_finished']) || ($invoice->is_finished == Invoice::IS_FINISHED) ? ' checked' : '';?> data-is_finished_name='<?php echo Invoice::$finishName[Invoice::IS_FINISHED];?>' data-no_finished_name='<?php echo Invoice::$finishName[Invoice::NO_FINISHED];?>'><span></span>
            <label for='is_finished'><?php echo Invoice::$finishName[(isset ($posts['is_finished']) && $posts['is_finished']) || ($invoice->is_finished == Invoice::IS_FINISHED) ? Invoice::IS_FINISHED : Invoice::NO_FINISHED];?></label>
          </div>
        </td>
      </tr>

      <tr>
        <th>備 註：</th>
        <td>
          <textarea name='memo' class='pure autosize' placeholder='請輸入備註..'><?php echo isset ($posts['memo']) ? $posts['memo'] : $invoice->memo;?></textarea>
        </td>
      </tr>

      <tr>
        <td colspan='2'>
          <a href='<?php echo base_url ('admin', 'invoices');?>'>回列表</a>
          <button type='reset' class='button'>重填</button>
          <button type='submit' class='button'>確定</button>
        </td>
      </tr>
    </tbody>
  </table>
</form>
