<?php /* @var App\Models\Order $order */ ?>
@extends('backend.general.basic_list')

@section('list_content')
    <div class="col-md-12">
        <h4><?= ($entry_orders_qty != 1) ? $entry_orders_qty.' Órders' : $entry_orders_qty.' Órder';?> in total <a href="<?=route('entry_order.store');?>" class="btn btn-success pull-right" style="margin-top:-10px;"><span class="glyphicon glyphicon-plus-sign"></span><p class="hidden-xs" style="display: inline"> Add new</p></a></h4>
        <br/>
        <?php
            if($entry_orders_qty > 0)
            {
                ?>
                <table id="table_list" class="table table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="30%">Órder</th>
                            <th width="40%">Details</th>
                            <th width="20%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($entry_orders as $order)
                        {
                            ?>
                            <tr>
                                <td>
                                    <?=$order->code;?><br><a href="<?=route('client.search',['slug' => $order->client->slug]);?>"><b><?=$order->client->name;?></b></a>
                                </td>
                                <td>
                                    <?php
                                        $products_qty = count($order->products);
                                    ?>
                                    <b>Products :</b> <?=($products_qty > 0) ? $products_qty : '<em class="text-muted">Without Products</em>';?><br>
                                    <b>Received by: </b><?=$order->received_by;?><br>
                                    <b>Delivered by: </b><?=$order->delivered_by;?><br>
                                    <b>Date: </b><?=custom_date_format($order->date);?><br>
                                    <b>Description: </b><?=(strlen($order->description) > 0) ? $order->description : '<em class="text-muted">Without description</em>';?>
                                </td>
                                <td>
                                    <?php
                                        if($order->is_processed == 'Y')
                                        {
                                            ?>
                                            <a href="<?=route('order_product.index',['order_type' => 'ordenes-de-entrada', 'order_code' => strtolower($order->code)]);?>" class="btn btn-default" title="Check Products"><span class=" glyphicon glyphicon-folder-open" aria-hidden="true"></span></a>
                                            <a href="<?=route('entry_order.report',['order_code' => $order->code]);?>" class="btn btn-info" title="Get Report"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span></a>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                            <a href="<?=route('order_product.index',['order_type' => 'ordenes-de-entrada', 'order_code' => strtolower($order->code)]);?>" class="btn btn-default" title="Manege Products"><span class=" glyphicon glyphicon-folder-open" aria-hidden="true"></span></a>
                                            <?php


                                            if($products_qty == 0)
                                            {
                                                ?>
                                                <button type="button" class="btn btn-warning" disabled><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <a href="#" id="process_<?=$order->id;?>" class="btn btn-warning confirmation_process_modal" title="Procesar Orden"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></a>
                                                <?php
                                            }

                                            ?>
                                            <a href="<?=route('entry_order.update',['slug' => $order->slug]);?>" class="btn btn-primary" title="Edit this order"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                            <a href="#" id="delete_<?=$order->id;?>" class="btn btn-danger confirmation_delete_modal" title="Delete this order"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
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
                well('Not Orders','There is not any order in the app right now.');
            }
        ?>
    </div><!-- /.col-md-12 -->

    <div class="modal fade" id="confirmation_process_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4>Process entry order</h4>
                    <div class="container-fluid">
                        This order is not gonna be able to edit or delete.<br>
                        ¿Are you sure you want to continue?
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
        $('#text_delete_modal').html('Entry Order');
        var ajax_url_delete = '<?=route('entry_order.delete',['id' => '']);?>/';
        var ajax_url_process = '<?=route('entry_order.process',['id' => '']);?>/';

        var registers_qty = parseInt('<?=count($entry_orders);?>');
        var per_page = parseInt('<?=$per_page;?>');
        var ajax_registers = '<?=route('entry_order.index');?>';
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
