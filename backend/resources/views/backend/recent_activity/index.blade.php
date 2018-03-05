@extends('backend.general.main')

@section('content')

    @include('backend.general.charging_modal')

    <div class="row">
        <div class="col-md-12">
            <ul class="timeline">
                <?php
                    $date = 'default';
                    $cont = 0;
                    $backgrounds_size = count($backgrounds);
                    
                    foreach($recent_activities as $activity)
                    {
                        if(custom_date_format($activity->date) != $date)
                        {
                            $date = custom_date_format($activity->date);

                            ?>
                            <li class="time-label">
                                <span class="<?=$backgrounds[$cont];?>">
                                    <?=$date;?>
                                </span>
                            </li>
                            <?php

                            if($cont == $backgrounds_size)
                            {
                                $cont = 0;
                            }
                            else
                            {
                                $cont++;
                            }
                        }

                        ?>
                        <li>
                            <i class="<?=$activity->icon;?>"></i>
                            <div class="timeline-item">
                                <span class="time"><i class="fa fa-clock-o"></i> <?=date('h:i a',reverse_date($activity->date)) ;?></span>
                                <h3 class="timeline-header no-border"><?=$activity->user." ".$activity->activity;?></h3>
                            </div>
                        </li>
                        <?php
                    }
                    
                    if(count($recent_activities) >= $per_page)
                    {
                        ?>
                        <li class="time-label" id="recharge">
                            <span class="bg-orange">
                                <a href="#" id="show_more_recent_activities" data-offset="<?=$per_page;?>" data-background="<?=$cont;?>" data-date="<?=$date;?>" style="color:white;">See more</a>
                            </span>
                        </li>
                        <?php
                    }
                    else
                    {
                        ?>
                        <li>
                            <i class="fa fa-history"></i>
                        </li>
                        <?php
                    }
                ?>
            </ul>
        </div><!-- /.col-md-12 -->
    </div><!-- /.row -->
@endsection

@section('css_header')
    <style>

    </style>
@endsection

@section('js_footer')
    <script>
        $(document).ready(function()
        {
            var backgrounds = JSON.parse('<?=json_encode($backgrounds);?>');

            $(document.body).on('click','#show_more_recent_activities',function(e)
            {
                e.preventDefault();

                $('#charging_modal').modal();

                var current_background = $(this).data('background');
                var backgrounds_size = backgrounds.length;
                var current_date = $(this).data('date');
                var current_offset = $(this).data('offset');
                var per_page = '<?=$per_page;?>';

                $.post('<?=route('recent_activities');?>',{'offset': current_offset,'_token': '<?=csrf_token();?>'}, function(data)
                {
                    var data = JSON.parse(data);
                    var new_recent_activities = '';

                    data.forEach(function(activity,index){

                        if(activity.formatted_date != current_date)
                        {
                            current_date = activity.formatted_date;

                            current_background++;
                            if(current_background >= backgrounds_size)
                            {
                                current_background = 0;
                            }

                            new_recent_activities += '<li class="time-label">';
                            new_recent_activities += '    <span class="'+backgrounds[current_background]+'">';
                            new_recent_activities += '        '+current_date;
                            new_recent_activities += '    </span>';
                            new_recent_activities += '</li>';
                        }

                        new_recent_activities += '<li>';
                        new_recent_activities += '    <i class="'+activity.icon+'"></i>';
                        new_recent_activities += '    <div class="timeline-item">';
                        new_recent_activities += '        <span class="time"><i class="fa fa-clock-o"></i> '+activity.hour+'</span>';
                        new_recent_activities += '        <h3 class="timeline-header no-border">'+activity.user+' '+activity.activity+'</h3>';
                        new_recent_activities += '    </div>';
                        new_recent_activities += '</li>';
                    });

                    if(data.length >= parseInt(per_page))
                    {
                        new_recent_activities += '<li class="time-label" id="recharge">';
                        new_recent_activities += '    <span class="bg-orange">';
                        new_recent_activities += '        <a href="#" id="show_more_recent_activities" data-offset="'+(parseInt(current_offset)+parseInt(per_page))+'" data-background="'+current_background+'" data-date="'+current_date+'" style="color:white;">Ver más</a>';
                        new_recent_activities += '    </span>';
                        new_recent_activities += '</li>';
                    }
                    else
                    {
                        new_recent_activities += '<li>';
                        new_recent_activities += '    <i class="fa fa-history"></i>';
                        new_recent_activities += '</li>';
                    }

                    $("#recharge").remove();
                    $(".timeline").append(new_recent_activities);

                    $('#charging_modal').modal('hide');
                });
            });
        });
    </script>
@endsection