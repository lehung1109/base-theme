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
    if(
        strpos($attr['class'], 'lazy-image') !== false ||
        strpos($attr['class'], 'swiper-lazy') !== false
    ) {
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
    wp_dequeue_style( 'wc-block-style' );
    wp_dequeue_style( 'wc-blocks-style' );
}

/**
 * @hook wp_footer
 */
function custom_theme_dequeue_wp_embed() {
    wp_deregister_script( 'wp-embed' );
}

/**
 * @param $buffer
 * @return mixed
 */
function custom_theme_buffer_process($buffer) {
    if (
        ! defined( 'LITESPEED_IS_HTML' ) &&
        (
            empty($_POST) ||
            empty( $_POST['_wpcf7'] )
        )
    ) return $buffer;

    // build array for replace html
    $script_body = [];
    $script_head = [];

    // check style and js
    $pattern = '#class="([^"]+)#i';
    preg_match_all(
        $pattern,
        $buffer,
        $matches
    );

    if (empty($matches)) return $buffer;

    $matches = array_unique( $matches[1] );
    $matches = implode( ' ', $matches );

    custom_theme_divide_scripts($matches, $script_body, $script_head);

    $buffer = str_replace(
        array(
            '<head>',
            '</body>',
        ),
        array(
            implode('',$script_head) . '<head>',
            implode('',$script_body) . '</body>',
        ),
        $buffer
    );

    return $buffer;
}

/**
 * add javascript for ls inactive
 */
function custom_theme_add_scripts_when_ls_inactive() {
    if (is_plugin_active('litespeed-cache/litespeed-cache.php')) return;

    $classes = `
            lazy-image
            is-max-height
            btn number
            select
            textarea
            checkbox
            submit
            form-item
            search-form
            contact-form
            icon
            table
            woocommerce-pagination
            banner
            archive
            post-template-default
            product-template-default
            has-slide
            wpcf7-form
        `;

    $script_body = [];
    $script_head = [];

    custom_theme_divide_scripts($classes, $script_body, $script_head);

    echo implode("", $script_head);
    echo implode("", $script_body);
}

/**
 * divide scripts for style and script
 */
function custom_theme_divide_scripts($matches, &$script_body, &$script_head) {
    // add core style
    $script_head[] = '<link rel="stylesheet" href="' . get_template_directory_uri() . '/assets/css/styles.css" type="text/css" media="all">';

    // declare function
    if ( ( $last_string = str_replace('lazy-image', '', $matches) ) !== $matches) {
        $matches = $last_string;
        $script_body[] = '<script defer type="text/javascript" src="' . get_template_directory_uri() . '/assets/js/functions/lazy-image.js"></script>';
    }

    if ( ( $last_string = str_replace('is-max-height', '', $matches) ) !== $matches) {
        $matches = $last_string;
        $script_body[] = '<script defer type="text/javascript" src="' . get_template_directory_uri() . '/assets/js/functions/max-height.js"></script>';
    }

    // add vendor style and script
    if (( $last_string = str_replace('has-slide', '', $matches) ) !== $matches) {
        $matches = $last_string;
        $script_head[] = '<link rel="stylesheet" href="' . get_template_directory_uri() . '/assets/css/components/custom-slide.css">';
        $script_body[] = '<script defer type="text/javascript" src="' . get_template_directory_uri() . '/assets/js/functions/swiper-bundle.min.js"></script>';
    }

    // add core js
    $script_body[] = '<script defer type="text/javascript" src="' . get_template_directory_uri() . '/assets/js/script.js"></script>';

    // add style and js for components and page
    if ( ( $last_string = str_replace('btn', '', $matches) ) !== $matches) {
        $matches = $last_string;
        $script_head[] = '<link rel="stylesheet" type="text/css" media="all" href="' . get_template_directory_uri() . '/assets/css/base/btn.css">';
    }

    if (( $last_string = str_replace('number', '', $matches) ) !== $matches) {
        $matches = $last_string;
        $script_head[] = '<link rel="stylesheet" type="text/css" media="all" href="' . get_template_directory_uri() . '/assets/css/base/form/number.css">';
    }

    if (( $last_string = str_replace('select', '', $matches) ) !== $matches) {
        $matches = $last_string;
        $script_head[] = '<link rel="stylesheet" type="text/css" media="all" href="' . get_template_directory_uri() . '/assets/css/base/form/select.css">';
    }

    if (( $last_string = str_replace('textarea', '', $matches) ) !== $matches) {
        $matches = $last_string;
        $script_head[] = '<link rel="stylesheet" type="text/css" media="all" href="' . get_template_directory_uri() . '/assets/css/base/form/textarea.css">';
    }

    if (( $last_string = str_replace(array('checkbox', 'radio'), array('', ''), $matches) ) !== $matches) {
        $matches = $last_string;
        $script_head[] = '<link rel="stylesheet" type="text/css" media="all" href="' . get_template_directory_uri() . '/assets/css/base/form/checkbox-radio.css">';
    }

    if (( $last_string = str_replace('submit', '', $matches) ) !== $matches) {
        $matches = $last_string;
        $script_head[] = '<link rel="stylesheet" type="text/css" media="all" href="' . get_template_directory_uri() . '/assets/css/base/form/submit.css">';
    }

    if (( $last_string = str_replace('form-item', '', $matches) ) !== $matches) {
        $matches = $last_string;
        $script_head[] = '<link rel="stylesheet" type="text/css" media="all" href="' . get_template_directory_uri() . '/assets/css/base/form/text.css">';
        $script_head[] = '<link rel="stylesheet" type="text/css" media="all" href="' . get_template_directory_uri() . '/assets/css/base/form/form-layout.css">';
    }

    if (( $last_string = str_replace('search-form', '', $matches) ) !== $matches) {
        $matches = $last_string;
        $script_head[] = '<link rel="stylesheet" type="text/css" media="all" href="' . get_template_directory_uri() . '/assets/css/base/form/search-form.css">';
    }

    if (( $last_string = str_replace('wpcf7-form', '', $matches) ) !== $matches) {
        $matches = $last_string;
        $script_head[] = '<link rel="stylesheet" type="text/css" media="all" href="' . get_template_directory_uri() . '/assets/css/base/form/contact-form.css">';

        $wpcf7 = array(
            'api' => array(
                'root' => esc_url_raw( get_rest_url() ),
                'namespace' => 'contact-form-7/v1',
            ),
            'cached' => 1
        );
        $script_body[] = '<script type="text/javascript">
                let wpcf7 = ' . json_encode($wpcf7) . ';
            </script>';
        $script_body[] = '<script defer type="text/javascript" src="' . wpcf7_plugin_url( 'includes/js/index.js' ) . '"></script>';
    }

    if (( $last_string = str_replace('icon', '', $matches) ) !== $matches) {
        $matches = $last_string;
        $script_head[] = '<link rel="stylesheet" type="text/css" media="all" href="' . get_template_directory_uri() . '/assets/css/base/icons.css">';
    }

    if (( $last_string = str_replace('table', '', $matches) ) !== $matches) {
        $matches = $last_string;
        $script_head[] = '<link rel="stylesheet" type="text/css" media="all" href="' . get_template_directory_uri() . '/assets/css/base/table.css">';
    }

    // woocommerce pagination
    if (( $last_string = str_replace('woocommerce-pagination', '', $matches) ) !== $matches) {
        $matches = $last_string;
        $script_head[] = '<link rel="stylesheet" type="text/css" media="all" href="' . get_template_directory_uri() . '/assets/css/layouts/pagination.css">';
    }

    // components
    if (( $last_string = str_replace('banner', '', $matches) ) !== $matches) {
        $matches = $last_string;
        $script_head[] = '<link rel="stylesheet" type="text/css" media="all" href="' . get_template_directory_uri() . '/assets/css/components/banner.css">';
        $script_body[] = '<script defer type="text/javascript" src="' . get_template_directory_uri() . '/assets/js/components/banner.js"></script>';
    }

    // page style
    if (( $last_string = str_replace('404', '', $matches) ) !== $matches) {
        $matches = $last_string;
        $script_head[] = '<link rel="stylesheet" type="text/css" media="all" href="' . get_template_directory_uri() . '/assets/css/page/404.css">';
    }

    if (( $last_string = str_replace('archive', '', $matches) ) !== $matches) {
        $matches = $last_string;
        $script_head[] = '<link rel="stylesheet" type="text/css" media="all" href="' . get_template_directory_uri() . '/assets/css/page/category.css">';
        $script_body[] = '<script defer type="text/javascript" src="' . get_template_directory_uri() . '/assets/js/page/category.js"></script>';
    }

    if (( $last_string = str_replace('product-template-default', '', $matches) ) !== $matches) {
        $matches = $last_string;
        $script_head[] = '<link rel="stylesheet" type="text/css" media="all" href="' . get_template_directory_uri() . '/assets/css/page/product.css">';
    }

    // add tracking code
//    if ($_SERVER['HTTP_HOST'] === 'demo.com' || $_SERVER['HTTP_HOST'] === 'www.demo.com') {
//        $script_body[] = '<script defer async type="text/javascript" src="https://www.googletagmanager.com/gtag/js?id={place_id_here}"></script>';
//    }
}
