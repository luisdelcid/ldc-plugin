<?php

    defined('ABSPATH') or die('No script kiddies please!');

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    if(!function_exists('ldc_attachment_url_to_postid')){
		function ldc_attachment_url_to_postid($url = ''){
			if($url){
				/** original */
				$post_id = ldc_guid_to_postid($url);
				if($post_id){
					return $post_id;
				}
                /** resized */
				preg_match('/^(.+)(-\d+x\d+)(\.' . substr($url, strrpos($url, '.') + 1) . ')?$/', $url, $matches);
				if($matches){
					$url = $matches[1];
					if(isset($matches[3])){
						$url .= $matches[3];
					}
                    $post_id = ldc_guid_to_postid($url);
    				if($post_id){
    					return $post_id;
    				}
				}
				/** scaled */
				preg_match('/^(.+)(-scaled)(\.' . substr($url, strrpos($url, '.') + 1) . ')?$/', $url, $matches);
				if($matches){
					$url = $matches[1];
					if(isset($matches[3])){
						$url .= $matches[3];
					}
                    $post_id = ldc_guid_to_postid($url);
    				if($post_id){
    					return $post_id;
    				}
				}
				/** edited */
				preg_match('/^(.+)(-e\d+)(\.' . substr($url, strrpos($url, '.') + 1) . ')?$/', $url, $matches);
				if($matches){
					$url = $matches[1];
					if(isset($matches[3])){
						$url .= $matches[3];
					}
                    $post_id = ldc_guid_to_postid($url);
    				if($post_id){
    					return $post_id;
    				}
				}
			}
			return 0;
		}
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    if(!function_exists('ldc_base64_urldecode')){
		function ldc_base64_urldecode($data = '', $strict = false){
			return base64_decode(strtr($data, '-_', '+/'), $strict);
		}
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    if(!function_exists('ldc_base64_urlencode')){
		function ldc_base64_urlencode($data = ''){
			return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
		}
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    if(!function_exists('ldc_format_admin_notice')){
		function ldc_format_admin_notice($admin_notice = '', $class = '', $is_dismissible = false){
            if($admin_notice){
    			if(!in_array($class, array('error', 'warning', 'success', 'info'))){
    				$class = 'warning';
    			}
    			if($is_dismissible){
    				$class .= ' is-dismissible';
    			}
    			$admin_notice = '<div class="notice notice-' . $class . '"><p>' . $admin_notice . '</p></div>';
    		}
            return $admin_notice;
		}
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    if(!function_exists('ldc_format_function')){
		function ldc_format_function($function_name = '', $args = array()){
			$str = '';
			if($function_name){
				$str .= '<div style="color: #24831d; font-family: monospace; font-weight: 400;">' . $function_name . '(';
				$function_args = array();
				if($args){
					foreach($args as $arg){
						$arg = shortcode_atts(array(
							'default' => 'null',
							'name' => '',
							'type' => '',
						), $arg);
						if($arg['default'] and $arg['name'] and $arg['type']){
							$function_args[] = '<span style="color: #cd2f23; font-family: monospace; font-style: italic; font-weight: 400;">' . $arg['type'] . '</span> <span style="color: #0f55c8; font-family: monospace; font-weight: 400;">$' . $arg['name'] . '</span> = <span style="color: #000; font-family: monospace; font-weight: 400;">' . $arg['default'] . '</span>';
						}
					}
				}
				if($function_args){
					$str .= ' ' . implode(', ', $function_args) . ' ';
				}
				$str .= ')</div>';
			}
			return $str;
		}
	}

	// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    if(!function_exists('ldc_guid_to_postid')){
		function ldc_guid_to_postid($guid = ''){
            global $wpdb;
			if($guid){
				$str = "SELECT ID FROM $wpdb->posts WHERE guid = %s";
				$sql = $wpdb->prepare($str, $guid);
				$post_id = $wpdb->get_var($sql);
				if($post_id){
					return (int) $post_id;
				}
			}
			return 0;
		}
	}
