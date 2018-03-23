<div class="single-menu color-green imaged image-to-right">
  <a href="#">KIEMELT AJÁNLATUNK</a>
</div>
<div class="single-menu imaged arrowed color-orange">
  <a href="#"><img src="<?=IMG?>stihl-bg-top.svg" alt="Stihl Termékek"> <strong>TERMÉKEK</strong></a>
</div>
<div class="cat-menu">
  <ul>
    <?php foreach ( $this->categories->tree  as $cat ) { ?>
    <li class="menu-item item<?=$cat['ID']?> deep<?=$cat['deep']?>"><a href="<?=$cat['link']?>"><?=$cat['neve']?></a><? if($cat['child']): ?><div class="toggler"><i class="fa fa-angle-right"></i></div><? endif; ?></li>
    <?php $child = $cat['child']; ?>
    <?php while( !empty($child) ): ?>
      <?php foreach ( $child as $cat): ?>
        <li class="menu-item item<?=$cat['ID']?> deep<?=$cat['deep']?> childof<?=$cat['szulo_id']?>"><a href="<?=$cat['link']?>"><?=$cat['neve']?></a><? if($cat['child']): ?><div class="toggler"><i class="fa fa-angle-right"></i></div><? endif; ?></li>
      <?php $child = $cat['child']; endforeach; ?>
    <?php  endwhile; ?>
    <?php } ?>
  </ul>
</div>
