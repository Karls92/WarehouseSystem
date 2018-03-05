<?php
    if($panel_config->breadcrumb == 'Y')
    {
        ?>
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <?=$panel_name;?>
                <small><?=$version;?></small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="<?=route('dashboard');?>"><i class="fa fa-dashboard"></i>Start</a></li>
                <?php
                    foreach($breadcrumb as $navigation)
                    {
                        if(isset($navigation['url']))
                        {
                            ?>
                            <li><a href="<?=$navigation['url'];?>"> <?=$navigation['name'];?></a></li>
                            <?php
                        }
                        else
                        {
                            ?>
                            <li class="active"><?=$navigation['name'];?></li>
                            <?php
                        }
                    }
                ?>
            </ol>
        </section>
        <?php
    }
?>