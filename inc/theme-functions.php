<?php
	/**
	 * hook intermediate_image_sizes_advanced
	 */
	function custom_theme_image_sizes($sizes) {
		unset($sizes['medium_large']);
		return $sizes;
	}

	/**
	 * hook max_srcset_image_width
	 */
	function custom_theme_disable_wp_responsive_images(): int
    {
		return 1;
	}

	/**
	 * hook wp_get_attachment_image_attributes
	 */
	function custom_theme_add_data_src_attribute($attr, $attachment, $size) {
		if(strpos($attr['class'], 'lazy-image') !== false) {
			$image = wp_get_attachment_image_src( $attachment->ID, $size );

			if ($image) {
				$attr['data-src'] = $attr['src'];
				$attr['src'] = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ' . $image[1] . ' ' . $image[2] . '"%3E%3C/svg%3E';
			}
		}

		return $attr;
	}

	/**
	 * Filters the updated attachment meta data.
	 *
	 * @hook wp_update_attachment_metadata
	 *
	 * @param array $data          Array of updated attachment meta data.
	 * @param int $attachment_id Attachment post ID.
	 */
	function custom_theme_update_alt_image_when_empty(array $data, int $attachment_id ): array
    {
		try {
			$attachment = get_post($attachment_id);
			$mime_type = get_post_mime_type( $attachment );

			if ( preg_match( '!^image/!', $mime_type ) ) {
				if( empty( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) ) {
					$alt = pathinfo($attachment->post_title, PATHINFO_FILENAME);
					$alt = str_replace('-', '_', $alt);
					update_post_meta( $attachment_id, '_wp_attachment_image_alt', $alt );
				}
			}
		} catch (\Throwable $th) {

		}

		return $data;
	}

	/**
	 * @hook wp_enqueue_scripts
	 */
	function custom_theme_remove_wp_block_library_css() {
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_deregister_style( 'wc-block-style' );
		wp_deregister_style( 'wc-blocks-vendors-style' );
	}

	/**
	 * @hook wp_footer
	 */
	function custom_theme_dequeue_wp_embed() {
		wp_deregister_script( 'wp-embed' );
	}

	/**
	 * @hook wp_enqueue_scripts
	 */
	function custom_theme_enqueue_styles() {
		wp_enqueue_style( 'style', get_template_directory_uri() . '/assets/css/styles.css' );

		$components = get_field('components');
		$rows = $components;
        $components = array();

		if ($rows) {
//            if ( in_array('banner', $components) ) {
//                wp_enqueue_style( 'banner', get_template_directory_uri() . '/assets/css/components/banner.css' );
//            }
        } elseif ( is_archive() ) {
//			wp_enqueue_style( 'category', get_template_directory_uri() . '/assets/css/layouts/category.css' );
        }
	}

	/**
	 * @hook wp_enqueue_scripts
	 */
	function custom_theme_enqueue_scripts() {
		$lib = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '/min/' : '';

		wp_enqueue_script( 'script', get_template_directory_uri() . '/assets/js/' . $lib . 'script.js', array (), 1, true);

//		if (is_page('contact')) {
//			wpcf7_recaptcha_enqueue_scripts();
//		}
	}

	/**
	 * @return bool
	 */
    function load_contact_form_js() {
		$content = get_the_content();

		if ($content && stripos($content, 'contact-form-7')) {
			return true;
		}

		return false;
    }
