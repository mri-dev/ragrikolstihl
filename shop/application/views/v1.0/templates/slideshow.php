<? if( count( $this->slideshow ) > 0): ?>
<div class="slideShow">
		<?php foreach($this->slideshow as $ss):  ?>
		<div>
			<? if($ss['url'] != ''): ?><a href="<?=$ss['url']?>"><? endif; ?>
	    	<img src="<?=$ss['kep']?>">
	    <? if($ss['url'] != ''): ?></a><? endif; ?>
	        <? if($ss['focim']): ?>
	        <div class="info-box">
		    		<div class="strip">
		    			<div class="main"><?=$ss['focim']?></div>
							<div class="clr"></div>
		    			<? if($ss['alcim']): ?>
		    			<div class="second"><?=$ss['alcim']?></div>
		    			<? endif; ?>
							<div class="clr"></div>
				    	<? if($ss['focim_link']): ?>
			    		<div class="more-link"><a target="_blank" href="<?=$ss['focim_link']?> "><?=$ss['focim_link_text']?></a></div>
			    		<? endif; ?>
		    		</div>
						<div class="clr"></div>
		    	</div>
	    	<? endif; ?>
		</div>
    <? endforeach; ?>
</div>
<script type="text/javascript">
	$(function(){
		fixSlideshowWidth();

		$(window).resize(function(){
				fixSlideshowWidth();
		});
	});

	function fixSlideshowWidth() {
		var w = $('.inside-content').width() - $('.filter-sidebar').width() - 10;
		$('.slideShow').css({
			width: w
		});
	}
</script>
<? endif; ?>
