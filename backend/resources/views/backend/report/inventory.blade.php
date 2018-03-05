@extends('backend.general.form_basic')

@section('form_content')
    <form class="form-horizontal" method="POST" action="<?=route('report.inventory');?>">
        <input type="hidden" name="_token" value="<?=csrf_token();?>">

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

        <div class="form-group">
            <label for="#formProductID" class="col-sm-2 control-label">Product</label>
            <div class="col-sm-5">
                <select name="product_id" class="form-control select2" id="formProductID">
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
    <style>

    </style>
@endsection

@section('js_footer')
    <script>
        $(document).ready(function()
        {
            var ajax_charge_url = '<?=route('report.products_client');?>';
            var _token = '<?=csrf_token();?>';

            $('#formClientID').change(function () {

                $('#select2-formProductID-container').text('LOADING...');

                $.post(ajax_charge_url,{'client_id':$(this).val(), '_token':_token}, function(data)
                {
                    var select_products = $("#formProductID");
                    select_products.empty();
                    $('#select2-formProductID-container').text('--- SELECT ---');

                    if(data != 'ERROR')
                    {
                        var products = JSON.parse(data);

                        products.forEach(function (product, index) {
                            select_products.append('<option value="'+product.id+'">'+product.name+'</option>');
                        });

                        select_products.prepend('<option value="" selected>--- SELECT ---</option>');
                    }
                    else
                    {
                        alert('Not results.');
                    }
                });
            });
        });
    </script>
@endsection