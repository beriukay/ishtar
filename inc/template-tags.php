<?php
/**
 * Custom template tags for this theme.
 * Eventually, some of the functionality here could be replaced by core features.
 * @package ishtar
 */

// Prints HTML with meta information for post-date/time and author.	
if ( ! function_exists( 'ishtar_posted_on' ) ) :
function ishtar_posted_on() {
	printf( __( 'Posted on <a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s" pubdate>%4$s</time></a><span class="byline"> by <span class="author vcard"><a class="url fn n" href="%5$s" title="%6$s" rel="author">%7$s</a></span></span>', 'ishtar' ),
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'ishtar' ), get_the_author() ) ),
		esc_html( get_the_author() )
	);
}
endif;

// Prints HTML with meta information for the categories. tags, and comments.
if ( ! function_exists( 'ishtar_entry_footer' ) ) :
function ishtar_entry_footer() {
	if ( 'post' === get_post_type() ) { // Hide category and tag text for pages.
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

// Returns true if a blog has more than 1 category.
function ishtar_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'ishtar_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories( array( 'hide_empty' => 1,) );

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );
		set_transient( 'all_the_cool_cats', $all_the_cool_cats );
	}
	return ( $all_the_cool_cats > 1 );
}

// Flush out the transients used in ishtar_categorized_blog.
function ishtar_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	delete_transient( 'all_the_cool_cats' ); // Like, beat it. Dig?
}
add_action( 'edit_category', 'ishtar_category_transient_flusher' );
add_action( 'save_post',     'ishtar_category_transient_flusher' );
