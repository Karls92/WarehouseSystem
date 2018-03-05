@extends('backend.general.form_basic')

@section('form_content')
    <form class="form-horizontal" method="POST" action="<?=route('report.orders');?>">
        <input type="hidden" name="_token" value="<?=csrf_token();?>">

        <div class="form-group">
            <label for="#formDate" class="col-sm-2 control-label">Start</label>
            <div class="input-group col-sm-5 custom_datepicker date" id="datepicker">
                <input type="text" class="form-control" readonly="readonly" id="formDate" name="date" value="<?=custom_date_format($date->end);?>" required>
                <div class="input-group-addon">
                    <span class="glyphicon glyphicon-th"></span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="#formOperation" class="col-sm-2 control-label">Operación</label>
            <div class="col-sm-5">
                <select name="operation" class="form-control select2" id="formOperation" required autofocus>
                    <option value="">--- SELECT ---</option>
                    <option value="entry">ENTRY</option>
                    <option value="out">OUT</option>
                    <option value="devolution">DEVOLUTIONS</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label for="#formClientID" class="col-sm-2 control-label">Cliente</label>
            <div class="col-sm-5">
                <select name="client_id" class="form-control select2" id="formClientID" required>
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
            <label for="#formOrderCode" class="col-sm-2 control-label">Órden</label>
            <div class="col-sm-5">
                <select name="order_code" class="form-control select2" id="formOrderCode" required>
                    <option value="">--- SELECT ---</option>
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
            var ajax_charge_url = '<?=route('report.orders_client');?>';
            var _token = '<?=csrf_token();?>';

            $('#formClientID').change(function () {

                $('#select2-formOrderCode-container').text('LOADING...');

                $.post(ajax_charge_url,{'client_id':$(this).val(),'date': $('#formDate').val(),'operation' : $('#formOperation').val(),'_token':_token}, function(data)
                {
                    var select_orders = $("#formOrderCode");
                    select_orders.empty();
                    $('#select2-formOrderCode-container').text('--- SELECT ---');

                    if(data != 'ERROR')
                    {
                        var orders = JSON.parse(data);

                        orders.forEach(function (order, index) {
                            select_orders.append('<option value="'+order.code+'">'+order.code+'</option>');
                        });

                        select_orders.prepend('<option value="" selected>--- SELECT ---</option>');
                    }
                    else
                    {
                        alert('Not results.');
                    }
                });
            });

            $('#datepicker').datepicker({
                autoclose: true,
                format: "dd/M/yyyy", // M es para Ene Feb , etc ; mm es para el mes con ceros: 01 02 03; yy es para año 15 16 ; D es el dia abreviado Lun Mar; DD para el dia completo Lunes Martes
                language: "es",
                startDate: "<?=custom_date_format($date->start);?>",
                endDate: "<?=custom_date_format($date->end);?>"
            });
        });
    </script>
@endsection