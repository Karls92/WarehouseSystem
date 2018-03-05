@extends('backend.general.form_basic')

@section('form_content')
    <form class="form-horizontal" method="POST" action="<?=route('settings.contact');?>">
        <input type="hidden" name="_token" value="<?=csrf_token();?>">

        <div class="form-group">
            <label for="" class="col-sm-2 control-label">SMTP Host</label>
            <div class="col-sm-5">
                <input type="text" name="smtp_host" class="form-control" id="" value="<?=$contact->smtp_host;?>" placeholder="¿Cuál es la dirección host del SMTP?" required>
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label">SMTP Port</label>
            <div class="col-sm-5">
                <input type="text" name="smtp_port" class="form-control" id="" value="<?=$contact->smtp_port;?>" placeholder="¿Cuál es el puerto del SMTP?" required>
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label">Correo Electrónico</label>
            <div class="col-sm-5">
                <input type="email" name="email" class="form-control" id="" value="<?=$contact->email;?>" placeholder="¿Correo de contacto?" required autofocus>
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label">Nueva Contraseña</label>
            <div class="col-sm-5">
                <input type="password" name="password_email" class="form-control" id="formPassword" placeholder="¿Nueva clave del correo?"  >
            </div>
        </div>
        <div style="display:none" id="changePassword">
            <div class="form-group">
                <label for="" class="col-sm-2 control-label">Repetir Contraseña</label>
                <div class="col-sm-5">
                    <input type="password" class="form-control" id="formRepeatPassword" name="repeat_password"  placeholder="Escriba su nueva clave otra vez"  >
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-sm-2 control-label">Contraseña Actual</label>
                <div class="col-sm-5">
                    <input type="password" class="form-control" id="formCurrentPassword" name="current_password"  placeholder="Escriba su clave actual">
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-sm-2 control-label">Tu Contraseña</label>
                <div class="col-sm-5">
                    <input type="password" class="form-control" id="formUserPassword" name="user_password"  placeholder="¿Tu contraseña?">
                </div>
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
            var btn = $('button[type=submit]');
            var form_inputs = $("input,textarea,select");
            var something_changed = true;

            $('form').submit(function(e)
            {
                evale_inputs();
                compare_passwords();

                if(btn.attr('disabled') == undefined)
                {
                    btn.html('Cargando <i class="fa fa-spin fa-spinner"></i>');
                    btn.attr('disabled',true);

                    $('#charging_modal').modal();
                }
                else
                {
                    e.preventDefault();
                }
            });

            function evale_inputs() {
                var process_continue = true;

                $('form input[required],form textarea[required]').each(function(){
                    if(process_continue && $(this).val().length == 0)
                    {
                        console.log($(this).attr('placeholder'));
                        process_continue = false;
                    }
                });

                if(process_continue)
                {
                    btn.attr('disabled',false);
                }
                else
                {
                    btn.attr('disabled',true);
                }
            }

            form_inputs.on("change keyup paste", function(){
                something_changed = true;
            });

            form_inputs.focusout(function ()
            {
                if(something_changed)
                {
                    evale_inputs();

                    something_changed = false;
                }
            });

            $('div#formSubmit').mouseover(function()
            {
                if(something_changed)
                {
                    evale_inputs();
                    compare_passwords();

                    something_changed = false;
                }
            });

            evale_inputs();

            var tooltip_is_active = false;

            $('#formPassword').tooltip({ placement: 'top',container: 'body', html : true,title:'No coinciden las contraseñas...', trigger:'manual'});
            $('#formRepeatPassword').tooltip({ placement: 'right',container: 'body', html : true,title:'No coinciden las contraseñas...', trigger:'manual'});

            $('#formPassword').focusin(function(e){
                document.getElementById("changePassword").style.display = "block";
            });

            $('#formPassword').focusout(function(e){

                var inputs_password = $('#changePassword>div.form-group>div>input[type=password],#formPassword');

                if(document.getElementById("formPassword").value == '')
                {
                    empty_password();
                }
                else
                {
                    if(document.getElementById("formRepeatPassword").value.length != 0)
                    {
                        compare_passwords();
                    }

                    inputs_password.attr('required',true);
                }
            });

            $('#formRepeatPassword').focusout(function(e){
                compare_passwords();
            });

            function empty_password() {
                var inputs_password = $('#changePassword>div.form-group>div>input[type=password],#formPassword');

                inputs_password.val('');

                document.getElementById("changePassword").style.display= 'none';

                if(tooltip_is_active)
                {
                    $('#formPassword').tooltip('hide');
                    $('#formRepeatPassword').tooltip('hide');

                    tooltip_is_active = false;
                }

                btn.attr('disabled',false);

                inputs_password.attr('required',false);

                evale_inputs();
            }

            function compare_passwords()
            {
                if(document.getElementById("formPassword").value != '')
                {
                    if(document.getElementById("formRepeatPassword").value != document.getElementById("formPassword").value)
                    {
                        if(!tooltip_is_active)
                        {
                            $('#formPassword').tooltip('show');
                            $('#formRepeatPassword').tooltip('show');

                            tooltip_is_active = true;
                        }

                        btn.attr('disabled',true);
                    }
                    else
                    {
                        if(tooltip_is_active)
                        {
                            $('#formPassword').tooltip('hide');
                            $('#formRepeatPassword').tooltip('hide');

                            tooltip_is_active = false;
                        }

                        btn.attr('disabled',false);

                        evale_inputs();
                    }
                }
                else
                {
                    empty_password();
                }
            }
        });
    </script>
@endsection