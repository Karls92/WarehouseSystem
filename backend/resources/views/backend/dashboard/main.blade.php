@extends('backend.general.main')

    <!-- Info boxes -->
    @section('content')
        <?php
            if(Auth::user()->admin() && Auth::user()->level == 0)
            {
                ?>
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="small-box bg-maroon">
                        <div class="inner">
                            <h3><?=$General_model->quantity('recent_activities');?></h3>
                            <p>Changes </p>
                        </div>
                        <div class="icon">
                            <i class="fa fa-history"></i>
                        </div>
                        <a href="<?=route('recent_activities');?>" class="small-box-footer">
                            More Information <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <?php
            }

            $background_count = 0;
            $background_qty = count($backgrounds);

            foreach($lateral_elements as $module => $menu)
            {
                if(Auth::user()->level <= $menu['details']['level'])
                {
                    if(isset($menu['url']) && count($menu) == 2)
                    {
                        ?>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="small-box <?=$backgrounds[$background_count];?>">
                                <div class="inner">
                                    <?php
                                        if(is_array($menu['details']['database'][$module]) )
                                        {
                                            ?>
                                            <h3><?=$General_model->quantity($menu['details']['database'][$module]['table_name'],$menu['details']['database'][$module]['where']);?></h3>
                                            <?php
                                        }
                                        else
                                        {
                                            ?>
                                            <h3><?=$General_model->quantity($menu['details']['database'][$module]);?></h3>
                                            <?php
                                        }
                                    ?>
                                    <p><?=trans('messages.plural.'.$module);?></p>
                                </div>
                                <div class="icon">
                                    <i class="<?=$menu['details']['icon'];?>"></i>
                                </div>
                                <a href="<?=$menu['url'];?>" class="small-box-footer">
                                    More Information <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <?php
                    }
                    else
                    {
                        foreach($menu as $submodule => $details)
                        {
                            if($submodule == 'details' || $menu['details']['database'][$submodule] == 'NO')
                            {
                                continue;
                            }
            
                            ?>
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="small-box <?=$backgrounds[$background_count];?>">
                                    <div class="inner">
                                        <?php
                                            if(is_array($menu['details']['database'][$submodule]) )
                                            {
                                                ?>
                                                <h3><?=$General_model->quantity($menu['details']['database'][$submodule]['table_name'],$menu['details']['database'][$submodule]['where']);?></h3>
                                                <?php
                                            }
                                            else
                                            {
                                                ?>
                                                <h3><?=$General_model->quantity($menu['details']['database'][$submodule]);?></h3>
                                                <?php
                                            }
                                        ?>
                                        <p><?=trans('messages.plural.'.$submodule);?></p>
                                    </div>
                                    <div class="icon">
                                        <i class="<?=$menu['details']['icon'];?>"></i>
                                    </div>
                                    <a href="<?=$details['url'];?>" class="small-box-footer">
                                        More Information <i class="fa fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <?php
                        }
                    }

                    if($background_count < ($background_qty - 1))
                    {
                        $background_count++;
                    }
                    else
                    {
                        $background_count = 0;
                    }
                }
            }
        ?>
@endsection

