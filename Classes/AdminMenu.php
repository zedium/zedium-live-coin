<?php

namespace Zedium\Classes;

class AdminMenu
{
    public function __construct(){
        add_action('admin_menu', [$this, 'registerAdminMenu']);
    }

    public function registerAdminMenu(){
        add_menu_page(
            'zedium-admin-page',
            'Coin Options',
            'manage_options',
            'zedium-live-coin',
            [$this, 'menuRenderCallback']
        );
    }

    public function menuRenderCallback(){
        echo '<div class="wrap">';
        (new Dashboard())->zedium_dashboard_widget_render_callback();
        echo '</div>';
    }
}