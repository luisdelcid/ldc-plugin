<?php
/**
 * Author: Luis del Cid
 * Author URI: https://luisdelcid.com
 * Description: Just another WordPress plugin.
 * Domain Path:
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Network:
 * Plugin Name: LDC Plugin
 * Plugin URI: https://github.com/luisdelcid/ldc-plugin
 * Text Domain: ldc-plugin
 * Version: 0.3.10.8
 */

    defined('ABSPATH') or die('No script kiddies please!');
    require_once(plugin_dir_path(__FILE__) . 'ldc-functions.php');
    if(!class_exists('LDC_Plugin_Base', false)){
        require_once(plugin_dir_path(__FILE__) . 'classes/class-ldc-plugin-base.php');
        if(!class_exists('LDC_Plugin', false)){
            require_once(plugin_dir_path(__FILE__) . 'classes/class-ldc-plugin.php');
            LDC_Plugin::init(__FILE__, '0.3.10.8');
        }
    }
