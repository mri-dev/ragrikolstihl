<? if( true ): ?>
    <div class="category-listing page-width">
        <? $this->render('templates/slideshow'); ?>
        <div class="list-view webshop-product-top">
          <div class="grid-layout">
            <div class="grid-row filter-sidebar">
              <form class="" action="" method="get">
                <div class="filters side-group">
                  <div class="head">
                    Keresés tulajdonságok szerint
                  </div>
                  <?php if ( !empty($this->productFilters) ): ?>
                    <?php foreach ( $this->productFilters as $pf ):

                      if(count($pf[hints]) == 0): continue; endif;
                      if( ($pf[type] != 'tartomany' && $pf[type] != 'szam') &&  count($pf[hints]) <= 1) continue;
                      if( ($pf[type] == 'tartomany' || $pf[type] == 'szam') &&  count($pf[hints]) <= 1) continue;
                    ?>
                    <div class="section-group filter-row">
                      <strong><?php echo $pf['parameter']; ?></strong> <?=($pf['me'] != '')?'('.$pf['me'].')':''?>
                    </div>
                    <div class="section-wrapper type-<?=$pf[type]?>">
                        <input type="hidden" name="fil_p_<?=$pf[ID]?>" id="p_<?=$pf[ID]?>_v" />
                        <div id="pmf_<?=$pf[ID]?>">
                           <? if($pf[type] == 'tartomany'): ?>
                           <div class="pos_rel">
                              <input mode="minmax" id="r<?=$pf[ID]?>_range_min" type="hidden" name="fil_p_<?=$pf[ID]?>_min" value="<?=$_GET['fil_p_'.$pf[ID].'_min']?>" class="form-control <?=($_GET['fil_p_'.$pf[ID].'_min'])?'filtered':''?>" />
                              <input mode="minmax" id="r<?=$pf[ID]?>_range_max" type="hidden" name="fil_p_<?=$pf[ID]?>_max" value="<?=$_GET['fil_p_'.$pf[ID].'_max']?>" class="form-control <?=($_GET['fil_p_'.$pf[ID].'_max'])?'filtered':''?>" />
                              <div class="range" key="r<?=$pf[ID]?>" smin="<?=$_GET['fil_p_'.$pf[ID].'_min']?>" smax="<?=$_GET['fil_p_'.$pf[ID].'_max']?>" amin="<?=$pf[minmax][min]?>" amax="<?=$pf[minmax][max]?>"></div>
                              <div class="rangeInfo">
                                 <div class="col-md-6 def"><em>(<?=$pf[minmax][min]?> - <?=$pf[minmax][max]?>)</em></div>
                                 <div class="col-md-6 sel" align="right"><span id="r<?=$pf[ID]?>_range_info_min"><?=$_GET['fil_p_'.$pf[ID].'_min']?></span> - <span id="r<?=$pf[ID]?>_range_info_max"><?=$_GET['fil_p_'.$pf[ID].'_max']?></span></div>
                              </div>
                           </div>
                           <? elseif($pf[type] == 'szam'): ?>
                           <div class="pos_rel">
                              <input mode="minmax" id="r<?=$pf[ID]?>_range_min" type="hidden" name="fil_p_<?=$pf[ID]?>_min" value="<?=$_GET['fil_p_'.$pf[ID].'_min']?>" class="form-control <?=($_GET['fil_p_'.$pf[ID].'_min'])?'filtered':''?>" />
                              <input mode="minmax" id="r<?=$pf[ID]?>_range_max" type="hidden" name="fil_p_<?=$pf[ID]?>_max" value="<?=$_GET['fil_p_'.$pf[ID].'_max']?>" class="form-control <?=($_GET['fil_p_'.$pf[ID].'_max'])?'filtered':''?>" />
                              <div class="range" key="r<?=$pf[ID]?>" smin="<?=$_GET['fil_p_'.$pf[ID].'_min']?>" smax="<?=$_GET['fil_p_'.$pf[ID].'_max']?>" amin="<?=$pf[minmax][min]?>" amax="<?=$pf[minmax][max]?>"></div>
                              <div class="rangeInfo">
                                 <div class="col-md-6 def"><em>(<?=$pf[minmax][min]?> - <?=$pf[minmax][max]?>)</em></div>
                                 <div class="col-md-6 sel" align="right"><span id="r<?=$pf[ID]?>_range_info_min"><?=$_GET['fil_p_'.$pf[ID].'_min']?></span> - <span id="r<?=$pf[ID]?>_range_info_max"><?=$_GET['fil_p_'.$pf[ID].'_max']?></span></div>
                              </div>
                           </div>
                           <? else: ?>
                           <div class="selectors">
                              <?php if (count($pf[hints]) > 0): ?>
                              <div class="sel-item-n">
                                <?=count($pf[hints])?>
                              </div>
                              <?php endif; ?>
                              <div class="selector" key="p_<?=$pf[ID]?>" id="p_<?=$pf[ID]?>">összes</div>
                              <div class="selectorHint p_<?=$pf[ID]?>" style="display:none;">
                                 <ul>
                                    <? foreach($pf[hints] as $h): ?>
                                    <li><label><input type="checkbox" <?=(in_array($h,$this->filters['fil_p_'.$pf[ID]]))?'checked':''?> for="p_<?=$pf[ID]?>" text="<?=$h?>" value="<?=$h?>" /> <?=$h?> <?=$pf[mertekegyseg]?></label></li>
                                    <? endforeach;?>
                                 </ul>
                              </div>
                           </div>
                           <? endif; ?>
                        </div>
                     </div>
                    <?php endforeach; ?>
                  <?php endif; ?>
                  <div class="section-group">
                    Rendezés
                  </div>
                  <div class="section-wrapper">
                    <select name="order" class="form-control">
			                <option value="ar_asc" selected="selected">Ár: növekvő</option>
                      <option value="ar_desc">Ár: csökkenő</option>
                      <option value="nev_asc">Név: A-Z</option>
                      <option value="nev_desc">Név: Z-A</option>
                    </select>
                  </div>
                  <div class="action-group">
                    <button type="submit">Szűrés <i class="fa fa-refresh"></i></button>
                  </div>
                </div>
              </form>

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

              <? if( $this->top_products->hasItems() ): ?>
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
            <div class="grid-row products">
              <div>
                  <? if($this->parent_menu&& count($this->parent_menu) > 0): ?>
                  <div class="sub-categories">
                      <div class="title">
                          <h3><? $subk = ''; foreach($this->parent_row as $sc) { $subk .= $sc.' / '; } echo rtrim($subk,' / '); ?> alkategóriái</h3>
                          <? if($this->parent_category): ?>
                          <a class="back" href="<?=$this->parent_category->getURL()?>"><i class="fa fa-arrow-left"></i> vissza: <?=$this->parent_category->getName()?></a>
                           <? endif; ?>
                      </div>
                      <div class="holder">
                        <? foreach( $this->parent_menu as $cat ): ?>
                        <div class="item">
                          <div class="wrapper">
                            <div class="img"><a href="<?=$cat['link']?>"><img src="<?=rtrim(IMGDOMAIN,"/").$cat['kep']?>" alt="<?=$cat['neve']?>"></a></div>
                            <div class="title"><a href="<?=$cat['link']?>"><?=$cat['neve']?></a></div>
                          </div>
                        </div>
                        <? endforeach; ?>
                      </div>
                  </div>
                  <? endif; ?>

                  <div class="category-title head">
                      <?php if ($this->myfavorite): ?>
                        <h1>Kedvencnek jelölt termékek</h1>
                      <?php elseif($this->category->getName() != ''): ?>
                        <h1><?=$this->category->getName()?></h1>
                      <?php else: ?>
                        <h1>Termékek</h1>
                      <?php endif; ?>
                      <?php $navh = '/termekek/'; ?>
                      <ul class="cat-nav">
                        <li><a href="/"><i class="fa fa-home"></i></a></li>
                        <li><a href="<?=$navh?>">Webshop</a></li>
                        <?php if ($this->myfavorite): ?>
                        <li>Kedvencek</li>
                        <?php endif; ?>
                        <?php
                        foreach ( $this->cat_nav as $nav ): $navh = \Helper::makeSafeUrl($nav['neve'],'_-'.$nav['ID']); ?>
                        <li><a href="/termekek/<?=$navh?>"><?php echo $nav['neve']; ?></a></li>
                        <?php endforeach; ?>
                      </ul>
                  </div>

                  <? if( !$this->products->hasItems()): ?>
                  <div class="no-product-items">
                      <?php if ($this->myfavorite): ?>
                        <div class="icon"><i class="fa fa-fire"></i></div>
                        <strong>Nincsenek kedvencnek jelölt termékei!</strong><br>
                        Kedvencnek jelölhet bármilyen terméket, hogy később gyorsan és könnyedén megtalálja.
                      <?php else: ?>
                        <div class="icon"><i class="fa fa-fire"></i></div>
                        <strong>Nincsenek termékek ebben a kategóriában!</strong><br>
                        A szűrőfeltételek alapján nincs megfelelő termék, amit ajánlani tudunk. Böngésszen további termékeink között.
                      <?php endif; ?>
                  </div>
                  <? else: ?>
                      <div class="grid-container">

                      <? /* foreach ( $this->product_list as $p ) {
                          $p['itemhash'] = hash( 'crc32', microtime() );
                          $p['sizefilter'] = ( count($this->products->getSelectedSizes()) > 0 ) ? true : false;
                          echo $this->template->get( 'product_list_item', $p );
                      }*/ ?>
                          <div class="items">
                              <? foreach ( $this->product_list as $p ) {
                                  $p['itemhash'] = hash( 'crc32', microtime() );
                                  $p['sizefilter'] = ( count($this->products->getSelectedSizes()) > 0 ) ? true : false;
                                  $p['show_variation'] = ($this->myfavorite) ? true : false;
                                  $p = array_merge( $p, (array)$this );
                                  echo $this->template->get( 'product_item', $p );
                              } ?>
                          </div>
                      </div>
                      <div class="clr"></div>
                      <? echo $this->navigator; ?>
                  <br>
                  <? endif; ?>
              </div>
            </div>
          </div>

        </div>
    </div>
<? else: ?>
    <?=$this->render('home')?>
<? endif; ?>
