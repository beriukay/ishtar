<?php
/**
 * Custom template tags for this theme.
 * Eventually, some of the functionality here could be replaced by core features.
 * @package ishtar
 */

// Prints HTML with meta information for the current post-date/time and author.
if ( ! function_exists( 'ishtar_posted_on' ) ) :
function ishtar_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		esc_html_x( 'Posted on %s', 'post date', 'ishtar' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	$byline = sprintf(
		esc_html_x( 'by %s', 'post author', 'ishtar' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="posted-on">' . $posted_on . '</span><span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

}
endif;

// Prints HTML with meta information for the categories, tags and comments.
if ( ! function_exists( 'ishtar_entry_footer' ) ) :
function ishtar_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'ishtar' ) );
		if ( $categories_list && ishtar_categorized_blog() ) {
			printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'ishtar' ) . '</span>', $categories_list ); // WPCS: XSS OK.
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html__( ', ', 'ishtar' ) );
		if ( $tags_list ) {
			printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'ishtar' ) . '</span>', $tags_list ); // WPCS: XSS OK.
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link( esc_html__( 'Leave a comment', 'ishtar' ), esc_html__( '1 Comment', 'ishtar' ), esc_html__( '% Comments', 'ishtar' ) );
		echo '</span>';
	}

	edit_post_link( esc_html__( 'Edit', 'ishtar' ), '<span class="edit-link">', '</span>' );
}
endif;

/* Returns true if a blog has more than 1 category.
 * @return bool
 */
function ishtar_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'ishtar_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array(
			'fields'     => 'ids',
			'hide_empty' => 1,

			// We only need to know if there is more than one category.
			'number'     => 2,
		) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'ishtar_categories', $all_the_cool_cats );
	}
	return ( $all_the_cool_cats > 1 );
}

// Flush out the transients used in ishtar_categorized_blog.
function ishtar_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	delete_transient( 'ishtar_categories' ); // Like, beat it. Dig?
}
add_action( 'edit_category', 'ishtar_category_transient_flusher' );
add_action( 'save_post',     'ishtar_category_transient_flusher' );
