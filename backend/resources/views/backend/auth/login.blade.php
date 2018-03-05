@extends('backend.auth.main')

    @section('content')
        <div class="container login-container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <?php
                        if(count($errors) > 0) //encontró un error
                        {
                            show_alert('danger', (count($errors) == 1) ? 'El formulario presenta el siguiente inconveniente:' : 'El formulario presenta los siguientes inconvenientes', $errors->all(), true);
                        }
                    ?>
                </div>
                <div class="clearfix"></div>
                <div class="col-md-4 col-md-offset-4  text-center">
                    @include('flash::message')
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <center class="well" style="  border: none; padding-top:1px;padding-bottom:1px;">
                                <img src="<?= img_dir('logo.png');?>" class="logo img-responsive" />
                            </center>
                            <form method="post" action="<?=route('login');?>">
                                <input type="hidden" name="_token" value="<?=csrf_token();?>">
                                <div class="form-group has-feedback">
                                    <input type="text" name="username" placeholder="Username" value="<?=old('username');?>" class="form-control input-lg"  style="  border-radius: 6px 6px 0px 0px;" required="required"/>
                                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                </div>
                                <div class="form-group has-feedback">
                                    <input type="password" name="password" placeholder="Password" class="form-control input-lg" style="  border-radius: 0px 0px 6px 6px;" required="required" />
                                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                </div>
                                <br>
                                <div class="hidden-md">
                                    
                                    <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-lock"></span> Login</button>
                                </div>
                                <div class="visible-md">
                                    <button type="submit" class="btn btn-success btn-lg btn-block"><span class="glyphicon glyphicon-lock"></span> Login <span class="glyphicon glyphicon-chevron-right"></span></button>
                                </div>
                                <div class="clearfix"></div>
                                <br>
                                <a href="<?=url('password/email');?>">¿Do you forget your password?</a><br>
                            </form>
                        </div>
                    </div>
                    <b><p class="text-muted">&copy; 2018 Design by Carmen Bravo</p></b>
                </div>
            </div>
        </div>
    @endsection

    @section('css_header')
        <style>
            .logo{
                margin: 25px auto;
                display: block;
                width: auto !important;
                height:auto !important;
            }
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