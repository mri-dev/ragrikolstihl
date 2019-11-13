<div class="home landing">
	<div class="pw">
		<div class="grid-layout">
			<div class="grid-row filter-sidebar">
				<div class="stihl-webshop">
					<div class="header">
						<a href="<?=$this->settings['welcome_outside_url']?>" target="_blank"><img src="<?=IMG?>/stihl-bg-top.svg" alt=""> TERMÉKEK <i class="fa fa-angle-right"></i></a>
						<div class="clr"></div>
					</div>
					<div class="image">
						<img src="<?=IMGDOMAIN . $this->settings['welcome_img_small']?>" alt="Stihl">
					</div>
					<div class="wshop">
						<a href="/webshop" target="_blank">Tovább a Webáruházra ></a>
					</div>
				</div>
			</div>
			<div class="grid-row home-content">
				<div class="welcome" style="background-image: url('<?=IMGDOMAIN . $this->settings['welcome_img_big']?>');">
					<div class="text">
						<h2><?=$this->settings['welcome_title']?></h2>
						<?php if ($this->settings['welcome_subtitle']): ?>
							<h3><?=$this->settings['welcome_subtitle']?></h3>
						<?php endif; ?>
						<div class="cont">
							<?=$this->settings['welcome_text']?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
