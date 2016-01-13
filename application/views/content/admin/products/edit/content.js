/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  autosize ($('.autosize'));
  var $ps = $('.ps');
  new Masonry ($('.ts').selector, { itemSelector: '.t', columnWidth: 1, transitionDuration: '0.3s', visibleStyle: { opacity: 1, transform: 'none' }});

  $('.pics .del').click (function () {
    $(this).parents ('.pic').fadeOut (function () {
      $(this).remove ();
    });
  });
  
  var $psfm = function (i) {
    return $('<div />', {'class': 'p'}).append (
      $('<input />', {'type': 'file', 'name': 'pictures[' + i + ']'})
    ).append (
      $('<button />', {'type': 'button', 'class': 'icon-bin'}).click (function () { $(this).parents ('.p').remove (); })
    );
  };

  $ps.find ('.icon-plus').click (function () {
    var i = $(this).data ('i');
    $psfm (i).insertBefore ($ps);
    $(this).data ('i', i + 1);
  }).click ();

  var $bifm = function (i, j, t, l) {
    return $('<div />', {'class': 'bi'}).append (
        $('<input />', {'type': 'text', 'name': 'blocks[' + i + '][items][' + j + '][title]', 'placeholder': '請輸入細項標題..'}).val (t)
      ).append (
        $('<input />', {'type': 'text', 'name': 'blocks[' + i + '][items][' + j + '][link]', 'placeholder': '請輸入細項鏈結..'}).val (l)
      ).append (
        $('<button />', {'type': 'button', 'class': 'icon-bin'}).click (function () { $(this).parents ('.bi').remove (); })
      );
  };
  var $bsfm = function (i, t, is) {
    return $('<tr />', { 'class': 'bs'}).append ($('<th />').text ('說 明：')).append ($('<td />').append (
        $('<div />', {'class': 'bt'}).append (
            $('<input />', {'type': 'text', 'name': 'blocks[' + i + '][title]', 'placeholder': '請輸入說明標題..', 'maxlength': '200', 'pattern': '.{1,200}', 'required': '', 'title': '輸入說明標題!'}).val (t)
          ).append (
            $('<button />', {'type': 'button', 'class': 'icon-bin'}).click (function () {
              $(this).parents ('tr').remove ();
            })
          )
      ).append (
        $('<div />', {'class': 'bb'}).append (
            $('<button />', {'type': 'button', 'class': 'icon-plus'}).data ('i', 0).click (function () {
              var j = $(this).data ('i');
              $bifm (i, j, '', '').insertBefore ($(this).parents ('.bb'));
              $(this).data ('i', j + 1);
            })
          )
      ));
  };
  var $addBlock = $('.add_block').click (function () {
    var text = window.prompt ('請輸入說明文字');
    if (text && text.length) {
      var i = $(this).data ('i');
      $bsfm (i, text).insertBefore ($(this).parents ('tr'));
      $(this).data ('i', i + 1);
    }
  });

  $.map ($addBlock.data ('blocks'), function (t) {
    var i = $addBlock.data ('i');
    var $b = $bsfm (i, t.title).insertBefore ($addBlock.parents ('tr')).find ('button.icon-plus');

    if (t.items) {
      $.map (t.items, function (t) {
        var j = $b.data ('i');
        $bifm (i, j, t.title, t.link).insertBefore ($b.parents ('.bb'));
        $b.data ('i', j + 1);
      });
    }
    $addBlock.data ('i', $addBlock.data ('i') + 1);
  });
});