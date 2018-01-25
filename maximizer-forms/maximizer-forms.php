<?php
 require_once( plugin_dir_path( __FILE__ ) . 'model/maximizer-model.php');
/*
  Plugin Name: Maximizer - Direct Maximizer Web To lead forms
  Plugin URI: http://maximizer.com/
  Description: Maximizer plugin makes web to lead form more easy to access creating forms with designs that can be added anywhere.
  Version: 1.0
  Author: NexusBond Asia Inc.
  Author URI: http://Nexusbond.com
  License: GPLv2+
  Text Domain:  Maximizer
*/
  class WP_Maximizer_Forms{

  // Constructor
  	
    function __construct() {
		    add_action( 'admin_enqueue_scripts', array( $this, 'my_admin_bootstrap') );
        add_action( 'admin_enqueue_scripts', array( $this, 'wpa_styles') );
        add_action( 'admin_menu', array( $this, 'wpa_add_menu' ));
        add_action( 'admin_enqueue_scripts', array( $this, 'my_admin_dragable') );
        add_action( 'admin_enqueue_scripts', array( $this, 'my_admin_jquery') );
        add_action( 'admin_enqueue_scripts', array( $this, 'my_admin_scripts') );
        add_action( 'admin_enqueue_scripts', array( $this, 'my_admin_bootstrap_js') );
        register_activation_hook( __FILE__, array( $this, 'wpa_install' ) );
        register_deactivation_hook( __FILE__, array( $this, 'wpa_uninstall' ) );
    }
	public function wpa_styles( $page ) {

	    wp_enqueue_style( 'wp-analytify-style', plugins_url('css/maximizer-style.css', __FILE__));
	}

  public function my_admin_bootstrap() {
      wp_enqueue_style('wp-bootstrap-style', plugins_url('lib/bootstrap-3.3.7-dist/css/bootstrap.min.css', __FILE__));
  }
	public function my_admin_jquery() {
//		    wp_enqueue_script( 'my_jquery.js', plugin_dir_url( __FILE__ ) . '/lib/jquery-ui/external/jquery/jquery.js', array( 'jquery' ),'1.0.0',true);
	}

	public function my_admin_scripts() {
		    wp_enqueue_script( 'maximizer', plugin_dir_url( __FILE__ ) . '/js/maximizer.js', array( 'jquery' ), '1.0.0', true );
	}
  public function my_admin_dragable() {
        wp_enqueue_script( 'jquery-ui', plugin_dir_url( __FILE__ ) . '/lib/jquery-ui/jquery-ui.min.js', array( 'jquery' ), '1.0.0', true );
  }
   public function my_admin_bootstrap_js() {
        wp_enqueue_script( 'wp-bootstrap-js', plugin_dir_url( __FILE__ ) . 'lib/bootstrap-3.3.7-dist/js/bootstrap.min.js', array( 'jquery' ), '1.0.0', true );
  }

    /*
      * Actions perform at loading of admin menu
      */
    function wpa_add_menu() {
        add_menu_page( 'Analytify simple', 'Maximizer', 'manage_options', 'analytify-dashboard', array(
                          __CLASS__,
                         'wpa_page_file_path'
                        ), plugins_url('images/wp-analytics-logo.png', __FILE__),'2.2.9');
     
    }
    /*
     * Actions perform on loading of menu pages
     */
    function wpa_page_file_path() {
      $model = new Model();
        wp_localize_script('maximizer', 'object_name', array( 'plugin_url' => plugins_url() ));
       if(isset($_GET['mypage'])) {
         		$viewpage = $_GET['mypage'];
     	 }else{
     	 		$viewpage = 'maximizer-main';
     	 }
        $model->transactions($viewpage);
        if(!isset($_POST['ids'])){
            include(plugin_dir_path(__FILE__) . 'views/' . $viewpage . '.php');
        }
    }

    /*
     * Actions perform on activation of plugin
     */
    function wpa_install() {
      require_once( plugin_dir_path( __FILE__ ) . 'database/maximizer-database.php');
    }

    /*
     * Actions perform on de-activation of plugin
     */
    function wpa_uninstall() {



    }
  
}
new WP_Maximizer_Forms();
?>