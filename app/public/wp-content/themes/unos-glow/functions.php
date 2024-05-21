<?php
/**
 *                  _   _             _   
 *  __      ___ __ | | | | ___   ___ | |_ 
 *  \ \ /\ / / '_ \| |_| |/ _ \ / _ \| __|
 *   \ V  V /| |_) |  _  | (_) | (_) | |_ 
 *    \_/\_/ | .__/|_| |_|\___/ \___/ \__|
 *           |_|                          
 *
 * :: Theme's main functions file ::::::::::::
 * :: Initialize and setup the theme :::::::::
 *
 * Hooks, Actions and Filters are used throughout this theme. You should be able to do most of your
 * customizations without touching the main code. For more information on hooks, actions, and filters
 * @see http://codex.wordpress.org/Plugin_API
 *
 * @package    Unos Glow
 */


/* === Theme Setup === */


/**
 * Theme Setup
 *
 * @since 1.0
 * @access public
 * @return void
 */
function unosglow_theme_setup(){

	// Load theme's Hootkit functions if plugin is active
	if ( class_exists( 'HootKit' ) && file_exists( hoot_data()->child_dir . 'hootkit/functions.php' ) )
		include_once( hoot_data()->child_dir . 'hootkit/functions.php' );

	// Load the about page.
	if ( apply_filters( 'unosglow_load_about', file_exists( hoot_data()->child_dir . 'admin/about.php' ) ) )
		require_once( hoot_data()->child_dir . 'admin/about.php' );

}
add_action( 'after_setup_theme', 'unosglow_theme_setup', 10 );

/**
 * Set dynamic css handle to child stylesheet
 *
 * @since 1.0
 * @access public
 * @return string
 */
if ( !function_exists( 'unosglow_dynamic_css_child_handle' ) ) :
function unosglow_dynamic_css_child_handle( $handle ) {
	return 'hoot-child-style';
}
endif;
// Backward compatibility // @deprecated < Unos2.9.16 @7.21
// Check after unos/library/styles has loaded via after_setup_theme@2
add_action( 'after_setup_theme', 'unosglow_enqueue_child_styles_backcompatibility', 3 );
function unosglow_enqueue_child_styles_backcompatibility() {
	if ( !function_exists( 'hoot_enqueue_child_styles' ) )
		add_filter( 'hoot_style_builder_inline_style_handle', 'unosglow_dynamic_css_child_handle', 7 );
	else
		add_filter( 'hoot_style_builder_inline_style_handle', 'unosglow_dynamic_css_child_handle', 8 );
}

/**
 * Add theme name in body class
 *
 * @since 1.0
 * @access public
 * @return string
 */
if ( !function_exists( 'unosglow_default_body_class' ) ) :
function unosglow_default_body_class( $class ) {
	return 'unos-glow';
}
endif;
add_filter( 'unos_default_body_class', 'unosglow_default_body_class', 7 );

/**
 * Unload Template's About Page
 *
 * @since 1.0
 * @access public
 * @return bool
 */
function unosglow_unload_template_about( $load ) {
	return false;
}
add_filter( 'unos_load_about', 'unosglow_unload_template_about', 5 );

/**
 * Add child theme's hook for unloading About page
 *
 * @since 1.0
 * @access public
 * @return array
 */
function unosglow_unload_about( $hooks ) {
	if ( is_array( $hooks ) )
		$hooks[] = 'unosglow_load_about';
	return $hooks;
}
add_filter( 'unos_unload_upsell', 'unosglow_unload_about', 5 );

/**
 * Update tags in Template's About Page
 *
 * @since 1.0
 * @access public
 * @return bool
 */
function unosglow_abouttags( $tags ) {
	return array(
		'slug' => 'unos-glow',
		'name' => __( 'Unos Glow', 'unos-glow' ),
		'vers' => hoot_data( 'childtheme_version' ),
		'shot' => ( file_exists( hoot_data()->child_dir . 'screenshot.jpg' ) ) ? hoot_data()->child_uri . 'screenshot.jpg' : (
					( file_exists( hoot_data()->child_dir . 'screenshot.png' ) ) ? hoot_data()->child_uri . 'screenshot.png' : ''
					),
		);
}
add_filter( 'unos_abouttags', 'unosglow_abouttags', 5 );

/**
 * Alter Customizer Section Pro args
 *
 * @since 1.0
 * @access public
 * @return void
 */
function unosglow_customize_section_pro( $args ) {
	if ( isset( $args['title'] ) )
		$args['title'] = esc_html__( 'Unos Glow Premium', 'unos-glow' );
	if ( isset( $args['pro_url'] ) )
		$args['pro_url'] = esc_url( admin_url('themes.php?page=unos-glow-welcome') );
	return $args;
}
add_filter( 'hoot_theme_customize_section_pro', 'unosglow_customize_section_pro' );

/**
 * Modify custom-header
 * Priority@5 to come before 10 used by unos for adding support
 *    @ref wp-includes/theme.php #2440
 *    // Merge in data from previous add_theme_support() calls.
 *    // The first value registered wins. (A child theme is set up first.)
 * For remove_theme_support, use priority@15
 *
 * @since 1.0
 * @access public
 * @return void
 */
function unosglow_custom_header() {
	add_theme_support( 'custom-header', array(
		'width' => 1440,
		'height' => 500,
		'flex-height' => true,
		'flex-width' => true,
		'default-image' => '',
		'header-text' => false
	) );
}
add_filter( 'after_setup_theme', 'unosglow_custom_header', 5 );


/* === Attr === */


/**
 * Topbar meta attributes.
 * Priority@10: 7-> base lite ; 9-> base prim
 *
 * @since 1.0
 * @param array $attr
 * @param string $context
 * @return array
 */
function unosglow_attr_topbar( $attr, $context ) {
	if ( !empty( $attr['classes'] ) )
		$attr['classes'] = str_replace( 'social-icons-invert', '', $attr['classes'] );
	return $attr;
}
add_filter( 'hoot_attr_topbar', 'unosglow_attr_topbar', 10, 2 );

/**
 * Loop meta attributes.
 * Priority@10: 7-> base lite ; 9-> base prim
 *
 * @since 1.0
 * @param array $attr
 * @param string $context
 * @return array
 */
function unosglow_attr_premium_loop_meta_wrap( $attr, $context ) {
	$attr['class'] = ( empty( $attr['class'] ) ) ? '' : $attr['class'];

	/* Overwrite all and apply background class for both */
	$attr['class'] = str_replace( array( 'loop-meta-wrap pageheader-bg-default', 'loop-meta-wrap pageheader-bg-stretch', 'loop-meta-wrap pageheader-bg-incontent', 'loop-meta-wrap pageheader-bg-both', 'loop-meta-wrap pageheader-bg-none', ), '', $attr['class'] );
	$attr['class'] .= ' loop-meta-wrap pageheader-bg-both';

	return $attr;
}
add_filter( 'hoot_attr_loop-meta-wrap', 'unosglow_attr_premium_loop_meta_wrap', 10, 2 );


/* === Dynamic CSS === */


/* Update user based style values for premium dynamic css */
/**
 * Create user based style values for premium dynamic css
 * Priority@6: apply_filters -> base lite ; 5-> base prim
 *
 * @since 1.0
 * @access public
 * @return array
 */
function unosglow_user_style( $styles ){

	/* Override Base styles */

	/* Add child styles */
	$styles['body_fontface']              = hoot_get_mod( 'body_fontface' );
	$styles['subheadings_fontface']       = hoot_get_mod( 'subheadings_fontface' );
	$styles['subheadings_fontface_style'] = hoot_get_mod( 'subheadings_fontface_style' );

	return $styles;
}
add_filter( 'unos_user_style', 'unosglow_user_style', 6 );

/**
 * Custom CSS built from user theme options
 * For proper sanitization, always use functions from library/sanitization.php
 *
 * @since 1.0
 * @access public
 */
function unosglow_dynamic_cssrules() {

	global $hoot_style_builder;

	// Get user based style values
	$styles = unos_user_style();
	extract( $styles );

	$bodyfontface = '';
	if ( 'fontpo' == $body_fontface )
		$bodyfontface = '"Poppins", sans-serif';
	elseif ( 'fontla' == $body_fontface )
		$bodyfontface = '"Lato", sans-serif';
	elseif ( 'fontcf' == $body_fontface )
		$bodyfontface = '"Comfortaa", sans-serif';
	elseif ( 'fontow' == $body_fontface )
		$bodyfontface = '"Oswald", sans-serif';
	elseif ( 'fontda' == $body_fontface )
		$bodyfontface = '"Dancing Script", sans-serif';
	elseif ( 'fontno' == $body_fontface )
		$bodyfontface = '"Noto Serif", serif';
	elseif ( 'fontsl' == $body_fontface )
		$bodyfontface = '"Slabo 27px", serif';
	elseif ( 'fontgr' == $body_fontface )
		$bodyfontface = 'Georgia, serif';
	hoot_add_css_rule( array(
						'selector'  => 'body' . ',' . '.enforce-body-font' . ',' . '.site-title-body-font',
						'property'  => 'font-family',
						'value'     => $bodyfontface,
					) );

	$headingproperty = array();
	if ( 'fontpo' == $headings_fontface )
		$headingproperty['font-family'] = array( '"Poppins", sans-serif' );
	elseif ( 'fontla' == $headings_fontface )
		$headingproperty['font-family'] = array( '"Lato", sans-serif' );
	elseif ( 'fontcf' == $headings_fontface )
		$headingproperty['font-family'] = array( '"Comfortaa", sans-serif' );
	elseif ( 'fontow' == $headings_fontface )
		$headingproperty['font-family'] = array( '"Oswald", sans-serif' );
	elseif ( 'fontda' == $headings_fontface )
		$headingproperty['font-family'] = array( '"Dancing Script", sans-serif' );
	elseif ( 'fontno' == $headings_fontface )
		$headingproperty['font-family'] = array( '"Noto Serif", serif' );
	elseif ( 'fontsl' == $headings_fontface )
		$headingproperty['font-family'] = array( '"Slabo 27px", serif' );
	elseif ( 'fontgr' == $headings_fontface )
		$headingproperty['font-family'] = array( 'Georgia, serif' );
	if ( 'uppercase' == $headings_fontface_style )
		$headingproperty['text-transform'] = array( 'uppercase' );
	else
		$headingproperty['text-transform'] = array( 'none' );
	if ( !empty( $headingproperty ) ) {
		hoot_add_css_rule( array(
						'selector'  => 'h1, h2, h3, h4, h5, h6, .title, .titlefont',
						'property'  => $headingproperty,
					) );
		hoot_add_css_rule( array(
						'selector'  => '.sidebar .widget-title, .sub-footer .widget-title, .footer .widget-title',
						'property'  => $headingproperty,
					) );
	}

	$subheadingproperty = array();
	if ( 'fontpo' == $subheadings_fontface )
		$subheadingproperty['font-family'] = array( '"Poppins", sans-serif' );
	elseif ( 'fontla' == $subheadings_fontface )
		$subheadingproperty['font-family'] = array( '"Lato", sans-serif' );
	elseif ( 'fontcf' == $subheadings_fontface )
		$subheadingproperty['font-family'] = array( '"Comfortaa", sans-serif' );
	elseif ( 'fontow' == $subheadings_fontface )
		$subheadingproperty['font-family'] = array( '"Oswald", sans-serif' );
	elseif ( 'fontda' == $subheadings_fontface )
		$subheadingproperty['font-family'] = array( '"Dancing Script", sans-serif' );
	elseif ( 'fontno' == $subheadings_fontface )
		$subheadingproperty['font-family'] = array( '"Noto Serif", serif' );
	elseif ( 'fontsl' == $subheadings_fontface )
		$subheadingproperty['font-family'] = array( '"Slabo 27px", serif' );
	elseif ( 'fontgr' == $subheadings_fontface )
		$subheadingproperty['font-family'] = array( 'Georgia, serif' );
	if ( 'uppercase' == $subheadings_fontface_style || 'uppercasei' == $subheadings_fontface_style )
		$subheadingproperty['text-transform'] = array( 'uppercase' );
	else
		$subheadingproperty['text-transform'] = array( 'none' );
	if ( 'standardi' == $subheadings_fontface_style || 'uppercasei' == $subheadings_fontface_style )
		$subheadingproperty['font-style'] = array( 'italic' );
	else
		$subheadingproperty['font-style'] = array( 'normal' );
	if ( !empty( $subheadingproperty ) ) {
		hoot_add_css_rule( array(
						'selector'  => '.hoot-subtitle, .entry-byline, .post-gridunit-subtitle .entry-byline, .hk-gridunit-subtitle .entry-byline, .posts-listunit-subtitle .entry-byline, .hk-listunit-subtitle .entry-byline, .content-block-subtitle .entry-byline', // @deprecated <= HootKit v1.1.3 @9.20 postgrid=>grid-widget postslist=>list-widget
						'property'  => $subheadingproperty,
					) );
	}

	$topbar_background_rgba = hoot_hex_to_rgb( $accent_color );
	$topbar_background_rgba = ( is_array( $topbar_background_rgba ) && count( $topbar_background_rgba ) == 3 ) ?
		$topbar_background_rgba['r'] . ',' . $topbar_background_rgba['g'] . ',' . $topbar_background_rgba['b'] . ',0.15' : 'rgba(0,0,0,0.03)'; // #f7f7f7
	hoot_add_css_rule( array(
						'selector'  => '#topbar',
						'property'  => array(
							'background' => $topbar_background_rgba, //array( 'rgba(255,159,156,0.15)' ), // $accent_color
							'color'      => array( 'inherit' ), // $accent_font
							),
					) );

	hoot_add_css_rule( array(
						'selector'  => '#topbar.js-search .searchform.expand .searchtext',
						'property'  => 'background',
						'value'     => '#f7f7f7', /* $content_bg_color, */ // $accent_color
					) );
	hoot_add_css_rule( array(
						'selector'  => '#topbar.js-search .searchform.expand .searchtext' . ',' . '#topbar .js-search-placeholder',
						'property'  => 'color',
						'value'     => 'inherit', // $accent_font
					) );

	$logoproperty = array();
	if ( 'fontpo' == $logo_fontface )
		$logoproperty['font-family'] = array( '"Poppins", sans-serif' );
	elseif ( 'fontla' == $logo_fontface )
		$logoproperty['font-family'] = array( '"Lato", sans-serif' );
	elseif ( 'fontcf' == $logo_fontface )
		$logoproperty['font-family'] = array( '"Comfortaa", sans-serif' );
	elseif ( 'fontow' == $logo_fontface )
		$logoproperty['font-family'] = array( '"Oswald", sans-serif' );
	elseif ( 'fontda' == $logo_fontface )
		$logoproperty['font-family'] = array( '"Dancing Script", sans-serif' );
	elseif ( 'fontno' == $logo_fontface )
		$logoproperty['font-family'] = array( '"Noto Serif", serif' );
	elseif ( 'fontsl' == $logo_fontface )
		$logoproperty['font-family'] = array( '"Slabo 27px", serif' );
	elseif ( 'fontgr' == $logo_fontface )
		$logoproperty['font-family'] = array( 'Georgia, serif' );
	if ( 'uppercase' == $logo_fontface_style )
		$logoproperty['text-transform'] = array( 'uppercase' );
	else
		$logoproperty['text-transform'] = array( 'none' );
	if ( !empty( $logoproperty ) ) {
		hoot_add_css_rule( array(
						'selector'  => '#site-title',
						'property'  => $logoproperty,
					) );
	}

	$sitetitleheadingfont = '';
	if ( 'fontpo' == $headings_fontface )
		$sitetitleheadingfont = '"Poppins", sans-serif';
	elseif ( 'fontla' == $headings_fontface )
		$sitetitleheadingfont = '"Lato", sans-serif';
	elseif ( 'fontcf' == $headings_fontface )
		$sitetitleheadingfont = '"Comfortaa", sans-serif';
	elseif ( 'fontow' == $headings_fontface )
		$sitetitleheadingfont = '"Oswald", sans-serif';
	elseif ( 'fontda' == $headings_fontface )
		$sitetitleheadingfont = '"Dancing Script", sans-serif';
	elseif ( 'fontno' == $headings_fontface )
		$sitetitleheadingfont = '"Noto Serif", serif';
	elseif ( 'fontsl' == $headings_fontface )
		$sitetitleheadingfont = '"Slabo 27px", serif';
	elseif ( 'fontgr' == $headings_fontface )
		$sitetitleheadingfont = 'Georgia, serif';
	hoot_add_css_rule( array(
						'selector'  => '.site-title-heading-font',
						'property'  => 'font-family',
						'value'     => $sitetitleheadingfont,
					) );
	hoot_add_css_rule( array(
						'selector'  => '.entry-grid .more-link',
						'property'  => 'font-family',
						'value'     => $sitetitleheadingfont,
					) );

	$hoot_style_builder->remove( array(
		// Unos < 2.9.15
		'.menu-items li.current-menu-item, .menu-items li.current-menu-ancestor, .menu-items li:hover',
		'.menu-items li.current-menu-item > a, .menu-items li.current-menu-ancestor > a, .menu-items li:hover > a',
		// Unos >= 2.9.15
		'.menu-items li.current-menu-item:not(.nohighlight), .menu-items li.current-menu-ancestor, .menu-items li:hover',
		'.menu-items li.current-menu-item:not(.nohighlight) > a, .menu-items li.current-menu-ancestor > a, .menu-items li:hover > a',
	) );
	hoot_add_css_rule( array(
						'selector'  => '.menu-items > li.current-menu-item:not(.nohighlight):after, .menu-items > li.current-menu-ancestor:after, .menu-items > li:hover:after' . ',' . '.menu-hoottag',
						'property'  => 'border-color',
						'value'     => $accent_color,
						'idtag'     => 'accent_color'
					) );
	hoot_add_css_rule( array(
						'selector'  => '.menu-items ul li.current-menu-item:not(.nohighlight), .menu-items ul li.current-menu-ancestor, .menu-items ul li:hover',
						'property'  => 'background',
						'value'     => $accent_font,
						'idtag'     => 'accent_font'
					) );
	hoot_add_css_rule( array(
						'selector'  => '.menu-items ul li.current-menu-item:not(.nohighlight) > a, .menu-items ul li.current-menu-ancestor > a, .menu-items ul li:hover > a',
						'property'  => 'color',
						'value'     => $accent_color,
						'idtag'     => 'accent_color'
					) );

	$halfwidgetmargin = false;
	if ( intval( $widgetmargin ) )
		$halfwidgetmargin = ( intval( $widgetmargin ) / 2 > 25 ) ? ( intval( $widgetmargin ) / 2 ) . 'px' : '25px';
	if ( $halfwidgetmargin )
		hoot_add_css_rule( array(
						'selector'  => '.main > .main-content-grid:first-child' . ',' . '.content-frontpage > .frontpage-area-boxed:first-child',
						'property'  => 'margin-top',
						'value'     => $halfwidgetmargin,
					) );

	hoot_add_css_rule( array(
						'selector'  => '.widget_newsletterwidget, .widget_newsletterwidgetminimal',
						'property'  => array(
							// property  => array( value, idtag, important, typography_reset ),
							'background' => array( $accent_color, 'accent_color' ),
							'color'      => array( $accent_font, 'accent_font' ),
							),
					) );

}
add_action( 'hoot_dynamic_cssrules', 'unosglow_dynamic_cssrules', 3 );


/* === Customizer Options === */


/**
 * Update theme defaults
 * Prim @priority 5
 * Prim child @priority 9
 *
 * @since 1.0
 * @access public
 * @return array
 */
if ( !function_exists( 'unosglow_default_style' ) ) :
function unosglow_default_style( $defaults ){
	$defaults = array_merge( $defaults, array(
		'accent_color'         => '#ff9f9c',
		'accent_font'          => '#ffffff',
		'widgetmargin'         => 35,
		'logo_fontface'        => 'fontda',
		'headings_fontface'    => 'fontla',
	) );
	return $defaults;
}
endif;
add_filter( 'unos_default_style', 'unosglow_default_style', 7 );

/**
 * Add Options (settings, sections and panels) to Hoot_Customize class options object
 *
 * Parent Lite/Prim add options using 'init' hook both at priority 0. Currently there is no way
 * to hook in between them. Hence we hook in later at 5 to be able to remove options if needed.
 * The only drawback is that options involving widget areas cannot be modified/created/removed as
 * those have already been used during widgets_init hooked into init at priority 1. For adding options
 * involving widget areas, we can alterntely hook into 'after_setup_theme' before lite/prim options
 * are built. Modifying/removing such options from lite/prim still needs testing.
 *
 * @since 1.0
 * @access public
 */
if ( !function_exists( 'unosglow_add_customizer_options' ) ) :
function unosglow_add_customizer_options() {

	$hoot_customize = Hoot_Customize::get_instance();

	// Modify Options
	$hoot_customize->remove_settings( array( 'logo_tagline_size', 'logo_tagline_style' ) );
	$hoot_customize->remove_settings( 'pageheader_background_location' );

	// Define Options
	$options = array(
		'settings' => array(),
		'sections' => array(),
		'panels' => array(),
	);

	$options['settings']['subheadings_fontface'] = array(
		'label'       => esc_html__( 'Sub Headings Font (Free Version)', 'unos-glow' ),
		'section'     => 'typography',
		'type'        => 'select',
		'priority'    => 207, // Non static options must have a priority
		'choices'     => array( ),
		'default'     => 'fontla',
	);

	$options['settings']['subheadings_fontface_style'] = array(
		'label'       => esc_html__( 'Sub Heading Font Style', 'unos-glow' ),
		'section'     => 'typography',
		'type'        => 'select',
		'priority'    => 207, // Non static options must have a priority
		'choices'     => array(
			'standard'   => esc_html__( 'Standard', 'unos-glow'),
			'standardi'  => esc_html__( 'Standard Italics', 'unos-glow'),
			'uppercase'  => esc_html__( 'Uppercase', 'unos-glow'),
			'uppercasei' => esc_html__( 'Uppercase Italics', 'unos-glow'),
		),
		'default'     => 'standard',
		'transport' => 'postMessage',
	);

	$options['settings']['body_fontface'] = array(
		'label'       => esc_html__( 'Body Font (Free Version)', 'unos-glow' ),
		'section'     => 'typography',
		'type'        => 'select',
		'priority'    => 207, // Non static options must have a priority
		'choices'     => array( ),
		'default'     => 'fontla',
	);

	// Add Options
	$hoot_customize->add_options( apply_filters( 'unosglow_customizer_options', array(
		'settings' => $options['settings'],
		'sections' => $options['sections'],
		'panels' => $options['panels'],
		) ) );

}
endif;
add_action( 'init', 'unosglow_add_customizer_options', 5 );

/**
 * Modify Lite customizer options
 * Prim hooks in later at priority 9
 *
 * @since 1.0
 * @access public
 */
function unosglow_modify_customizer_options( $options ){

	if ( isset( $options['settings']['widgetmargin'] ) )
		$options['settings']['widgetmargin']['input_attrs']['placeholder'] = esc_html__( 'default: 35', 'unos-glow' );
	if ( isset( $options['settings']['menu_location'] ) )
		$options['settings']['menu_location']['default'] = 'bottom';
	if ( isset( $options['settings']['logo_side'] ) )
		$options['settings']['logo_side']['default'] = 'none';
	if ( isset( $options['settings']['fullwidth_menu_align'] ) )
		$options['settings']['fullwidth_menu_align']['default'] = 'left';
	if ( isset( $options['settings']['logo_size'] ) )
		$options['settings']['logo_size']['default'] = 'large';
	if ( isset( $options['settings']['logo_custom']['attributes'] ) )
		$options['settings']['logo_custom']['attributes']['open-state'] = 'line2';
	if ( isset( $options['settings']['logo_custom'] ) )
		$options['settings']['logo_custom']['default'] = array(
			'line1'  => array( 'text' => wp_kses_post( __( '<b>Hoot</b> <em>Unos</em>', 'unos-glow' ) ), 'size' => '25px', 'font' => 'standard' ),
			'line2'  => array( 'text' => wp_kses_post( __( '<em>Glow</em>', 'unos-glow' ) ), 'size' => '100px' ),
			// 'line3'  => array( 'sortitem_hide' => 1, 'font' => 'standard' ),
			// 'line4'  => array( 'sortitem_hide' => 1, ),
		);
	if ( !empty( $options['settings']['logo_custom']['description'] ) )
		$options['settings']['logo_custom']['description'] = sprintf( esc_html__( 'Use &lt;b&gt; and &lt;em&gt; tags in "Line Text" fields below to emphasize different words. Example:%1$s%2$s&lt;b&gt;Unos&lt;/b&gt; &lt;em&gt;Glow&lt;/em&gt;%3$s', 'unos-glow' ), '<br />', '<code>', '</code>' );

	if ( isset( $options['settings']['logo_custo']['options'] ) ) {
		foreach ( $options['settings']['logo_custom']['options'] as $linekey => $linevalue ) {
			$options['settings']['logo_custom']['options'][$linekey] = array_merge( $options['settings']['logo_custom']['options'][$linekey], array(
				'accentbg' => array(
					'label'       => esc_html__( 'Accent Background', 'unos-glow' ),
					'type'        => 'checkbox',
				),
			) );
		}
	}
	if ( isset( $options['settings']['logo_fontface_style'] ) )
		$options['settings']['logo_fontface_style']['default'] = 'standard';
	return $options;
}
add_filter( 'unos_customizer_options', 'unosglow_modify_customizer_options', 7 );

/**
 * Modify customizer options before being added to Class options variable
 *
 * @since 1.0
 * @access public
 */
function unosglow_hoot_customize_add_settings( $settings ){
	$fontoptions = array( 'logo_fontface', 'headings_fontface', 'subheadings_fontface', 'body_fontface' );
	foreach ( $fontoptions as $key ) if ( !empty( $settings[ $key ] ) )
		$settings[ $key ]['choices'] = array(
			'fontla' => esc_html__( 'Standard Font 1 (Lato)', 'unos-glow'),
			'fontpo' => esc_html__( 'Standard Font 2 (Poppins)', 'unos-glow'),
			'fontcf' => esc_html__( 'Alternate Font (Comfortaa)', 'unos-glow'),
			'fontda' => esc_html__( 'Display Font 1 (Dancing Script)', 'unos-glow'),
			'fontow' => esc_html__( 'Display Font 2 (Oswald)', 'unos-glow'),
			'fontno' => esc_html__( 'Heading Font 1 (Noto Serif)', 'unos-glow'),
			'fontsl' => esc_html__( 'Heading Font 2 (Slabo)', 'unos-glow'),
			'fontgr' => esc_html__( 'Heading Font 3 (Georgia)', 'unos-glow'),
		);
	return $settings;
}
add_filter( 'hoot_customize_add_settings', 'unosglow_hoot_customize_add_settings' );

/**
 * Modify Customizer Link Section
 *
 * @since 1.0
 * @access public
 */
function unosglow_customizer_option_linksection( $lcontent ){
	if ( is_array( $lcontent ) ) {
		if ( !empty( $lcontent['demo'] ) )
			$lcontent['demo'] = str_replace( 'demo.wphoot.com/unos', 'demo.wphoot.com/unos-glow', $lcontent['demo'] );
		if ( !empty( $lcontent['install'] ) )
			$lcontent['install'] = str_replace( 'wphoot.com/support/unos', 'wphoot.com/support/unos-glow', $lcontent['install'] );
		if ( !empty( $lcontent['rateus'] ) )
			$lcontent['rateus'] = str_replace( 'wordpress.org/support/theme/unos', 'wordpress.org/support/theme/unos-glow', $lcontent['rateus'] );
	}
	return $lcontent;
}
add_filter( 'unos_customizer_option_linksection', 'unosglow_customizer_option_linksection' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @since 1.0
 * @return void
 */
function unosglow_customize_preview_js() {
	if ( file_exists( hoot_data()->child_dir . 'admin/customize-preview.js' ) )
		wp_enqueue_script( 'unosglow-customize-preview', hoot_data()->child_uri . 'admin/customize-preview.js', array( 'hoot-customize-preview', 'customize-preview' ), hoot_data()->childtheme_version, true );
}
add_action( 'customize_preview_init', 'unosglow_customize_preview_js', 12 );

/**
 * Add style tag to support dynamic css via postMessage script in customizer preview
 *
 * @since 1.0
 * @access public
 */

function unosglow_customize_dynamic_selectors( $settings ) {
	if ( !is_array( $settings ) ) return $settings;
	$hootpload = ( function_exists( 'hoot_lib_premium_core' ) ) ? 1 : '';

	$modify = array(
		'box_background_color' => array(
			'color'			=> array( 'remove' => array(), 'add' => array(), ),
			'background'	=> array( 'remove' => array(), 'add' => array(), ),
		),
		'accent_color' => array(
			'color' => array(
				'remove' => array(
				),
				'add' => array(
					'.menu-items ul li.current-menu-item > a, .menu-items ul li.current-menu-ancestor > a, .menu-items ul li:hover > a',
				),
			),
			'background' => array(
				'add' => array(
					'.widget_newsletterwidget, .widget_newsletterwidgetminimal',
				),
				'remove' => array(
					'.menu-items li.current-menu-item, .menu-items li.current-menu-ancestor, .menu-items li:hover',
					'.social-icons-icon',
				),
			),
			'border-color' => array(
				'add' => array(
					'.menu-items > li.current-menu-item:after, .menu-items > li.current-menu-ancestor:after, .menu-items > li:hover:after' . ',' . '.menu-hoottag',
				),
			),
		),
		'accent_font' => array(
			'color' => array(
				'add' => array(
					'.widget_newsletterwidget, .widget_newsletterwidgetminimal',
				),
				'remove' => array(
					'.menu-items li.current-menu-item > a, .menu-items li.current-menu-ancestor > a, .menu-items li:hover > a',
					'#topbar .social-icons-icon, #page-wrapper .social-icons-icon',
				),
			),
			'background' => array(
				'remove' => array(
				),
				'add' => array(
					'.menu-items ul li.current-menu-item, .menu-items ul li.current-menu-ancestor, .menu-items ul li:hover',
				),
			),
		),
	);

	if ( !$hootpload ) {
		array_push( $modify['accent_color']['background']['remove'], '#topbar', '#topbar.js-search .searchform.expand .searchtext' );
		array_push( $modify['accent_font']['color']['remove'], '#topbar', '#topbar.js-search .searchform.expand .searchtext', '#topbar .js-search-placeholder' );
		/* array_push( $modify['box_background_color']['background']['add'], '#topbar.js-search .searchform.expand .searchtext' ); */
		$modify['headings_fontface_style']['text-transform']['add'] = array( '.sidebar .widget-title, .sub-footer .widget-title, .footer .widget-title' );
	}

	foreach ( $modify as $id => $props ) {
		foreach ( $props as $prop => $ops ) {
			foreach ( $ops as $op => $values ) {
				if ( $op == 'remove' ) {
					foreach ( $values as $val ) {
						$akey = array_search( $val, $settings[$id][$prop] );
						if ( $akey !== false ) unset( $settings[$id][$prop][$akey] );
					}
				} elseif ( $op == 'add' ) {
					foreach ( $values as $val ) {
						$settings[$id][$prop][] = $val;
					}
				}
			}
		}
	}

	if ( !$hootpload ) {
		$settings['subheadings_fontface_style'] = array(
			'font-style'=> array( '.hoot-subtitle, .entry-byline, .post-gridunit-subtitle .entry-byline, .hk-gridunit-subtitle .entry-byline, .posts-listunit-subtitle .entry-byline, .hk-listunit-subtitle .entry-byline, .content-block-subtitle .entry-byline' ), // @deprecated <= HootKit v1.1.3 @9.20 postgrid=>grid-widget postslist=>list-widget
		);
		$settings['subheadings_fontface_style_trans'] = array(
			'text-transform'=> array( '.hoot-subtitle, .entry-byline, .post-gridunit-subtitle .entry-byline, .hk-gridunit-subtitle .entry-byline, .posts-listunit-subtitle .entry-byline, .hk-listunit-subtitle .entry-byline, .content-block-subtitle .entry-byline' ), // @deprecated <= HootKit v1.1.3 @9.20 postgrid=>grid-widget postslist=>list-widget
		);
	}

	return $settings;
}
add_filter( 'hoot_customize_dynamic_selectors', 'unosglow_customize_dynamic_selectors', 5 );


/* === Fonts === */


/**
 * Build URL for loading Google Fonts
 * Priority@5 : Prim loads at priority 10
 *
 * @since 1.0
 * @access public
 * @return void
 */
function unosglow_google_fonts_preparearray( $fonts ) {
	$fonts = array();

		$modsfont = array( hoot_get_mod( 'body_fontface' ), hoot_get_mod( 'logo_fontface' ), hoot_get_mod( 'headings_fontface' ), hoot_get_mod( 'subheadings_fontface' ) );

		if ( in_array( 'fontpo', $modsfont ) ) {
			$fonts[ 'Poppins' ] = array(
				'normal' => array( '400','500','700' ),
				'italic' => array( '400','500','700' ),
			);
		}
		if ( in_array( 'fontla', $modsfont ) ) {
			$fonts[ 'Lato' ] = array(
				'normal' => array( '300','400','500','700' ),
				'italic' => array( '400','700' ),
			);
		}
		if ( in_array( 'fontcf', $modsfont ) ) {
			$fonts[ 'Comfortaa' ] = array(
				'normal' => array( '400','700' ),
			);
		}
		if ( in_array( 'fontow', $modsfont ) ) {
			$fonts[ 'Oswald' ] = array(
				'normal' => array( '400' ),
			);
		}
		if ( in_array( 'fontda', $modsfont ) ) {
			$fonts[ 'Dancing Script' ] = array(
				'normal' => array( '400', '700' ),
			);
		}
		if ( in_array( 'fontno', $modsfont ) ) {
			$fonts[ 'Noto Serif' ] = array(
				'normal' => array( '400','700' ),
				'italic' => array( '400','700' ),
			);
		}
		if ( in_array( 'fontsl', $modsfont ) ) {
			$fonts[ 'Slabo 27px' ] = array(
				'normal' => array( '400' ),
			);
		}

	return $fonts;
}
add_filter( 'unos_google_fonts_preparearray', 'unosglow_google_fonts_preparearray', 5, 2 );

/**
 * Modify the font (websafe) list
 * Font list should always have the form:
 * {css style} => {font name}
 * 
 * Even though this list isn't currently used in customizer options (no typography options)
 * this is still needed so that sanitization functions recognize the font.
 * Priority@15 to overwrite Lite @priority 10
 *
 * @since 1.0
 * @access public
 * @return array
 */
function unosglow_fonts_list( $fonts ) {
	if ( !function_exists( 'hoot_lib_premium_core' ) ) {
		if ( isset( $fonts['"Lora", serif'] ) )
			unset( $fonts['"Lora", serif'] );
		if ( isset( $fonts['"Open Sans", sans-serif'] ) )
			unset( $fonts['"Open Sans", sans-serif'] );
		$fonts['"Noto Serif", serif'] = 'Noto Serif';
		$fonts['"Poppins", sans-serif'] = 'Poppins';
		$fonts['"Lato", sans-serif'] = 'Lato';
		$fonts['"Dancing Script", sans-serif'] = 'Dancing Script';
	} else {
		// let those fonts occur in their natural order as stated in hoot_googlefonts_list()
		return $fonts;
	}
	return $fonts;
}
add_filter( 'hoot_fonts_list', 'unosglow_fonts_list', 15 );


/* === Menu === */


/**
 * Add default values for Nav Menu
 *
 * @since 1.0
 */
function unosglow_nav_menu_defaults( $defaults ){
	return array(
		'tagbg' => '#ff9f9c',
		'tagfont' => '#ffffff',
	);
}
add_filter( 'unos_nav_menu_defaults', 'unosglow_nav_menu_defaults' );

/**
 * Disable menu tag hover change
 *
 * @since 1.0
 * @access public
 * @return bool
 */
function unosglow_menutag_inverthover( $enable ){
	return false;
}
add_filter( 'unos_menutag_inverthover', 'unosglow_menutag_inverthover', 5 );


/* === Misc === */


/**
 * Disable accent typography for sidebar and footer widget titles
 *
 * @since 1.0
 * @access public
 * @return bool
 */
function unosglow_sidebarwidgettitle_accenttypo( $enable ){
	return false;
}
add_filter( 'unos_sidebarwidgettitle_accenttypo', 'unosglow_sidebarwidgettitle_accenttypo', 5 );