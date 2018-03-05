@extends('backend.general.form_basic')

@section('form_content')
    <form class="form-horizontal" method="POST" action="<?=route('order_product.store',['order_code' => $order_code,'order_type' => $order_type]);?>">
        <input type="hidden" name="_token" value="<?=csrf_token();?>">

        <div class="form-group">
            <label for="#formProduct" class="col-sm-2 control-label">Product</label>
            <div class="col-sm-5">
                <select name="product_id" class="form-control select2" id="formProduct" required autofocus>
                    <option value="">--- SELECT ---</option>
                    <?php
                        foreach($products as $product)
                        {
                            ?>
                            <option value="<?=$product->id;?>"><?=$product->name;?></option>
                            <?php
                        }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="#formQuantity" class="col-sm-2 control-label">Quantity</label>
            <div class="col-sm-5">
                <input type="number" min="1" name="quantity" class="form-control" id="formQuantity" value="<?=(!is_null(old('quantity'))) ? old('quantity') : 1;?>" placeholder="¿Quantity?" required autocomplete="off">
            </div>
        </div>
        <div class="form-group" id="formSubmit">
            <div class="col-sm-offset-2 col-sm-5">
                <hr>
                <button type="submit" class="btn btn-success pull-right"><span class="glyphicon glyphicon-plus-sign"></span> Add new</button>
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

