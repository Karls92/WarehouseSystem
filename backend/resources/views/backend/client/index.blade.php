<?php /* @var App\Models\Client $client */ ?>
@extends('backend.general.basic_list')

@section('list_content')
    <div class="col-md-12">
        <h4><?= ($clients_qty != 1) ? $clients_qty.' Clients' : $clients_qty.' Client';?> in total <a href="<?=route('client.store');?>" class="btn btn-success pull-right" style="margin-top:-10px;"><span class="glyphicon glyphicon-plus-sign"></span><p class="hidden-xs" style="display: inline"> Add new</p></a></h4>
        <br/>
        <?php
            if($clients_qty > 0)
            {
                ?>
                <table id="table_list" class="table table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="30%">Client</th>
                            <th width="45%">Details</th>
                            <th width="15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($clients as $client)
                        {
                            ?>
                            <tr>
                                <td>
                                    <?=$client->name;?><br>
                                    <?=$client->document;?>
                                </td>
                                <td>
                                    <b>City: <a href="<?=route('city.search',['slug' => $client->city->slug]);?>"><?=$client->city->name;?></a></b><br>
                                    <b>Code: </b><?=$client->client_code;?><br>
                                    <b>Telephone 1: </b><?=(strlen($client->phone_1) > 5) ? $client->phone_1 : '<em class="text-muted">Not telephone</em>';?><br>
                                    <b>Telephone 2: </b><?=(strlen($client->phone_2) > 5) ? $client->phone_2 : '<em class="text-muted">Not telephone</em>';?><br>
                                    <b>Email: </b><?=(strlen($client->email) > 5) ? $client->email : '<em class="text-muted">Not email</em>';?><br>
                                    <b>Observations: </b><?=(strlen($client->description) > 0) ? $client->description : '<em class="text-muted">Without observations</em>';?>
                                </td>
                                <td>
                                    <a href="<?=route('client.update',['slug' => $client->slug]);?>" class="btn btn-primary" title="Edit this client"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                    <a href="#" id="delete_<?=$client->id;?>" class="btn btn-danger confirmation_delete_modal" title="Delete this client"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
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
                well('Not Clients','There is not any client in system right now..');
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
        $('#text_delete_modal').html('Clients');
        var ajax_url_delete = '<?=route('client.delete',['id' => '']);?>/';

        var registers_qty = parseInt('<?=count($clients);?>');
        var per_page = parseInt('<?=$per_page;?>');
        var ajax_registers = '<?=route('client.index');?>';
        var _token = '<?=csrf_token();?>';

        $(document).ready(function()
        {

        });
    </script>
@endsection
