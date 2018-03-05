@extends('backend.general.main')

@section('content')
    @include('backend.general.charging_modal')

    <div class="box box-<?=$form_type;?> color-palette-box">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-tag"></i> <?=$page_title;?></h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <?php
                        if(count($errors) > 0) //encontró un error
                        {
                            show_alert('danger', (count($errors) == 1) ? 'El formulario presenta el siguiente inconveniente:' : 'El formulario presenta los siguientes inconvenientes', $errors->all(), true);
                        }
                    ?>
                    @include('flash::message')

                    @yield('form_content')
                    <br>
                </div><!-- /.col-md-12 -->
            </div><!-- /.row -->
        </div><!-- /.box-body -->
    </div><!-- /.box-->
@endsection

@section('css_template_header')
    <?=plugins_css_dir('select2/select2.min.css')."\n";?>
@endsection

@section('js_template_footer')
    <?=(!isset($personalize_form_script)) ? js_dir('form_script.js')."\n" : '';?>
    <?=plugins_js_dir('select2/select2.min.js')."\n";?>
    <script>
        $(document).ready(function()
        {
            $('.select2').select2();
        });
    </script>
@endsection
