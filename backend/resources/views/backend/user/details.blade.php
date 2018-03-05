@extends('backend.general.main')

@section('content')
    @include('backend.general.image_preview_modal')

    <?php
        if($user)
        {
            ?>
            <div class="box box-info color-palette-box">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-tag"></i> Details of <?=$user->full_name;?></h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-1">
                            <h4><b>Usurname : </b><?=$user->username;?></h4>
                            <h4><b>Full Name  : </b><?=$user->full_name;?></h4>
                            <h4><b>Email  : </b><?=$user->email;?></h4>
                            <h4><b>Telephone: </b><?=$user->phone;?></h4>
                        </div><!-- /.col-md-9 -->
                        <div class="col-md-4 text-center">
                            <img src="<?=img_dir('users/'.$user->image);?>" class="img-thumbnail show_image_preview image_user_details" />
                        </div>
                    </div><!-- /.row -->
                </div><!-- /.box-body -->
            </div><!-- /.box -->
            <?php
        }
        else
        {
            ?>
            <div class="box box-info color-palette-box">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-tag"></i> No existe el Perfil</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-1">
                            <h4><b>Usuario : </b>No existe</h4>
                            <h4><b>Nombre  : </b>No existe</h4>
                            <h4><b>Correo  : </b>No existe</h4>
                            <h4><b>Teléfono: </b>No existe</h4>
                        </div><!-- /.col-md-9 -->
                        <div class="col-md-4 text-center">
                            <img src="<?=img_dir('users/default.png');?>" class="img-thumbnail show_image_preview image_user_details" />
                        </div>
                    </div><!-- /.row -->
                </div><!-- /.box-body -->
            </div><!-- /.box -->
            <?php
        }
    ?>

@endsection

@section('css_header')
    <style>
        .image_user_details{
            width: 150px !important;
            height: 120px !important;
        }
    </style>
@endsection

@section('js_footer')
    <script>
        $(document).ready(function()
        {
            $(document.body).on('click','.show_image_preview',function(e)
                {
                    e.preventDefault();

                    var src = $(this).attr('src');

                    $('#image_preview_modal').modal();
                    $('#image_preview_modal img').attr('src',src);
                }
            );
        });
    </script>
@endsection