/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  var $tds = $('td.s');
  var $ma = $tds.find ('.ma');

  var $fm = function (i, t, h) {
    return $('<div />').addClass ('m').append (
      $('<div />').append ($('<a />').addClass ('icon-triangle-up2').click (function () {
        var $p = $(this).parents ('.m');
        $p.clone (true).insertBefore ($p.index () > 0 ? $p.prev () : $ma);
        $p.remove ();
      })).append ($('<a />').addClass ('icon-triangle-down2').click (function () {
        var $p = $(this).parents ('.m'), $x = $p.next (), $n = $p.clone (true);
        if ($x.hasClass ('ma')) $n.prependTo ($td);
        else $n.insertAfter ($x);
        $p.remove ();
      }))
    ).append (
      $('<input />').attr ('type', 'text').attr ('name', 'sources[' + i + '][title]').attr ('placeholder', '請輸入參考來源名稱..').attr ('maxlength', 200).val (t ? t : '')
    ).append (
      $('<input />').attr ('type', 'text').attr ('name', 'sources[' + i + '][href]').attr ('placeholder', '請輸入參考來源網址..').val (h ? h : '')
    ).append (
      $('<button />').attr ('type', 'button').addClass ('icon-bin').click (function () {
        $(this).parents ('.m').remove ();
      })
    );
  };

  if ($tds.data ('ms'))
    $tds.data ('ms').forEach (function (t) {
      $fm ($tds.data ('i'), t.title, t.href).insertBefore ($ma);
      $tds.data ('i', $tds.data ('i') + 1);
    });

  $ma.find ('.icon-plus').click (function () {
    $fm ($tds.data ('i')).insertBefore ($ma);
    $tds.data ('i', $tds.data ('i') + 1);
  }).click ();

  $('textarea.cke').ckeditor ({
    filebrowserUploadUrl: $('#tools_ckeditors_upload_image_url').val (),
    height: 400,
  });
});