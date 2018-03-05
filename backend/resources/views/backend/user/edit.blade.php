@extends('backend.general.form_basic')

@section('form_content')
    <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="<?=route('users.update',['username' => $user->username]);?>">
        <input type="hidden" name="_token" value="<?=csrf_token();?>">
        
        <div class="form-group">
            <label for="#formUsername" class="col-sm-2 control-label">Usuario</label>
            <div class="col-sm-5">
                <input type="text" name="username" class="form-control" id="formUsername" value="<?=$user->username;?>" placeholder="¿Qué cambios le harás al campo Usuario?" required autofocus>
            </div>
        </div>
        <div class="form-group">
            <label for="#formFirstName" class="col-sm-2 control-label">Nombre</label>
            <div class="col-sm-5">
                <input type="text" name="first_name" class="form-control" id="formFirstName" value="<?=$user->first_name;?>" placeholder="¿Qué cambios le harás al campo Nombre?" required>
            </div>
        </div>
        <div class="form-group">
            <label for="#formLastName" class="col-sm-2 control-label">Apellido</label>
            <div class="col-sm-5">
                <input type="text" name="last_name" class="form-control" id="formLastName" value="<?=$user->last_name;?>" placeholder="¿Qué cambios le harás al campo Apellido?" required>
            </div>
        </div>
        <div class="form-group">
            <label for="#formEmail" class="col-sm-2 control-label">Correo Electrónico</label>
            <div class="col-sm-5">
                <input type="text" name="email" class="form-control" id="formEmail" value="<?=$user->email;?>" placeholder="¿Qué cambios le harás al campo Correo Electrónico?" required>
            </div>
        </div>
        <div class="form-group">
            <label for="#formPhone" class="col-sm-2 control-label">Teléfono</label>
            <div class="col-sm-5">
                <input type="text" name="phone" class="form-control" id="formPhone" value="<?=$user->phone;?>" placeholder="¿Qué cambios le harás al campo Teléfono?" required>
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label">Imagen</label>
            <div class="row col-sm-4">
                <div class="col-sm-4">
                    <img src="<?=img_dir('users/'.$user->image);?>" id="formImage" alt="" class="img-responsive img-thumbnail" />
                </div>
                <div class="col-sm-8">
                    <input type="file" name="image">
                    <p class="text-muted">Formatos permitidos: png|jpg|jpeg.</p>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label">Tipo</label>
            <div class="col-sm-5">
                <select name="type" class="form-control">
                    <option value="member">Miembro</option>
                    <option value="admin" <?=($user->type == 'admin') ? 'selected' : '';?>>Administrador</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label">Nivel</label>
            <div class="col-sm-5">
                <select name="level" class="form-control">
                    <option value="0">Máximo</option>
                    <option value="1" <?=($user->level == 1) ? 'selected' : '';?>>Medio</option>
                    <option value="2" <?=($user->level == 2) ? 'selected' : '';?>>Mínimo</option>
                </select>
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
    <?=plugins_js_dir('input-mask/jquery.inputmask.js')."\n";?>
    <?=plugins_js_dir('input-mask/jquery.inputmask.extensions.js')."\n";?>

    <script>
        $(document).ready(function()
        {
            $('#formPhone').inputmask({"mask": "(9999) 999-9999"});
        });
    </script>
@endsection