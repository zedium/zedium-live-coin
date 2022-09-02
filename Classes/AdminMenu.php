<?php

namespace Zedium\Classes;

/**
 * Add admin menu and corresponding page to it
 */
class AdminMenu
{
    public function __construct(){

        add_action('admin_menu', [$this, 'registerAdminMenu']);

    }

    /**
     * register custom menu to wp menu list
     * @return void
     */
    public function registerAdminMenu(){

        add_menu_page(
            'zedium-admin-page',
            'Coin List',
            'manage_options',
            'zedium-live-coin',
            [$this, 'menuRenderCallback']
        );

    }

    /**
     * Render the coins table to new created page
     * @return void
     */
    public function menuRenderCallback(){

        echo '<div class="wrap">';
        (new Dashboard())->zedium_dashboard_widget_render_callback();
        echo '</div>';

    }
}