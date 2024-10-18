<?php

namespace WpStaging\AbTesting;

if (!defined('ABSPATH')) {
    exit;
}

class AssetsLoader
{
    public function init()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
    }

    public function enqueueScripts()
    {
        // Enqueue common utilities
        wp_enqueue_script(
            'wp-staging-ab-tests-common',
            plugin_dir_url(__FILE__) . 'assets/js/common.js',
            [],
            '1.0.0',
            true
        );

        // Enqueue specific tests
        wp_enqueue_script(
            'wp-staging-ab-tests-test1',
            plugin_dir_url(__FILE__) . 'assets/js/test1-bounce-rate.js',
            ['wp-staging-ab-tests-common'], // Load common.js first
            '1.0.0',
            true
        );

        wp_enqueue_script(
            'wp-staging-ab-tests-test2',
            plugin_dir_url(__FILE__) . 'assets/js/test2-user-flow.js',
            ['wp-staging-ab-tests-common'],
            '1.0.0',
            true
        );
    }
}
