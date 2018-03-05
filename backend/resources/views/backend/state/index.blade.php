<?php /* @var App\Models\State $state */ ?>
@extends('backend.general.basic_list')

@section('list_content')
    <div class="col-md-12">
        <h4><?= ($states_qty != 1) ? $states_qty.' States' : $states_qty.' State';?> en total <a href="<?=route('state.store');?>" class="btn btn-success pull-right" style="margin-top:-10px;"><span class="glyphicon glyphicon-plus-sign"></span><p class="hidden-xs" style="display: inline"> Add new</p></a></h4>
        <br/>
        <?php
            if($states_qty > 0)
            {
                ?>
                <table id="table_list" class="table table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="30%">State</th>
                            <th width="15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($states as $state)
                        {
                            ?>
                            <tr>
                                <td>
                                    <?=$state->name;?>
                                </td>
                                <td>
                                    <a href="<?=route('state.update',['slug' => $state->slug]);?>" class="btn btn-primary" title="Editar éste estado"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                    <a href="#" id="delete_<?=$state->id;?>" class="btn btn-danger confirmation_delete_modal" title="Borrar éste estado"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
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
                well('Not State','There is not any state in system right now..');
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
        $('#text_delete_modal').html('State');
        var ajax_url_delete = '<?=route('state.delete',['id' => '']);?>/';

        var registers_qty = parseInt('<?=count($states);?>');
        var per_page = 'all';
        var ajax_registers = '<?=route('state.index');?>';
        var _token = '<?=csrf_token();?>';

        $(document).ready(function()
        {

        });
    </script>
@endsection
