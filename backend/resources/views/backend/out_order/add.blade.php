@extends('backend.general.form_basic')

@section('form_content')
    <form class="form-horizontal" method="POST" action="<?=route('out_order.store');?>">
        <input type="hidden" name="_token" value="<?=csrf_token();?>">

        <div class="form-group">
            <label for="#formClientID" class="col-sm-2 control-label">Client</label>
            <div class="col-sm-5">
                <select name="client_id" class="form-control select2" id="formClientID" required autofocus>
                    <option value="">--- SELECT ---</option>
                    <?php
                        foreach($clients as $client)
                        {
                            ?>
                            <option value="<?=$client->id;?>"><?=$client->name;?></option>
                            <?php
                            }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="#formDeliveredBy" class="col-sm-2 control-label">Delivered by</label>
            <div class="col-sm-5">
                <input type="text" name="delivered_by" class="form-control" id="formDeliveredBy" value="<?=(!is_null(old('delivered_by')) && strlen(old('delivered_by')) > 0) ? old('delivered_by') : Auth::user()->full_name;?>" placeholder="¿Delivered by?" required autocomplete="off">
            </div>
        </div>
        <div class="form-group">
            <label for="#formReceivedBy" class="col-sm-2 control-label">Received by</label>
            <div class="col-sm-5">
                <input type="text" name="received_by" class="form-control" id="formReceivedBy" value="<?=old('received_by');?>" placeholder="¿Received by?" required autocomplete="off">
            </div>
        </div>
        <div class="form-group">
            <label for="#formDate" class="col-sm-2 control-label">Date</label>
            <div class="input-group col-sm-5 custom_datepicker date" id="datepicker">
                <input type="text" class="form-control" readonly="readonly" id="formDate" name="date" value="<?=(!is_null(old('date')) && strlen(old('date')) > 0) ? custom_date_format(old('date')) : custom_date_format();?>" required>
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-th"></span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="#formDescription" class="col-sm-2 control-label">Notes</label>
            <div class="col-sm-5">
                <textarea name="description" class="form-control" id="formDescription" placeholder="¿Notes?" rows="5"><?=old('description');?></textarea>
            </div>
        </div>
        <div class="form-group" id="formSubmit">
            <div class="col-sm-offset-2 col-sm-5">
                <hr>
                <button type="submit" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus-sign"></span> Add new</button>
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

