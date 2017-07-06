<?php

/**
* Admin Functionality class
*
* @since 1.0.0
*/
class WC_Mailerlite_Admin {

    /**
    * Autometically loaded when class initiate
    *
    * @since 1.0.0
    */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'load_mailerlite_menu' ), 40 );
    }


    /**
     * Initializes the WC_Mailerlite_Admin() class
     *
     * Checks for an existing WC_Mailerlite_Admin() instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        static $instance = false;

        if ( ! $instance ) {
            $instance = new WC_Mailerlite_Admin();
        }

        return $instance;
    }

    /**
    * Get menu tab
    *
    * @since 1.0.0
    *
    * @return void
    **/
    public function get_nav_tab() {
        $base_url = add_query_arg( array( 'page' => 'wc-mailerlite' ), admin_url( 'admin.php' ) );

        $nav_tab = array(
            'integration' => array(
                'title' => __( 'Integration', 'wc-mailerlite' ),
                'url' => $base_url,
                'icon' => 'dashicons-admin-network'
            ),

            'manual' => array(
                'title' => __( 'Manual', 'wc-mailerlite' ),
                'url' => add_query_arg( array( 'tab' => 'manual' ), $base_url ),
                'icon' => 'dashicons-yes'
            ),

            'autometic' => array(
                'title' => __( 'Autometic', 'wc-mailerlite' ),
                'url' => add_query_arg( array( 'tab' => 'autometic' ), $base_url ),
                'icon' => 'dashicons-update'
            )
        );

        return apply_filters( 'wc_mailerlite_get_admin_page_nav', $nav_tab, $base_url );
    }

    /**
    * Load mailerlite menu
    *
    * @since 1.0.0
    *
    * @return void
    **/
    public function load_mailerlite_menu() {
        add_submenu_page( 'woocommerce', 'WooCommerce Mailerlite', 'Mailerlite', 'manage_options', 'wc-mailerlite', array( $this, 'load_page_content' ) );
    }

    /**
    * Load mailerlite submenu page content
    *
    * @since 1.0.0
    *
    * @return void
    **/
    public function load_page_content() {
        $nav_tab = $this->get_nav_tab();
        $tab = ( isset( $_GET['tab'] ) && !empty( $_GET['tab'] ) ) ? $_GET['tab'] : 'integration';
        ?>
        <div class="wrap">
            <h2 class="nav-tab-wrapper">
                <?php foreach ( $nav_tab as $key => $nav ): ?>
                    <?php
                    $active_class = ( $tab == $key ) ? 'nav-tab-active' : '';
                    ?>
                    <a href="<?php echo $nav['url']; ?>" class="nav-tab <?php echo $active_class; ?>" id="wc-mailerlite-<?php echo $key; ?>">
                        <span class="nav-dashicons-icons dashicons <?php echo $nav['icon']; ?>"></span>
                        <?php echo $nav['title'] ?>
                    </a>
                <?php endforeach ?>
            </h2>
            <div class="nav-tab-content">
                <?php
                    switch ( $tab ) {
                        case 'integration':
                            require_once WC_MAILERLITE_INC_PATH . '/views/integration.php';
                            break;

                        case 'manual':
                            require_once WC_MAILERLITE_INC_PATH . '/views/manual.php';
                            break;

                        default:
                            do_action( 'wc_mailerlite_load_nav_content', $tab );
                            break;
                    }
                ?>
            </div>
        </div>
        <style>
            .nav-dashicons-icons {
                width: 15px;
                height: 15px;
                font-size: 16px;
                margin-top: 5px;
                color: #333;
            }

            .nav-tab-content {
                margin-top: 25px ;
            }
        </style>
        <?php
    }

}