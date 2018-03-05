     
        <!--inicio del formulario en la ventana modal -->
<div class="form-group">
    <form class="form-horizontal" method="POST" action="<?=route('email.store');?>">
      <input type="hidden" name="_token" value="<?=csrf_token();?>">
        <div class="form-group">
            <label for="#formName" class="col-sm-2 control-label">Marca</label>
            <div class="col-sm-5">
                <input type="text" name="name" class="form-control" id="formName" value="<?=old('name');?>" placeholder="¿Marca?" required autocomplete="off" autofocus>
            </div>
        </div>
        <div class="form-group" id="formSubmit">
            <div class="col-sm-offset-2 col-sm-5">
                <hr>
                <button type="submit" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus-sign"></span> Agregar</button>
            </div>
        </div>
    </form> <!--fin del formulario en la ventana modal -->
</div>
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