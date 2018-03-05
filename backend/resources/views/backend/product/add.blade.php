@extends('backend.general.form_basic')

@section('form_content') 
    <form class="form-horizontal" method="POST" action="<?=route('product.store');?>">
        <input type="hidden" name="_token" value="<?=csrf_token();?>">

        <div class="form-group">
            <label for="#formProductCode" class="col-sm-2 control-label"> Product Code</label>
            <div class="col-sm-5">
                <input type="text" name="product_code" class="form-control" id="formProductCode" value="<?=old('product_code');?>" placeholder="¿Product Code?" required autocomplete="off" autofocus>
            </div>
        </div>
        <div class="form-group">
            <label for="#formName" class="col-sm-2 control-label">Product</label>
            <div class="col-sm-5">
                <input type="text" name="name" class="form-control" id="formName" value="<?=old('name');?>" placeholder="¿Product?" required autocomplete="off">
            </div>
        </div>
        <div class="form-group">
            <label for="#formDescription" class="col-sm-2 control-label">Description</label>
            <div class="col-sm-5">
                <textarea name="description" class="form-control" id="formDescription" rows="4" placeholder="¿Description?" required><?=old('description');?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="#formBrandID" class="col-sm-2 control-label">Brand</label>
            <div class="col-sm-5">
                <select name="brand_id" class="form-control select2" required id="formBrandID">
                    <?php
                        foreach($brands as $brand)
                        {
                            ?>
                            <option value="<?=$brand->id;?>"><?=$brand->name;?></option>
                            <?php
                        }
                    ?>
                </select>
            </div>
            <!-- AQUI PUEDO AGREGAR UN NUEVO BOTON DE PRUEBA 
            <button type= "button" class="btn btn-success" data-toggle="modal" data-target="#miModal"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button> -->
          
           </div>

   <!--         
<div class="modal fade" id="miModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"> <!--codigo de inicio de la ventana modal -->
 <!-- <div class="modal-dialog" role="document">
    <div class="modal-content"> <!--contenido de la ventana modal -->
   <!--   <div class="modal-header"> <!--inicio del espacio de cabecera de la ventana modal -->
    <!--    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button> <!--boton para cerrar la ventana modal. se encuentra en la cabecera -->
     <!--   <h3 class="modal-title" id="myModalLabel">Agregar Marca</h3> <!--titulo de la ventana modal -->
    <!--  </div> <!--fin de la cabecera de la ventana modal -->
    <!--  <div class="espacioBody"> <!--espacio body de la ventana modal -->
       
     <!-- </div>
      <br>
    </div> <!--fin del espacio de contenido de la ventana modal -->
  <!--</div>
</div> <!--div de finalización de la ventana modal --> 


        <div class="form-group">
            <label for="#formModelID" class="col-sm-2 control-label">Model</label>
            <div class="col-sm-5">
                <select name="model_id" class="form-control select2" required id="formModelID">
                    <?php
                    foreach($models as $model)
                    {
                        ?>
                        <option value="<?=$model->id;?>"><?=$model->name;?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <!-- UN NUEVO BOTON DE PRUEBA2 AGREGADO 
            <button type="button" class="btn btn-success"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
-->
        </div>
        <div class="form-group">
            <label for="#formClassificationID" class="col-sm-2 control-label">Clasification</label>
            <div class="col-sm-5">
                <select name="classification_id" class="form-control select2" id="formClassificationID" required>
                    <option value="">--- SELECT ---</option>
                    <?php
                        foreach($classifications as $classification)
                        {
                            ?>
                            <option value="<?=$classification->id;?>"><?=$classification->name;?></option>
                            <?php
                        }
                    ?>
                </select>
            </div>
            <!-- UN NUEVO BOTON DE PRUEBA3 AGREGADO 
            <button type="button" class="btn btn-success"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
-->
        </div>
        <div class="form-group">
            <label for="#formUOMID" class="col-sm-2 control-label">Unit of measure</label>
            <div class="col-sm-5">
                <select name="uom_id" class="form-control select2" required id="formUOMID">
                    <?php
                        foreach($units_of_measure as $uom)
                        {
                            ?>
                            <option value="<?=$uom->id;?>"><?=$uom->name;?></option>
                            <?php
                        }
                    ?>
                </select>
            </div>
            <!-- UN NUEVO BOTON DE PRUEBA4 AGREGADO 
            <button type="button" class="btn btn-success"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button>
            -->
        </div>
        <div class="form-group">
            <label for="#formObservation" class="col-sm-2 control-label">Notes</label>
            <div class="col-sm-5">
                <textarea name="observation" class="form-control" id="formObservation" placeholder="¿Notes?"><?=old('observation');?></textarea>
            </div>
        </div>
        <div class="form-group" id="formSubmit">
            <div class="col-sm-offset-2 col-sm-5">
                <hr>
                <button type="submit" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus-sign"></span> Add New</button>
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
        var ajax_charge_url = '<?=route('brand.models');?>';
        var _token = '<?=csrf_token();?>';

        $(document).ready(function()
        {
            var select2Text = $('#select2-formModelID-container');

            $('#formBrandID').change(function ()
            {
                select2Text.text('LOADING...');

                $.post(ajax_charge_url,{'brand_id':$(this).val(),'_token':_token}, function(data)
                {
                    var select_models = $("#formModelID");
                    select_models.empty();

                    select2Text.text('NOT INFORMATION');

                    if(data != 'ERROR')
                    {
                        var models = JSON.parse(data);

                        models.forEach(function (model, index) {
                            select_models.append('<option value="'+model.id+'">'+model.name+'</option>');
                        });
                    }
                    else
                    {
                        alert('Without results.');
                    }
                });
            });
        });
    </script>
@endsection


     