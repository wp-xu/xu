<?php

/**
 * Get current url.
 *
 * @param  bool $parse If url should be parsed using `parse_url`. Default false.
 * @param  bool $obj   If parsed url should return as a object instead of array. Default false.
 *
 * @return mixed
 */
function xu_current_url( $parse = false, $obj = false ) {
	$url = $_SERVER['REQUEST_URI'];
	$url = ltrim( $url, '/' );
	$url = home_url() . '/' . $url;

	// If url shouldn't be parsed,
	// just return it as string.
	if ( ! (bool) $parse ) {
		return $url;
	}

	$parts = parse_url( $url );
	$parts = $parts === false ? [] : $parts;

	// Return as object or array.
	return $obj ? (object) $parts : $parts;
}

/**
 * Get post id from current url.
 *
 * @return string
 */
function xu_current_url_to_postid() {
	return xu_url_to_postid( xu_current_url() );
}

/**
 * Cached version of `url_to_post_id`.
 *
 * @see https://developer.wordpress.org/reference/functions/url_to_post_id/
 *
 * @param  string $url
 *
 * @return int
 */
function xu_url_to_postid( $url = '' ) {
	// `url_to_postid` don't work before `init` action.
	if ( ! did_action( 'init' ) ) {
		return 0;
	}

	// Only valid urls.
	if ( parse_url( $url, PHP_URL_HOST ) !== parse_url( home_url(), PHP_URL_HOST ) ) {
		return 0;
	}

	return (int) xu_cache_get( 'url_to_postid', [$url], __FUNCTION__ );
}
