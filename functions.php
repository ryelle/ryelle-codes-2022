<?php
/**
 * Ryelle 2022 functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Ryelle 2022
 */

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function ryelle_2022_setup() {
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );

	register_block_style(
		'core/separator',
		array(
			'name'         => 'half',
			'label'        => 'Half-width',
			'is_default'   => true,
			'inline_style' => '.wp-block-separator.is-style-half { height: 0 !important; width: auto !important; } .wp-block-separator.is-style-half::after {content: ""; display: block; width: 45%; min-width: 10em; height: var(--wp--custom--border--horizontal); background: currentColor; opacity: 1; }',
		)
	);

	register_block_style(
		'core/spacer',
		array(
			'name'         => 'line',
			'label'        => 'Line',
			'is_default'   => false,
			'inline_style' => '.wp-block-spacer.is-style-line { margin-left: auto; margin-right: auto; width: 0; border-right: 2px dotted var(--wp--preset--color--blue); opacity: 0.5; }',
		)
	);
}
add_action( 'after_setup_theme', 'ryelle_2022_setup' );

/**
 * Enqueue the assets.
 */
function ryelle_2022_assets() {
	$version = filemtime( __DIR__ . '/build/blocks.css' );
	wp_register_style( 'ryelle-2022-blocks', get_template_directory_uri() . '/build/blocks.css', [], $version );

	$version = filemtime( __DIR__ . '/build/style.css' );
	wp_register_style( 'ryelle-2022-style', get_template_directory_uri() . '/build/style.css', [ 'ryelle-2022-blocks' ], $version );

	wp_enqueue_style( 'ryelle-2022-style' );
}
add_action( 'wp_enqueue_scripts', 'ryelle_2022_assets' );

add_action(
	'mkaz_prism_css_path',
	function() {
		return '/build/syntax.css';
	}
);

/**
 * Enqueue the editor assets.
 */
function ryelle_2022_editor_styles() {
	add_editor_style(
		array(
			'./build/blocks.css',
			'./build/style.css',
		)
	);
}
add_action( 'admin_init', 'ryelle_2022_editor_styles' );

/**
 * Remove dashicons in frontend for unauthenticated users.
 */
function ryelle_2022_dequeue_dashicons() {
	if ( ! is_user_logged_in() ) {
		wp_deregister_style( 'dashicons' );
	}
}
add_action( 'wp_enqueue_scripts', 'ryelle_2022_dequeue_dashicons' );

/**
 * Comments template block: Add a message when no comments are found.
 *
 * @param string $block_content
 * @return string
 */
function ryelle_2022_render_no_comments( $block_content ) {
	if ( '' === $block_content ) {
		return '<p class="has-small-font-size">No comments.</p>';
	}
	return $block_content;
}
add_action( 'render_block_core/comment-template', 'ryelle_2022_render_no_comments' );

/**
 * Preload the web fonts to improve performance.
 *
 * ETC Trispace & IBM Plex Sans (normal and italics) are used on every page.
 * Does not preload `Fira Code`, since that is only used for code blocks.
 */
function ryelle_2022_preload_assets() {
	$font_urls = array(
		get_theme_file_uri( 'fonts/etc-trispace.woff2' ),
		get_theme_file_uri( 'fonts/IBMPlexSansVar-Roman.woff2' ),
		get_theme_file_uri( 'fonts/IBMPlexSansVar-Italic.woff2' ),
	);

	foreach ( $font_urls as $url ) {
		$ext = pathinfo( $url, PATHINFO_EXTENSION );
		?>
		<link rel="preload" href="<?php echo esc_url( $url ); ?>" as="font" type="font/woff2" crossorigin>
		<?php
	}
}
add_action( 'wp_head', 'ryelle_2022_preload_assets' );

add_filter(
	'syntax_highlighting_code_block_auto_detect_languages',
	function() {
		return [ 'css', 'scss', 'php', 'html', 'javascript' ];
	}
);
