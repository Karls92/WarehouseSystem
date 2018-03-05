<?php /* @var App\Models\Brand $brand */ ?>
@extends('backend.general.basic_list')

@section('list_content')
    <div class="col-md-12">
        <h4><?= ($brands_qty != 1) ? $brands_qty.' Brands' : $brands_qty.' Brand';?> in total <a href="<?=route('brand.store');?>" class="btn btn-success pull-right" style="margin-top:-10px;"><span class="glyphicon glyphicon-plus-sign"></span><p class="hidden-xs" style="display: inline"> Add new</p></a></h4>
        <br/>
        <?php
            if($brands_qty > 0)
            {
                ?>
                <table id="table_list" class="table table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="30%">Brand</th>
                            <th width="15%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($brands as $brand)
                        {
                            ?>
                            <tr>
                                <td>
                                    <?=$brand->name;?>
                                </td>
                                <td>
                                    <a href="<?=route('brand.update',['slug' => $brand->slug]);?>" class="btn btn-primary" title="Edit this brand"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                    <a href="#" id="delete_<?=$brand->id;?>" class="btn btn-danger confirmation_delete_modal" title="Delete this brand"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
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
                well('Not brands','There is not any brand in system right now.');
            }
        ?>
    </div><!-- /.col-md-12 -->
@endsection

@section('css_header')
    <style>
        #table_list td:last-child{
            text-align: center;
        }
    </style>
@endsection

@section('js_footer')
    <script>
        $('#text_delete_modal').html('Brand');
        var ajax_url_delete = '<?=route('brand.delete',['id' => '']);?>/';

        var registers_qty = parseInt('<?=count($brands);?>');
        var per_page = 'all';
        var ajax_registers = '<?=route('brand.index');?>';
        var _token = '<?=csrf_token();?>';

        $(document).ready(function()
        {

        });
    </script>
@endsection
