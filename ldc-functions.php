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

    if(!function_exists('ldc_clone_role')){
		function ldc_clone_role($source_role = '', $destination_role = '', $display_name = ''){
            if($source_role and $destination_role and $display_name){
                $role = get_role($source_role);
                $capabilities = $role->capabilities;
                add_role($destination_role, $display_name, $capabilities);
            }
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

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    if(!function_exists('ldc_is_mysql_date')){
		function ldc_is_mysql_date($pattern = ''){
            if(preg_match('/^\d{4}-\d{2}-\d{2}\s{1}\d{2}:\d{2}:\d{2}$/', $pattern)){
                return true;
            }
			return false;
		}
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    if(!function_exists('ldc_is_response')){
		function ldc_is_response($response = array()){
            if(is_array($response) and isset($response['data'], $response['message'], $response['success'])){
                return true;
            }
			return false;
		}
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    if(!function_exists('ldc_is_success')){
		function ldc_is_success($code = 0){
			if($code == 200){
				return true;
			}
			return false;
		}
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    if(!function_exists('ldc_maybe_json_decode_response')){
		function ldc_maybe_json_decode_response($response = array(), $assoc = false, $depth = 512, $options = 0){
			if(ldc_is_response($response)){
                if(is_string($response['data']) and preg_match('/^\{\".*\"\:.*\}$/', $response['data'])){
                    $data = json_decode($response['data'], $assoc, $depth, $options);
    				if(json_last_error() == JSON_ERROR_NONE){
    					$response['data'] = $data;
    				} else {
    					$response['message'] = json_last_error_msg();
    					$response['success'] = false;
    				}
                }
			}
			return $response;
		}
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    if(!function_exists('ldc_maybe_merge_response')){
		function ldc_maybe_merge_response($response = array()){
            if(ldc_is_response($response)){
                $data = $response['data'];
                if(ldc_is_response($data)){
                    $data = ldc_maybe_json_decode_response($data);
                    $response['data'] = $data['data'];
                    $response['message'] = $data['message'];
                    $response['success'] = $data['success'];
                }
            }
            return $response;
		}
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

    if(!function_exists('ldc_parse_response')){
		function ldc_parse_response($response = null){
			if(is_a($response, 'Requests_Exception')){
				return ldc_response_error('', $response->getMessage());
			} elseif(is_a($response, 'Requests_Response')){
				$response_body = $response->body;
				$response_code = $response->status_code;
				$response_message = get_status_header_desc($response_code);
				if(ldc_is_success($response_code)){
					return ldc_response_success($response_body, $response_message);
				} else {
					return ldc_response_error($response_body, $response_message);
				}
			} elseif(is_wp_error($response)){
				return ldc_response_error('', $response->get_error_message());
			} else {
				$response_body = wp_remote_retrieve_body($response);
				$response_code = wp_remote_retrieve_response_code($response);
                if($response_body and $response_code){
                    $response_message = wp_remote_retrieve_response_message($response);
    				if(!$response_message){
    					$response_message = get_status_header_desc($response_code);
    				}
    				if(ldc_is_success($response_code)){
    					return ldc_response_success($response_body, $response_message);
    				} else {
    					return ldc_response_error($response_body, $response_message);
    				}
                }
			}
            return ldc_response_error('', __('Invalid object type.'));
		}
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	if(!function_exists('ldc_response')){
		function ldc_response($data = '', $message = '', $success = false){
			if(!$message){
                if($success){
                    $message = 'OK';
                } else {
                    $message = __('Something went wrong.');
                }
			}
            $response = array(
				'data' => $data,
				'message' => $message,
				'success' => $success,
			);
            $response = ldc_maybe_json_decode_response($response);
            $response = ldc_maybe_merge_response($response);
			return $response;
		}
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	if(!function_exists('ldc_response_error')){
		function ldc_response_error($data = '', $message = ''){
			return ldc_response($data, $message, false);
		}
	}

    // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	if(!function_exists('ldc_response_success')){
		function ldc_response_success($data = '', $message = ''){
			return ldc_response($data, $message, true);
		}
	}
