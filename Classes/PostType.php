<?php

namespace Zedium\Classes;

class PostType
{
    private $metaBoxes;

    /*
     * Inject metabox object to render it when cutome post type is registered
     */
    function __construct($metaBoxes){
        $this->metaBoxes = $metaBoxes;
        add_action('init', [$this, 'registerPostType']);
    }

    public function registerPostType(){

        register_post_type(
            ZEDIUM_POST_TYPE_NAME,
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
                'supports'=>['title']
            )
        );

        $this->metaBoxes->render();

    }
}