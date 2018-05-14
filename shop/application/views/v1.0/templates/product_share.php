<?
$show 	= true;
$url 	= 'http://'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];

if( $show ): ?>
<div class="holder">
  <div class="facebook" 	title="Megosztás Facebook-on!"><a href="javascript:void(0);" onclick="window.open('https://www.facebook.com/dialog/share?app_id=<?=$this->settings['FB_APP_ID']?>&display=popup&href=<?=$url?>&redirect_uri=<?=$this->settings['site_url']?>','','width=800, height=240')"><i class="fa fa-facebook"></i></a></div>
  <div class="googleplus" 	title="Megosztás Google Plus-on!"><a href="https://plus.google.com/share?url=<?=$url?>" onclick="javascript:window.open('https://plus.google.com/share?url=<?=$url?>',
  '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;"><i class="fa fa-google-plus"></i></a></div>
  <div class="mailer" 	title="Hivatkozás küldése email-ben!"><a href="mailto:?subject=<? echo $this->title; ?>&body=<?=__('Szia! <br><br><br> Találtam egy jó oldalt!<br><br>Weboldal elérhetősége')?>: http%3A%2F%2Fwww.<?php echo $url; ?>"><i class="fa fa-envelope"></i></span></a></div>
</div>
<? endif; ?>
