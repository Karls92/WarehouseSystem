@extends('backend.general.form_basic')

@section('form_content')
    <form class="form-horizontal" method="POST" action="<?=route('order_product.update',['order_code' => $order_code,'order_type' => $order_type, 'id' => $order_product->pivot->id]);?>">
        <input type="hidden" name="_token" value="<?=csrf_token();?>">

        <div class="form-group">
            <label for="#formQuantity" class="col-sm-2 control-label">Cantidad</label>
            <div class="col-sm-5">
                <input type="number" min="1" name="quantity" class="form-control" id="formQuantity" value="<?=$order_product->pivot->quantity;?>" placeholder="¿Cantidad?" required autocomplete="off">
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
    <script>
        $(document).ready(function()
        {
        
        });
    </script>
@endsection

