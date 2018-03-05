@extends('backend.general.form_basic')

@section('form_content')
    <form class="form-horizontal" method="POST" action="<?=route('settings.social_networks');?>">
        <input type="hidden" name="_token" value="<?=csrf_token();?>">

        <div class="form-group">
            <label for="" class="col-sm-2 control-label">Facebook</label>
            <div class="col-sm-5">
                <input type="text" name="social_facebook" class="form-control" id="" value="<?=$social_network->social_facebook;?>" placeholder="¿Dirección de Facebook?"  autofocus>
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label">Twitter</label>
            <div class="col-sm-5">
                <input type="text" name="social_twitter" class="form-control" id="" value="<?=$social_network->social_twitter;?>" placeholder="¿Dirección de Twitter?">
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label">Instagram</label>
            <div class="col-sm-5">
                <input type="text" name="social_instagram" class="form-control" id="" value="<?=$social_network->social_instagram;?>" placeholder="¿Dirección de Instagram?">
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label">Youtube</label>
            <div class="col-sm-5">
                <input type="text" name="social_youtube" class="form-control" id="" value="<?=$social_network->social_youtube;?>" placeholder="¿Canal de Youtube?">
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label">Google+</label>
            <div class="col-sm-5">
                <input type="text" name="social_google_plus" class="form-control" id="" value="<?=$social_network->social_google_plus;?>" placeholder="¿Dirección de Google+?">
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label">Mercado Libre</label>
            <div class="col-sm-5">
                <input type="text" name="social_mercado_libre" class="form-control" id="" value="<?=$social_network->social_mercado_libre;?>" placeholder="¿Dirección de Mercado Libre?">
            </div>
        </div>
        <div class="form-group" id="formSubmit">
            <div class="col-sm-offset-2 col-sm-5">
                <hr>
                <button type="submit" class="btn btn-primary pull-right" ><span class="glyphicon glyphicon-pencil"></span> Guardar Cambios</button>
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