<!--JS FOOTER-->

<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

<?=js_dir('jQuery-2.1.4.min.js',true)."\n";?>
<?=plugins_js_dir('bootstrap-3.3.7/js/bootstrap.min.js')."\n";?>
<?=plugins_js_dir('slimScroll/jquery.slimscroll.min.js')."\n";?>
<?=js_dir('backend/app.min.js')."\n";?>

@yield('js_template_footer')
@yield('js_footer')