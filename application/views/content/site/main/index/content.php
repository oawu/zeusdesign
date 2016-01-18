<div class='_t'>
  <span>Home</span>
  <a href=''>更多關於宙思 »</a>
</div>

<article class='b1'>
  <div class='l'>
    <div>web design</div>
    <div>graphic design</div>
    <div>photography</div>
    <div>design project</div>
    <p>宙思設計團隊，服務廣泛，凡舉網頁、平面、包裝、印刷及攝影皆可製作。</p>
    <p>我們擁有各領域的人才，希望能將您的需求，以最完整的服務與最精湛的設計呈現給您。</p>
  </div>
  <div class='r'>
    <div id='banners'>
<?php foreach (Banner::all (array ('order' => 'sort DESC', 'conditions' => array ('is_enabled = ?', Banner::ENABLE_YES))) as $banner) { ?>
        <figure>
          <img src='<?php echo $banner->cover->url ('800w');?>' alt='<?php echo $banner->title;?>' />
          <figcaption>
            <h3><?php echo $banner->title;?></h3>
            <p><?php echo $banner->content;?></p>
            <p><a href='<?php echo $banner->link;?>'<?php echo $banner->target == Banner::TARGET_BLANK ? ' target="_blank"' : '';?>>more</a></p>
            <a>←</a><a>→</a>
          </figcaption>
        </figure>
<?php } ?>
      
    </div>
  </div>
</article>

<div class='_t'>
  <span>服務項目</span>
</div>

<article class='b2'>
  <section>
    <h2>網頁設計</h2>
    <h3>web design</h3>
    <p>網站規劃及官網製作經驗豐富。</p>
    <p>網站製作技術包含：前台畫面設計、後台程式架設、RWD製作(響應式網站：符合智慧型平台)、APP UI介面製作、FB活動...等等。</p>
    <p>網站周邊製作：banner形象製作、EDM...等等。</p>
  </section>

  <section>
    <h2>平面設計</h2>
    <h3>graphic design</h3>
    <p>具有多年平面設計經驗，特別擅常整體形象設計，凡舉可以印刷之相關設計物品，皆可承接製作。</p>
    <p>宙思亦有印刷服務，多年與固定印刷廠配合，能將設計作品以最好的方式，印出成品。</p>
  </section>

  <section>
    <h2>商業攝影</h2>
    <h3>photography</h3>
    <p>宙思擁有專業攝影棚，運用攝影經驗及後製電修技巧，將產品完美呈現。</p>
    <p>服務範圍：商品攝影、產品情境照拍攝、人像攝影、活動攝影及婚禮攝影。</p>
  </section>
  
  <section>
    <h2>設計專案</h2>
    <h3>design project</h3>
    <p>結合網頁、平面及商業攝影之設計專案。</p>
    <p>由一項設計項目開始，規劃整體視覺，進而延伸網頁及整體形象，搭配商業攝影，讓品牌形象更為一致。</p>
  </section>
</article>


<div class='_t'>
  <span>設計作品</span>
  <a href=''>設計作品欣賞更多作品 »</a>
</div>

<article class='b3'>
  <?php 
  foreach (Promo::all (array ('order' => 'sort DESC', 'limit' => 4)) as $promo) { ?>
    <figure>
      <a href='<?php echo $promo->link;?>'<?php echo $promo->target == Promo::TARGET_BLANK ? ' target="_blank"' : '';?>>
        <img src='<?php echo $promo->cover->url ('500w');?>' alt='<?php echo $promo->title;?>'>
      </a>

      <figcaption>
        <a href=''><?php echo $promo->title;?></a>
        <p><?php echo $promo->content;?></p>
      </figcaption>
    </figure>
    
  <?php 
  } ?>
</article>