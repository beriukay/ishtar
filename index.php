<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * It is used to display a page when nothing more specific matches a query.
 * E.g., home page when no home.php file exists.
 * @link https://codex.wordpress.org/Template_Hierarchy
 * @package ishtar
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<?php if ( is_home() && ! is_front_page() ) : ?>
				<header class="entry-header">
					<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'ishtar' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
				</header>

			<?php if ( is_search() ) : // Only display Excerpts for Search ?>
			<div class="entry-summary">
	 			<?php the_excerpt(); ?>
			</div><!-- .entry-summary -->
			<?php else : ?>
			<div class="entry-content">
	 			<?php the_content( __( 'Continue reading <span class="meta-nav">→</span>', 'ishtar' ) ); ?>
	 			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'ishtar' ), 'after' => '</div>' ) ); ?>
			</div><!-- .entry-content -->

			<?php endif; ?>

<?php endif; ?>

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<div class="entry-summary">
					<?php the_excerpt(); ?>
				</div><!-- .entry-summary -->
			<?php endwhile; ?>

			<?php the_posts_navigation(); ?>

		<?php else : ?>

			<?php get_template_part( 'template-parts/content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
