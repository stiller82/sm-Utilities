<?php
    namespace sm_utilities;
    // Version 1.1
    
	//$SaS = new \sm_utilities\StylesAndScripts();
	//add_action('admin_menu', function() use ($SaS) { $SaS->enqueueFiles(); });
    class StylesAndScripts {
        public static function enqueueFiles() {
            wp_enqueue_style('style', plugin_dir_url(__FILE__) . 'style.css');
            wp_enqueue_style('style_bknd', plugin_dir_url(__FILE__) . 'style_bknd.css');
            wp_enqueue_script('script', plugin_dir_url(__FILE__) . 'script.js');
            wp_enqueue_script('script_bknd', plugin_dir_url(__FILE__) . 'script_bknd.js');
        }
    }

	//$DL = new \sm_utilities\DataLoader();
	//$DL->init('init_db', 'init_db');
    class DataLoader {
        public static function loadData($filename) {
            $json_content = file_get_contents(plugin_dir_path(__FILE__) . 'data/' . $filename .'.json');
            return json_decode($json_content, true);
        }
    
        public static function init($feld, $dateiname) {
            $GLOBALS[$feld] = self::loadData($dateiname);
        }
    }
?>