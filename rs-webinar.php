<?php
/**
 * Plugin Name: RS Webinar
 * Description: A simple plugin to display webinars.
 * Version: 1.0
 * Author: Rick Sanchez
 * Author URI: https://ricksanchez.ir
 */

defined('ABSPATH') or die('No script kiddies please!');

function rs_webinar_enqueue_scripts() {
    wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');
    wp_enqueue_style('vazir-font', 'https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font@latest/dist/font-face.css');
    wp_enqueue_script('rs-webinar-script', plugins_url('/js/webinar.js', __FILE__), array('jquery'), null, true);
}

add_action('wp_enqueue_scripts', 'rs_webinar_enqueue_scripts');

function rs_webinar_shortcode() {
    ob_start(); ?>
    
    <div class="header" id="header">
        <h1>وبینارهای پیشنهادی <a href="https://ricksanchez.ir" style="color: inherit;">ریک سانچز</a></h1>
    </div>

    <div class="container">
        <div id="webinarList" class="row"></div>
    </div>

    <div class="footer">
        <p>برنامه نویسی شده به صورت متن باز توسط <a href="https://ricksanchez.ir" target="_blank">ریک سانچز 🤍</a> | قدرت گرفته از <a href="https://eseminar.tv" target="_blank">ایسمینار</a> | از <a href="https://github.com/rastikerdar/vazir-font" target="_blank">فونت وزیرمتن</a> استفاده شده به یاد صابر راستی کردار.</p>
        <div class="social-icons mb-2">
            <a class="github-button" href="https://github.com/m4tinbeigi-official/event" data-icon="octicon-star" data-show-count="true" aria-label="Star m4tinbeigi-official/event on GitHub">Star</a> 
            <script async defer src="https://buttons.github.io/buttons.js"></script>
            <a href="https://github.com/m4tinbeigi-official" role="button"><i class="fab fa-github"></i></a>
            <a href="https://twitter.com/m4tinbeigi" role="button"><i class="fab fa-twitter"></i></a>
            <a href="https://linkedin.com/in/matinbeigi" role="button"><i class="fab fa-linkedin"></i></a>
            <a href="https://instagram.com/m4tinbeigi" role="button"><i class="fab fa-instagram"></i></a>
        </div>
    </div>

    <?php return ob_get_clean();
}

add_shortcode('webinar_list', 'rs_webinar_shortcode');
