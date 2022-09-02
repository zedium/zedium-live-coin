<?php

namespace Zedium\Classes;

class MetaBoxes
{
    private CustomDB $dbContext;

    public function __construct(CustomDB $dbContext){
        $this->dbContext = $dbContext;
    }
    public function render(){
        add_action('add_meta_boxes', [$this, 'initMetaBoxes']);
        add_action('save_post', [$this, 'savePostAction']);
    }
    public function initMetaBoxes(){

        add_meta_box(
            'zedium_coin_metabox',
            __('Coin properties', ZEDIUM_TEXT_DOMAIN),
            [$this, 'renderMetaBoxesCallback'],
            ZEDIUM_POST_TYPE_NAME,
            'advanced',
            'high'
        );

    }

    public function renderMetaBoxesCallback(){
        $postID = sanitize_text_field( $_GET['post'] ?? 0 );

        $result = $this->dbContext->getMetaBoxByPostID($postID);

        $short_name = '';
        $usd_price = '';
        $market_cap = '';

        if(isset( $result )) {
            $short_name = $result->short_name;
            $usd_price = $result->usd_price;
            $market_cap = $result->market_cap;
        }
        ?>
        <?php echo wp_nonce_field('zedium_nonce', 'zedium_nonce_field'); ?>
        <table>
            <tr>
                <td style="width: 50%">Coin Short Name</td>
                <td><input type="text" size="40" name="short_name" value="<?php echo esc_html( $short_name ) ?>" /></td>
            </tr>
            <tr>
                <td style="width: 50%">Price(USD)</td>
                <td><input type="text" size="40" name="usd_price" value="<?php echo esc_html( $usd_price) ?>"/></td>
            </tr>
            <tr>
                <td style="width: 50%">Market Cap</td>
                <td><input type="text" size="40" name="market_cap" value="<?php echo esc_html( $market_cap) ?>"/></td>
            </tr>
            <!--<tr>
                <td style="width: 50%">Last Update</td>
                <td><input type="text" size="40" name="last_update" /></td>
            </tr>-->
        </table>
        <?php
    }

    public function savePostAction($postID){

        $short_name = '';
        $usd_price = '';
        $market_cap = '';
        $last_update = date(DATE_TIME_FORMAT);

        if( !isset($_POST['zedium_nonce_field']) )
            return $postID;

        $nonce = $_POST['zedium_nonce_field'];

        if( !wp_verify_nonce($nonce , 'zedium_nonce' ) )
            return $postID;

        if( isset($_POST['short_name']) ){
            $short_name = sanitize_text_field( strtoupper( $_POST['short_name'] ));
        }

        if( isset($_POST['usd_price']) ){
            $usd_price = sanitize_text_field( $_POST['usd_price'] );
        }

        if( isset($_POST['market_cap']) ){
            $market_cap = sanitize_text_field( $_POST['market_cap'] );
        }

        /*if( isset($_POST['last_update']) ){
            $short_name = sanitize_text_field( $_POST['last_update'] );
        }*/

        if( !$this->dbContext->isPostMetaExists($postID) ){
            $this->dbContext->insertPostMeta($postID, $short_name, $usd_price, $market_cap, $last_update);
        }
        else{
            $this->dbContext->updatePostMeta($postID, $short_name, $usd_price, $market_cap, $last_update);
        }


    }



}