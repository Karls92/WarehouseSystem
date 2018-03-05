<?php /* @var App\Models\UnitOfMeasure $unit_of_measure */ ?>
@extends('backend.general.basic_list')

@section('list_content')
    <div class="col-md-12">
        <h4><?= ($units_of_measure_qty != 1) ? $units_of_measure_qty.' Units of Measure' : $units_of_measure_qty.' Unit of Measure';?> in total <a href="<?=route('unit_of_measure.store');?>" class="btn btn-success pull-right" style="margin-top:-10px;"><span class="glyphicon glyphicon-plus-sign"></span><p class="hidden-xs" style="display: inline"> Add new</p></a></h4>
        <br/>
        <?php
            if($units_of_measure_qty > 0)
            {
                ?>
                <table id="table_list" class="table table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="30%">Unit of measure</th>
                            <th width="15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($units_of_measure as $unit_of_measure)
                        {
                            ?>
                            <tr>
                                <td>
                                    <?=$unit_of_measure->name;?>
                                </td>
                                <td>
                                    <a href="<?=route('unit_of_measure.update',['slug' => $unit_of_measure->slug]);?>" class="btn btn-primary" title="Editar ésta unidad de medida"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                    <a href="#" id="delete_<?=$unit_of_measure->id;?>" class="btn btn-danger confirmation_delete_modal" title="Borrar ésta unidad de medida"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
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
                well('Not Unit of Measure','There is not any unit of measure in system right now..');
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
        $('#text_delete_modal').html('Units of Measure');
        var ajax_url_delete = '<?=route('unit_of_measure.delete',['id' => '']);?>/';

        var registers_qty = parseInt('<?=count($units_of_measure);?>');
        var per_page = 'all'
        var ajax_registers = '<?=route('unit_of_measure.index');?>';
        var _token = '<?=csrf_token();?>';

        $(document).ready(function()
        {

        });
    </script>
@endsection
