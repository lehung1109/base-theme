<?php

    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    add_filter('wp_update_attachment_metadata', 'custom_theme_update_alt_image_when_empty', 10, 2);
    add_filter('wp_lazy_loading_enabled', function () { return false; });
    add_filter('max_srcset_image_width', 'custom_theme_disable_wp_responsive_images');
    add_filter('wp_get_attachment_image_attributes', 'custom_theme_add_data_src_attribute', 10, 3);
    add_filter('woocommerce_enqueue_styles', '__return_false');
    remove_action('wp_enqueue_scripts', [WC_Frontend_Scripts::class, 'load_scripts']);
    remove_action('wp_print_scripts', [WC_Frontend_Scripts::class, 'localize_printed_scripts'], 5);
    remove_action('wp_print_footer_scripts', [WC_Frontend_Scripts::class, 'localize_printed_scripts'], 5);
    add_action( 'wp_enqueue_scripts', 'custom_theme_remove_wp_block_library_css', 100 );
    add_action( 'wp_footer', 'custom_theme_dequeue_wp_embed' );
    remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
    remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
    remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
    add_filter('wpcf7_load_js', function () { return false; });
    add_filter('wpcf7_load_css', function () { return false; });
    remove_action('wp_enqueue_scripts', 'wpcf7_recaptcha_enqueue_scripts', 20);
    add_action( 'after_setup_theme', function () { if ( ! defined('WP_CACHE') ) ob_start('custom_theme_send_headers_force'); }, 0 );
    add_filter('litespeed_buffer_before', 'custom_theme_send_headers_force');
    add_filter('rocket_buffer', 'custom_theme_send_headers_force', -1000);
