@extends('backend.general.basic_list')

@section('list_content')
    <div class="col-md-12">
        <h4><?= ($visits_qty != 1) ? $visits_qty.' Visitas' : $visits_qty.' Visita';?> en total</h4>
        <br/>
        <?php
            if($visits_qty > 0)
            {
                ?>
                <table id="table_list" class="table table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="10%">Fecha</th>
                            <th width="15%">Ip</th>
                            <th width="15%">País</th>
                            <th width="40%">Referido</th>
                            <th width="10%">Navegador</th>
                            <th width="10%">SO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($visits as $visit)
                        {
                            ?>
                            <tr>
                                <td>
                                    <?=custom_date_format($visit->date);?><br>
                                    <?=date('h:i a',reverse_date($visit->date));?>
                                </td>
                                <td><?=$visit->ip;?></td>
                                <td><?=$visit->country;?></td>
                                <?php
                                    if($visit->referrer == 'www.codigo42.com.ve' || $visit->referrer == 'codigo42.com.ve')
                                    {
                                        $link = 'http://www.codigo42.com.ve';
                                        $referrer_page = 'www.codigo42.com.ve';
                                    }
                                    else
                                    {
                                        $link = $visit->referrer_link;
                                        $referrer_page = $visit->referrer;
                                    }

                                    if($referrer_page == 'Directo')
                                    {
                                        ?>
                                        <td><b><?=$referrer_page;?></b></td>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <td><a href="<?=$link;?>" target="_blank"><b><?=$referrer_page;?></b></a></td>
                                        <?php
                                    }
                                ?>
                                <td><?=$visit->browser;?></td>
                                <td><?=$visit->so;?></td>
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
                well('Sin Visitas','No hay ninguna visita en el sistema.');
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
        var registers_qty = parseInt('<?=count($visits);?>');
        var per_page = parseInt('<?=$per_page;?>');
        var ajax_registers = '<?=route('visits.index');?>';
        var _token = '<?=csrf_token();?>';

        $(document).ready(function()
        {

        });
    </script>
@endsection