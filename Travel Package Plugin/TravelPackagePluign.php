<?php
/**
 * Plugin Name: Travel Packages Plugin
 * Description: Adds a custom post type for Travel Packages with custom fields and enhanced UI/UX.
 * Version: 2.6
 * Author: Mohammad Taghipoor
 * Text Domain: travel-packages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Load Plugin Text Domain
 */
function tp_load_textdomain() {
    load_plugin_textdomain( 'travel-packages', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'tp_load_textdomain' );

/**
 * Register Custom Post Type: Travel Package
 */
function tp_register_travel_package_cpt() {

    $labels = array(
        'name'                  => _x( 'Travel Packages', 'Post Type General Name', 'travel-packages' ),
        'singular_name'         => _x( 'Travel Package', 'Post Type Singular Name', 'travel-packages' ),
        'menu_name'             => __( 'Travel Packages', 'travel-packages' ),
        'name_admin_bar'        => __( 'Travel Package', 'travel-packages' ),
        'add_new_item'          => __( 'Add New Travel Package', 'travel-packages' ),
        'new_item'              => __( 'New Travel Package', 'travel-packages' ),
        'edit_item'             => __( 'Edit Travel Package', 'travel-packages' ),
        'view_item'             => __( 'View Travel Package', 'travel-packages' ),
        'all_items'             => __( 'All Travel Packages', 'travel-packages' ),
        'search_items'          => __( 'Search Travel Packages', 'travel-packages' ),
    );

    $args = array(
        'label'                 => __( 'Travel Package', 'travel-packages' ),
        'description'           => __( 'Custom Post Type for Travel Packages', 'travel-packages' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail' ),
        'public'                => true,
        'menu_icon'             => 'dashicons-palmtree',
        'has_archive'           => true,
        'rewrite'               => array( 'slug' => 'travel-packages' ),
        'show_in_rest'          => true,
    );

    register_post_type( 'travel_package', $args );

}
add_action( 'init', 'tp_register_travel_package_cpt' );

/**
 * Register Custom Taxonomy: Availability
 */
function tp_register_availability_taxonomy() {
    $labels = array(
        'name'              => _x( 'Availabilities', 'taxonomy general name', 'travel-packages' ),
        'singular_name'     => _x( 'Availability', 'taxonomy singular name', 'travel-packages' ),
        'search_items'      => __( 'Search Availabilities', 'travel-packages' ),
        'all_items'         => __( 'All Availabilities', 'travel-packages' ),
        'parent_item'       => __( 'Parent Availability', 'travel-packages' ),
        'parent_item_colon' => __( 'Parent Availability:', 'travel-packages' ),
        'edit_item'         => __( 'Edit Availability', 'travel-packages' ),
        'update_item'       => __( 'Update Availability', 'travel-packages' ),
        'add_new_item'      => __( 'Add New Availability', 'travel-packages' ),
        'new_item_name'     => __( 'New Availability Name', 'travel-packages' ),
        'menu_name'         => __( 'Availability', 'travel-packages' ),
    );

    $args = array(
        'hierarchical'      => true, // Set to true for dropdown
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'show_in_rest'      => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'availability' ),
    );

    register_taxonomy( 'availability', array( 'travel_package' ), $args );
}
add_action( 'init', 'tp_register_availability_taxonomy' );

/**
 * Insert Default Availability Terms
 */
function tp_insert_default_availability_terms() {
    $terms = array( 'Available', 'Sold Out', 'Limited Availability' );
    foreach ( $terms as $term ) {
        if ( ! term_exists( $term, 'availability' ) ) {
            wp_insert_term( $term, 'availability' );
        }
    }
}
add_action( 'init', 'tp_insert_default_availability_terms' );

/**
 * Flush Rewrite Rules on Activation
 */
function tp_flush_rewrite_rules() {
    tp_register_travel_package_cpt();
    tp_register_availability_taxonomy();
    tp_insert_default_availability_terms();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'tp_flush_rewrite_rules' );

/**
 * Add Meta Boxes for Travel Package Details
 */
function tp_add_meta_boxes() {
    add_meta_box(
        'tp_meta_box',
        __( 'Travel Package Details', 'travel-packages' ),
        'tp_meta_box_callback',
        'travel_package',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'tp_add_meta_boxes' );

/**
 * Meta Box Callback Function
 */
function tp_meta_box_callback( $post ) {
    // Add a nonce field
    wp_nonce_field( 'tp_save_meta_box_data', 'tp_meta_box_nonce' );

    // Retrieve existing values from the database
    $price = get_post_meta( $post->ID, '_tp_price', true );

    // Display the form fields
    echo '<p><label for="tp_price">' . __( 'Price:', 'travel-packages' ) . '</label>';
    echo '<input type="number" id="tp_price" name="tp_price" value="' . esc_attr( $price ) . '" size="25" step="0.01" min="0" /></p>';
}

/**
 * Save Meta Box Data
 */
function tp_save_meta_box_data( $post_id ) {
    // Check if our nonce is set.
    if ( ! isset( $_POST['tp_meta_box_nonce'] ) ) {
        return;
    }

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $_POST['tp_meta_box_nonce'], 'tp_save_meta_box_data' ) ) {
        return;
    }

    // If this is an autosave, our form has not been submitted.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    // Check the user's permissions.
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // Sanitize and save the data
    if ( isset( $_POST['tp_price'] ) ) {
        $price = floatval( $_POST['tp_price'] );
        if ( $price >= 0 ) {
            update_post_meta( $post_id, '_tp_price', $price );
        }
    }
}
add_action( 'save_post', 'tp_save_meta_box_data' );

/**
 * Enqueue Plugin Styles and Scripts
 */
function tp_enqueue_assets() {
    // Enqueue Google Fonts
    wp_enqueue_style(
        'google-fonts',
        'https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap',
        array(),
        null
    );

    // Enqueue Bootstrap CSS
    wp_enqueue_style(
        'bootstrap-css',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css',
        array(),
        '5.3.0'
    );

    // Enqueue AOS CSS
    wp_enqueue_style(
        'aos-css',
        'https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css',
        array(),
        '2.3.4'
    );

    // Enqueue Plugin CSS
    wp_enqueue_style(
        'travel-packages-style',
        plugin_dir_url( __FILE__ ) . 'assets/css/style.css',
        array( 'bootstrap-css', 'aos-css' ),
        '1.0'
    );

    // Enqueue Bootstrap JS
    wp_enqueue_script(
        'bootstrap-js',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js',
        array( 'jquery' ),
        '5.3.0',
        true
    );

    // Enqueue AOS JS
    wp_enqueue_script(
        'aos-js',
        'https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js',
        array(),
        '2.3.4',
        true
    );

    // Enqueue Plugin JS
    wp_enqueue_script(
        'travel-packages-js',
        plugin_dir_url( __FILE__ ) . 'assets/js/main.js',
        array( 'jquery', 'aos-js' ),
        '1.0',
        true
    );

    // Localize script to pass AJAX URL and nonce
    wp_localize_script(
        'travel-packages-js',
        'tp_ajax_obj',
        array(
            'ajax_url'      => admin_url( 'admin-ajax.php' ),
            'tp_nonce'      => wp_create_nonce( 'tp_ajax_nonce' ),
            'error_message' => __( 'An unexpected error occurred.', 'travel-packages' ),
        )
    );
}
add_action( 'wp_enqueue_scripts', 'tp_enqueue_assets' );

/**
 * Use Custom Templates from Plugin
 */
function tp_template_include( $template ) {
    if ( is_post_type_archive( 'travel_package' ) ) {
        $theme_template = locate_template( array( 'archive-travel_package.php' ) );
        if ( $theme_template ) {
            return $theme_template;
        } else {
            return plugin_dir_path( __FILE__ ) . 'templates/archive-travel_package.php';
        }
    } elseif ( is_singular( 'travel_package' ) ) {
        $theme_template = locate_template( array( 'single-travel_package.php' ) );
        if ( $theme_template ) {
            return $theme_template;
        } else {
            return plugin_dir_path( __FILE__ ) . 'templates/single-travel_package.php';
        }
    }
    return $template;
}
add_filter( 'template_include', 'tp_template_include' );

/**
 * Apply Filters to Travel Packages Archive
 */
function tp_apply_filters_to_archive( $query ) {
    if ( ! is_admin() && $query->is_main_query() && is_post_type_archive( 'travel_package' ) ) {
        $meta_query = array();
        $tax_query  = array();

        // Filter by Price Range
        if ( ! empty( $_GET['price_min'] ) || ! empty( $_GET['price_max'] ) ) {
            $price_min = isset( $_GET['price_min'] ) ? floatval( $_GET['price_min'] ) : 0;
            $price_max = isset( $_GET['price_max'] ) ? floatval( $_GET['price_max'] ) : PHP_INT_MAX;

            $meta_query[] = array(
                'key'     => '_tp_price',
                'value'   => array( $price_min, $price_max ),
                'type'    => 'NUMERIC',
                'compare' => 'BETWEEN',
            );
        }

        // Filter by Availability Taxonomy
        if ( ! empty( $_GET['availability'] ) ) {
            $tax_query[] = array(
                'taxonomy' => 'availability',
                'field'    => 'slug',
                'terms'    => sanitize_text_field( $_GET['availability'] ),
            );
        }

        if ( ! empty( $meta_query ) ) {
            $query->set( 'meta_query', $meta_query );
        }

        if ( ! empty( $tax_query ) ) {
            $query->set( 'tax_query', $tax_query );
        }
    }
}
add_action( 'pre_get_posts', 'tp_apply_filters_to_archive' );

/**
 * Handle AJAX Booking Form Submission
 */
function tp_handle_booking_form() {
    // Check the nonce for security
    check_ajax_referer( 'tp_ajax_nonce', 'security' );

    // Sanitize and validate form data
    $name       = sanitize_text_field( $_POST['name'] );
    $email      = sanitize_email( $_POST['email'] );
    $dates      = sanitize_text_field( $_POST['dates'] );
    $package_id = intval( $_POST['package_id'] );

    // Basic validation
    if ( empty( $name ) || empty( $email ) || empty( $dates ) || empty( $package_id ) ) {
        wp_send_json_error( array( 'message' => __( 'Please fill in all required fields.', 'travel-packages' ) ) );
    }

    if ( ! is_email( $email ) ) {
        wp_send_json_error( array( 'message' => __( 'Please provide a valid email address.', 'travel-packages' ) ) );
    }

    // Get package title
    $package_title = get_the_title( $package_id );

    // Prepare email content
    $to      = get_option( 'admin_email' );
    $subject = sprintf( __( 'New Booking Request for %s', 'travel-packages' ), $package_title );
    $headers = array( 'Content-Type: text/html; charset=UTF-8' );
    $message = '<h2>' . __( 'Booking Details', 'travel-packages' ) . '</h2>';
    $message .= '<p><strong>' . __( 'Name:', 'travel-packages' ) . '</strong> ' . esc_html( $name ) . '</p>';
    $message .= '<p><strong>' . __( 'Email:', 'travel-packages' ) . '</strong> ' . esc_html( $email ) . '</p>';
    $message .= '<p><strong>' . __( 'Preferred Dates:', 'travel-packages' ) . '</strong> ' . esc_html( $dates ) . '</p>';
    $message .= '<p><strong>' . __( 'Travel Package:', 'travel-packages' ) . '</strong> ' . esc_html( $package_title ) . '</p>';

    // Send email to admin
    $mail_sent = wp_mail( $to, $subject, $message, $headers );

    // Save booking as a custom post type (Optional)
    $booking_data = array(
        'post_title'   => 'Booking for ' . $name . ' - ' . $package_title,
        'post_status'  => 'publish',
        'post_type'    => 'tp_booking',
        'meta_input'   => array(
            'tp_booking_name'        => $name,
            'tp_booking_email'       => $email,
            'tp_booking_dates'       => $dates,
            'tp_booking_package_id'  => $package_id,
        ),
    );

    wp_insert_post( $booking_data );

    if ( $mail_sent ) {
        // Send confirmation email to the user (Optional)
        $user_subject = __( 'Your Booking Request Received', 'travel-packages' );
        $user_message = '<p>' . __( 'Dear', 'travel-packages' ) . ' ' . esc_html( $name ) . ',</p>';
        $user_message .= '<p>' . __( 'Thank you for your booking request. We will get back to you shortly.', 'travel-packages' ) . '</p>';
        $user_message .= '<p>' . __( 'Best regards,', 'travel-packages' ) . '<br>' . get_bloginfo( 'name' ) . '</p>';

        wp_mail( $email, $user_subject, $user_message, $headers );

        wp_send_json_success( array( 'message' => __( 'Your booking request has been sent successfully.', 'travel-packages' ) ) );
    } else {
        wp_send_json_error( array( 'message' => __( 'An error occurred while sending your request. Please try again later.', 'travel-packages' ) ) );
    }
}
add_action( 'wp_ajax_tp_handle_booking_form', 'tp_handle_booking_form' );
add_action( 'wp_ajax_nopriv_tp_handle_booking_form', 'tp_handle_booking_form' );

?>
