<?php

    defined('ABSPATH') or die('No script kiddies please!');
    class LDC_Plugin_Base {

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    private static $admin_notices = array(), $called_class = '', $file = '', $meta_boxes = array(), $settings_page = array(), $version = '';

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	static public function add_admin_notice($admin_notice = '', $class = 'error', $is_dismissible = false){
		self::$admin_notices[] = ldc_format_admin_notice($admin_notice, $class, $is_dismissible);
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    static public function add_setting($id = '', $setting = array(), $tab = ''){
        $id = sanitize_title($id);
        if($id){
            if(!self::$settings_page){
                self::$settings_page = self::$called_class::get_settings_page();
            }
            if(!$tab){
                $tab = __('General');
            }
            $tab = wp_strip_all_tags($tab);
            $tab_id = self::$called_class::get_slug() . '-' . sanitize_title($tab);
            if(empty(self::$settings_page['tabs'][$tab_id])){
				self::$settings_page['tabs'][$tab_id] = $tab;
			}
			if(empty(self::$meta_boxes[$tab_id])){
				self::$meta_boxes[$tab_id] = array(
					'fields' => array(),
					'id' => $tab_id,
					'settings_pages' => self::$called_class::get_slug(),
					'tab' => $tab_id,
					'title' => $tab,
				);
			}
			if(empty($setting['columns'])){
				$setting['columns'] = 12;
			}
            $setting['id'] = $id;
			self::$meta_boxes[$tab_id]['fields'][$id] = $setting;
        }
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	static public function admin_notices(){
        if(self::$admin_notices){
            foreach(self::$admin_notices as $admin_notice){
                echo $admin_notice;
            }
        }
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    static public function after_setup_theme(){
        add_action('admin_notices', array(self::$called_class, 'admin_notices'));
        add_filter('mb_settings_pages', array(self::$called_class, 'mb_settings_pages'));
        add_filter('rwmb_meta_boxes', array(self::$called_class, 'rwmb_meta_boxes'));
    }

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    static public function get_assets_path(){
        return self::$called_class::get_dir_path() . 'assets/';
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    static public function get_assets_url(){
        return self::$called_class::get_dir_url() . 'assets/';
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    static public function get_basename(){
        return plugin_basename(self::$file);
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    static public function get_dir_path(){
        return plugin_dir_path(self::$file);
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    static public function get_dir_url(){
        return plugin_dir_url(self::$file);
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    static public function get_github_url(){
        return 'https://github.com/luisdelcid/' . self::$called_class::get_slug();
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    static public function get_includes_path(){
        return self::$called_class::get_dir_path() . 'includes/';
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    static public function get_includes_url(){
        return self::$called_class::get_dir_url() . 'includes/';
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    static public function get_name(){
        return str_replace('_', ' ', str_replace('LDC_', '', self::$called_class));
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    static public function get_option_name(){
        return str_replace('-', '_', self::$called_class::get_slug());
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    static public function get_setting($id = ''){
        if(function_exists('rwmb_meta')){
            return rwmb_meta($id, array(
                'object_type' => 'setting',
            ), self::$called_class::get_option_name());
        }
        return false;
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    static public function get_settings_page(){
        return array(
            'columns' => 1,
            'id' => self::$called_class::get_slug(),
            'menu_title' => self::$called_class::get_name(),
            'option_name' => self::$called_class::get_option_name(),
            'page_title' => self::$called_class::get_name() . ' &#8212; ' . __('Settings'),
            'parent' => 'ldc-plugin',
            'style' => 'no-boxes',
            'tabs' => array(),
            'tab_style' => 'left',
        );
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    static public function get_slug(){
        return str_replace('_', '-', strtolower(self::$called_class));
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    static public function get_version(){
        return self::$version;
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    static public function init($file = '', $version = ''){
        $called_class = get_called_class();
        if(is_file($file) and version_compare($version, '0') > 0 and is_subclass_of($called_class, get_class())){
            self::$called_class = $called_class;
            self::$file = $file;
            self::$version = $version;
            if(class_exists('Puc_v4_Factory', false)){
                Puc_v4_Factory::buildUpdateChecker(self::$called_class::get_github_url(), self::$file, self::$called_class::get_slug());
            }
            add_action('after_setup_theme', array(self::$called_class, 'after_setup_theme'));
            return true;
        }
        return false;
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    static public function is_current_screen(){
        if(is_admin()){
            $current_screen = get_current_screen();
            if($current_screen){
                if(str_replace('ldc-plugin_page_', '', $screen->id) === self::$called_class::get_slug()){
                    return true;
                }
            }
        }
        return false;
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    static public function mb_settings_pages($settings_pages){
        if(self::$settings_page){
            ksort(self::$settings_page['tabs']);
            $general_id = sanitize_title(__('General'));
            if(!empty(self::$settings_page['tabs'][$general_id])){
                $general = self::$settings_page['tabs'][$general_id];
                unset(self::$settings_page['tabs'][$general_id]);
                self::$settings_page['tabs'] = array_merge(array(
                    $general_id => $general,
                ), self::$settings_page['tabs']);
            }
            $settings_pages[] = self::$settings_page;
        }
        return $settings_pages;
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    static public function rwmb_meta_boxes($meta_boxes){
        if(is_admin()){
            if(self::$meta_boxes){
                foreach(self::$meta_boxes as $meta_box){
                    $meta_box['fields'] = array_values($meta_box['fields']);
                    $meta_boxes[] = $meta_box;
                }
            }
        }
        return $meta_boxes;
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    }
