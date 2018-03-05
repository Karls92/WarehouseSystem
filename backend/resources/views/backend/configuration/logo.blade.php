@extends('backend.general.form_basic')

@section('form_content')
    <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="<?=route('settings.logo');?>">
        <input type="hidden" name="_token" value="<?=csrf_token();?>">

        <div class="form-group">
            <label for="" class="col-sm-2 control-label">Actual Logo</label>
            <div class="col-sm-5">
                <img src="<?=img_dir("logo.png");?>?ref=<?=mt_rand(1,3);?>" alt="Logo de la página" class="img-responsive" style="width: 300px;height: auto;" />
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <input name="image" type="file" id="image" required>
                <p class="help-block">Formats allowed: .png. Size max: 2 mb.</p>
            </div>
        </div>
        <div class="col-sm-offset-2 col-sm-5" id="formSubmit">
            <hr>
            <button type="submit" value="enviando" name="btn_post" class="btn btn-primary pull-right"><span class="glyphicon glyphicon-pencil"></span> Save Changes</button>
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