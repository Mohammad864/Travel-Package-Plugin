<?php
/* Single Template for Travel Packages */
get_header();
?>
<div class="container my-5">
    <?php while ( have_posts() ) : the_post(); ?>
        <div class="travel-package-single">
            <h1 class="mb-5 text-center"><?php the_title(); ?></h1>

            <div class="row">
                <?php
                // Fetch attached images
                $images = get_attached_media( 'image', $post->ID );
                if ( $images ) :
                ?>
                    <div id="packageCarousel" class="carousel slide col-md-6" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php
                            $active = ' active';
                            foreach ( $images as $image ) {
                                $img_url = wp_get_attachment_image_src( $image->ID, 'large' )[0];
                                echo '<div class="carousel-item' . $active . '">';
                                echo '<img src="' . esc_url( $img_url ) . '" class="d-block w-100 img-fluid rounded" alt="">';
                                echo '</div>';
                                $active = '';
                            }
                            ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#packageCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden"><?php _e( 'Previous', 'travel-packages' ); ?></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#packageCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden"><?php _e( 'Next', 'travel-packages' ); ?></span>
                        </button>
                    </div>
                <?php else : ?>
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="col-md-6">
                            <?php the_post_thumbnail( 'large', array( 'class' => 'img-fluid rounded' ) ); ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

                <div class="col-md-6">
                    <div class="mb-4">
                        <p><strong><?php _e( 'Price:', 'travel-packages' ); ?></strong> <?php echo esc_html( get_post_meta( get_the_ID(), '_tp_price', true ) ); ?></p>
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
                        <p><strong><?php _e( 'Availability:', 'travel-packages' ); ?></strong> <?php echo esc_html( $availability ); ?></p>
                    </div>

                    <div class="package-content">
                        <?php the_content(); ?>
                    </div>

                    <!-- Book Now Button -->
                    <div class="mt-4">
                        <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#bookingModal">
                            <?php _e( 'Book Now', 'travel-packages' ); ?>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Booking Modal -->
            <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="bookingModalLabel"><?php _e( 'Book Travel Package', 'travel-packages' ); ?></h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php _e( 'Close', 'travel-packages' ); ?>"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Booking Form -->
                            <form id="bookingForm">
                                <?php wp_nonce_field( 'tp_ajax_nonce', 'tp_nonce_field' ); ?>
                                <input type="hidden" id="package_id" name="package_id" value="<?php echo get_the_ID(); ?>">
                                <div class="mb-3">
                                    <label for="name" class="form-label"><?php _e( 'Name', 'travel-packages' ); ?></label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label"><?php _e( 'Email', 'travel-packages' ); ?></label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="dates" class="form-label"><?php _e( 'Preferred Dates', 'travel-packages' ); ?></label>
                                    <input type="text" class="form-control" id="dates" name="dates" required>
                                </div>
                                <!-- Additional form fields as needed -->
                                <button type="submit" class="btn btn-primary"><?php _e( 'Submit', 'travel-packages' ); ?></button>
                            </form>
                            <!-- Success Message -->
                            <div id="bookingSuccess" class="alert alert-success mt-3 d-none" role="alert">
                                <?php _e( 'Your booking request has been sent successfully.', 'travel-packages' ); ?>
                            </div>
                            <!-- Error Message -->
                            <div id="bookingError" class="alert alert-danger mt-3 d-none" role="alert">
                                <?php _e( 'An error occurred while sending your request. Please try again later.', 'travel-packages' ); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Back to Packages Button -->
            <div class="mt-5 text-center">
                <a href="<?php echo get_post_type_archive_link( 'travel_package' ); ?>" class="btn btn-secondary">
                    <?php _e( 'Back to Travel Packages', 'travel-packages' ); ?>
                </a>
            </div>
        </div>
    <?php endwhile; ?>
</div>
<?php get_footer(); ?>
