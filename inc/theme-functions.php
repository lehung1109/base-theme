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

    return custom_theme_rebuild_buffer($buffer);
}

/**
 * @param $buffer
 * @return array|mixed|string|string[]
 */
function custom_theme_rebuild_buffer($buffer) {
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

    $matches = $matches[1];
    $matches = implode( ' ', $matches );

    custom_theme_divide_scripts($matches, $script_body, $script_head);

    return str_replace(
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
}

/**
 * add javascript for ls inactive
 */
function custom_theme_add_scripts_when_ls_inactive() {
    if (!function_exists('is_plugin_active')) {
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    }
  
    if (is_plugin_active('litespeed-cache/litespeed-cache.php')) return;

    $script_body = [];
    $script_head = [];

    custom_theme_divide_scripts([], $script_body, $script_head, true);

    echo implode("", $script_head);
    echo implode("", $script_body);
}

/**
 * divide scripts for style and script
 */
function custom_theme_divide_scripts($matches, &$script_body, &$script_head, $include_all = false) {
    $classes = [
        'lazy-image',
        'is-max-height',
        'btn',
        'form-type-number',
        'form-type-select',
        'form-type-textarea',
        'form-type-checkbox',
        'form-type-submit',
        'form-item',
        'search-form',
        'wpcf7-form',
        'icon',
        'table',
        'woocommerce-pagination',
        'layout',
        'sidebar',
        'banner',
        'box-provider',
        'box-slider',
        'box-text',
        'archive',
        '404',
        'post-template-default',
        'product-template-default',
        'has-slide',
        'wpcf7-form',
    ];

    if ( $include_all ) $matches = implode(' ', $classes);

    $matches = explode(' ', $matches);
    $matches = array_intersect($classes, $matches);
    $matches = array_flip($matches);

    // add core style
    $script_head[] = '<link rel="stylesheet" href="' . get_template_directory_uri() . '/assets/css/style.css" type="text/css" media="all">';

    // declare function
    if ( isset( $matches['lazy-image'] ) ) {
        $script_body[] = '<script defer type="text/javascript" src="' . get_template_directory_uri() . '/assets/js/functions/lazy-image.js"></script>';
    }

    if ( isset( $matches['is-max-height'] ) ) {
        $script_body[] = '<script defer type="text/javascript" src="' . get_template_directory_uri() . '/assets/js/functions/max-height.js"></script>';
    }

    // add vendor style and script
    if ( isset( $matches['has-slide'] ) ) {
        $script_head[] = '<link rel="stylesheet" href="' . get_template_directory_uri() . '/assets/css/components/custom-slide.css">';
        $script_body[] = '<script defer type="text/javascript" src="' . get_template_directory_uri() . '/assets/js/functions/swiper-bundle.min.js"></script>';
    }

    // add core js
    $script_body[] = '<script defer type="text/javascript" src="' . get_template_directory_uri() . '/assets/js/scripts.js"></script>';

    // add style and js for components and page
    if ( isset( $matches['btn'] ) ) {
        $script_head[] = '<link rel="stylesheet" type="text/css" media="all" href="' . get_template_directory_uri() . '/assets/css/base/btn.css">';
    }

    if ( isset( $matches['form-type-number'] ) ) {
        $script_head[] = '<link rel="stylesheet" type="text/css" media="all" href="' . get_template_directory_uri() . '/assets/css/base/form/number.css">';
    }

    if ( isset( $matches['form-type-select'] ) ) {
        $script_head[] = '<link rel="stylesheet" type="text/css" media="all" href="' . get_template_directory_uri() . '/assets/css/base/form/select.css">';
    }

    if ( isset( $matches['form-type-textarea'] ) ) {
        $script_head[] = '<link rel="stylesheet" type="text/css" media="all" href="' . get_template_directory_uri() . '/assets/css/base/form/textarea.css">';
    }

    if ( isset( $matches['form-type-checkbox'] ) || isset( $matches['form-type-radio'] ) ) {
        $script_head[] = '<link rel="stylesheet" type="text/css" media="all" href="' . get_template_directory_uri() . '/assets/css/base/form/checkbox-radio.css">';
    }

    if ( isset( $matches['form-type-submit'] ) ) {
        $script_head[] = '<link rel="stylesheet" type="text/css" media="all" href="' . get_template_directory_uri() . '/assets/css/base/form/submit.css">';
    }

    if ( isset( $matches['form-item'] ) ) {
        $script_head[] = '<link rel="stylesheet" type="text/css" media="all" href="' . get_template_directory_uri() . '/assets/css/base/form/text.css">';
        $script_head[] = '<link rel="stylesheet" type="text/css" media="all" href="' . get_template_directory_uri() . '/assets/css/base/form/form-layout.css">';
    }

    if ( isset( $matches['search-form'] ) ) {
        $script_head[] = '<link rel="stylesheet" type="text/css" media="all" href="' . get_template_directory_uri() . '/assets/css/base/form/search-form.css">';
    }

    if ( isset( $matches['wpcf7-form'] ) ) {
        $script_head[] = '<link rel="stylesheet" type="text/css" media="all" href="' . get_template_directory_uri() . '/assets/css/base/form/contact-form.css">';

        if (function_exists('wpcf7_plugin_url')) {
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

          // support recaptcha
          custom_theme_add_recaptcha($script_body);
        }
    }

    if ( isset( $matches['icon'] ) ) {
        $script_head[] = '<link rel="stylesheet" type="text/css" media="all" href="' . get_template_directory_uri() . '/assets/css/base/icons.css">';
    }

    if ( isset( $matches['table'] ) ) {
        $script_head[] = '<link rel="stylesheet" type="text/css" media="all" href="' . get_template_directory_uri() . '/assets/css/base/table.css">';
    }

    // woocommerce pagination
    if ( isset( $matches['woocommerce-pagination'] ) ) {
        $script_head[] = '<link rel="stylesheet" type="text/css" media="all" href="' . get_template_directory_uri() . '/assets/css/layouts/pagination.css">';
    }

    // components
    if ( isset( $matches['banner'] ) ) {
        $script_head[] = '<link rel="stylesheet" type="text/css" media="all" href="' . get_template_directory_uri() . '/assets/css/components/banner.css">';
        $script_body[] = '<script defer type="text/javascript" src="' . get_template_directory_uri() . '/assets/js/components/banner.js"></script>';
    }

    // page style
    if ( isset( $matches['404'] ) ) {
        $script_head[] = '<link rel="stylesheet" type="text/css" media="all" href="' . get_template_directory_uri() . '/assets/css/page/404.css">';
    }

    if ( isset( $matches['archive'] ) ) {
        $script_head[] = '<link rel="stylesheet" type="text/css" media="all" href="' . get_template_directory_uri() . '/assets/css/page/category.css">';
        $script_body[] = '<script defer type="text/javascript" src="' . get_template_directory_uri() . '/assets/js/page/category.js"></script>';
    }

    if ( isset( $matches['product-template-default'] ) ) {
        $script_head[] = '<link rel="stylesheet" type="text/css" media="all" href="' . get_template_directory_uri() . '/assets/css/page/product.css">';
    }

    // add tracking code
//    if ($_SERVER['HTTP_HOST'] === 'demo.com' || $_SERVER['HTTP_HOST'] === 'www.demo.com') {
//        $script_body[] = '<script defer async type="text/javascript" src="https://www.googletagmanager.com/gtag/js?id={place_id_here}"></script>';
//    }
}

/**
 * @param $buffer
 * @return array|mixed|string|string[]
 */
function custom_theme_wp_rocket_buffer_process($buffer) {
    if (
        empty($_POST) ||
        empty( $_POST['_wpcf7'] )
    ) return $buffer;

    return custom_theme_rebuild_buffer($buffer);
}

/**
 * add recaptcha support
 */
function custom_theme_add_recaptcha(&$script_body) {
    if ( ! class_exists('WPCF7_RECAPTCHA') ) return;

    $service = WPCF7_RECAPTCHA::get_instance();

    if ( ! $service->is_active() ) return;

    $script_body[] = '<script defer type="text/javascript" src="https://www.recaptcha.net/recaptcha/api.js?render=' . $service->get_sitekey() . '"></script>';

    $wpcf7_recaptcha = array(
        'sitekey' => $service->get_sitekey(),
        'actions' => array(
            'homepage' => 'homepage',
            'contactform' => 'contactform',
        )
    );
    $script_body[] = '<script type="text/javascript">let wpcf7_recaptcha = ' . json_encode($wpcf7_recaptcha) . ';</script>';
    $script_body[] = '<script defer type="text/javascript" src="' . wpcf7_plugin_url( 'modules/recaptcha/index.js' ) . '"></script>';
}
