<?php
/* Archive Template for Travel Packages */
get_header();
?>
<div class="container my-5">
    <h1 class="text-center"><?php post_type_archive_title(); ?></h1>

    <!-- Filter Form -->
    <form method="GET" class="mb-5">
        <div class="row g-2">
            <div class="col-md-4">
                <input type="number" name="price_min" class="form-control" placeholder="<?php _e( 'Min Price', 'travel-packages' ); ?>" value="<?php echo isset( $_GET['price_min'] ) ? esc_attr( $_GET['price_min'] ) : ''; ?>" step="0.01" min="0">
            </div>
            <div class="col-md-4">
                <input type="number" name="price_max" class="form-control" placeholder="<?php _e( 'Max Price', 'travel-packages' ); ?>" value="<?php echo isset( $_GET['price_max'] ) ? esc_attr( $_GET['price_max'] ) : ''; ?>" step="0.01" min="0">
            </div>
            <div class="col-md-4">
                <?php
                $terms = get_terms( array(
                    'taxonomy'   => 'availability',
                    'hide_empty' => false,
                ) );
                ?>
                <select name="availability" class="form-select">
                    <option value=""><?php _e( 'All', 'travel-packages' ); ?></option>
                    <?php foreach ( $terms as $term ) : ?>
                        <option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( isset( $_GET['availability'] ) ? $_GET['availability'] : '', $term->slug ); ?>>
                            <?php echo esc_html( $term->name ); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-12 mt-3 text-center">
                <button type="submit" class="btn btn-primary"><?php _e( 'Filter', 'travel-packages' ); ?></button>
                <a href="<?php echo get_post_type_archive_link( 'travel_package' ); ?>" class="btn btn-secondary"><?php _e( 'Reset', 'travel-packages' ); ?></a>
            </div>
        </div>
    </form>

    <?php if ( have_posts() ) : ?>
        <div class="row g-4">
            <?php while ( have_posts() ) : the_post(); ?>
                <div class="col-md-4">
                    <div class="card h-100 travel-package" data-aos="fade-up">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="position-relative">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail( 'medium', array( 'class' => 'card-img-top' ) ); ?>
                                    <div class="img-overlay">
                                        <div class="overlay-text"><?php _e( 'View Details', 'travel-packages' ); ?></div>
                                    </div>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="<?php the_permalink(); ?>" class="text-decoration-none text-dark">
                                    <?php the_title(); ?>
                                </a>
                            </h5>
                            <p class="card-text"><?php echo wp_trim_words( get_the_content(), 15, '...' ); ?></p>
                        </div>
                        <div class="card-footer">
                            <p class="mb-1"><strong><?php _e( 'Price:', 'travel-packages' ); ?></strong> <?php echo esc_html( get_post_meta( get_the_ID(), '_tp_price', true ) ); ?></p>
                            <?php
                            // Get availability terms
                            $availability_terms = get_the_terms( get_the_ID(), 'availability' );
                            if ( $availability_terms && ! is_wp_error( $availability_terms ) ) {
                                $availabilities = wp_list_pluck( $availability_terms, 'name' );
                                $availability = implode( ', ', $availabilities );
                            } else {
                                $availability = __( 'Not specified', 'travel-packages' );
                            }
                            ?>
                            <p class="mb-0"><strong><?php _e( 'Availability:', 'travel-packages' ); ?></strong> <?php echo esc_html( $availability ); ?></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation" class="mt-5">
            <?php
            the_posts_pagination( array(
                'mid_size'  => 2,
                'prev_text' => __( '&laquo; Previous', 'travel-packages' ),
                'next_text' => __( 'Next &raquo;', 'travel-packages' ),
                'screen_reader_text' => __( 'Travel Packages navigation', 'travel-packages' ),
                'class'     => 'pagination justify-content-center',
            ) );
            ?>
        </nav>
    <?php else : ?>
        <p class="text-center"><?php _e( 'No travel packages found.', 'travel-packages' ); ?></p>
    <?php endif; ?>
</div>
<?php get_footer(); ?>
