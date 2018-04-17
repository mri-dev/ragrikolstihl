<div class="item">
  <div class="wrapper">
    <? if( $ajanlatunk == '1' ): ?>
    <div class="ajanlott">
      <img src="<?=IMG?>ajanlatunk.svg" title="Ajánlatunk!" alt="Ajánlatunk">
    </div>
    <? endif; ?>
    <div class="cimkek">
      <? if( $akcios == '1' ): ?>
      <div class="news">
        <img src="<?=IMG?>discount_icon.svg" title="Akciós!" alt="Akciós">
      </div>
      <? endif; ?>
      <? if($ujdonsag): ?>
      <div class="news">
        <img src="<?=IMG?>new_icon.svg" title="Újdonság!" alt="Újdonság">
      </div>
      <? endif; ?>
    </div>

    <div class="image">
			<a href="<?=$link?>"><img title="<?=$product_nev?>" src="<?=$profil_kep?>" alt="<?=$product_nev?>"></a>
      <div class="short-desc">
        <?php echo $rovid_leiras; ?>
      </div>
		</div>

    <div class="prices">
      <?php
        $ar = $brutto_ar;
        $wo_price = ($without_price == '1') ? true : false;
        if( $akcios == '1' ) $ar = $akcios_fogy_ar;
      ?>
      <div class="wrapper <?=($wo_price)?'wo-price':''?>">
        <?php if ( $wo_price ): ?>
          <div class="ar">
            <strong>ÉRDEKLŐDJÖN!</strong><br>
            Kérje szakértőnk tanácsát!
          </div>
        <?php else: ?>
          <?php if ( $akcios == '1' ): ?>
            <div class="ar akcios">
              <div class="old"><?=Helper::cashFormat($brutto_ar)?> <?=$valuta?></div>
              <div class="percents">-<? echo 100-round($akcios_fogy_ar / ($brutto_ar / 100)); ?>%</div>
              <div class="current"><?=Helper::cashFormat($ar)?> <?=$valuta?></div>
            </div>
          <?php else: ?>
            <div class="ar">
              <div class="current"><?=Helper::cashFormat($ar)?> <?=$valuta?></div>
            </div>
          <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>

    <div class="title">
      <h3><a href="<?=$link?>"><?=$product_nev?></a></h3>
    </div>
    <?php if ($show_variation): ?>
    <div class="variation">
      <strong title="Termék variáció"><?=$szin?></strong>
    </div>
    <?php endif; ?>
    <div class="subtitle"><?=__($csoport_kategoria)?></div>

    <div class="buttons">
      <div class="fav" ng-class="(fav_ids.indexOf(<?=$product_id?>) !== -1)?'selected':''" title="Kedvencekhez adom" ng-click="productAddToFav(<?=$product_id?>, $event)">
        <i class="fa fa-heart" ng-show="fav_ids.indexOf(<?=$product_id?>) !== -1"></i>
        <i class="fa fa-heart-o" ng-show="fav_ids.indexOf(<?=$product_id?>) === -1"></i>
      </div>
      <div class="link">
        <a href="<?=$link?>">Megnézem</a>
      </div>
      <?php if ( $wo_price ): ?>
      <div class="order req-price" ng-click="requestPrice(<?=$product_id?>)">
        Árat kérek
      </div>
      <?php else: ?>
      <div class="order">
        <a href="<?=$link?>?buy=1">Megrendelem</a>
      </div>
      <?php endif; ?>
    </div>

  </div>
</div>
