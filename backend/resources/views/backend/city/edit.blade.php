<?php /* @var App\Models\City $city */ ?>
@extends('backend.general.form_basic')

@section('form_content')
    <form class="form-horizontal" method="POST" action="<?=route('city.update',['slug' => $city->slug]);?>">
        <input type="hidden" name="_token" value="<?=csrf_token();?>">

        <div class="form-group">
            <label for="#formBrandID" class="col-sm-2 control-label">State</label>
            <div class="col-sm-5">
                <select name="state_id" class="form-control select2" required>
                    <?php
                        foreach($states as $state)
                        {
                            ?>
                            <option value="<?=$state->id;?>" <?=($state->id == $city->state_id) ? 'selected' : ''?>><?=$state->name;?></option>
                            <?php
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="#formName" class="col-sm-2 control-label">City</label>
            <div class="col-sm-5">
                <input type="text" name="name" class="form-control" id="formName" value="<?=$city->name;?>" placeholder="¿What do you want to change on this file?" required autocomplete="off" autofocus>
            </div>
        </div>
        <div class="form-group" id="formSubmit">
            <div class="col-sm-offset-2 col-sm-5">
                <hr>
                <button type="submit" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-pencil"></span> Save Changes</button>
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

