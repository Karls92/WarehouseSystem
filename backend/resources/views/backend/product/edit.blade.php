<?php /* @var App\Models\Product $product */ ?>
@extends('backend.general.form_basic')

@section('form_content')
    <form class="form-horizontal" method="POST" action="<?=route('product.update',['slug' => $product->slug]);?>">
        <input type="hidden" name="_token" value="<?=csrf_token();?>">

        <div class="form-group">
            <label for="#formProductCode" class="col-sm-2 control-label">Código del Producto</label>
            <div class="col-sm-5">
                <input type="text" name="product_code" class="form-control" id="formProductCode" value="<?=$product->product_code;?>" placeholder="¿Código del Producto?" required autocomplete="off" autofocus>
            </div>
        </div>
        <div class="form-group">
            <label for="#formName" class="col-sm-2 control-label">Producto</label>
            <div class="col-sm-5">
                <input type="text" name="name" class="form-control" id="formName" value="<?=$product->name;?>" placeholder="¿Producto?" required autocomplete="off">
            </div>
        </div>
        <div class="form-group">
            <label for="#formDescription" class="col-sm-2 control-label">Descripción</label>
            <div class="col-sm-5">
                <textarea name="description" class="form-control" id="formDescription" rows="4" placeholder="¿Descripción?" required><?=$product->description;?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="#formBrandID" class="col-sm-2 control-label">Marca</label>
            <div class="col-sm-5">
                <select name="brand_id" class="form-control select2" id="formBrandID" required>
                    <?php
                        foreach($brands as $brand)
                        {
                            ?>
                            <option value="<?=$brand->id;?>" <?=($brand->id == $product->brand_id) ? 'selected' : '';?>><?=$brand->name;?></option>
                            <?php
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="#formModelID" class="col-sm-2 control-label">Modelo</label>
            <div class="col-sm-5">
                <select name="model_id" class="form-control select2" id="formModelID" required>
                    <?php
                        foreach($models as $model)
                        {
                            ?>
                            <option value="<?=$model->id;?>" <?=($model->id == $product->model_id) ? 'selected' : '';?>><?=$model->name;?></option>
                            <?php
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="#formClassificationID" class="col-sm-2 control-label">Clasificación</label>
            <div class="col-sm-5">
                <select name="classification_id" class="form-control select2" id="formClassificationID" required>
                    <?php
                        foreach($classifications as $classification)
                        {
                            ?>
                            <option value="<?=$classification->id;?>" <?=($classification->id == $product->classification_id) ? 'selected' : '';?>><?=$classification->name;?></option>
                            <?php
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="#formUOMID" class="col-sm-2 control-label">Unidad de Medida</label>
            <div class="col-sm-5">
                <select name="uom_id" class="form-control select2" id="formUOMID" required>
                    <?php
                        foreach($units_of_measure as $uom)
                        {
                            ?>
                            <option value="<?=$uom->id;?>" <?=($uom->id == $product->uom_id) ? 'selected' : '';?>><?=$uom->name;?></option>
                            <?php
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="#formObservation" class="col-sm-2 control-label">Observaciones</label>
            <div class="col-sm-5">
                <textarea name="observation" class="form-control" id="formObservation" placeholder="¿Observación?"><?=$product->observation;?></textarea>
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
    <style>

    </style>
@endsection

@section('js_footer')
    <script>
        var ajax_charge_url = '<?=route('brand.models');?>';
        var _token = '<?=csrf_token();?>';

        $(document).ready(function()
        {
            var select2Text = $('#select2-formModelID-container');

            $('#formBrandID').change(function ()
            {
                select2Text.text('CARGANDO...');

                $.post(ajax_charge_url,{'brand_id':$(this).val(),'_token':_token}, function(data)
                {
                    var select_models = $("#formModelID");
                    select_models.empty();

                    select2Text.text('SIN ESPECIFICAR');

                    if(data != 'ERROR')
                    {
                        var models = JSON.parse(data);

                        models.forEach(function (model, index) {
                            select_models.append('<option value="'+model.id+'">'+model.name+'</option>');
                        });
                    }
                    else
                    {
                        alert('Sin resultados.');
                    }
                });
            });
        });
    </script>
@endsection

