<?php

    defined('ABSPATH') or die('No script kiddies please!');
    class LDC_Plugin extends LDC_Plugin_Base {

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    static public function get_name(){
        return str_replace('_', ' ', get_called_class());
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    static public function init($file = '', $version = ''){
        $plugin_update_checker = plugin_dir_path($file) . 'includes/plugin-update-checker-4.9/plugin-update-checker.php';
        if(is_file($plugin_update_checker)){
            if(!class_exists('Puc_v4_Factory', false)){
                require_once($plugin_update_checker);
            }
        }
        if(parent::init($file, $version)){
            self::add_setting(self::get_slug(), array(
                'name' => sprintf(__('%1$s is proudly powered by %2$s'), self::get_name(), '<a href="https://luisdelcid.com" target="_blank">Luis del Cid</a>'),
    			'std' => '<a class="button" href="https://luisdelcid.com" target="_blank">luisdelcid.com</a>',
    			'type' => 'custom_html',
            ));
            do_action('ldc_plugin_loaded');
        }
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    static public function is_current_screen(){
        if(is_admin()){
            $current_screen = get_current_screen();
            if($current_screen){
                if(str_replace('toplevel_page_', '', $screen->id) === self::get_slug() or strpos($screen->id, 'ldc-plugin_page_') === 0){
                    return true;
                }
            }
        }
        return false;
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    static public function add_settings_page(){
        if(!self::$settings_page){
            self::$settings_page = array(
                'columns' => 1,
                'icon_url' => 'data:image/svg+xml;base64,PHN2ZyBpZD0iTGF5ZXJfMSIgZGF0YS1uYW1lPSJMYXllciAxIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA3OTQuMjIgNDQ4LjExIj48ZGVmcz48c3R5bGU+LmNscy0xe2ZpbGw6I2ZmZjt9PC9zdHlsZT48L2RlZnM+PHRpdGxlPmxkYy00czwvdGl0bGU+PHBhdGggY2xhc3M9ImNscy0xIiBkPSJNOTA3LjE3LDU0NC4xMUE5Niw5NiwwLDEsMSw5NzQuOCwzODBsNDUuMjYtNDUuMjZhMTYwLDE2MCwwLDEsMCwuNSwyMjYuMjdMOTc1LjMsNTE1Ljc0QTk1LjczLDk1LjczLDAsMCwxLDkwNy4xNyw1NDQuMTFaIiB0cmFuc2Zvcm09InRyYW5zbGF0ZSgtMjM1IC0xNjApIi8+PHBvbHlnb24gY2xhc3M9ImNscy0xIiBwb2ludHM9Ijc3OC40OSA0MTcuNzIgNzc4LjQ4IDQxNy43MyA3NzguNDkgNDE3LjcyIDc3OC40OSA0MTcuNzIiLz48Y2lyY2xlIGNsYXNzPSJjbHMtMSIgY3g9Ijc2Mi4yMiIgY3k9IjE5Ny44MSIgcj0iMzIiLz48Y2lyY2xlIGNsYXNzPSJjbHMtMSIgY3g9Ijc2Mi4yMiIgY3k9IjM3OC44MyIgcj0iMzIiLz48cmVjdCBjbGFzcz0iY2xzLTEiIHdpZHRoPSI2NCIgaGVpZ2h0PSI0NDgiIHJ4PSIzMiIvPjxwYXRoIGNsYXNzPSJjbHMtMSIgZD0iTTUyMywyODcuNzVhMTYwLDE2MCwwLDEsMCwxNjAsMTYwQTE2MCwxNjAsMCwwLDAsNTIzLDI4Ny43NVptMCwyNTZhOTYsOTYsMCwxLDEsOTYtOTZBOTYsOTYsMCwwLDEsNTIzLDU0My43NVoiIHRyYW5zZm9ybT0idHJhbnNsYXRlKC0yMzUgLTE2MCkiLz48cmVjdCBjbGFzcz0iY2xzLTEiIHg9IjM4NCIgd2lkdGg9IjY0IiBoZWlnaHQ9IjQ0OCIgcng9IjMyIi8+PC9zdmc+',
                'id' => self::get_slug(),
                'menu_title' => self::get_name(),
                'option_name' => self::get_option_name(),
                'page_title' => __('General Settings'),
                'style' => 'no-boxes',
                'submenu_title' => __('General'),
                'tabs' => array(),
                'tab_style' => 'left',
            );
        }
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    }
