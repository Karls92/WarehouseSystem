<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <?php
            if(strlen($version) > 0)
            {
                $version_parts = explode(' ',$version);

                echo '<b>'.$version_parts[0].'</b> '.$version_parts[1];
            }
        ?>
    </div>
    <div class="text-center">
        <strong>Copyright &copy; 2018 <a href="<?=$maker_link;?>">Design by Carmen Bravo</a>.</strong> All rights Reserves.
    </div>
</footer>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">

    </ul>
    <!-- Tab panes -->
    <div class="tab-content">

    </div>
</aside><!-- /.control-sidebar -->
<!-- Add the sidebar's background. This div must be placed
         immediately after the control sidebar -->
<div class="control-sidebar-bg"></div>