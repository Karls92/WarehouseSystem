<?php /* @var App\Models\ProductModel $model */ ?>
@extends('backend.general.basic_list')

@section('list_content')
    <div class="col-md-12">
        <h4><?= ($models_qty != 1) ? $models_qty.' Models' : $models_qty.' Model';?> in total <a href="<?=route('model.store');?>" class="btn btn-success pull-right" style="margin-top:-10px;"><span class="glyphicon glyphicon-plus-sign"></span><p class="hidden-xs" style="display: inline"> Add new</p></a></h4>
        <br/>
        <?php
            if($models_qty > 0)
            {
                ?>
                <table id="table_list" class="table table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="30%">Model</th>
                            <th width="45%">Brand</th>
                            <th width="15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($models as $model)
                        {
                            ?>
                            <tr>
                                <td>
                                    <?=$model->name;?>
                                </td>
                                <td>
                                    <a href="<?=route('brand.search',['slug' => $model->brand->slug]);?>"><b><?=$model->brand->name;?></b></a>
                                </td>
                                <td>
                                    <a href="<?=route('model.update',['slug' => $model->slug]);?>" class="btn btn-primary" title="Editar éste modelo"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                    <a href="#" id="delete_<?=$model->id;?>" class="btn btn-danger confirmation_delete_modal" title="Borrar éste modelo"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
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
                well('Not Models','There is any model in system right now.');
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

        }
    </style>
@endsection

@section('js_footer')
    <script>
        $('#text_delete_modal').html('Models');
        var ajax_url_delete = '<?=route('model.delete',['id' => '']);?>/';

        var registers_qty = parseInt('<?=$models_qty;?>');
        var per_page = 'all'; // parseInt('<?=$per_page;?>')
        var ajax_registers = '<?=route('model.index');?>';
        var _token = '<?=csrf_token();?>';

        $(document).ready(function()
        {

        });
    </script>
@endsection
