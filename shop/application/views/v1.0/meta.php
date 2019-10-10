<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- STYLES -->
<?php $this->switchJSAsync('defer'); ?>
<link rel="icon" href="<?=IMG?>icons/favicon.ico" type="image/x-icon">
<?=$this->addStyle('master', 'media="all"')?>
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
<?=$this->addStyle('bootstrap.min', 'media="all"')?>
<?=$this->addStyle('bootstrap-theme.min', 'media="all"')?>
<?=$this->addStyle('FontAwesome.min', 'media="all"')?>
<?=$this->addStyle('dashicons.min','media="all"')?>
<!--<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">-->
<?=$this->addStyle('media', 'media="all"', false)?>
<link rel="stylesheet" type="text/css" href="<?=JS?>fancybox/jquery.fancybox.css?v=2.1.4" media="all" />
<link rel="stylesheet" type="text/css" href="<?=JS?>fancybox/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
<link rel="stylesheet" type="text/css" href="<?=JS?>slick/slick.css"/>
<link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/angular_material/1.1.4/angular-material.min.css" />

<!-- JS's -->
<!-- Angular Material requires Angular.js Libraries -->
<script defer src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular.min.js"></script>
<script defer src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-animate.min.js"></script>
<script defer src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-aria.min.js"></script>
<script defer src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-messages.min.js"></script>
<?php $this->switchJSAsync('async'); ?>
<?=$this->addJS('//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js',true)?>
<?php $this->switchJSAsync('defer'); ?>
<script defer src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
<script defer src='//www.google.com/recaptcha/api.js?hl=hu'></script>

<!-- Angular Material Library -->
<script defer src="//ajax.googleapis.com/ajax/libs/angular_material/1.1.4/angular-material.min.js"></script>
<?=$this->addJS('bootstrap.min',false)?>
<?=$this->addJS('jquery.cookieaccept',false,false)?>
<?=$this->addJS('master',false,false)?>
<?=$this->addJS('pageOpener',false,false)?>
<?=$this->addJS('user',false,false)?>
<?=$this->addJS('jquery.cookie',false)?>
<?=$this->addJS('angular.min',false)?>
<?=$this->addJS('app',false,false)?>
<?=$this->addJS('upload',false,false)?>
<?=$this->addJS('angular-cookies',false, false)?>
<?=$this->addJS('jquery.cetelemCalculator',false, false)?>
<script defer type="text/javascript" src="<?=JS?>slick/slick.min.js"></script>
<script defer type="text/javascript" src="<?=JS?>fancybox/jquery.fancybox.js?v=2.1.4"></script>
<script defer type="text/javascript" src="<?=JS?>fancybox/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
<script defer src='//www.google.com/recaptcha/api.js?hl=hu'></script>
