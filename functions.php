<?php
    namespace sm_utilities;
    // Version 1.21
    
	//$SaS = new \sm_utilities\StylesAndScripts();
	//add_action('admin_menu', function() use ($SaS) { $SaS->enqueueFiles("SHORT"); });
    class StylesAndScripts {
        public static function enqueueFiles($short) {
            wp_enqueue_style($short.'style', plugin_dir_url(__FILE__) .'styles/style.css');
            wp_enqueue_style($short.'style_bknd', plugin_dir_url(__FILE__) . 'styles/style_bknd.css');
            wp_enqueue_script($short.'script', plugin_dir_url(__FILE__) . 'styles/script.js');
            wp_enqueue_script($short.'script_bknd', plugin_dir_url(__FILE__) . 'styles/script_bknd.js');
        }
    }

	//$DL = new \sm_utilities\DataLoader();
	//$DL->loadData('init_db');
    class DataLoader {
        public static function loadData($filename) {
            $json_content = file_get_contents(plugin_dir_path(__FILE__) . 'data/' . $filename .'.json');
            return json_decode($json_content, true);
        }
    }
?>