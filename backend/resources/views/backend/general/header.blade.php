<header class="main-header">
    <!-- Logo -->
    <a href="<?=route('dashboard');?>" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><?=$minimalist_panel_name;?></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">
            <img src="<?=img_dir('logo.png');?>" alt="logo" onclick="" style="padding:8px 0px; height: 50px;" />
        </span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= img_dir('users/'.Auth::user()->image);?>" class="user-image" alt="User Image" />
                        <span class="hidden-xs"><?=Auth::user()->full_name;?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= img_dir('users/'.Auth::user()->image);?>" class="img-circle" alt="User Image" />
                            <p>
                                <?=Auth::user()->full_name;?>
                                <small><?=Auth::user()->username;?></small>
                            </p>
                        </li>

                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="<?=route('profile.edit');?>" class="btn btn-default btn-flat">Edit Profile</a>
                            </div>
                            <div class="pull-right">
                                <a href="<?=route('logout');?>" class="btn btn-default btn-flat">Log out</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>

    </nav>
</header>

<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?=img_dir('users/'.Auth::user()->image);?>" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
                <p><?=Auth::user()->full_name;?></p>
            </div>
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <?php
            if(!isset($active_module))
            {
                $active_module = 'dashboard';
            }

            if(!isset($active_submodule))
            {
                $active_submodule = '';
            }

        ?>
        <ul class="sidebar-menu">
            <li class="header text-center">MAIN MENU</li>
            <li <?=($active_module == 'dashboard') ? 'class="active"' : '';?>>
                <a href="<?=route('dashboard');?>">
                    <i class="fa fa-dashboard"></i> <span>Summery</span>
                </a>
            </li>
            <?php
            foreach($lateral_elements as $module => $menu)
            {
                if(Auth::user()->level <= $menu['details']['level'])
                {
                    if(isset($menu['url']) && count($menu) == 2)
                    {
                        ?>
                        <li <?=($active_module == strtolower(quitar_caracteres_especiales($module))) ? 'class="active"' : '';?>>
                            <a href="<?=$menu['url'];?>">
                                <i class="<?=$menu['details']['icon'];?>"></i> <span><?=trans('messages.plural.'.$module);?></span>
                            </a>
                        </li>
                        <?php
                    }
                    else
                    {
                        ?>
                        <li class="treeview <?=($active_module == strtolower(quitar_caracteres_especiales($module))) ? 'active' : '';?>">
                            <a href="#">
                                <i class="<?=$menu['details']['icon'];?>"></i> <span><?=trans('messages.plural.'.$module);?></span> <i class="fa fa-angle-left pull-right"></i>
                            </a>
                            <ul class="treeview-menu">
                                <?php
                                    foreach($menu as $submodule => $details)
                                    {
                                        if($submodule == 'details')
                                        {
                                            continue;
                                        }

                                        ?>
                                        <li class="<?=($active_submodule == strtolower(quitar_caracteres_especiales($submodule))) ? 'active' : '';?>"><a href="<?=$details['url'];?>"><i class="fa fa-circle-o"></i> <?=trans('messages.plural.'.$submodule);?></a></li>
                                        <?php
                                    }
                                ?>
                            </ul>
                        </li>
                        <?php
                    }
                }
            }

            if(Auth::user()->admin() && Auth::user()->level == 0)
            {
                ?>
                <li <?=($active_module == 'recent_activities') ? 'class="active"' : '';?>>
                    <a href="<?= route('recent_activities');?>">
                        <i class="fa fa-history"></i> <span>Recient Activities</span>
                    </a>
                </li>
                <li class="treeview <?=($active_module == 'site_config') ? 'active' : '';?>">
                    <a href="#">
                        <i class="fa fa-cogs"></i> <span>Setting</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <?php
                            foreach($site_config as $submodule => $details)
                            {
                                ?>
                                <li class="<?=($active_submodule == strtolower( quitar_caracteres_especiales( $submodule))) ? 'active' : '';?>"><a href="<?=$details['url'];?>"><i class="fa fa-circle-o"></i> <?=trans('messages.plural.'.$submodule);?></a></li>
                                <?php
                            }
                        ?>
                    </ul>
                </li>
                <?php
            }
            else
            {
                ?>
                <li class="treeview <?=($active_module == 'site_config') ? 'active' : '';?>">
                    <a href="#">
                        <i class="fa fa-cogs"></i> <span>Setting</span> <i class="fa fa-angle-left pull-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li class="<?=($active_submodule == 'administrative_panel') ? 'active' : '';?>"><a href="<?=route('settings.panel');?>"><i class="fa fa-circle-o"></i> <?=trans('messages.plural.administrative_panel');?></a></li>
                    </ul>
                </li>
                <?php
            }
        ?>
        </ul>
        <!-- Si fuese necesario usar ampliacion del menu lateral se coloca esto -->
        <?=($panel_config->box_design == 'Y' && count($lateral_elements) > 7) ? '<div style="margin-bottom: '.((count($lateral_elements)-7)*30 + 100).'px;"></div>' : '';?>
    </section>
    <!-- /.sidebar -->
</aside>