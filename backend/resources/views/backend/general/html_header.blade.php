<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta autor="<?=$maker;?>" />
<link rel="shortcut icon" type="image/x-icon" href="<?= img_dir('favicon.ico');?>" />
<title><?=isset($page_title) ? $page_title : $page_title;?> | Panel of Administration</title>

<!-- Font Icons -->
<?=plugins_css_dir('font-awesome-4.7.0/css/font-awesome.min.css')."\n";?>
<?=css_dir('backend/ionicons.min.css')."\n"; ?>

<!--  CSS  -->
<?=plugins_css_dir('bootstrap-3.3.7/css/bootstrap.min.css')."\n";?>
@yield('css_template_header')
<?=css_dir('backend/AdminLTE.min.css')."\n"; ?>
<?=css_dir('backend/admin-general.css')."\n"; ?>
<?=css_dir('backend/skins/_all-skins.min.css')."\n"; ?>
@yield('css_header')
<!--  JS  -->
@yield('js_template_header')
@yield('js_header')

<script>if(window.top !== window.self) window.top.location.replace(window.self.location.href);</script>