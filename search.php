<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package WordPress
 */

get_header();

$count = $wp_query->found_posts;
?>

	<main role="main" class="main">
		<section class="search">
            <div class="container">
                <div class="search__wrap">
	                <?php if ( have_posts() ): ?>
                        <h1 class="ken-block-title">
			                <?php
			                printf(
				                __(
					                "%s Search Results",
					                "custom-theme"
				                ),
				                $count
			                );
			                ?>
                        </h1>

                        <div class="search__content">
			                <?php while ( have_posts() ) : the_post(); ?>

				                <?php get_template_part( 'content', 'search' ); ?>

			                <?php endwhile; ?>
                        </div>

                        <div class="search__pagination">
			                <?php custom_theme_posts_navigation(); ?>
                        </div>
	                <?php else: ?>
                        <h1 class="ken-block-title">
			                <?php _e( "No Results", "custom-theme" ); ?>
                        </h1>

                        <div class="search__content">
                            <p>
				                <?php
				                _e(
					                "Sorry. We can’t find what you are looking for. Please <a href='/contact/'>Contact Us</a> so we can help you further.",
					                "custom-theme"
				                );
				                ?>
                            </p>
                        </div>
	                <?php endif; ?>
                </div>
            </div>
		</section>
	</main>

<?php
get_footer();
