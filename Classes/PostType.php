<?php

namespace Zedium\Classes;

class PostType
{
    function __construct(){
        add_action('init', [$this, 'registerPostType']);
    }

    public function registerPostType(){

        register_post_type('coin',
            array(
                'labels'      => array(
                    'name'          => __('Coins', ZEDIUM_TEXT_DOMAIN),
                    'singular_name' => __('Coin', ZEDIUM_TEXT_DOMAIN),
                    ),
                'public'      => true,
                'has_archive' => true,
                'rewrite'     => array( 'slug' => 'coins' ),
                'menu_position'=>5,
                'map_meta_cap'=>true,
                supports=>['title']
            )
        );

    }
}