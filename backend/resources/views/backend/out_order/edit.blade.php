<?php /* @var App\Models\Order $out_order */ ?>
@extends('backend.general.form_basic')

@section('form_content')
    <form class="form-horizontal" method="POST" action="<?=route('out_order.update',['slug' => $out_order->slug]);?>">
        <input type="hidden" name="_token" value="<?=csrf_token();?>">

        <div class="form-group">
            <label for="#formDeliveredBy" class="col-sm-2 control-label">Entregado Por</label>
            <div class="col-sm-5">
                <input type="text" name="delivered_by" class="form-control" id="formDeliveredBy" value="<?=$out_order->delivered_by;?>" placeholder="¿Entregado Por?" required autocomplete="off">
            </div>
        </div>
        <div class="form-group">
            <label for="#formReceivedBy" class="col-sm-2 control-label">Recibido Por</label>
            <div class="col-sm-5">
                <input type="text" name="received_by" class="form-control" id="formReceivedBy" value="<?=$out_order->received_by;?>" placeholder="¿Recibido Por?" required autocomplete="off">
            </div>
        </div>
        <div class="form-group">
            <label for="#formDate" class="col-sm-2 control-label">Fecha de Orden</label>
            <div class="input-group col-sm-5 custom_datepicker date" id="datepicker">
                <input type="text" class="form-control" readonly="readonly" id="formDate" name="date" value="<?=custom_date_format($out_order->date);?>" required>
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-th"></span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="#formDescription" class="col-sm-2 control-label">Observaciones</label>
            <div class="col-sm-5">
                <textarea name="description" class="form-control" id="formDescription" placeholder="¿Observaciones?" rows="5"><?=$out_order->description;?></textarea>
            </div>
        </div>
        <div class="form-group" id="formSubmit">
            <div class="col-sm-offset-2 col-sm-5">
                <hr>
                <button type="submit" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-pencil"></span> Guardar cambios</button>
            </div>
        </div>
    </form>
@endsection

@section('css_header')
    <?=plugins_css_dir('datepicker/bootstrap-datepicker.min.css')."\n";?>
    <style>
        .datepicker{
            z-index: 88810 !important;
        }
        .custom_datepicker{
            padding-left: 15px !important;
            padding-right:15px !important;
        }
    </style>
@endsection

@section('js_footer')
    <?=plugins_js_dir('datepicker/bootstrap-datepicker.min.js')."\n";?>
    <?=plugins_js_dir('datepicker/bootstrap-datepicker.es.min.js')."\n";?>
    <script>
        $(document).ready(function()
        {
            $('#datepicker').datepicker({
                autoclose: true,
                format: "dd/M/yyyy", // M es para Ene Feb , etc ; mm es para el mes con ceros: 01 02 03; yy es para año 15 16 ; D es el dia abreviado Lun Mar; DD para el dia completo Lunes Martes
                language: "es",
                startDate: "<?=custom_date_format(date('Y-m-d H:i:s',reverse_date('-1 year')));?>",
                endDate: "<?=custom_date_format(date('Y-m-d H:i:s',reverse_date('+1 week')));?>"
            });
        });
    </script>
@endsection

