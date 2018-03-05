@extends('backend.general.form_basic')

@section('form_content')
    <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="<?=route('users.store');?>">
        <input type="hidden" name="_token" value="<?=csrf_token();?>">
        
        <div class="form-group">
            <label for="#formUsername" class="col-sm-2 control-label">Username</label>
            <div class="col-sm-5">
                <input type="text" name="username" class="form-control" id="formUsername" value="<?=old('username');?>" placeholder="¿Username?" required autofocus>
            </div>
        </div>
        <div class="form-group">
            <label for="#formFirstName" class="col-sm-2 control-label">First Name</label>
            <div class="col-sm-5">
                <input type="text" name="first_name" class="form-control" id="formFirstName" value="<?=old('first_name');?>" placeholder="¿First Name?" required>
            </div>
        </div>
        <div class="form-group">
            <label for="#formLastName" class="col-sm-2 control-label">Last Name</label>
            <div class="col-sm-5">
                <input type="text" name="last_name" class="form-control" id="formLastName" value="<?=old('last_name');?>" placeholder="¿Last Name?" required>
            </div>
        </div>
        <div class="form-group">
            <label for="#formEmail" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-5">
                <input type="text" name="email" class="form-control" id="formEmail" value="<?=old('email');?>" placeholder="¿Email?" required>
            </div>
        </div>
        <div class="form-group">
            <label for="#formPhone" class="col-sm-2 control-label">Telephone</label>
            <div class="col-sm-5">
                <input type="text" name="phone" class="form-control" id="formPhone" value="<?=old('phone');?>" placeholder="¿Telephone?" required>
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label">Image</label>
            <div class="col-sm-5">
                <input name="image" type="file" class="form-control" />
                <small class="text-muted">Formats allowed: png|jpg|jpeg.</small>
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label">Type</label>
            <div class="col-sm-5">
                <select name="type" class="form-control">
                    <option value="member">Member</option>
                    <option value="admin" <?=(old('type') == 'admin') ? 'selected' : '';?>>Admin</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label">Level</label>
            <div class="col-sm-5">
                <select name="level" class="form-control">
                    <option value="0">Máx</option>
                    <option value="1" <?=(old('level') == 1) ? 'selected' : '';?>>Medium</option>
                    <option value="2" <?=(old('level') == 2) ? 'selected' : '';?>>Mín</option>
                </select>
            </div>
        </div>
        <div class="form-group" id="formSubmit">
            <div class="col-sm-offset-2 col-sm-5">
                <hr>
                <button type="submit" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus-sign"></span> Add User</button>
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
        $(document).ready(function()
        {
            $('#formPhone').inputmask({"mask": "(9999) 999-9999"});
        });
    </script>
@endsection