<?php
	/*
	Plugin Name: WP Utilities
	Description: Plugin zum deaktivieren diverser Menüpunkte in Wordpress zur übersichtlicheren Gestaltung des Admin bereiches
	Version: 2.0
	Author: Stefan Stiller | stiller media
	Author URI: https://www.stillermedia.de/
	*/

	if ( !defined( 'WPINC' ) ) { die; }
	error_reporting(0);

	class wp_utilities {
		public static $table;
		public static $initDB;
		public static $version;

		public static function init() {
			global $wpdb;

			self::$version = "2.0";

            $json_content = file_get_contents(plugin_dir_path(__FILE__) . 'data/init_db.json');
            self::$initDB = json_decode($json_content, true);

			self::$table = $wpdb->prefix . "wp_utilities";
		}
	}
	add_action('init', array('wp_utilities', 'init'));
	
/******************************************************************************************************* */

	function wp_utilities_admFiles(){
		wp_enqueue_style( 'wp_utilities_style', plugin_dir_url(__FILE__) . 'styles/style_bknd.css');
	}
	add_action( 'admin_enqueue_scripts', 'wp_utilities_admFiles' );

/******************************************************************************************************* */	

	function wp_utilities_install() {
		wp_utilities::init();
		global $wpdb;

		$sql = "CREATE TABLE IF NOT EXISTS ".wp_utilities::$table."(
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`name` varchar(255) NOT NULL,
			`beschreibung` varchar(255) NOT NULL,
			`befehl` varchar(255) NOT NULL,
			`bereich` varchar(255) NOT NULL,
			`status` boolean NOT NULL, PRIMARY KEY (id)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);

		$wpdb->get_results("SELECT *  FROM ".wp_utilities::$table);
		if(!$wpdb->num_rows) {
			foreach(wp_utilities::$initDB as $row){ 
				$wpdb->insert(wp_utilities::$table, $row); 
			}
		}
	}
	register_activation_hook(__FILE__, 'wp_utilities_install');

/******************************************************************************************************* */

	function wp_utilities_admMainMenu() {
		add_menu_page(
			'WP Utilities',
			'WP Utilities',
			'manage_options',
			'sm_utilities_plugin',
				function() { include 'php/bknd_settings.php'; },
			'dashicons-admin-tools',
			'99'
		);
	}
	add_action( 'admin_menu', 'wp_utilities_admMainMenu' );

/******************************************************************************************************* */

	function wp_utilities_initFunctions() {
		global $wpdb;

		$functions = $wpdb->get_results("SELECT *  FROM ".wp_utilities::$table." WHERE bereich = 'Allgemein'");
		foreach($functions as $funcion) {
			if($funcion->name == "UpdateMail" && $funcion->status == 0 ) {
				add_filter( 'auto_plugin_update_send_email', '__return_false' );
				add_filter( 'auto_theme_update_send_email', '__return_false' );
			}

			if($funcion->name == "Gutenberg" && $funcion->status == 0 ) {
				add_filter('use_block_editor_for_post', '__return_false');
				add_filter('use_block_editor_for_post_type', '__return_false');
			}
		}
	}
	add_action('init', 'wp_utilities_initFunctions');

/******************************************************************************************************* */

	function wp_utilities_viewMenuSide() {
		global $wpdb;

		$functions = $wpdb->get_results("SELECT *  FROM ".wp_utilities::$table." WHERE bereich = 'Menü'");
		foreach($functions as $funcion) {
			if($funcion->status == 0) { 
				remove_menu_page($funcion->befehl); 
			}
		}
	}
	add_action('admin_init', 'wp_utilities_viewMenuSide');

/******************************************************************************************************* */

	function wp_utilities_viewMenuBar() {
		global $wp_admin_bar;
		global $wpdb;

		$functions = $wpdb->get_results("SELECT *  FROM ".wp_utilities::$table." WHERE bereich = 'Top'");
		foreach($functions as $funcion) {
			if($funcion->status == 0) { 
				$wp_admin_bar->remove_node( $funcion->befehl );
			}
		}
	}
	add_action( 'admin_bar_menu', 'wp_utilities_viewMenuBar', 999 );
?>