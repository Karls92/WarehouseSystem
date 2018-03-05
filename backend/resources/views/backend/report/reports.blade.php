@extends('backend.general.form_basic')

@section('form_content')
    <form class="form-horizontal" method="POST" action="<?=route('report.reports');?>">
        <input type="hidden" name="_token" value="<?=csrf_token();?>">

        <div class="form-group">
            <label for="#formDate" class="col-sm-2 control-label">Start</label>
            <div class="input-group col-sm-5 custom_datepicker date" id="datepicker_start">
                <input type="text" class="form-control" readonly="readonly" id="formDate" name="start" value="<?=custom_date_format($date->start);?>" required>
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-th"></span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="#formDate" class="col-sm-2 control-label">End</label>
            <div class="input-group col-sm-5 custom_datepicker date" id="datepicker_end">
                <input type="text" class="form-control" readonly="readonly" id="formDate" name="end" value="<?=custom_date_format($date->end);?>" required>
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-th"></span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="#formOperation" class="col-sm-2 control-label">Operation</label>
            <div class="col-sm-5">
                <select name="operation" class="form-control select2" id="formOperation" required autofocus>
                    <option value="">--- SELECT ---</option>
                    <option value="entry">ENTRY</option>
                    <option value="out">OUT</option>
                    <option value="devolution">DEVOLUTION</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="#formClientID" class="col-sm-2 control-label">Client</label>
            <div class="col-sm-5">
                <select name="client_id" class="form-control select2" id="formClientID">
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
        <div class="form-group" id="formSubmit">
            <div class="col-sm-offset-2 col-sm-5">
                <hr>
                <button type="submit" class="btn btn-danger pull-right" ><span class="glyphicon glyphicon-refresh"></span> Get Report</button>
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
            $('#datepicker_start,#datepicker_end').datepicker({
                autoclose: true,
                format: "dd/M/yyyy", // M es para Ene Feb , etc ; mm es para el mes con ceros: 01 02 03; yy es para año 15 16 ; D es el dia abreviado Lun Mar; DD para el dia completo Lunes Martes
                language: "es",
                startDate: "<?=custom_date_format($date->start);?>",
                endDate: "<?=custom_date_format($date->end);?>"
            });
        });
    </script>
@endsection