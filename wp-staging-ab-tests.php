<?php

namespace WpStaging\AbTesting;

/**
 * Plugin Name: WP STAGING - A/B testing
 * Plugin URI: https://wp-staging.com
 * Description: Run simple A/B tests on your WordPress site.
 * Author: René Hermenau
 * Author URI: https://wp-staging.com
 * Version: 0.0.1
 * Text Domain: wpstgab
 * Domain Path: languages
 *
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once plugin_dir_path(__FILE__) . 'Main.php';

define('WP_STAGING_AB_TESTS_API_KEY', '123456'); // Replace with actual API key

function init() {
    $main = new Main();
    $main->init();
}

add_action('plugins_loaded', 'WpStaging\AbTests\init');

