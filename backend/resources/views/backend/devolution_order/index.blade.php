<?php /* @var App\Models\Order $order */ ?>
@extends('backend.general.basic_list')

@section('list_content')
    <div class="col-md-12">
        <h4><?= ($devolution_orders_qty != 1) ? $devolution_orders_qty.' Orders' : $devolution_orders_qty.' Order';?> in total <a href="<?=route('devolution_order.store');?>" class="btn btn-success pull-right" style="margin-top:-10px;"><span class="glyphicon glyphicon-plus-sign"></span><p class="hidden-xs" style="display: inline"> Add new</p></a></h4>
        <br/>
        <?php
            if($devolution_orders_qty > 0)
            {
                ?>
                <table id="table_list" class="table table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="30%">Órders</th>
                            <th width="40%">Details</th>
                            <th width="20%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($devolution_orders as $order)
                        {
                            ?>
                            <tr>
                                <td>
                                    <?=$order->code;?><br><a href="<?=route('client.search',['slug' => $order->client->slug]);?>"><b><?=$order->client->name;?></b></a>
                                </td>
                                <td>
                                    <?php
                                        $products_qty = count($order->products);

                                        if($order->is_processed != 'Y')
                                        {
                                            if($order->qty_disp <= 0)
                                            {
                                                ?>
                                                <b class="broken_order"> La orden de salida no posee productos que puedan ya ser devueltos</b><br>
                                                <?php
                                            }

                                            if($order->broken_products > 0)
                                            {
                                                ?>
                                                <b class="broken_order"> Uno de los productos de ésta orden, tiene una cantidad incorrecta</b><br>
                                                <?php
                                            }
                                        }
                                    ?>
                                    <b>Order out: <a href="<?=route('out_order.search',['slug' => $order->slug_out]);?>" ><?=$order->code_out;?></a></b><br>
                                    <b>Products :</b> <?=($products_qty > 0) ? $products_qty : '<em class="text-muted">Not products</em>';?><br>
                                    <b>Received by: </b><?=$order->received_by;?><br>
                                    <b>Delivered by: </b><?=$order->delivered_by;?><br>
                                    <b>Date: </b><?=custom_date_format($order->date);?><br>
                                    <b>Descriptions: </b><?=(strlen($order->description) > 0) ? $order->description : '<em class="text-muted">Not description</em>';?>
                                </td>
                                <td>
                                    <?php
                                        if($order->is_processed == 'Y')
                                        {
                                            ?>
                                            <a href="<?=route('order_product.index',['order_type' => 'ordenes-de-devolucion', 'order_code' => strtolower($order->code)]);?>" class="btn btn-default" title="Check Products"><span class=" glyphicon glyphicon-folder-open" aria-hidden="true"></span></a>
                                            <a href="<?=route('devolution_order.report',['order_code' => $order->code]);?>" class="btn btn-info" title="Get Report"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span></a>
                                            <?php
                                        }
                                        else
                                        {
                                            if($order->qty_disp <= 0)
                                            {
                                                ?>
                                                <a href="#" id="delete_<?=$order->id;?>" class="btn btn-danger confirmation_delete_modal" title="Delete this order"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <a href="<?=route('order_product.index',['order_type' => 'ordenes-de-devolucion', 'order_code' => strtolower($order->code)]);?>" class="btn btn-default" title="Manage Products"><span class=" glyphicon glyphicon-folder-open" aria-hidden="true"></span></a>
                                                <?php

                                                if($order->broken_products > 0 || $products_qty == 0)
                                                {
                                                    ?>
                                                    <button type="button" class="btn btn-warning" disabled><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>
                                                    <?php
                                                }
                                                else
                                                {
                                                    ?>
                                                    <a href="#" id="process_<?=$order->id;?>" class="btn btn-warning confirmation_process_modal" title="Process Order"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></a>
                                                    <?php
                                                }

                                                ?>
                                                <a href="<?=route('devolution_order.update',['slug' => $order->slug]);?>" class="btn btn-primary" title="Edit this order"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                                <a href="#" id="delete_<?=$order->id;?>" class="btn btn-danger confirmation_delete_modal" title="Delete this order"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
                                                <?php
                                            }
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
                well('Not Orders','There is not orders in system right now..');
            }
        ?>
    </div><!-- /.col-md-12 -->

    <div class="modal fade" id="confirmation_process_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4>Process Devolution Order</h4>
                    <div class="container-fluid">
                        No se podrá editar de ninguna forma la órden.<br>
                        Esta acción no se puede deshacer. ¿Está seguro?
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="process_btn" data-id="">Yes</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css_header')
    <style>
        #table_list td:last-child{
            text-align: center;
        }
        /* xs solamente: */
        @media (max-width: 768px){
            #table_list th:nth-child(2),#table_list td:nth-child(2){
                display: none;
            }
        }
    </style>
@endsection

@section('js_footer')
    <script>
        $('#text_delete_modal').html('Devolutions Orders');
        var ajax_url_delete = '<?=route('devolution_order.delete',['id' => '']);?>/';
        var ajax_url_process = '<?=route('devolution_order.process',['id' => '']);?>/';

        var registers_qty = parseInt('<?=count($devolution_orders);?>');
        var per_page = parseInt('<?=$per_page;?>');
        var ajax_registers = '<?=route('devolution_order.index');?>';
        var _token = '<?=csrf_token();?>';

        $(document).ready(function()
        {
            $(document.body).on('click','.confirmation_process_modal',function(e)
                {
                    e.preventDefault();

                    var process_id = $(this).attr('id').split('_')[1];

                    $('#confirmation_process_modal').modal();
                    $('#process_btn').data('id',process_id);
                }
            );

            $(document.body).on('click','#process_btn',function(e)
                {
                    e.preventDefault();

                    $('#confirmation_process_modal').modal('hide');
                    $('#charging_modal').modal();

                    location.href = ajax_url_process+$(this).data('id');
                }
            );
        });
    </script>
@endsection
