<?php /* @var App\Models\Classification $classification */ ?>
@extends('backend.general.basic_list')

@section('list_content')
    <div class="col-md-12">
        <h4><?= ($classifications_qty != 1) ? $classifications_qty.' Classifications' : $classifications_qty.' Classification';?> in total <a href="<?=route('classification.store');?>" class="btn btn-success pull-right" style="margin-top:-10px;"><span class="glyphicon glyphicon-plus-sign"></span><p class="hidden-xs" style="display: inline"> Add new</p></a></h4>
        <br/>
        <?php
            if($classifications_qty > 0)
            {
                ?>
                <table id="table_list" class="table table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="30%">Classification</th>
                            <th width="15%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($classifications as $classification)
                        {
                            ?>
                            <tr>
                                <td>
                                    <?=$classification->name;?>
                                </td>
                                <td>
                                    <a href="<?=route('classification.update',['slug' => $classification->slug]);?>" class="btn btn-primary" title="Edit this classification"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                    <a href="#" id="delete_<?=$classification->id;?>" class="btn btn-danger confirmation_delete_modal" title="Delete this classification"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
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
                well('Not Classifications','There is not any classification in system right now..');
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
        $('#text_delete_modal').html('Classifications');
        var ajax_url_delete = '<?=route('classification.delete',['id' => '']);?>/';

        var registers_qty = parseInt('<?=count($classifications);?>');
        var per_page = 'all'
        var ajax_registers = '<?=route('classification.index');?>';
        var _token = '<?=csrf_token();?>';

        $(document).ready(function()
        {

        });
    </script>
@endsection
