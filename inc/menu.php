<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="images/logo.png?v=<?php echo microtime()?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?php echo $member['first_name']?></p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree" data-api="tree" >
            <?php
            foreach((array)$menu as $i => $item){

                if(count($item['sub_menu'][0]) > 0) {

                    echo '<li id="menu-'.$item['menu_path'].'" class="treeview">';
                    echo '<a href="javascript:void(0)" '.($item['menu_active']==0?'disabled':'').'>'.($item['menu_icon']!='null'?'<i class="'.$item['menu_icon'].'"></i>':'').' <span>' . $item['menu_name'] . '</span>';
                    echo '<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i> </span></a>';
                    echo '<ul class="treeview-menu">';
                    foreach((array)$item['sub_menu'] as $m => $value){
                        echo '<li id="menu-'.$item['sub_menu'][$m]['sub_menu_path'].'" data-parent="menu-'.$item['menu_path'].'" class="submenu"><a href="page-'.$item['sub_menu'][$m]['sub_menu_path'].'" '.($item['sub_menu'][$m]['sub_menu_active']==0?'disabled':'').'>'.($item['sub_menu_icon']!='null'?'<i class="'.$item['sub_menu_icon'].'"></i>':'').' '.$item['sub_menu'][$m]['sub_menu_name'].'</a></li>';
                    }
//                    for ($m = 0; $m <= ($sub-1); ++$m) {
//                        echo '<li><a href="page-'.$item['sub_menu'][$m]['sub_menu_path'].'" '.($item['sub_menu'][$m]['sub_menu_active']==0?'disabled':'').'>'.($item['sub_menu_icon']!='null'?'<i class="'.$item['sub_menu_icon'].'"></i>':'').' '.$item['sub_menu'][$m]['sub_menu_name'].'</a></li>';
//                    }
                    echo '</ul>';
                    echo '</li>';

                }else{
                    echo '<li id="menu-'.$item['menu_path'].'" class=""><a href="page-'.$item['menu_path'].'" '.($item['menu_active']==0?'disabled':'').'>'.($item['menu_icon']!='null'?'<i class="'.$item['menu_icon'].'"></i>':'').' <span>'.$item['menu_name'].'</span></a></li>';
                }

            }
            ?>
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>