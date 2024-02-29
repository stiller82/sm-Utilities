<?php
	/*
	Plugin Name: Utilities
	Description: Diverse Tools zum deaktivieren von Menüpunkten
	Version: 1.43
	Author: Stefan Stiller | stiller media
	Author URI: https://www.stillermedia.de/
	*/

	if ( !defined( 'WPINC' ) ) { die; }
	error_reporting(0);

	require_once(plugin_dir_path(__FILE__) . 'functions.php');

	$SaS = new \sm_utilities\StylesAndScripts();
	add_action('admin_menu', function() use ($SaS) { $SaS->enqueueFiles(); });

	global $wpdb;
	global $init_db;
	$DL = new \sm_utilities\DataLoader();
	$init_db = $DL->loadData('init_db');

	global $table;
	$table = $wpdb->prefix . "sm_utilities";

	function sm_utilities_install() {
		global $wpdb;
		global $table;
		global $init_db;

		$sql = "CREATE TABLE IF NOT EXISTS ".$table."(
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`name` varchar(255) NOT NULL,
			`beschreibung` varchar(255) NOT NULL,
			`befehl` varchar(255) NOT NULL,
			`bereich` varchar(255) NOT NULL,
			`status` boolean NOT NULL, PRIMARY KEY (id)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);

		$wpdb->get_results("SELECT *  FROM ".$table);
		if(!$wpdb->num_rows) {
			foreach($init_db as $row){ 
				$wpdb->insert($table, $row); 
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
		global $table;

		$functions = $wpdb->get_results("SELECT *  FROM ".$table." WHERE bereich = 'Allgemein'");
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
		global $table;

		$functions = $wpdb->get_results("SELECT *  FROM ".$table." WHERE bereich = 'Menü'");
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
		global $table;

		$functions = $wpdb->get_results("SELECT *  FROM ".$table." WHERE bereich = 'Top'");
		foreach($functions as $funcion) {
			if($funcion->status == 0) { 
				$wp_admin_bar->remove_node( $funcion->befehl );
			}
		}
	}
	add_action( 'admin_bar_menu', 'sm_utilities_adminBar', 999 );
?>