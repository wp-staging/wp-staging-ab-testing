<?php

namespace WpStaging\AbTesting;

use WpStaging\AbTesting\RateLimiter;

if (!defined('ABSPATH')) {
    exit;
}

class Main
{
    public function init()
    {

        require_once plugin_dir_path(__FILE__) . 'Database.php';
        require_once plugin_dir_path(__FILE__) . 'RateLimiter.php';
        require_once plugin_dir_path(__FILE__) . 'AssetsLoader.php';
        require_once plugin_dir_path(__FILE__) . 'ApiHandler.php';

        $rateLimiter = new RateLimiter();
        $rateLimiter->init();

        $assetsLoader = new AssetsLoader();
        $assetsLoader->init();

        $apiHandler = new ApiHandler();
        $apiHandler->init();
    }
}
