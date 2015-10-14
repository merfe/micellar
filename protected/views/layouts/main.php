<!DOCTYPE html>
<!--[if IE]>      <html class="no-js ie" lang="en"> <![endif]-->
<!--[if gt IE 10]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
    <meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	
    <link rel="shortcut icon" href="/images/favicon.png">
    <title><?= $this->pageTitle?></title>

    <link rel="stylesheet" type="text/css" href="/css/normalize.css" />
    <link rel="stylesheet" type="text/css" href="/css/style.css" />
    <link rel="stylesheet" type="text/css" href="/css/main.css" />
    <link rel="stylesheet" type="text/css" href="/css/media.css" />
	<script type="text/javascript" src="/js/libs/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="/js/libs/device.min.js"></script>
</head>

<body>
	<div class="container">
		<?php $this->widget('application.widgets.HeaderWidget'); ?>
		<?php $this->widget('application.widgets.MainMenuWidget'); ?>
		
		<?= $content; ?>
		
		<?php $this->widget('application.widgets.FooterWidget'); ?>
	</div>
	
	<script type="text/javascript" src="/js/libs/jquery-ui.js"></script>
	<script type="text/javascript" src="/js/plugins/jquery.eraser.js"></script>
	<script type="text/javascript" src="/js/app.js"></script>
	<script type="text/javascript" src="/js/init.js"></script>
</body>
</html>

