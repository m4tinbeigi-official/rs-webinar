<?php
/**
 * Plugin Name: RS Webinar
 * Description: A simple plugin to display webinars from E-Seminar API.
 * Version: 1.0
 * Author: Rick Sanchez
 * Author URI: https://ricksanchez.ir
 */

defined('ABSPATH') or die('No script kiddies please!');

// Enqueue Styles and Scripts
function rs_webinar_enqueue_scripts() {
    wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');
    wp_enqueue_style('vazir-font', 'https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font@latest/dist/font-face.css');
    wp_enqueue_script('rs-webinar-script', plugins_url('/js/webinar.js', __FILE__), array('jquery'), null, true);
}

add_action('wp_enqueue_scripts', 'rs_webinar_enqueue_scripts');

// Shortcode to Display Webinars
function rs_webinar_shortcode() {
    ob_start();

    // Fetch webinar data from E-Seminar API
    $webinars_json_url = 'webinars.json'; // URL to your JSON file or API endpoint

    // Fetching webinar data
    $response = wp_remote_get($webinars_json_url);
    
    if (is_wp_error($response)) {
        return '<p>خطا در بارگذاری وبینارها.</p>'; // Error message if fetching fails
    }
    
    $data = json_decode(wp_remote_retrieve_body($response), true);
    
    if (empty($data['webinars'])) {
        return '<p>هیچ وبیناری یافت نشد.</p>'; // Message if no webinars are found
    }
    
    echo '<div class="header" id="header"><h1>وبینارهای پیشنهادی <a href="https://ricksanchez.ir" style="color: inherit;">ریک سانچز</a></h1></div>';
    echo '<div class="container"><div id="webinarList" class="row">';
    
    // Process each webinar from the fetched data
    foreach ($data['webinars'] as $slug) {
        $api_url = "https://api.eseminar.tv/api/v1/webinar/{$slug}";
        $api_response = wp_remote_get($api_url);
        
        if (is_wp_error($api_response)) {
            continue; // Skip if fetching this particular webinar fails
        }
        
        $webinar_data = json_decode(wp_remote_retrieve_body($api_response), true);
        
        if ($webinar_data['status'] !== "success") {
            continue; // Skip if the response status is not success
        }
        
        $webinar = $webinar_data['data']['webinar'];
        $startAt = new DateTime($webinar['start_at']);
        $cover = esc_url($webinar['cover']);
        
        // Only show upcoming webinars
        if ($startAt > new DateTime()) {
            echo '<div class="col-md-4 col-sm-6">';
            echo '<div class="webinar-card card">';
            echo '<img src="' . $cover . '" alt="' . esc_attr($webinar['title']) . '">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . esc_html($webinar['title']) . '</h5>';
            echo '<p class="card-text">' . esc_html($webinar['description']) . '</p>';
            echo '<p><strong>تاریخ شروع:</strong> ' . esc_html($startAt->format('Y-m-d H:i:s')) . '</p>';
            echo '<a class="btn btn-primary" href="https://eseminar.tv/webinar/' . esc_attr($slug) . '" target="_blank">ثبت نام در وبینار</a>';
            echo '</div></div></div>';
        }
    }
    
    echo '</div></div>';
    return ob_get_clean();
}

add_shortcode('webinar_list', 'rs_webinar_shortcode');
