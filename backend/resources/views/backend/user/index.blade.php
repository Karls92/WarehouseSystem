@extends('backend.general.basic_list')

@section('list_content')
    <div class="col-md-12">
        <h4><?= ($users_qty != 1) ? $users_qty.' Users' : $users_qty.' User';?> en total <a href="<?=route('users.store');?>" class="btn btn-success pull-right" style="margin-top:-10px;"><span class="glyphicon glyphicon-plus-sign"></span><p class="hidden-xs" style="display: inline"> Add new</p></a></h4>
        <br/>
        <?php
            if($users_qty > 0)
            {
                ?>
                <table id="table_list" class="table table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="10%">Image</th>
                            <th width="30%">User</th>
                            <th width="45%">Details</th>
                            <th width="15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($users as $user)
                        {
                            ?>
                            <tr>
                                <td>
                                    <img src="<?=img_dir('users/'.$user->image);?>" class="img-responsive img-thumbnail show_image_preview">
                                </td>
                                <td>
                                    <?=$user->full_name;?><br>
                                    <?=$user->username;?>
                                </td>
                                <td>
                                    <b>Telephone: </b><?=$user->phone;?><br>
                                    <b>Email: </b><?=$user->email;?><br>
                                    <b>Type: </b><?=$user->type;?><br>
                                    <b>Level: </b><?=$user->level;?>
                                </td>
                                <td>
                                    <a href="<?=route('users.update',['username' => $user->username]);?>" class="btn btn-primary" title="Editar éste usuario"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>
                                    <a href="#" id="delete_<?=$user->id;?>" class="btn btn-danger confirmation_delete_modal" title="Borrar éste usuario"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>
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
                well('Not User','There is not any user now.');
            }
        ?>
    </div><!-- /.col-md-12 -->
@endsection

@section('css_header')
    <style>
        #table_list td:nth-child(1),#table_list td:nth-child(2),#table_list td:nth-child(4){
            text-align: center;
        }
        /* xs solamente: */
        @media (max-width: 768px){
            th:nth-child(1),td:nth-child(1),th:nth-child(3),td:nth-child(3){
                display: none;
            }
        }
    </style>
@endsection

@section('js_footer')
    <script>
        $('#text_delete_modal').html('User');
        var ajax_url_delete = '<?=route('users.delete',['id' => '']);?>/';

        var registers_qty = parseInt('<?=count($users);?>');
        var per_page = 'all';

        $(document).ready(function()
        {

        });
    </script>
@endsection