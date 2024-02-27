<?php
    // add_action('admin_menu', function() { StylesAndScripts::enqueueFiles(); });
    class StylesAndScripts {
        public static function enqueueFiles() {
            wp_enqueue_style('style', plugin_dir_url(__FILE__) . 'style.css');
            wp_enqueue_script('script', plugin_dir_url(__FILE__) . 'script.js');
        }
    }

    //DataLoader::init('GLOBALS_FELD_NAME', 'NAME.json');
    class DataLoader {
        public static function loadData($filename) {
            $json_content = file_get_contents(plugin_dir_path(__FILE__) . 'data/' . $filename);
            return json_decode($json_content, true);
        }
    
        public static function init($feld, $dateiname) {
            $GLOBALS[$feld] = self::loadData($dateiname);
        }
    }

    // add_action('admin_menu', function() { ExportMenu::addMenu('NAME', 'NAME_CODE'); });
    class ExportMenu {
        public static function addMenu($page_title, $menu_slug) {
            $hook = add_menu_page(
                $page_title,
                $page_title,
                'edit_posts',
                $menu_slug,
                '__return_true',
                'dashicons-media-spreadsheet'
            );
    
            if ($hook) {
                add_action("load-$hook", array('ExportMenu', 'exportCallback'));
            }
        }
    
        //Hier muss die Seite Dynamisch rein statt Export?!?!
        public static function exportCallback() {
            include plugin_dir_path(__FILE__) . 'php/crndi_export.php';
        }
    }
?>