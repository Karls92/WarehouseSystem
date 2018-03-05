@extends('backend.general.form_basic')

@section('form_content')
    <form class="form-horizontal" method="POST" action="<?=route('model.update',['slug' => $model->slug]);?>">
        <input type="hidden" name="_token" value="<?=csrf_token();?>">

        <div class="form-group">
            <label for="#formBrandID" class="col-sm-2 control-label">Marca</label>
            <div class="col-sm-5">
                <select name="brand_id" class="form-control select2" required>
                    <?php
                        foreach($brands as $brand)
                        {
                            ?>
                            <option value="<?=$brand->id;?>" <?=($brand->id == $model->brand_id) ? 'selected' : ''?>><?=$brand->name;?></option>
                            <?php
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="#formName" class="col-sm-2 control-label">Modelo</label>
            <div class="col-sm-5">
                <input type="text" name="name" class="form-control" id="formName" value="<?=$model->name;?>" placeholder="¿Qué cambios le harás al Modelo?" required autocomplete="off" autofocus>
            </div>
        </div>
        <div class="form-group" id="formSubmit">
            <div class="col-sm-offset-2 col-sm-5">
                <hr>
                <button type="submit" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-pencil"></span> Guardar Cambios</button>
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

        });
    </script>
@endsection