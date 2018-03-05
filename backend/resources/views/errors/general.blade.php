@extends('backend.auth.main')

    @section('content')
        <section class="content">
            <div class="error-page">
                <h2 class="headline text-red"><?=$code;?></h2>
                <div class="error-content" style="padding-top:10px !important;">
                    <h3><i class="fa fa-warning text-red"></i> Oops! Algo pasó.</h3>
                    <p>
                        Seguramente no tienes acceso a este contenido o posiblemente ya haya sido borrado.
                        Mientras tanto, podrías <a href="<?=$previous_url;?>">regresar a donde estabas</a> para continuar con tus actividades.
                    </p>
                </div>
            </div><!-- /.error-page -->
        </section><!-- /.content -->
    @endsection

    @section('css_header')
        <style>
            html, body {
                height: 100%;
            }

            html {
                display: table;
                margin: auto;
            }

            body {
                display: table-cell;
                vertical-align: middle;
                background:#ECF0F5;
            }
        </style>
    @endsection
