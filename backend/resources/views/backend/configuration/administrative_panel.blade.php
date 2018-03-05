@extends('backend.general.form_basic')

@section('form_content')
    <form class="form-horizontal" method="POST" action="<?=route('settings.panel');?>">
        <input type="hidden" name="_token" value="<?=csrf_token();?>">

        <div class="form-group">
            <label for="" class="col-sm-2 control-label">Colour of theme</label>
            <div class="col-sm-5">
                <select class="form-control" name="theme_color">
                    <option value="skin-blue">Blue and Black</option>
                    <option value="skin-black" <?=($panel_config->theme_color == 'skin-black') ? 'selected' : '';?>>White and Black</option>
                    <option value="skin-purple" <?=($panel_config->theme_color == 'skin-purple') ? 'selected' : '';?>>Purple and Black</option>
                    <option value="skin-green" <?=($panel_config->theme_color == 'skin-green') ? 'selected' : '';?>>Green and Black</option>
                    <option value="skin-red" <?=($panel_config->theme_color == 'skin-red') ? 'selected' : '';?>>Red and Black</option>
                    <option value="skin-yellow" <?=($panel_config->theme_color == 'skin-yellow') ? 'selected' : '';?>>Yellow and Black</option>

                    <option value="skin-blue-light" <?=($panel_config->theme_color == 'skin-blue-light') ? 'selected' : '';?>>Blue and White</option>
                    <option value="skin-black-light" <?=($panel_config->theme_color == 'skin-black-light') ? 'selected' : '';?>>White and White</option>
                    <option value="skin-purple-light" <?=($panel_config->theme_color == 'skin-purple-light') ? 'selected' : '';?>>Purple and White</option>
                    <option value="skin-green-light" <?=($panel_config->theme_color == 'skin-green-light') ? 'selected' : '';?>>Green and White</option>
                    <option value="skin-red-light" <?=($panel_config->theme_color == 'skin-red-light') ? 'selected' : '';?>>Red and White</option>
                    <option value="skin-yellow-light" <?=($panel_config->theme_color == 'skin-yellow-light') ? 'selected' : '';?>>Yellow and White</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label">Size of Screen</label>
            <div class="col-sm-5">
                <select class="form-control" name="screen">
                    <option value="N">NO</option>
                    <option value="Y" <?=($panel_config->screen == 'Y') ? 'selected' : '';?>>YES</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label">Breadcrumb</label>
            <div class="col-sm-5">
                <select class="form-control" name="breadcrumb">
                    <option value="Y">YES</option>
                    <option value="N" <?=($panel_config->breadcrumb == 'N') ? 'selected' : '';?>>NO</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label">Design of box</label>
            <div class="col-sm-5">
                <select class="form-control" name="box_design">
                    <option value="N">NO</option>
                    <option value="Y" <?=($panel_config->box_design == 'Y') ? 'selected' : '';?>>YES</option>
                </select>
            </div>
        </div>
        <div class="form-group" id="formSubmit">
            <div class="col-sm-offset-2 col-sm-5">
                <hr>
                <button type="submit" class="btn btn-primary pull-right" ><span class="glyphicon glyphicon-pencil"></span> Save changes</button>
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