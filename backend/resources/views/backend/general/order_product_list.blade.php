@extends('backend.general.main')

@section('content')
    @include('backend.general.charging_modal')
    @include('backend.general.image_preview_modal')
    @include('backend.general.confirmation_delete_modal')

    <div class="box box-default color-palette-box">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li role="presentation" class="active"><a href="<?=route('order_product.index',['order_code' => $order_code, 'order_type' => $order_type]);?>"><?=str_replace('List of ','',$page_title);?></a></li>
            </ul>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    @include('flash::message')
                </div>

                @yield('list_content')
            </div><!--row-->
        </div><!--box-body-->
    </div><!--box-->
@endsection

@section('css_template_header')
    <?=plugins_css_dir('datatables/dataTables.bootstrap.css')."\n";?>
    <style>

    </style>
@endsection

@section('js_template_footer')
    <?=plugins_js_dir('datatables/jquery.dataTables.min.js')."\n";?>
    <?=plugins_js_dir('datatables/dataTables.bootstrap.min.js')."\n";?>
    <?=js_dir('custom_datatable.js')."\n";?>
    <?=js_dir('list_script.js')."\n";?>

    <script>
        $(document).ready(function()
        {

        });
    </script>
@endsection