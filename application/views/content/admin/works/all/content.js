/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  $('.ps > div').imgLiquid ({verticalAlign: 'center'})
                .fancybox ({
                    padding: 0,
                    helpers: {
                      overlay: { locked: false },
                      title: { type: 'over' },
                      thumbs: { width: 50, height: 50 }
                    }
                 });
});