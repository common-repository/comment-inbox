<?php
/*
Plugin Name: Comment Inbox
Version: 0.3
Plugin URI: http://txfx.net/code/wordpress/the-comment-inbox/
Author: Mark Jaquith
Author URI: http://markjaquith.com/
Description: Sends all incoming comments to the moderation queue, but still shows them on the blog.  This allows you to treat the moderation queue as a "comment inbox" without having to approve each comment before it appears on the site. Note: recommended that you turn moderation e-mail notification OFF
*/

function cws_ci_turn_comment_moderation_on( $option_value ) {
	return '1';
}

function cws_ci_filter_comments_query( $query ) {
	global $wpdb;
	if ( is_admin() || strpos( $query, "SELECT * FROM $wpdb->comments WHERE comment_post_ID = ") === false || strpos( $query, "comment_approved = '1'" ) === false )
		return $query; //abort
	$query = str_replace( "comment_approved = '1'", "( comment_approved = '1' OR comment_approved = '0' )", $query );
	return $query;
}

function cws_ci_filter_comments_array( $temp_comments ) {
	if ( is_admin() )
		return;
	$comments = array();
	foreach ( (array) $temp_comments as $temp_comment ) {
		$temp_comment->comment_approved = '1';
		$comments[] = $temp_comment;
	}
	return $comments;
}

function cws_ci_filter_awaiting_moderation_text( $text ) {
	global $wp_version;
	$text = preg_replace( '|>Pending \((<span .*?>)?([0-9]+)(</span>)?\)|', '>Comment Inbox ($1$2$3)', $text ); // WP 2.7
	$text = preg_replace( '|>Awaiting Moderation \((<span .*?>)?([0-9]+)(</span>)?\)|', '>Comment Inbox ($1$2$3)', $text );
	$text = str_replace( 'value="Approve"', 'value="Archive"', $text );
	$text = str_replace( '>Approve</a>', '>Archive</a>', $text );
	$text = preg_replace( '|status=approved"( class="current")>Approved</a>|', 'status=approved"$1>Comment Archive</a>', $text );
	if ( version_compare( $wp_version, '2.5', '<' ) ) {
		// WP < 2.5
		$text = str_replace( '>Approved</a>', '>Archive</a>', $text );
		$text = str_replace( '<h2>Moderation Queue</h2>', '<h2>Comment Inbox</h2>', $text );
		$text = str_replace( '> Approve</label>', '> Archive</label>', $text );
	}
	return $text;
}

function cws_ci_filter_dashboard( $text ) {
	$text = preg_replace( '|>([0-9]+) comments awaiting moderation</a>|', '>$1 comments in your inbox</a>', $text );
	return $text;
}

function cws_ci_admin_filter_init() {
	global $pagenow;
	if ( !is_admin() )
		return;
	if ( 'moderation.php' == $pagenow || 'edit-comments.php' == $pagenow )
		ob_start( 'cws_ci_filter_awaiting_moderation_text' );
	elseif ( 'index.php' == $pagenow )
		ob_start( 'cws_ci_filter_dashboard' );
}

function cws_ci_fix_count( $post_id ) {
	global $wpdb;

	$post_id = (int) $post_id;

	$new['comment_count'] = (int) $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $wpdb->comments WHERE comment_post_ID = %d AND (comment_approved = '1' OR comment_approved = '0')", $post_id ) );
	$where['ID'] = $post_id;
	$wpdb->update( $wpdb->posts, $new, $where );

	clean_post_cache( $post_id );
	return $post_id;
}

function cws_ci_fix_count_by_comment_id ( $comment_id, $approved ) {
	global $wpdb;
	$comment = get_comment( $comment_id );
	if ( $comment->comment_post_ID )
		cws_ci_fix_count( $comment->comment_post_ID );
	return $comment_id;
}

function cws_ci_fix_count_transition( $unused1, $unused2, $comment ) {
	if ( isset( $comment->comment_post_ID ) )
		cws_ci_fix_count( $comment->comment_post_ID );
	return $unused1;
}

add_action( 'wp_update_comment_count', 'cws_ci_fix_count' );
add_action( 'comment_post', 'cws_ci_fix_count_by_comment_id', 50, 2 );
add_action( 'transition_comment_status', 'cws_ci_fix_count_transition', 10, 3 );
add_filter( 'pre_option_comment_moderation', 'cws_ci_turn_comment_moderation_on' );
add_filter( 'query', 'cws_ci_filter_comments_query' );
add_filter( 'comments_array', 'cws_ci_filter_comments_array' );
add_filter( 'init', 'cws_ci_admin_filter_init');
