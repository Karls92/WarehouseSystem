<?php /* @var App\Models\Product $product */ ?>
@extends('backend.general.basic_list')

@section('list_content')
    <div class="col-md-12">
        <h4><?= ($products_qty != 1) ? $products_qty.' Products' : $products_qty.' Product';?> in total <a href="<?=route('product.store');?>" class="btn btn-success pull-right" style="margin-top:-10px;"><span class="glyphicon glyphicon-plus-sign"></span><p class="hidden-xs" style="display: inline"> Add New</p></a></h4>
        <br/>
        <?php
            if($products_qty > 0)
            {
                ?>
                <table id="table_list" class="table table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="30%">Product</th>
                            <th width="45%">Details</th>
                            <th width="15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($products as $product)
                        {
                            ?>
                            <tr>
                                <td>
                                    <?=$product->name;?><br><?=$product->product_code;?>
                                </td>
                                <td>
                                    <b>Clasification: <a href="<?=route('classification.search',['slug' => $product->classification->slug]);?>"><?=$product->classification->name;?></a></b><br>
                                    <b>Unit of measure: </b><?=($product->uom_id > 1) ? '<a href="'.route('unit_of_measure.search',['slug' => $product->unit_of_measure->slug]).'"><b>'.$product->unit_of_measure->name.'</b></a>' : '<em class="text-muted">'.$product->unit_of_measure->name.'</em>';?><br>
                                    <b>Brand: </b><?=($product->brand_id > 1) ? '<a href="'.route('brand.search',['slug' => $product->brand->slug]).'"><b>'.$product->brand->name.'</b></a>' : '<em class="text-muted">'.$product->brand->name.'</em>';?><br>
                                    <b>Model: </b><?=($product->model_id > 1) ? '<a href="'.route('model.search',['slug' => $product->model->slug]).'"><b>'.$product->model->name.'</b></a>' : '<em class="text-muted">'.$product->model->name.'</em>';?><br>
                                    <b>Description: </b><?=$product->description;?><br>
                                    <b>Notes: </b><?=(strlen($product->observation) > 0) ? $product->observation : '<em class="text-muted">Sin observación</em>';?>
                                </td>
                                <td>
                                    <a href="<?=route('product.update',['slug' => $product->slug]);?>" class="btn btn-primary" title="Editar éste producto"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                    <a href="#" id="delete_<?=$product->id;?>" class="btn btn-danger confirmation_delete_modal" title="Borrar éste producto"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
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
                well('Not Products','There is not any product in system right now.');
            }
        ?>
    </div><!-- /.col-md-12 -->
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
        $('#text_delete_modal').html('Products');
        var ajax_url_delete = '<?=route('product.delete',['id' => '']);?>/';

        var registers_qty = parseInt('<?=count($products);?>');
        var per_page = parseInt('<?=$per_page;?>');
        var ajax_registers = '<?=route('product.index');?>';
        var _token = '<?=csrf_token();?>';

        $(document).ready(function()
        {

        });
    </script>
@endsection
