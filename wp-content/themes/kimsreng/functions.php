<?php
/**
 * Kimsreng functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Kimsreng
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function kimsreng_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on Kimsreng, use a find and replace
		* to change 'kimsreng' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'kimsreng', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'kimsreng' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'kimsreng_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'kimsreng_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function kimsreng_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'kimsreng_content_width', 640 );
}
add_action( 'after_setup_theme', 'kimsreng_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function kimsreng_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'kimsreng' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'kimsreng' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'kimsreng_widgets_init' );	

/**
 * Enqueue scripts and styles.
 */
function kimsreng_scripts() {
	wp_enqueue_style( 'kimsreng-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_enqueue_style( 'kimsreng-main', get_template_directory_uri() . '/css/main.css');
	wp_enqueue_style( 'bootsrap-main', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css');
	
	wp_style_add_data( 'kimsreng-style', 'rtl', 'replace' );

	wp_enqueue_script( 'kimsreng-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );
	wp_enqueue_script( 'bootstrap-popper','https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js',array('jquery'));
	wp_enqueue_script( 'bootstrap-script','https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js',array('jquery'));
	wp_enqueue_script( 'kimsreng-script',get_template_directory_uri() ,'/js/script.js',array('jquery'));





	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'kimsreng_scripts' );

/**
 * custom font
 */

function enqueue_custom_fonts() {
	if(!is_admin()) {
		wp_register_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');
		wp_enqueue_style('google-fonts');
		wp_register_style('montserrat', 'https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Oswald&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100..900;1,100..900&family=Source+Code+Pro:ital,wght@0,200..900;1,200..900&family=Titillium+Web:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700&display=swap');
		wp_enqueue_style('montserrat');
	}
}
add_action('wp_enqueue_scripts','enqueue_custom_fonts');
/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Show cart contents / total Ajax
 */
add_filter( 'woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment' );

function woocommerce_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;

	ob_start();

	?>
	<a class="cart-customlocation" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php _e('View your shopping cart', 'woothemes'); ?>"><?php echo sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count);?> – <?php echo $woocommerce->cart->get_cart_total(); ?></a>
	<?php
	$fragments['a.cart-customlocation'] = ob_get_clean();
	return $fragments;
}


// footer widget one

function custom_footer_widget_one() {
	$args = array(
		'id'            => 'footer-widget-col-one',
		'name'          => __('Footer Column One','text_domain'),
		'description'   => __('column one' , 'text_domain'),
		'before_widget' => '<h3 class="title">',
		'after_title'   => '</h3>',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>'
	);
	register_sidebar( $args );
}
add_action( 'widgets_init', 'custom_footer_widget_one' );


// footer widget two

function custom_footer_widget_two() {
	$args = array(
		'id'            => 'footer-widget-col-two',
		'name'          => __('Footer Column Two','text_domain'),
		'description'   => __('column one' , 'text_domain'),
		'before_widget' => '<h3 class="title">',
		'after_title'   => '</h3>',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>'
	);
	register_sidebar( $args );
}
add_action( 'widgets_init', 'custom_footer_widget_two' );


// footer widget three

function custom_footer_widget_three() {
	$args = array(
		'id'            => 'footer-widget-col-three',
		'name'          => __('Footer Column Three','text_domain'),
		'description'   => __('column' , 'text_domain'),
		'before_widget' => '<h3 class="title">',
		'after_title'   => '</h3>',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>'
	);
	register_sidebar( $args );
}
add_action( 'widgets_init', 'custom_footer_widget_three' );

// Modify search to include posts and pages
function search_posts_and_pages($query) {
    if ($query->is_search && !is_admin()) {
        $query->set('post_type', array('post', 'page'));
    }
    return $query;
}
add_filter('pre_get_posts', 'search_posts_and_pages');




/////////////////////////////
// Enqueue jQuery for AJAX
function enqueue_live_search_script() {
    wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'enqueue_live_search_script');

// Live Search Shortcode for Elementor
function live_search_shortcode() {
    ob_start(); ?>

    <input type="text" id="live-search" placeholder="Search posts & pages..." style="width:100%; padding:10px; border:1px solid #ccc;" onkeyup="fetchLiveSearch()">
    <div id="search-results" style="margin-top:10px;"></div>

    <script>
    function fetchLiveSearch() {
        var keyword = jQuery('#live-search').val();
        if (keyword.length < 2) {
            jQuery('#search-results').html('');
            return;
        }

        jQuery.ajax({
            type: 'POST',
            url: '<?php echo admin_url('admin-ajax.php'); ?>',
            data: {
                action: 'fetch_live_search',
                keyword: keyword
            },
            success: function(response) {
                jQuery('#search-results').html(response);
            }
        });
    }
    </script>

    <?php
    return ob_get_clean();
}

add_shortcode('live_search', 'live_search_shortcode');

// AJAX Function to Fetch Search Results
function fetch_live_search_results() {
    if (!isset($_POST['keyword'])) {
        wp_die();
    }

    $keyword = sanitize_text_field($_POST['keyword']);

    $args = [
        'post_type'      => ['post', 'page'], // Searches both posts & pages
        's'              => $keyword,
        'posts_per_page' => 10,
    ];

    $query = new WP_Query($args);
    $output = '<ul style="list-style:none; padding:0;">';

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $output .= '<li style="padding:5px 0;"><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></li>';
        }
    } else {
        $output .= '<li>No results found.</li>';
    }

    $output .= '</ul>';
    wp_reset_postdata();
    echo $output;
    wp_die();
}

add_action('wp_ajax_fetch_live_search', 'fetch_live_search_results');
add_action('wp_ajax_nopriv_fetch_live_search', 'fetch_live_search_results'); // Allows non-logged-in users to search
// Add custom post type for "About Us"


add_filter('template_include', 'custom_template_override');
function custom_template_override($template) {
    // Custom logic that might not include the_content()
    return get_stylesheet_directory() . '/custom-template.php';
}
function custom_new_order_email_recipient($recipient, $order) {
    // Add your custom email address
    $recipient .= 'natkimsreng@gmail.com'; // Append to existing recipients
    // Or replace entirely: $recipient = 'customemail@example.com';
    return $recipient;
}
add_action('woocommerce_review_order_after_payment', 'restore_place_order_button');
function restore_place_order_button() {
    echo '<button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order">' . __('Place Order', 'woocommerce') . '</button>';
}
add_theme_support('woocommerce');
