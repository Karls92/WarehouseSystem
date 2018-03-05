@extends('backend.auth.main')

    @section('content')
       
            <div class="clearfix"></div>
                <div class="col-md-4 col-md-offset-4  text-center">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <form method="post" action="<?=route('password/email');?>">
                                <input type="hidden" name="_token" value="<?=csrf_token();?>">
                                <div class="form-group has-feedback">
                                    <input type="text" name="email" placeholder="Correo Electronico" value="<?=old('email');?>" class="form-control input-lg"  style="  border-radius: 6px 6px 0px 0px;" required="required"/>
                                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                </div>
                                <br>
                                <div class="hidden-xs">
                                    <a href="<?= url();?>" class="btn btn-primary pull-left"><span class="glyphicon glyphicon-chevron-left"></span> Regresar a la página</a>
                                    <button type="submit" class="btn btn-success pull-right"><span class="glyphicon glyphicon-lock"></span> Enviar</button>
                                </div>
                                <div class="visible-xs">
                                    <button type="submit" class="btn btn-success btn-lg btn-block"><span class="glyphicon glyphicon-lock"></span> Enviar <span class="glyphicon glyphicon-chevron-right"></span></button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <b>
                    <p class="text-muted">&copy; <?=$created_at;?> <?=(strlen($false_maker) > 0) ? $false_maker : $maker;?>.</p></b>
                </div>
            </div>
        </div>
    @endsection

    @section('css_header')
        <style>
            .login-container{
                /*padding-top: 180px;*/

            }
            .form-group{
                margin-bottom: -1px;
            }
            input:focus{
                box-shadow: none !important;
            }
            body{
                /*background: #EBEBEB !important;*/
                background: transparent url(<?=img_dir('boxed-bg.jpg');?>) repeat fixed 0% 0%;
                display: table-cell;
                vertical-align: middle;
            }
            html, body {
                height: 100%;
            }
            html {
                display: table;
                margin: auto;
            }
            .panel{
                border-radius:5px;
                border: 1px solid #CFCFCF;
                box-shadow: 0px 0px 15px 0px #0A0000;
            }
            /* Desde xs hasta sm: */
            @media (max-width: 992px){
                .login-container{
                    padding-top: 25px !important;
                }
            }
        </style>
    @endsection