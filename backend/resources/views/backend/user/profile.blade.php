@extends('backend.general.form_basic')

@section('form_content')
    <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="<?=route('profile.edit');?>">
        <input type="hidden" name="_token" value="<?=csrf_token();?>">

        <div class="form-group">
            <label for="#formUsername" class="col-sm-2 control-label">Username</label>
            <div class="col-sm-5">
                <input type="text" name="username" class="form-control" id="" value="<?=$user->username;?>" placeholder="¿Username?" required autofocus>
            </div>
        </div>
        <div class="form-group">
            <label for="#formFirstname" class="col-sm-2 control-label">First Name</label>
            <div class="col-sm-5">
                <input type="text" name="first_name" class="form-control" id="" value="<?=$user->first_name;?>" placeholder="¿First Name?" required>
            </div>
        </div>
        <div class="form-group">
            <label for="#formLastname" class="col-sm-2 control-label">Last Name</label>
            <div class="col-sm-5">
                <input type="text" name="last_name" class="form-control" id="" value="<?=$user->last_name;?>" placeholder="¿Last Name?" required>
            </div>
        </div>
        <div class="form-group">
            <label for="#formEmail" class="col-sm-2 control-label">Email</label>
            <div class="col-sm-5">
                <input type="email" name="email" class="form-control" id="" value="<?=$user->email;?>" placeholder="¿Email address?" required>
            </div>
        </div>
        <div class="form-group">
            <label for="#formPhone" class="col-sm-2 control-label">Telephone number</label>
            <div class="col-sm-5">
                <input type="text" name="phone" class="form-control" id="formPhone" value="<?=$user->phone;?>" placeholder="¿Telephone number?" required>
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-sm-2 control-label">Image</label>
            <div class="row col-sm-4">
                <div class="col-sm-4">
                    <img src="<?=img_dir('users/'.$user->image);?>" id="image" alt="" class="img-responsive img-thumbnail" />
                </div>
                <div class="col-sm-8">
                    <input type="file" name="image">
                    <p class="text-muted">Formats allowed: png|jpg|jpeg.</p>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="#formPassword" class="col-sm-2 control-label">New Password</label>
            <div class="col-sm-5">
                <input type="password" class="form-control" id="formPassword" name="password"  placeholder="If you change your password, last one's gonna delete"  >
            </div>
        </div>
        <div style="display:none" id="changePassword">
            <div class="form-group">
                <label for="#formRepeatPassword" class="col-sm-2 control-label">Insert Password again</label>
                <div class="col-sm-5">
                    <input type="password" class="form-control" id="formRepeatPassword" name="repeat_password"  placeholder="Write new password again"  >
                </div>
            </div>
            <div class="form-group">
                <label for="#formCurrentPassword" class="col-sm-2 control-label">Actual Password</label>
                <div class="col-sm-5">
                    <input type="password" class="form-control" id="formCurrentPassword" name="current_password"  placeholder="Write your actual password">
                </div>
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
    <?=plugins_js_dir('input-mask/jquery.inputmask.js')."\n";?>
    <?=plugins_js_dir('input-mask/jquery.inputmask.extensions.js')."\n";?>

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
                    btn.html('Loading <i class="fa fa-spin fa-spinner"></i>');
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
                    if($(this).val().length == 0)
                    {
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

            $('#formPhone').inputmask({"mask": "(9999) 999-9999"});

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