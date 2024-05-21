<?php
/**
 * This file contains functions and hooks for styling Hootkit plugin
 *   Hootkit is a free plugin released under GPL license and hosted on wordpress.org.
 *   It is recommended to the user via wp-admin using TGMPA class
 *
 * This file is loaded at 'after_setup_theme' action @priority 10 ONLY IF hootkit plugin is active
 *
 * @package    Unos Glow
 * @subpackage HootKit
 */

// Register HootKit
// Parent added @priority 5
add_filter( 'hootkit_register', 'unosglow_register_hootkit', 7 );

// Add dynamic CSS for hootkit
add_action( 'hoot_dynamic_cssrules', 'unosglow_hootkit_dynamic_cssrules', 8 );

/**
 * Register Hootkit
 * Parent added @priority 5
 *
 * @since 1.0
 * @param array $config
 * @return string
 */
if ( !function_exists( 'unosglow_register_hootkit' ) ) :
function unosglow_register_hootkit( $config ) {
	// Array of configuration settings.
	if ( isset( $config['supports'] ) && is_array( $config['supports'] ) )
		$config['supports'][] = 'widget-subtitle';
	return $config;
}
endif;

add_action( 'wp_enqueue_scripts', 'unosglow_enqueue_hootkit', 15 );
if ( !function_exists( 'unosglow_enqueue_hootkit' ) ) :
function unosglow_enqueue_hootkit() {

	// Backward compatibility // @deprecated < Unos2.9.16 @7.21
	if ( !function_exists( 'unos_enqueue_childhootkit' ) ) {
	/* 'unos-hootkit' is loaded using 'hoot_locate_style' which loads child theme location. Hence deregister it and load files again */
	wp_deregister_style( 'unos-hootkit' );
	/* Load Hootkit Style - Add dependency so that hotkit is loaded after */
	if ( file_exists( hoot_data()->template_dir . 'hootkit/hootkit.css' ) )
	wp_enqueue_style( 'unos-hootkit', hoot_data()->template_uri . 'hootkit/hootkit.css', array( 'hoot-style' ), hoot_data()->template_version );
	if ( file_exists( hoot_data()->child_dir . 'hootkit/hootkit.css' ) )
	wp_enqueue_style( 'unosglow-hootkit', hoot_data()->child_uri . 'hootkit/hootkit.css', array( 'hoot-style', 'unos-hootkit' ), hoot_data()->childtheme_version );
	}

}
endif;

add_filter( 'hoot_style_builder_inline_style_handle', 'unosglow_dynamic_css_hootkit_handle', 8 );
if ( !function_exists( 'unosglow_dynamic_css_hootkit_handle' ) ) :
function unosglow_dynamic_css_hootkit_handle( $handle ) {
	// Backward compatibility // @deprecated < Unos2.9.16 @7.21
	if ( !function_exists( 'unos_dynamic_css_childhootkit_handle' ) )
	return 'unosglow-hootkit';
	else return $handle;
}
endif;

/**
 * Custom CSS built from user theme options for hootkit features
 *
 * @since 1.0
 * @access public
 */
if ( !function_exists( 'unosglow_hootkit_dynamic_cssrules' ) ) :
function unosglow_hootkit_dynamic_cssrules() {

	global $hoot_style_builder;

	// Get user based style values
	$styles = unos_user_style();
	extract( $styles );

	$hoot_style_builder->remove( array(
		'.social-icons-icon',
		'#topbar .social-icons-icon, #page-wrapper .social-icons-icon',
	) );

	/*** Add Dynamic CSS ***/

}
endif;

/**
 * Modify Post Grid default style
 *
 * @since 1.0
 * @param array $settings
 * @return string
 */
function unosglow_post_grid_widget_settings( $settings ) {
	if ( isset( $settings['form_options']['unitheight']['desc'] ) )
		$settings['form_options']['unitheight']['desc'] = __( 'Default: 205 (in pixels)', 'unos-glow' );
	return $settings;
}
add_filter( 'hootkit_post_grid_widget_settings', 'unosglow_post_grid_widget_settings', 5 );
add_filter( 'hootkit_content_grid_widget_settings', 'unosglow_post_grid_widget_settings', 5 );

/**
 * Modify Ticker default style
 *
 * @since 1.0
 * @param array $settings
 * @return string
 */
function unosglow_ticker_widget_settings( $settings ) {
	if ( isset( $settings['form_options']['background'] ) )
		$settings['form_options']['background']['std'] = '#f1f1f1';
	if ( isset( $settings['form_options']['fontcolor'] ) )
		$settings['form_options']['fontcolor']['std'] = '#ff9f9c';
	return $settings;
}
function unosglow_ticker_products_widget_settings( $settings ) {
	if ( isset( $settings['form_options']['background'] ) )
		$settings['form_options']['background']['std'] = '#f1f1f1';
	if ( isset( $settings['form_options']['fontcolor'] ) )
		$settings['form_options']['fontcolor']['std'] = '#666666';
	return $settings;
}
add_filter( 'hootkit_ticker_widget_settings', 'unosglow_ticker_widget_settings', 5 );
add_filter( 'hootkit_ticker_posts_widget_settings', 'unosglow_ticker_widget_settings', 5 );
add_filter( 'hootkit_products_ticker_widget_settings', 'unosglow_ticker_products_widget_settings', 5 );

/**
 * Modify Products Cart Icon default style
 *
 * @since 1.0
 * @param array $settings
 * @return string
 */
function unosmvu_products_carticon_widget_settings( $settings ) {
	if ( isset( $settings['form_options']['background'] ) )
		$settings['form_options']['background']['std'] = '#ff9f9c';
	if ( isset( $settings['form_options']['fontcolor'] ) )
		$settings['form_options']['fontcolor']['std'] = '#ffffff';
	return $settings;
}
add_filter( 'hootkit_products_carticon_widget_settings', 'unosmvu_products_carticon_widget_settings', 5 );

/**
 * Filter Ticker and Ticker Posts display Title markup
 *
 * @since 1.0
 * @param array $settings
 * @return string
 */
function unosglow_hootkit_widget_title( $display, $title, $context, $icon = '' ) {
	// if ( $context == 'ticker-posts' || $context == 'ticker' || $context == 'products-ticker' )
	$display = '<div class="ticker-title accent-typo">' . $icon . $title . '</div>';
	return $display;
}
add_filter( 'hootkit_widget_ticker_title', 'unosglow_hootkit_widget_title', 5, 4 );