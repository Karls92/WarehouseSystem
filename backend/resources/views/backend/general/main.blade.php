<!DOCTYPE html>
<html lang="es">
    <head>
        @include('backend.general.html_header')
    </head>
    <body class="<?=($panel_config->box_design != 'Y') ? 'fixed' : 'layout-boxed';?> <?=($panel_config->screen == 'Y') ? 'sidebar-collapse' : '';?> <?=$panel_config->theme_color;?> sidebar-mini">
        <div class="wrapper">
            @include('backend.general.header')

            <div class="content-wrapper">
                @include('backend.general.breadcrumb')

                <section class="content">
                    @yield('content')
                    <div class="clearfix"></div>
                </section>
            </div>

            @include('backend.general.footer')
            @include('backend.general.html_footer')
        </div>
    </body>
</html>