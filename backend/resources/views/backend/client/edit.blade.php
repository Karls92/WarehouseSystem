<?php /* @var App\Models\Client $client */ ?>
@extends('backend.general.form_basic')

@section('form_content')
    <form class="form-horizontal" method="POST" action="<?=route('client.update',['slug' => $client->slug]);?>">
        <input type="hidden" name="_token" value="<?=csrf_token();?>">

        <div class="form-group">
            <label for="#formClientCode" class="col-sm-2 control-label">Code</label>
            <div class="col-sm-5">
                <input type="text" name="client_code" class="form-control" id="formClientCode" value="<?=$client->client_code;?>" placeholder="¿Client code?" required autocomplete="off" autofocus>
            </div>
        </div>
        <div class="form-group">
            <label for="#formName" class="col-sm-2 control-label">Client</label>
            <div class="col-sm-5">
                <input type="text" name="name" class="form-control" id="formName" value="<?=$client->name;?>" placeholder="¿Client Name or Company?" required autocomplete="off">
            </div>
        </div>
        <div class="form-group">
            <label for="#formDocument" class="col-sm-2 control-label">I.D/RIF</label>
            <div class="col-sm-5">
                <input type="text" name="document" class="form-control" id="formDocument" value="<?=$client->document;?>" placeholder="¿I.D/RIF?" required autocomplete="off">
            </div>
        </div>
        <div class="form-group">
            <label for="#formAddress" class="col-sm-2 control-label">Address</label>
            <div class="col-sm-5">
                <textarea name="address" class="form-control" rows="3" id="formAddress" placeholder="¿Address?" required><?=$client->address;?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="#formState" class="col-sm-2 control-label">State</label>
            <div class="col-sm-5">
                <select name="state_id" class="form-control select2" id="formState" required>
                    <option value="">--- SELECT ---</option>
                    <?php
                        foreach($states as $state)
                        {
                            ?>
                            <option value="<?=$state->id;?>" <?=($state->id == $client->city->state_id) ? 'selected': '';?>><?=$state->name;?></option>
                            <?php
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="#formCity" class="col-sm-2 control-label">City</label>
            <div class="col-sm-5">
                <select name="city_id" class="form-control select2" id="formCity" required>
                    <?php
                        foreach($cities as $city)
                        {
                            ?>
                            <option value="<?=$city->id;?>" <?=($city->id == $client->city_id) ? 'selected': '';?>><?=$city->name;?></option>
                            <?php
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="#formPhone_1" class="col-sm-2 control-label">Telephone 1</label>
            <div class="col-sm-5">
                <input type="text" name="phone_1" class="form-control" id="formPhone_1" value="<?=$client->phone_1;?>" placeholder="¿Telephone 1?">
            </div>
        </div>
        <div class="form-group">
            <label for="#formPhone_2" class="col-sm-2 control-label">Telephone 2</label>
            <div class="col-sm-5">
                <input type="text" name="phone_2" class="form-control" id="formPhone_2" value="<?=$client->phone_2;?>" placeholder="¿Telephone 2?">
            </div>
        </div>
        <div class="form-group">
            <label for="#formEmail" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-5">
                <input type="email" name="email" class="form-control" id="formEmail" value="<?=$client->email;?>" placeholder="¿Email?">
            </div>
        </div>
        <div class="form-group">
            <label for="#formDescription" class="col-sm-2 control-label">Observations</label>
            <div class="col-sm-5">
                <textarea name="description" class="form-control" rows="5" id="formDescription" placeholder="¿Observations?"><?=$client->description;?></textarea>
            </div>
        </div>
        <div class="form-group" id="formSubmit">
            <div class="col-sm-offset-2 col-sm-5">
                <hr>
                <button type="submit" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-pencil"></span> Save changes</button>
            </div>
        </div>
    </form>
@endsection

@section('css_header')
    <style>

    </style>
@endsection

@section('js_footer')
    <?=plugins_js_dir('input-mask/jquery.inputmask.js')."\n";?>
    <?=plugins_js_dir('input-mask/jquery.inputmask.extensions.js')."\n";?>
    <script>
        var ajax_charge_url = '<?=route('state.cities');?>';
        var _token = '<?=csrf_token();?>';

        $(document).ready(function()
        {
            $('#formState').change(function () {

                $('#select2-formCity-container').text('LOADING...');

                $.post(ajax_charge_url,{'state_id':$(this).val(),'_token':_token}, function(data)
                {
                    var select_cities = $("#formCity");
                    select_cities.empty();
                    $('#select2-formCity-container').text('--- SELECT ---');

                    if(data != 'ERROR')
                    {
                        var cities = JSON.parse(data);

                        cities.forEach(function (city, index) {
                            select_cities.append('<option value="'+city.id+'">'+city.name+'</option>');
                        });

                        select_cities.prepend('<option value="" selected>--- SELECT ---</option>');
                    }
                    else
                    {
                        alert('Not results.');
                    }
                });
            });

            $('#formPhone_1,#formPhone_2').inputmask({"mask": "(9999) 999-9999"});
        });
    </script>
@endsection

