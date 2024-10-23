# Travel Packages Plugin

**Contributors:** Mohammad Taghipoor  
**Tags:** travel, packages, booking, custom post type, taxonomy, AJAX, WordPress  
**Requires at least:** 5.0  
**Tested up to:** 6.2  
**Requires PHP:** 7.0  
**Stable tag:** 2.6  
**License:** GPLv2 or later  
**License URI:** [https://www.gnu.org/licenses/gpl-2.0.html](https://www.gnu.org/licenses/gpl-2.0.html)

## Description

The **Travel Packages Plugin** allows you to create and manage travel packages on your WordPress website seamlessly. With features like custom post types, taxonomies, AJAX-powered booking forms, and customizable templates, this plugin offers a comprehensive solution for showcasing and booking travel packages.

### Key Features

- **Custom Post Type:** Create and manage **Travel Packages** with ease.
- **Custom Taxonomy:** Categorize packages using the **Availability** taxonomy (e.g., Available, Sold Out, Limited Availability).
- **Meta Boxes:** Add custom fields like price to your travel packages.
- **AJAX Booking Form:** Allow users to book packages without page reloads.
- **Custom Templates:** Override default templates with your own for archive and single views.
- **Filter Functionality:** Enable users to filter packages based on price range and availability.
- **Responsive Design:** Built with Bootstrap for mobile-friendly layouts.
- **Animations:** Integrates AOS (Animate On Scroll) library for engaging animations.
- **Translation Ready:** Fully internationalized for multilingual support.

---

## Table of Contents

1. [Installation](#installation)
2. [Activation](#activation)
3. [Usage](#usage)
    - [Adding Travel Packages](#adding-travel-packages)
    - [Managing Availability](#managing-availability)
    - [Booking a Travel Package](#booking-a-travel-package)
    - [Filtering Packages](#filtering-packages)
4. [Shortcodes](#shortcodes)
5. [Template Integration](#template-integration)
6. [Troubleshooting](#troubleshooting)
7. [Frequently Asked Questions (FAQ)](#frequently-asked-questions-faq)
8. [Changelog](#changelog)
9. [License](#license)

---

## Installation

### 1. Uploading the Plugin

1. **Download the Plugin:**
   - Obtain the plugin ZIP file (`travel-packages-plugin.zip`).

2. **Install via WordPress Admin:**
   - Log in to your WordPress admin dashboard.
   - Navigate to **Plugins > Add New**.
   - Click on the **Upload Plugin** button at the top.
   - Choose the downloaded `travel-packages-plugin.zip` file and click **Install Now**.

3. **Manual Installation:**
   - Extract the `travel-packages-plugin.zip` file.
   - Upload the `travel-packages-plugin` folder to the `/wp-content/plugins/` directory via FTP.

### 2. Activate the Plugin

1. **Activate via Admin Dashboard:**
   - After installation, navigate to **Plugins > Installed Plugins**.
   - Locate **Travel Packages Plugin** in the list.
   - Click on **Activate**.

2. **Plugin Activation Hook:**
   - Activation triggers the `register_activation_hook`, which:
     - Registers the **Travel Package** post type and **Availability** taxonomy.
     - Inserts default terms for **Availability**.
     - Flushes rewrite rules to ensure permalinks work correctly.

---

## Usage

### Adding Travel Packages

1. **Navigate to Travel Packages:**
   - In the WordPress admin dashboard, go to **Travel Packages > Add New**.

2. **Create a New Package:**
   - **Title:** Enter the name of the travel package.
   - **Content:** Provide a detailed description of the package.
   - **Price:** In the **Travel Package Details** meta box, enter the price of the package.
   - **Featured Image:** Set a featured image to represent the package visually.

3. **Set Availability:**
   - In the **Availability** meta box (usually on the right sidebar), select the availability status from the dropdown (e.g., Available, Sold Out, Limited Availability).

4. **Publish:**
   - Once all details are filled in, click **Publish** to make the package live.

### Managing Availability

1. **Accessing Availability Terms:**
   - Navigate to **Travel Packages > Availabilities**.

2. **Adding New Terms:**
   - Click on **Add New Availability**.
   - Enter the name of the new availability status and click **Add New Availability**.

3. **Editing or Deleting Terms:**
   - Hover over an existing term and choose to **Edit** or **Delete** as needed.

### Booking a Travel Package

1. **Frontend Booking:**
   - On the single travel package page, click the **Book Now** button to open the booking modal.

2. **Filling the Form:**
   - Enter your **Name**, **Email**, and **Preferred Dates**.
   - Click **Submit** to send your booking request.

3. **AJAX Submission:**
   - The form submits via AJAX, providing real-time feedback without reloading the page.
   - Success or error messages will display based on the submission outcome.

4. **Admin Notifications:**
   - Upon successful booking, an email is sent to the site administrator with the booking details.
   - A confirmation email is optionally sent to the user.

### Filtering Packages

1. **Accessing the Archive Page:**
   - Visit the **Travel Packages** archive page on your website (e.g., `yourwebsite.com/travel-packages/`).

2. **Using the Filter Form:**
   - **Price Range:** Enter the minimum and/or maximum price to filter packages within that range.
   - **Availability:** Select an availability status to filter packages accordingly.
   - Click **Filter** to apply the filters.
   - Click **Reset** to clear all filters and view all packages.

---

## Shortcodes

*Currently, the plugin does not include additional shortcodes beyond the default functionalities. Future updates may introduce more shortcode options for enhanced flexibility.*

---

## Template Integration

The plugin includes custom templates for both the archive and single views of **Travel Packages**. These templates can be overridden by placing similarly named files in your active theme's directory.

### Overriding Templates

1. **Locate Plugin Templates:**
   - **Archive Template:** `travel-packages-plugin/templates/archive-travel_package.php`
   - **Single Template:** `travel-packages-plugin/templates/single-travel_package.php`

2. **Copy to Theme:**
   - Copy the desired template file to your theme's root directory or a specific subdirectory as needed.
   - For example, copy `archive-travel_package.php` to `your-theme/`.

3. **Customize:**
   - Modify the copied template file to suit your design and functionality requirements.
   - WordPress will prioritize theme templates over plugin templates.

---

## Troubleshooting

### Availability Taxonomy Not Visible on Edit Page

**Solution:**

1. **Ensure Plugin is Active:**
   - Verify that the **Travel Packages Plugin** is activated in **Plugins > Installed Plugins**.

2. **Check Screen Options:**
   - On the edit screen, click **Screen Options** at the top right.
   - Ensure that the **Availability** checkbox is checked.

3. **Flush Rewrite Rules:**
   - Navigate to **Settings > Permalinks**.
   - Click **Save Changes** without modifying any settings to flush rewrite rules.

4. **Check for Conflicts:**
   - Deactivate other plugins to rule out conflicts.
   - Switch to a default WordPress theme to ensure the theme isn't causing issues.

5. **Enable Debugging:**
   - Edit your `wp-config.php` file to enable debugging:
     ```php
     define( 'WP_DEBUG', true );
     define( 'WP_DEBUG_LOG', true );
     define( 'WP_DEBUG_DISPLAY', false );
     ```
   - Check the `wp-content/debug.log` file for any errors related to the plugin.

6. **Verify Taxonomy Registration:**
   - Use the `[tp_debug_taxonomies]` shortcode to list registered taxonomies and ensure **Availability** is present.

### AJAX Booking Form Not Working

**Solution:**

1. **Check JavaScript Console:**
   - Open your browser's developer tools and check for JavaScript errors.

2. **Ensure jQuery is Loaded:**
   - Verify that jQuery is properly enqueued and loaded on the page.

3. **Verify AJAX URL and Nonce:**
   - Ensure that the AJAX URL and nonce are correctly passed to the JavaScript file via `wp_localize_script`.

4. **Check Server Email Configuration:**
   - Ensure that your server can send emails using `wp_mail`. Consider using SMTP plugins if emails are not being sent.

5. **Review AJAX Handlers:**
   - Confirm that the AJAX handlers (`tp_handle_booking_form`) are correctly hooked and functional.

### Styles and Scripts Not Loading

**Solution:**

1. **Verify File Paths:**
   - Ensure that `style.css` and `main.js` are correctly located in the `assets/css/` and `assets/js/` directories respectively.

2. **Check Enqueue Functions:**
   - Confirm that the `tp_enqueue_assets` function is properly hooked to `wp_enqueue_scripts`.

3. **Clear Caches:**
   - Clear any caching plugins or server-side caches that might prevent new styles or scripts from loading.

---

## Frequently Asked Questions (FAQ)

### 1. **Can I add more custom fields to the Travel Package post type?**

**Answer:**  
Yes! You can extend the functionality by adding more meta boxes or utilizing custom fields plugins like Advanced Custom Fields (ACF) for additional fields.

### 2. **Is the booking information stored in the database?**

**Answer:**  
Yes, each booking submission is saved as a custom post type (`tp_booking`). You can manage bookings via **Bookings** in the admin dashboard.

### 3. **How can I customize the email templates sent upon booking?**

**Answer:**  
To customize the email templates, modify the `wp_mail` functions within the `tp_handle_booking_form` function in the main plugin file. You can adjust the HTML and content as needed.

### 4. **Does the plugin support translation?**

**Answer:**  
Yes, the plugin is fully internationalized and translation-ready. You can add translation files in the `languages/` directory or use translation plugins like Loco Translate.

### 5. **How do I update the availability terms?**

**Answer:**  
Navigate to **Travel Packages > Availabilities** in the admin dashboard. Here, you can add, edit, or delete availability terms as required.

---

## Changelog

### 2.6

- **Added:**
  - Custom Post Type for **Booking** (`tp_booking`) to manage bookings.
  - Enhanced AJAX booking form with success and error messages.
  - Shortcode `[tp_debug_taxonomies]` for debugging taxonomy registrations.

- **Fixed:**
  - Resolved issue where the **Availability** taxonomy wasn't appearing on the edit screen.
  - Improved enqueueing of scripts and styles to ensure proper loading order.

### 2.5

- **Added:**
  - Integration with AOS (Animate On Scroll) library for engaging animations.
  - Custom templates for archive and single views.
  - Filter functionality on the archive page for price range and availability.

- **Fixed:**
  - Corrected taxonomy registration order to ensure proper association with the post type.
  - Enhanced AJAX security with nonce verification.

### 2.0

- **Initial Release:**
  - Creation of **Travel Package** custom post type.
  - **Availability** taxonomy registration.
  - Meta boxes for adding package details like price.
  - AJAX-powered booking form.
  - Enqueueing of necessary scripts and styles.
  - Responsive design with Bootstrap integration.

---

## Support

For support, contact the plugin author directly at [developer8640@gmail.com](mailto:developer8640@gmail.com).

---

## Contributing

Contributions are welcome! Please fork the repository and submit a pull request with your enhancements or bug fixes.

---

## Acknowledgments

- [Bootstrap](https://getbootstrap.com/) for the responsive framework.
- [AOS (Animate On Scroll)](https://michalsnik.github.io/aos/) for the scroll animations.
- WordPress community for continuous support and inspiration.

---

**Enjoy managing your travel packages seamlessly with the Travel Packages Plugin!**

---
