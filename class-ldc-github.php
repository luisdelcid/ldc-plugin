<?php

if(!class_exists('ldc_github')){
	final class ldc_github {

		// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        //
        // public
        //
        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        public function require($owner = '', $repo = '', $release = 'latest'){
			$option = 'ldc_github_library_' . md5($owner . '-' . $repo . '-' . $release);
			$library = get_option($option);
			if($library){
				return $library;
			}
            $url = 'https://api.github.com/repos/' . $owner . '/' . $repo . '/releases/' . $release;
            $response = ldc()->remote($url)->get();
            if($response->success){
                $url = 'https://github.com/' . $owner . '/' . $repo . '/archive/refs/tags/' . $response->data['tag_name'] . '.zip';
                $dir = $repo . '-' . $response->data['name'];
                $library = ldc()->require($url, $dir);
				if(!is_wp_error($library)){
					update_option($option, $library);
				}
				return $library;
            } else {
                return $response->to_wp_error();
            }
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

	}
}
