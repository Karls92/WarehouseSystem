<?php /* @var App\Models\City $city */ ?>
@extends('backend.general.basic_list')

@section('list_content')
    <div class="col-md-12">
        <h4><?= ($cities_qty != 1) ? $cities_qty.' Cities' : $cities_qty.' City';?> in total <a href="<?=route('city.store');?>" class="btn btn-success pull-right" style="margin-top:-10px;"><span class="glyphicon glyphicon-plus-sign"></span><p class="hidden-xs" style="display: inline"> Add new</p></a></h4>
        <br/>
        <?php
            if($cities_qty > 0)
            {
                ?>
                <table id="table_list" class="table table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="30%">City</th>
                            <th width="45%">State</th>
                            <th width="15%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($cities as $city)
                        {
                            ?>
                            <tr>
                                <td>
                                    <?=$city->name;?>
                                </td>
                                <td>
                                    <a href="<?=route('state.search',['slug' => $city->state->slug]);?>"><b><?=$city->state->name;?></b></a>
                                </td>
                                <td>
                                    <a href="<?=route('city.update',['slug' => $city->slug]);?>" class="btn btn-primary" title="Edit this city"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                    <a href="#" id="delete_<?=$city->id;?>" class="btn btn-danger confirmation_delete_modal" title="Delete this city"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
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
                well('Not cities','There is not any city in system right now.');
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
        $('#text_delete_modal').html('City');
        var ajax_url_delete = '<?=route('city.delete',['id' => '']);?>/';

        var registers_qty = parseInt('<?=count($cities);?>');
        var per_page = 'all';
        var ajax_registers = '<?=route('city.index');?>';
        var _token = '<?=csrf_token();?>';

        $(document).ready(function()
        {

        });
    </script>
@endsection
