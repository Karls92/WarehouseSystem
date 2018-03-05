@extends('backend.general.basic_list')

@section('list_content')
    <div class="col-md-12">
        <h4><?= ($referrers_qty != 1) ? $referrers_qty.' Referidos' : $referrers_qty.' Referido';?> en total</h4>
        <br/>
        <?php
            if($referrers_qty > 0)
            {
                ?>
                <table id="table_list" class="table table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="30%">Referido</th>
                            <th width="65%">Link</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($referrers as $referrer)
                        {
                            if($referrer->referrer == 'www.codigo42.com.ve' || $referrer->referrer == 'codigo42.com.ve')
                            {
                                $link = 'http://www.codigo42.com.ve';
                                $referrer_page = 'www.codigo42.com.ve';
                            }
                            else
                            {
                                $link = $referrer->referrer_link;
                                $referrer_page = $referrer->referrer;
                            }
                            ?>
                            <tr>
                                <td><?=$referrer->count;?></td>
                                <td><b><?=$referrer_page;?></b></td>
                                <td><a href="<?=$link;?>"><?=$link;?></a></td>
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
                well('Sin Referidos','No hay ningún referido en el sistema.');
            }
        ?>
    </div><!-- /.col-md-12 -->
@endsection

@section('css_header')
    <style>
        #table_list td{
            text-align: center;
        }
        /* xs solamente: */
        @media (max-width: 768px){
            th:nth-child(2),td:nth-child(2),th:nth-child(5),td:nth-child(5),th:nth-child(6),td:nth-child(6){
                display: none;
            }
        }
    </style>
@endsection

@section('js_footer')
    <script>
        var registers_qty = parseInt('<?=$referrers_qty;?>');
        var per_page = 'all';

        $(document).ready(function()
        {

        });
    </script>
@endsection