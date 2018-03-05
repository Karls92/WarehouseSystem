<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" type="image/x-icon" href="<?= img_dir('favicon.ico');?>" />
<meta autor="<?=$maker;?>" />
<title><?= (isset($page_title) ? $page_title.' | ' : '').$site_name ;?></title>

<?=plugins_css_dir('font-awesome-4.7.0/css/font-awesome.min.css')."\n";?>
<?=plugins_css_dir('bootstrap-3.3.7/css/bootstrap.min.css')."\n";?>
<?=css_dir('backend/AdminLTE.min.css')."\n"; ?>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<script>if(window.top !== window.self) window.top.location.replace(window.self.location.href);</script>