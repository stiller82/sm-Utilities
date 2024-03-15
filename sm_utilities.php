<?php
	/*
	Plugin Name: Utilities
	Description: Diverse Tools zum deaktivieren von Menüpunkten
	Version: 1.61
	Author: Stefan Stiller | stiller media
	Author URI: https://www.stillermedia.de/
	*/

	if ( !defined( 'WPINC' ) ) { die; }
	error_reporting(0);

	require_once(plugin_dir_path(__FILE__) . 'functions.php');

	//Klasse zum erzeugen von Globalen Variablen
	class sm_utilities {
		public static $table;
		public static $DL;
		public static $initDB;
		public static $version;

		public static function init() {
			global $wpdb;

			self::$version = "1.6";

			$DL = new \sm_utilities\DataLoader();
			self::$initDB = $DL->loadData('init_db');

			self::$table = $wpdb->prefix . "sm_utilities";

			$SaS = new \sm_utilities\StylesAndScripts();
			add_action('admin_menu', function() use ($SaS) { $SaS->enqueueFiles("sm_utilities_"); });
		}
	}
	add_action('init', array('sm_utilities', 'init'));

	function sm_utilities_install() {
		global $wpdb;

		$sql = "CREATE TABLE IF NOT EXISTS ".sm_utilities::$table."(
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`name` varchar(255) NOT NULL,
			`beschreibung` varchar(255) NOT NULL,
			`befehl` varchar(255) NOT NULL,
			`bereich` varchar(255) NOT NULL,
			`status` boolean NOT NULL, PRIMARY KEY (id)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);

		$wpdb->get_results("SELECT *  FROM ".sm_utilities::$table);
		if(!$wpdb->num_rows) {
			foreach(sm_utilities::$initDB as $row){ 
				$wpdb->insert(sm_utilities::$table, $row); 
			}
		}
	}
	register_activation_hook(__FILE__, 'sm_utilities_install');

	function sm_utilities_bkndMain() {
		add_menu_page(
			'Utilities',
			'Utilities',
			'manage_options',
			'sm_utilities_plugin',
				function() { include 'php/bknd_settings.php'; },
			'dashicons-admin-tools',
			'2'
		);
	}
	add_action( 'admin_menu', 'sm_utilities_bkndMain' );

	function sm_utilities_main() {
		global $wpdb;

		$functions = $wpdb->get_results("SELECT *  FROM ".sm_utilities::$table." WHERE bereich = 'Allgemein'");
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
	add_action('init', 'sm_utilities_main');

	function sm_utilities_adminInit() {
		global $wpdb;

		$functions = $wpdb->get_results("SELECT *  FROM ".sm_utilities::$table." WHERE bereich = 'Menü'");
		foreach($functions as $funcion) {
			if($funcion->status == 0) { 
				remove_menu_page($funcion->befehl); 
			}
		}
	}
	add_action('admin_init', 'sm_utilities_adminInit');

	function sm_utilities_adminBar() {
		global $wp_admin_bar;
		global $wpdb;

		$functions = $wpdb->get_results("SELECT *  FROM ".sm_utilities::$table." WHERE bereich = 'Top'");
		foreach($functions as $funcion) {
			if($funcion->status == 0) { 
				$wp_admin_bar->remove_node( $funcion->befehl );
			}
		}
	}
	add_action( 'admin_bar_menu', 'sm_utilities_adminBar', 999 );
?>