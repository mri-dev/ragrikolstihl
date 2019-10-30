<?php
  $ar = $this->product['ar'];
?>
<div class="product-view page-width">
  <div class="product-data">
    <div class="nav">
      <div class="pagi">
        <?php
          $navh = '/termekek/';
          $lastcat = end($this->product['nav']);
        ?>
        <ul class="cat-nav">
          <li><a href="/"><i class="fa fa-home"></i></a></li>
          <li><a href="<?=$navh?>">Webshop</a></li>
          <?php
          foreach ( $this->product['nav'] as $nav ): $navh = \Helper::makeSafeUrl($nav['neve'],'_-'.$nav['ID']); ?>
          <li><a href="/termekek/<?=$navh?>"><?php echo $nav['neve']; ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
    <div class="top-datas">
      <div class="images">
        <?php if (true): ?>
        <div class="main-img img-auto-cuberatio">
          <? if( $ar >= $this->settings['cetelem_min_product_price'] && $ar <= $this->settings['cetelem_max_product_price'] && $this->product['no_cetelem'] != 1 ): ?>
              <img class="cetelem" src="<?=IMG?>cetelem_badge.png" alt="Cetelem Online Hitel">
          <? endif; ?>
          <?  if( $this->product['akcios'] == '1' && $this->product['akcios_fogy_ar'] > 0): ?>
          <div class="discount-percent"><div class="p">-<? echo 100-round($this->product['akcios_fogy_ar'] / ($this->product['brutto_ar'] / 100)); ?>%</div></div>
          <? endif; ?>
          <div class="img-thb">
              <a href="<?=$this->product['profil_kep']?>" class="zoom"><img di="<?=$this->product['profil_kep']?>" src="<?=$this->product['profil_kep']?>" alt="<?=$this->product['nev']?>"></a>
          </div>
        </div>
        <div class="all">
          <?  foreach ( $this->product['images'] as $img ) { ?>
          <div class="imgslide img-auto-cuberatio__">
            <div class="wrp">
              <img class="aw" i="<?=\PortalManager\Formater::productImage($img)?>" src="<?=\PortalManager\Formater::productImage($img, 150)?>" alt="<?=$this->product['nev']?>">
            </div>
          </div>
          <? } ?>
        </div>
        <?php endif; ?>
      </div>
      <div class="main-data">
        <h1><?=$this->product['nev']?></h1>
        <div class="csoport">
          <?=$this->product['csoport_kategoria']?>
        </div>
        <div class="cimkek">
        <? if($this->product['ujdonsag'] == '1'): ?>
            <img src="<?=IMG?>new_icon_sq.svg" title="Újdonság!" alt="Újdonság">
        <? endif; ?>
        </div>
        <div class="prices">
              <div class="base">
                <?php if ($this->product['without_price']): ?>
                  <div class="current">
                    ÉRDEKLŐDJÖN!
                  </div>
                <?php else: ?>
                  <?php
                    $price_title_prefix = 'Kiskeredkedelmi';
                    $show_kisker_prices = false;
                    if ($this->user) {
                      switch ($this->user['data']['price_group_title']) {
                        case 'Viszonteladó':
                          $price_title_prefix = 'Viszonteladói';
                          $show_kisker_prices = true;
                        break;
                        case 'Nagyker vásárló':
                          $price_title_prefix = 'Nagykereskedői';
                          $show_kisker_prices = true;
                        break;
                      }
                    }
                  ?>
                  <?php if ($this->user && $this->user[data][user_group] == 'company'): ?>
                  <div class="netto">
                    <div class="pricehead"><?=$price_title_prefix?> <strong>nettó ár</strong>:</div>
                    <span class="price"><?=\PortalManager\Formater::cashFormat($ar/1.27)?> <?=$this->valuta?><? if($this->product['mertekegyseg'] != ''): ?><span class="unit-text">/<?=($this->product['mertekegyseg_ertek']!=1)?$this->product['mertekegyseg_ertek']:''?><?=$this->product['mertekegyseg']?></span><? endif; ?></span>
                  </div>
                  <?php endif; ?>
                  <div class="brutto">
                    <div class="pricehead"><?=$price_title_prefix?> <strong>bruttó ár</strong>:</div>
                    <span class="price current <?=( $this->product['akcios'] == '1' && $this->product['akcio']['mertek'] > 0)?'discounted':''?>"><?=\PortalManager\Formater::cashFormat($ar)?> <?=$this->valuta?><? if($this->product['mertekegyseg'] != ''): ?><span class="unit-text">/<?=$this->product['mertekegyseg']?></span><? endif; ?></span>
                    <?  if( $this->product['akcios'] == '1' && $this->product['akcio']['mertek'] > 0):
                        $ar = $this->product['eredeti_ar'];
                    ?>
                    <div class="price old"><strike><?=\PortalManager\Formater::cashFormat($ar)?> <?=$this->valuta?><? if($this->product['mertekegyseg'] != ''): ?><span class="unit-text">/<?=$this->product['mertekegyseg']?></span><? endif; ?></strike></div>
                    <? endif; ?>
                  </div>
                  <?php if ($show_kisker_prices && $this->product[kisker_ar] && $this->product[kisker_ar][brutto] != '0'): ?>
                  <div class="kisker-addon-price">
                    <div class="pricehead">Kiskereskedelmi ár:</div>
                    <span class="price"><?php echo \PortalManager\Formater::cashFormat($this->product['kisker_ar']['brutto']); ?> <?=$this->valuta?> <span class="net">(<?php echo \PortalManager\Formater::cashFormat($this->product['kisker_ar']['netto']); ?> <?=$this->valuta?> + ÁFA)</span></span>
                  </div>
                  <?php endif; ?>
                <?php endif; ?>
              </div>
              <div class="cimkek">
              <? if($this->product['akcios'] == '1'): ?>
                  <img src="<?=IMG?>discount_icon.png" title="Akciós!" alt="Akciós">
              <? endif; ?>
              </div>
          </div>
        <div class="divider"></div>
        <? if($this->settings['stock_outselling'] == '0' && $this->product['raktar_keszlet'] <= 0): ?>
            <div class="out-of-stock">
              A termék jelenleg nem rendelhető.
            </div>
            <? endif; ?>

            <?php if ($this->product['show_stock'] == 1): ?>
            <div class="stock-info <?=($this->product['raktar_keszlet'] <=0)?'no-stock':''?>">
              <?php if ($this->product['raktar_keszlet'] > 0): ?>
                Készleten: <strong><?php echo $this->product['raktar_keszlet']; ?> <?php echo strtolower($this->product['mertekegyseg']); ?>.</strong>
              <?php else: ?>
                Készleten: <strong>Nincs készleten jelenleg.</strong>
              <?php endif; ?>
            </div>
            <?php endif; ?>
        <div class="status-params">
          <div class="avaibility">
            <div class="h">Elérhetőség:</div>
            <div class="v"><?=$this->product['keszlet_info']?></div>
          </div>
          <div class="transport">
            <div class="h">Várható szállítás:</div>
            <div class="v"><span><?=$this->product['szallitas_info']?></span></div>
          </div>
          <?php if ( $ar > $this->settings['FREE_TRANSPORT_ABOVEPRICE']): ?>
          <div class="free-transport">
            <div class="free-transport-ele">
              <i class="fa fa-car"></i> Ingyen szállítjuk
            </div>
          </div>
          <?php endif; ?>
        </div>
        <div class="divider"></div>
        <div class="short-desc">
          <?=$this->product['rovid_leiras']?>
        </div>

        <?
        if( count($this->product['hasonlo_termek_ids']['colors']) > 1 ):
            $colorset = $this->product['hasonlo_termek_ids']['colors'];
        ?>
        <div class="divider"></div>
        <div class="variation-header">
          Elérhető variációk:
        </div>
        <div class="variation-list">
        <? foreach ($colorset as $szin => $adat ) : ?>
          <div class="variation<?=($szin == $this->product['szin'] )?' actual':''?>"><a href="<?=$adat['link']?>"><?=$szin?></a></div>
        <? endforeach; ?>
        </div>
        <? endif; ?>
        <div class="divider"></div>
        <div class="cart-info">
          <div id="cart-msg"></div>
          <div class="group" >
            <?
            if( count($this->product['hasonlo_termek_ids']['colors'][$this->product['szin']]['size_set']) > 1 ):
                $colorset = $this->product['hasonlo_termek_ids']['colors'][$this->product['szin']]['size_set'];
                //unset($colorset[$this->product['szin']]);
            ?>
            <div class="size-selector cart-btn dropdown-list-container">
                <div class="dropdown-list-title"><span id=""><?=__('Kiszerelés')?>: <strong><?=$this->product['meret']?></strong></span> <? if( count( $this->product['hasonlo_termek_ids']['colors'][$this->product['szin']]['size_set'] ) > 0): ?> <i class="fa fa-angle-down"></i><? endif; ?></div>

                <div class="number-select dropdown-list-selecting overflowed">
                <? foreach ($colorset as $szin => $adat ) : ?>
                    <div link="<?=$adat['link']?>"><?=$adat['size']?></div>
                <? endforeach; ?>
                </div>
            </div>
            <? endif; ?>
            <div class="order <?=($this->product['without_price'])?'requestprice':''?>">
              <?php if ( !$this->product['without_price'] ): ?>
              <div class="men">
                <div class="wrapper">
                  <label for="add_cart_num">Mennyiség:</label>
                  <input type="number" name="" id="add_cart_num" cart-count="<?=$this->product['ID']?>" value="1" min="1">
                </div>
              </div>
              <?php endif; ?>
              <?php if ( !$this->product['without_price'] ): ?>
                <div class="buttonorder">
                  <button id="addtocart" cart-data="<?=$this->product['ID']?>" cart-remsg="cart-msg" title="Kosárba rakom" class="tocart cart-btn"> <img src="<?=IMG?>icons/cart.svg" alt="kosárba rakom"> <?=__('kosárba rakom')?></i></button>
                </div>
              <?php else: ?>
                <div class="requestbutton">
                  <md-tooltip md-direction="top">
                    Erre a gombra kattintva árajánlatot kérhet erre a termékre.
                  </md-tooltip>
                  <button aria-label="Erre a gombra kattintva árajánlatot kérhet erre a termékre." class="tocart cart-btn" ng-click="requestPrice(<?=$this->product['ID']?>)"><?=__('Ajánlatot kérek')?></i></button>
                </div>
              <?php endif; ?>
            </div>
            <?php if ($this->product['mertekegyseg_egysegar']): ?>
            <div class="egysegar">
              <?php if ($this->product['meret'] != ''): ?>
                Kiszerelés: <strong><?php echo $this->product['meret']; ?></strong> &nbsp;
              <?php endif; ?>
              Egységár: <strong><?php echo $this->product['mertekegyseg_egysegar']; ?></strong>
            </div>
            <?php endif; ?>
          </div>
          <div class="divider"></div>
          Cikkszám: <strong><?=$this->product['cikkszam']?></strong>
          <div class="group helpdesk-actions">
            <div class="social-shares">
              <div class="h">Megosztás:</div>
              <div class="sharing">
                <?php echo $this->render('templates/product_share'); ?>
              </div>
            </div>
            <div class="fav" ng-class="(fav_ids.indexOf(<?=$this->product['ID']?>) !== -1)?'selected':''" ng-click="productAddToFav(<?=$this->product['ID']?>)">
              <div class="wrapper">
                <div ng-show="fav_ids.indexOf(<?=$this->product['ID']?>) !== -1">
                  <i class="fa fa-star"></i> Kedvenc termék
                </div>
                <div ng-show="fav_ids.indexOf(<?=$this->product['ID']?>) === -1">
                  <i class="fa fa-star-o"></i> Kedvencekhez
                </div>
              </div>
            </div>
            <div class="callhelp">
              <div class="wrapper icoed">
                <div class="ico">
                  <i class="fa fa-phone"></i>
                </div>
                <div class="text">
                  Segíthetünk?
                  <div class="phone">
                    <a href="tel:<?=$this->settings['page_author_phone']?>"><?=$this->settings['page_author_phone']?></a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="more-datas">
      <div class="info-texts">

        <div class="description">
          <div class="head">
            <h3>Termék leírás</h3>
          </div>
          <div class="clr"></div>
          <div class="c">
            <?=$this->product['leiras']?>
          </div>
        </div>

        <?php if ($this->product['parameters'] && !empty($this->product['parameters'])): ?>
          <div class="parameters">
            <div class="head">
              <h3>Műszaki adatok</h3>
            </div>
            <div class="clr"></div>
            <div class="c">
              <div class="params">
                <?php foreach ( $this->product['parameters'] as $p ): ?>
                <div class="param">
                  <div class="key">
                    <?php echo $p['neve']; ?>
                  </div>
                  <div class="val">
                    <?php echo $p['ertek']; ?> <span class="me"><?php echo $p['me']; ?></span>
                  </div>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        <?php endif; ?>

        <?php if ($this->product['documents']): ?>
        <a name="documents"></a>
        <div class="documents">
          <div class="head"><h3>Dokumentumok</h3></div>
          <div class="clr"></div>
          <div class="c">
            <div class="docs">
              <?php foreach ( (array)$this->product['documents'] as $doc ): ?>
              <div class="doc">
                <a target="_blank" title="Kiterjesztés: <?=strtoupper($doc['ext'])?>" href="/app/dcl/<?=$doc['hashname']?>"><img src="<?=IMG?>icons/<?=$doc['icon']?>.svg" alt=""><strong><?=$doc['cim']?></strong><?=($doc[filesize])?' <span class="size">&bull; '.strtoupper($doc['ext']).' &bull; '.$doc[filesize].'</span>':''?></a>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
        <?php endif; ?>

      </div>
      <?php if ( $this->related_list ): ?>
      <div class="related-products">
        <div class="head">
          <h3>Ajánljuk még</h3>
        </div>
        <div class="c">
          <div class="items">
          <?php if ( $this->related_list ): ?>
            <? foreach ( $this->related_list as $p ) {
                $p['itemhash'] = hash( 'crc32', microtime() );
                $p = array_merge( $p, (array)$this );
                echo $this->template->get( 'product_item', $p );
            } ?>
          <?php endif; ?>
          </div>
        </div>
      </div>
      <?php endif; ?>
    </div>
    <pre><?php //print_r($this->product); ?></pre>
  </div>
  <div class="sidebar filter-sidebar">

    <? if( $this->viewed_products_list ): ?>
    <div class="lastviewed side-group">
      <div class="head">
        Legutoljára megnézett termékek
      </div>
      <div class="wrapper">
        <div class="product-side-items imaged-style">
          <? foreach ( $this->viewed_products_list as $viewed ) { ?>
          <div class="item">
            <div class="img">
              <a href="<?php echo $viewed['link']; ?>"><img src="<?php echo $viewed['profil_kep']; ?>" alt="<?php echo $viewed['product_nev']; ?>"></a>
            </div>
            <div class="name">
              <a href="<?php echo $viewed['link']; ?>"><?php echo $viewed['product_nev']; ?></a>
            </div>
            <div class="desc">
              <?php echo $viewed['csoport_kategoria']; ?>
            </div>
          </div>
          <? } ?>
        </div>
      </div>
    </div>
    <? endif; ?>

    <? if( $this->top_products && $this->top_products->hasItems() ): ?>
    <div class="topproducts side-group">
      <div class="head">
        Top termékek
      </div>
      <div class="wrapper">
        <div class="product-side-items simple-style">
          <? foreach ( $this->top_products_list as $topp ) { ?>
          <div class="item">
            <div class="name">
              <a href="<?php echo $topp['link']; ?>"><?php echo $topp['product_nev']; ?></a>
            </div>
            <div class="desc">
              <?php echo $topp['csoport_kategoria']; ?>
            </div>
          </div>
          <? } ?>
        </div>
      </div>
    </div>
    <? endif; ?>

    <? if( $this->live_products_list ): ?>
    <div class="liveproducts side-group">
      <div class="head">
        Mások ezeket nézik
      </div>
      <div class="wrapper">
        <div class="product-side-items imaged-style">
          <? foreach ( $this->live_products_list as $livep ) { ?>
          <div class="item">
            <div class="img">
              <a href="<?php echo $livep['link']; ?>"><img src="<?php echo $livep['profil_kep']; ?>" alt="<?php echo $livep['product_nev']; ?>"></a>
            </div>
            <div class="name">
              <a href="<?php echo $livep['link']; ?>"><?php echo $livep['product_nev']; ?></a>
            </div>
            <div class="desc">
              <?php echo $livep['csoport_kategoria']; ?>
            </div>
          </div>
          <? } ?>
        </div>
      </div>
    </div>
    <? endif; ?>
  </div>
</div>
<pre><?php //print_r($this->product); ?></pre>
<script type="text/javascript">
    $(function() {
        <? if( $_GET['buy'] == 'now'): ?>
        $('#add_cart_num').val(1);
        $('#addtocart').trigger('click');
        setTimeout( function(){ document.location.href='/kosar' }, 1000);
        <? endif; ?>
        $('.number-select > div[num]').click( function (){
            $('#add_cart_num').val($(this).attr('num'));
            $('#item-count-num').text($(this).attr('num')+' db');
        });
        $('.size-selector > .number-select > div[link]').click( function (){
            document.location.href = $(this).attr('link');
        });

        $('.product-view .images .all img').hover(function(){
            changeProfilImg( $(this).attr('i') );
        });

        $('.product-view .images .all img').bind("mouseleave",function(){
            //changeProfilImg($('.product-view .main-view a.zoom img').attr('di'));
        });

        $('.products > .grid-container > .item .colors-va li')
        .bind( 'mouseover', function(){
            var hash    = $(this).attr('hashkey');
            var mlink   = $('.products > .grid-container > .item').find('.item_'+hash+'_link');
            var mimg    = $('.products > .grid-container > .item').find('.item_'+hash+'_img');

            var url = $(this).find('a').attr('href');
            var img = $(this).find('img').attr('data-img');

            mimg.attr( 'src', img );
            mlink.attr( 'href', url );
        });

        $('.viewSwitcher > div').click(function(){
            var view = $(this).attr('view');

            $('.viewSwitcher > div').removeClass('active');
            $('.switcherView').removeClass('switch-view-active');

            $(this).addClass('active');
            $('.switcherView.view-'+view).addClass('switch-view-active');

        });

        $('.images .all').slick({
          infinite: true,
          slidesToShow: 5,
          slidesToScroll: 1,
          speed: 400,
          autoplay: true
        });
    })

    function changeProfilImg(i){
        $('.product-view .main-img a.zoom img').attr('src',i);
        $('.product-view .main-img a.zoom').attr('href',i);
    }
</script>
