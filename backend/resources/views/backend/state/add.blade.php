@extends('backend.general.form_basic')

@section('form_content')
    <form class="form-horizontal" method="POST" action="<?=route('state.store');?>">
        <input type="hidden" name="_token" value="<?=csrf_token();?>">

        <div class="form-group">
            <label for="#formName" class="col-sm-2 control-label">State</label>
            <div class="col-sm-5">
                <input type="text" name="name" class="form-control" id="formName" value="<?=old('name');?>" placeholder="¿State?" required autocomplete="off" autofocus>
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

