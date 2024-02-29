<?php
    // Version 1.1
    
    // add_action('admin_menu', function() { StylesAndScripts::enqueueFiles(); });
    class StylesAndScripts {
        public static function enqueueFiles() {
            wp_enqueue_style('style', plugin_dir_url(__FILE__) . 'style.css');
            wp_enqueue_style('style_bknd', plugin_dir_url(__FILE__) . 'style_bknd.css');
            wp_enqueue_script('script', plugin_dir_url(__FILE__) . 'script.js');
            wp_enqueue_script('script_bknd', plugin_dir_url(__FILE__) . 'script_bknd.js');
        }
    }

    //DataLoader::init('GLOBALS_FELD_NAME', 'NAME.json');
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