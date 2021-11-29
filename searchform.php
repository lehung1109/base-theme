<?php
/**
 * The searchform.php template.
 *
 * Used any time that get_search_form() is called.
 *
 * @link https://developer.wordpress.org/reference/functions/wp_unique_id/
 * @link https://developer.wordpress.org/reference/functions/get_search_form/
 *
 * @package WordPress
 */

/*
 * Generate a unique ID for each form and a string containing an aria-label
 * if one was passed to get_search_form() in the args array.
 */
$unique_id = wp_unique_id( 'search-form-' );

$label = ! empty( $args['aria_label'] ) ? 'aria-label="' . esc_attr( $args['aria_label'] ) . '"' : '';
?>
<form role="search" <?php echo $label; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped above. ?> method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label style="font-size: 0"><input type="search" id="<?php echo esc_attr( $unique_id ); ?>" class="search-field" value="<?php echo get_search_query(); ?>" name="s" />search</label>
    <button type="submit" aria-label="search" class="search-submit icon-search" value=""></button>
</form>
