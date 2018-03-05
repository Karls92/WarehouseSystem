<?php
/**
 * @var App\Models\Product $product
 * @var App\Models\Order $order
 */

if($order->is_processed != 'Y')
{
    $add_button = '<a href="'.route('order_product.store',['order_code' => $order_code,'order_type' => $order_type]).'" class="btn btn-success pull-right" style="margin-top:-10px;"><span class="glyphicon glyphicon-plus-sign"></span><p class="hidden-xs" style="display: inline"> Add</p></a>';
}
else
{
    $add_button = '<button type="button" class="btn btn-success pull-right" style="margin-top:-10px;" disabled><span class="glyphicon glyphicon-plus-sign"></span><p class="hidden-xs" style="display: inline"> Add</p></button>';
}

?>
@extends('backend.general.order_product_list')

@section('list_content')
    <div class="col-md-12">
        <h4><?= ($order_products_qty != 1) ? $order_products_qty.' Products' : $order_products_qty.' Product';?> in total <?=$add_button;?></h4>
        <br/>
        <?php
            if($order_products_qty > 0)
            {
                ?>
                <table id="table_list" class="table table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="50%">Product</th>
                            <th width="30%">Quantity</th>
                            <th width="15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($order_products as $product)
                        {
                            ?>
                            <tr>
                                <td>
                                    <?php
                                        if($order->is_processed != 'Y'  && $product->pivot->quantity > $product->qty_disp)
                                        {
                                            if($order_type == 'devolution')
                                            {
                                                ?>
                                                <b class="broken_order">La cantidad solicitada es incorrecta (Disponible: <?=$product->qty_disp;?>)</b><br>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <b class="broken_order">La cantidad del producto sobrepasa a la existente en el inventario (Disponible: <?=$product->qty_disp;?>)</b><br>
                                                <?php
                                            }
                                        }
                                        ?>
                                        <a href="<?=route('product.search',['slug' => $product->slug]);?>"><b><?=$product->name;?></b></a>
                                        <?php
                                    ?>
                                </td>
                                <td>
                                    <?=$product->pivot->quantity;?>
                                </td>
                                <td>
                                    <?php
                                        if($order->is_processed != 'Y')
                                        {
                                            if($product->pivot->quantity <= $product->qty_disp || $product->qty_disp > 0)
                                            {
                                                ?>
                                                <a href="<?=route('order_product.update',['order_code' => $order_code,'order_type' => $order_type,'id' => $product->pivot->id]);?>" class="btn btn-primary" title="Editar éste producto de la órden"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                                <?php
                                            }

                                            ?>
                                            <a href="#" id="delete_<?=$product->pivot->id;?>" class="btn btn-danger confirmation_delete_modal" title="Borrar éste producto de la órden"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                            <button type="button" class="btn btn-primary" disabled><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                                            <button type="button" class="btn btn-danger" disabled><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
                                            <?php
                                        }
                                    ?>

                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
                <?php
            }
            else
            {
                well('Not Products in this order','There is not any product to this order right now..');
            }
        ?>
    </div><!-- /.col-md-12 -->
@endsection

@section('css_header')
    <style>
        #table_list td:nth-child(2),#table_list td:last-child{
            text-align: center;
        }
        /* xs solamente: */
        @media (max-width: 768px){

        }
    </style>
@endsection

@section('js_footer')
    <script>
        $('#text_delete_modal').html('Out order Products');
        var ajax_url_delete = '<?=route('order_product.delete',['order_code' => $order_code,'order_type' => $order_type,'id' => '']);?>/';

        var registers_qty = parseInt('<?=count($order_products);?>');
        var per_page = 'all';

        $(document).ready(function()
        {

        });
    </script>
@endsection
