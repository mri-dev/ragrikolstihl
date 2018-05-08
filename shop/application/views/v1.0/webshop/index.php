<div class="home">
	<div class="pw">

		<div class="grid-layout">
			<div class="grid-row filter-sidebar">
				<? $this->render('templates/sidebar'); ?>
			</div>
			<div class="grid-row home-content">
				<? $this->render('templates/slideshow'); ?>
				<div class="title-header">
					<div class="">
						<h2>Ajánlott termékek</h2>
					</div>
				</div>
				<div class="webshop-product-top">
					<?php if (true): ?>
						<div class="items">
							<? foreach ( $this->kiemelt_products_list as $p ) {
									$p['itemhash'] = hash( 'crc32', microtime() );
									$p['sizefilter'] = ( count($this->kiemelt_products->getSelectedSizes()) > 0 ) ? true : false;
									$p['show_variation'] = ($this->myfavorite) ? true : false;
									$p = array_merge( $p, (array)$this );
									echo $this->ptemplate->get( 'product_item', $p );
							} ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>
