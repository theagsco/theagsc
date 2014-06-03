<?php
/**
 * @package   wp-bitly
 * @author    Mark Waterous <mark@watero.us
 * @license   GPL-2.0+
 */


/**
 * Write to a WP Bitly debug log file
 *
 * @since 2.2.3
 * @param   string  $towrite    The data we want to add to the logfile
 */
function wpbitly_debug_log( $towrite, $message, $bypass = true ) {

    $wpbitly = wpbitly();

    if ( !$wpbitly->get_option( 'debug' ) || !$bypass )
        return;


    $log = fopen( WPBITLY_LOG, 'a' );

    fwrite( $log, '# [ ' . date( 'F j, Y, g:i a' ) . " ]\n" );
    fwrite( $log, '# [ ' . $message . " ]\n\n" );
    fwrite( $log, ( is_array( $towrite ) ? print_r( $towrite, true ) : var_dump( $towrite ) ) );
    fwrite( $log, "\n\n\n" );

    fclose( $log );

}


/**
 * What better way to store our api access call endpoints? I'm sure there is one, but this works for me.
 *
 * @since 2.0
 * @param   string $api_call Which endpoint do we need?
 * @return  string           Returns the URL for our requested API endpoint
 */
function wpbitly_api( $api_call ) {

    $api_links  = array(
        'shorten'       => '/v3/shorten?access_token=%1$s&longUrl=%2$s',
        'expand'        => '/v3/expand?access_token=%1$s&shortUrl=%2$s',
        'link/clicks'   => '/v3/link/clicks?access_token=%1$s&link=%2$s',
        'link/refer'    => '/v3/link/referring_domains?access_token=%1$s&link=%2$s',
        'user/info'     => '/v3/user/info?access_token=%1$s',
    );

    if ( !array_key_exists( $api_call, $api_links ) )
        trigger_error( __( 'WP Bitly Error: No such API endpoint.', 'wp-bitly' ) );

    return WPBITLY_BITLY_API . $api_links[ $api_call ];
}


/**
 * WP Bitly wrapper for wp_remote_get. Why have I been using cURL when WordPress already does this?
 * Thanks to Otto, who while teaching someone else how to do it right unwittingly taught me the right
 * way as well.
 *
 * @since   2.1
 * @param   string     $url The API endpoint we're contacting
 * @return  bool|array      False on failure, array on success
 */

function wpbitly_get( $url ) {

    $the = wp_remote_get( $url, array( 'timeout' => '30', ) );

    if ( is_array( $the ) && '200' == $the['response']['code'] )
        return json_decode( $the['body'], true );
}


/**
 * Generates the shortlink for the post specified by $post_id.
 *
 * @since   0.1
 * @param   int         $post_id The post ID we need a shortlink for.
 * @return  bool|string          Returns the shortlink on success.
 */

function wpbitly_generate_shortlink( $post_id ) {

    $wpbitly = wpbitly();

    $permalink = get_the_permalink( $post_id );

    $authorized = $wpbitly->get_option( 'authorized' );

    if ( !$authorized )
        return $permalink;

    $token      = $wpbitly->get_option( 'oauth_token' );
    $post_types = $wpbitly->get_option( 'post_types' );


    if ( defined( 'WP_IMPORTING' ) && WP_IMPORTING  )
        return $permalink;

    // Gleefully ripped from the pages of Publicize. They know more than me, what can I say?
    if ( defined( 'DOING_AJAX' ) && DOING_AJAX &&
        !did_action( 'p2_ajax' ) &&
        !did_action( 'wp_ajax_json_quickpress_post' ) &&
        !did_action( 'wp_ajax_instapost_publish' ) &&
        !did_action( 'wp_ajax_post_reblog' ) )
        return $permalink;

    if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )  )
        return $permalink;

    // Do we need to generate a shortlink for this post yet?
    if ( $parent = wp_is_post_revision( $post_id ) )
        $post_id = $parent;

    $post_status = get_post_status( $post_id );
    $post_type   = get_post_type( $post_id );

    if ( !in_array( $post_status, array( 'publish', 'future' ) ) || !in_array( $post_type, $post_types ) )
        return $permalink;


    $shortlink = get_post_meta( $post_id, '_wpbitly', true );

    if ( !empty( $shortlink ) ) {
        $url = sprintf( wpbitly_api( 'expand' ), $token, $shortlink );
        $response = wpbitly_get( $url );

        wpbitly_debug_log( $response, '/expand/' );

        if ( $permalink == $response['data']['expand'][0]['long_url'] )
            return $shortlink;
    }

    // Get Shorty.
    $url = sprintf( wpbitly_api( 'shorten' ), $token, urlencode( $permalink ) );
    $response = wpbitly_get( $url );

    wpbitly_debug_log( $response, '/shorten/' );

    // @TODO We're not error checking, we're assuming? Whyyy? Because lazy?
    if ( is_array( $response ) ) {
        $shortlink = $response['data']['url'];
        update_post_meta( $post_id, '_wpbitly', $shortlink );
    }

    // @TODO wpbitly_is_shortlink()? HMM?
    return $shortlink;
}


/**
 * Short circuits the `pre_get_shortlink` filter.
 *
 * @since   0.1
 * @param   bool   $permalink Depending on the filter this could be false, or a standard permalink
 * @param   int    $post_id   Current $post->ID, or 0 for the current post.
 * @return  string            A shortlink
 */
function wpbitly_get_shortlink( $shortlink, $post = 0 ) {

    if ( !is_object( $post ) ) {
        $post = get_post( $post );
        if ( !( $post instanceof WP_Post ) )
            return $shortlink;
    }

    $wpbitly = wpbitly();

    $authorized = $wpbitly->get_option( 'authorized' );
    $post_types = $wpbitly->get_option( 'post_types' );

    if ( $authorized && in_array( $post->post_type, $post_types ) ) {

        $shortlink = get_post_meta( $post->ID, '_wpbitly', true );

        if ( !$shortlink )
            $shortlink = wpbitly_generate_shortlink( $post->ID );

    }

    return $shortlink;
}


/**
 * Hook the post_link and post_type_link filters so that a shortlink is returned by plugins
 * requesting get_the_permalink(). This should satisfy social media plugins that post to twitter, facebook
 * et al without directly requesting the_shortlink(). If it doesn't, then shhhit.
 *
 * @since 2.2.8
 * @param $permalink Passed by the filter, return this if we're not in a plugin
 * @param int $post WP_Post passed via filter
 * @return string One of the permalink (not in a plugin) or shortlink (in a plugin)
 */
function wpbitly_get_post_link( $permalink, $post = 0 ) {

    $inside_of_a_plugin_maybe = false;
    $trace = debug_backtrace();

    foreach ( $trace as $line ) {
        if ( array_key_exists( 'file', $line ) ) {

            if ( false !== strpos( $line['file'], 'wp-content/plugins' ) )
                $inside_of_a_plugin_maybe = true;

            if ( false !== strpos( $line['file'], 'wp-bitly' ) ) {
                $inside_of_a_plugin_maybe = false;
                break;
            }

        }

    }

    if ( $inside_of_a_plugin_maybe )
        return wpbitly_get_shortlink( $permalink, $post );

    return $permalink;
}


/**
 * This is our shortcode handler, feel free to call it directly as well.
 *
 * @since   0.1
 * @param   array $atts Default shortcode attributes.
 */
function wpbitly_shortlink( $atts = array() ) {

    $post = get_post();

    $defaults = array(
        'text'      => '',
        'title'     => '',
        'before'    => '',
        'after'     => '',
        'post_id'   => $post->ID, // Use the current post by default, or pass an ID
    );

    extract( shortcode_atts( $defaults, $atts ) );

    if ( empty( $text ) )
        $text = __( 'This is the short link.', 'wp-bitly' );

    if ( empty( $title ) )
        $title = the_title_attribute( array( 'echo' => false ) );

    $shortlink = wp_get_shortlink( $post->ID );

    if ( !empty( $shortlink ) ) {
        $link = '<a rel="shortlink" href="' . esc_url( $shortlink ) . '" title="' . $title . '">' . $text . '</a>';
        $link = apply_filters( 'the_shortlink', $link, $shortlink, $text, $title );
        $link = $before . $link . $after;
    }

    return $link;
}

