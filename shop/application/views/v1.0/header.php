<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html xmlns="http://www.w3.org/1999/html4"
      xmlns:og="http://ogp.me/ns#"
      xmlns:fb="http://www.facebook.com/2008/fbml" lang="hu-HU" ng-app="tuzvedelmicentrum">
<head>
    <title><?=$this->title?></title>
    <?=$this->addMeta('robots','index,folow')?>
    <?=$this->SEOSERVICE?>
    <meta property="fb:app_id" content="<?=$this->settings['FB_APP_ID']?>" />
    <? $this->render('meta'); ?>
</head>
<body ng-controller="App" ng-init="init(<?=($this->gets[0] == 'kosar' && $this->gets[1] == 4)?'true':'false'?>)">
<? if(!empty($this->settings[google_analitics])): ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', ' <?=$this->settings[google_analitics]?>', 'auto');
  ga('send', 'pageview');
</script>
<? endif; ?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/hu_HU/sdk.js#xfbml=1&version=v2.3";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<header>
  <div class="top">
    <div class="pw">
      <div class="flex">
        <div class="social">
          <div class="flex flexmob-exc-resp">
            <?php if ( !empty($this->settings['social_facebook_link'])) : ?>
            <div class="facebook">
              <a target="_blank" title="Facebook oldalunk" href="<?=$this->settings['social_facebook_link']?>"><i class="fa fa-facebook"></i></a>
            </div>
            <?php endif; ?>
            <?php if ( !empty($this->settings['social_youtube_link'])) : ?>
            <div class="youtube">
              <a target="_blank" title="Youtube csatornánk" href="<?=$this->settings['social_youtube_link']?>"><i class="fa fa-youtube"></i></a>
            </div>
            <?php endif; ?>
            <?php if ( !empty($this->settings['social_googleplus_link'])) : ?>
            <div class="googleplus">
              <a target="_blank" title="Google+ oldalunk" href="<?=$this->settings['social_googleplus_link']?>"><i class="fa fa-google-plus"></i></a>
            </div>
            <?php endif; ?>
            <?php if ( !empty($this->settings['social_twitter_link'])) : ?>
            <div class="twitter">
              <a target="_blank" title="Twitter oldalunk" href="<?=$this->settings['social_twitter_link']?>"><i class="fa fa-twitter"></i></a>
            </div>
            <?php endif; ?>
          </div>
        </div>
        <div class="actions" ng-controller="ActionButtons">
          <div class="flex flexmob-exc-resp">
            <div class="visszahivas">
              <button type="button" ng-click="requestRecall()">Ingyenes visszahívás</button>
            </div>
            <div class="ajanlatkeres">
              <button type="button" ng-click="requestAjanlat()">Ingyenes árajánlat</button>
            </div>
          </div>
        </div>
        <div class="contact hide-on-mobile">
          <div class="flex">
            <div class="telefon">
              <div class="wrapper">
                <i class="fa fa-phone"></i>
                <div class="title">
                  Telefon:
                </div>
                <div class="val">
                  <a href="tel:<?=$this->settings['page_author_phone']?>"><?=$this->settings['page_author_phone']?></a>
                </div>
              </div>
            </div>
            <div class="email">
              <div class="wrapper">
                <i class="fa fa-envelope-o"></i>
                <div class="title">
                  E-mail:
                </div>
                <div class="val">
                  <a href="mailto:<?=$this->settings['primary_email']?>"><?=$this->settings['primary_email']?></a>
                </div>
              </div>
            </div>
            <div class="address">
              <div class="wrapper">
                <i class="fa fa-map-marker"></i>
                <div class="title">
                  Cím:
                </div>
                <div class="val">
                  <?=$this->settings['page_author_address']?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="tudastar hide-on-mobile">
          <div class="flex">
            <div class="ico">
              <div class="wrap">
                  <i class="fa fa-lightbulb-o"></i>
              </div>
            </div>
            <div class="text">
              Tudástár
            </div>
            <div class="dropmenu">
              <div class="dropmenu-container">
                <div class="flex flexmob-exc-resp">
                  <div class="text">
                    Válasszon témakört
                  </div>
                  <div class="arrow">
                    <i class="fa fa-angle-down"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="main">
    <div class="pw">
      <div class="flex">
        <div class="logo">
          <a href="<?=$this->settings['page_url']?>"><img src="<?=IMG?>ragrikol_logo_white.svg" alt="<?=$this->settings['page_title']?>"></a>
        </div>
        <div class="searcher">
          <div class="searchform">
            <form class="" action="/termekek/" method="get">
            <div class="flex flexmob-exc-resp">
              <div class="input">
                <input type="text" name="src" value="" placeholder="Keresési kifejezés megadása">
              </div>
              <div class="button">
                <button type="submit"><i class="fa fa-search"></i> Keresés</button>
              </div>
            </div>
            </form>
          </div>
        </div>
        <div class="actions">
          <div class="flex">
            <div class="favorite">
              <a href="/kedvencek" class="holder">
                <div class="ico">
                  <span class="badge">{{fav_num}}</span>
                  <i class="fa fa-heart"></i>
                </div>
                Kedvencek
              </a>
            </div>
            <div class="div">&nbsp;</div>
            <div class="account">
              <a href="/user/belepes" class="holder">
                <div class="ico">
                  <img src="<?=IMG?>icons/lock.svg" alt="Belépés">
                </div>
                Belépés
              </a>
            </div>
            <div class="div"></div>
            <div class="cart">
              <div class="holder" id="mb-cart">
                <div class="flex" mb-event="true" data-mb='{ "event": "toggleOnClick", "target" : "#mb-cart" }'>
                  <div class="ico">
                    <span class="badge" id="cart-item-num-v">0</span>
                    <img src="<?=IMG?>icons/cart.svg" alt="Kosár" />
                  </div>
                  <div class="info">
                    <div class="h">Kosár tartalom</div>
                    <div class="l">Összeg</div>
                    <div class="cash"><span class="amount" id="cart-item-prices">0</span> Ft</div>
                  </div>
                </div>
                <div class="floating">
                  <div id="cartContent" class="overflowed">
          					<div class="noItem"><div class="inf">A kosár üres</div></div>
          				</div>
                  <div class="whattodo">
                    <div class="flex">
                      <div class="doempty">
                        <a href="/kosar/?clear=1">Kosár ürítése <i class="fa fa-trash"></i></a>
                      </div>
                      <div class="doorder">
                        <a href="/kosar">Megrendelése <i class="fa fa-arrow-circle-o-right"></i></a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="div"></div>
            <div class="contact">
              <a href="/kapcsolat" class="holder">
                <div class="ico">
                  <i class="fa fa-fire-extinguisher"></i>
                </div>
                Kapcsolat
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="bottom">
    <div class="pw">
      <div class="nav">
				<ul>
					<? foreach ( $this->menu_header->tree as $menu ): ?>
					<li>
						<a href="<?=($menu['link']?:'')?>">
							<? if($menu['kep']): ?><img src="<?=\PortalManager\Formater::sourceImg($child['kep'])?>"><? endif; ?>
							<?=$menu['nev']?> <? if($menu['child']): ?><i class="fa fa-angle-down"></i><? endif; ?></a>
						<? if($menu['child']): ?>
						<div class="sub nav-sub-view">
							<div class="pw">
								<div class="inside">
									<? foreach($menu['child'] as $child): ?>
									<?
										$has_stacklink = false;
										//print_r($child['child']);
										if( $child['child'] && count($child['child']) > 0) {
											foreach($child['child'] as $e):
												if ( strpos($e['css_class'], 'nav-link-stackview') !== false ) {
													$has_stacklink = true;
													break;
												}
											endforeach;
										}
									?>
									<div class="sub-col <?=($has_stacklink) ? 'has-stacklink' : ''?> <?=($child['lista'] ? 'kat-childlist' : '')?>">
										<div class="item item-header <?=$child['css_class']?>" >
										<? if($child['link']): ?><a href="<?=$child['link']?>"><? endif; ?>
										<? if($child['kep']): ?><img src="<?=\PortalManager\Formater::sourceImg($child['kep'])?>"><? endif; ?>
										<span style="<?=$child['css_styles']?>"><?=$child['nev']?></span>
										<? if($child['link']): ?></a><? endif; ?>
										</div>
										<? if($child['lista']): ?>
										<? foreach ($child['lista'] as $elem ) { ?>
											<div class="item <?=$elem['css_class']?>">
												<? if($elem['link']): ?><a href="<?=$elem['link']?>"><? endif; ?>
												<span style="<?=$elem['css_styles']?>"><?=$elem['neve']?></span>
												<? if($elem['link']): ?></a><? endif; ?>
											</div>
										<? }?>
										<? endif; ?>
										<? if($child['child']): ?>
										<? foreach ($child['child'] as $elem ) { ?>
											<div class="item <?=$elem['css_class']?>">
												<? if($elem['link']): ?><a href="<?=$elem['link']?>"><? endif; ?>
												<? if($elem['kep']): ?><img src="<?=\PortalManager\Formater::sourceImg($elem['kep'])?>"><? endif; ?>
												<span style="<?=$elem['css_styles']?>"><?=$elem['nev']?></span>
												<? if($elem['link']): ?></a><? endif; ?>
											</div>
										<? }?>
										<? endif; ?>
									</div>
									<? endforeach; ?>
								</div>
							</div>
						</div>
						<? endif; ?>
					</li>
					<? endforeach; ?>
				</ul>
			</div>
    </div>
  </div>
  <div class="sec-bottom">
    <div class="pw">
      <? if( count($this->highlight_text) > 0 ): ?>
      <div class="highlight-view">
      	<div class="items">
      		<div class="hl-cont">
      			<? if( count($this->highlight_text['data']) > 1 ): ?>
      			<a href="javascript:void(0);" title="Előző" class="prev handler" key="prev"><i class="fa fa-angle-left"></i> |</a>
      			<a href="javascript:void(0);" title="Következő" class="next handler" key="next">| <i class="fa fa-angle-right"></i></a>
      			<? endif; ?>
      			<ul>
      				<? $step = 0; foreach( $this->highlight_text['data'] as $text ): $step++; ?>
      				<li class="<?=($step == 1)?'active':''?>" index="<?=$step?>"><?=$text['tartalom']?></li>
      				<? endforeach; ?>
      			</ul>
            <div class="clr"></div>
      		</div>
      	</div>
      </div>
      <? endif; ?>
    </div>
  </div>
</header>
<?php if ( !$this->homepage ): ?>
<!-- Content View -->
<div class="website">
		<?=$this->gmsg?>
		<div class="general-sidebar"></div>
		<div class="site-container <?=($this->gets[0]=='termek' || $this->gets[0]=='kosar' )?'productview':''?>">
			<div class="clr"></div>
			<div class="inside-content">
<?php endif; ?>
