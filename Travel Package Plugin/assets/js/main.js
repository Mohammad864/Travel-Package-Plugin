/* assets/js/main.js */

jQuery(document).ready(function($) {

    // AJAX Booking Form Submission
    $('#bookingForm').on('submit', function(e) {
        e.preventDefault();

        // Get form data
        var formData = {
            action: 'tp_handle_booking_form',
            security: $('#tp_nonce_field').val(),
            name: $('#name').val(),
            email: $('#email').val(),
            dates: $('#dates').val(),
            package_id: $('#package_id').val(),
        };

        // Clear previous messages
        $('#bookingSuccess').addClass('d-none');
        $('#bookingError').addClass('d-none').text('');

        // Disable submit button to prevent multiple submissions
        $(this).find('button[type="submit"]').prop('disabled', true);

        // Send AJAX request
        $.post(tp_ajax_obj.ajax_url, formData, function(response) {
            if (response.success) {
                // Show success message
                $('#bookingSuccess').removeClass('d-none');
                // Reset form fields
                $('#bookingForm')[0].reset();
                // Close modal after a short delay
                setTimeout(function() {
                    $('#bookingModal').modal('hide');
                }, 2000);
            } else {
                // Show error message
                $('#bookingError').removeClass('d-none').text(response.data.message);
            }
            // Re-enable submit button
            $('#bookingForm').find('button[type="submit"]').prop('disabled', false);
        }).fail(function() {
            // Handle AJAX errors
            $('#bookingError').removeClass('d-none').text(tp_ajax_obj.error_message || 'An unexpected error occurred.');
            // Re-enable submit button
            $('#bookingForm').find('button[type="submit"]').prop('disabled', false);
        });
    });

    // Initialize AOS (Animate On Scroll) Library
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true
    });

});
