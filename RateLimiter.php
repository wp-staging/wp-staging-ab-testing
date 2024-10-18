<?php

namespace WpStaging\AbTesting;

if (!defined('ABSPATH')) {
    exit;
}

class RateLimiter
{
    private int $maxRequests = 10;
    private int $timeFrame = 60; // 60 seconds

    public function init()
    {
        add_action('init', [$this, 'startSession']);
    }

    public function startSession()
    {
        if (!session_id()) {
            session_start();
        }

        $clientIp = $_SERVER['REMOTE_ADDR'];

        if (!isset($_SESSION['rate_limit'][$clientIp])) {
            $_SESSION['rate_limit'][$clientIp] = [];
        }

        // Clear old requests
        $_SESSION['rate_limit'][$clientIp] = array_filter(
            $_SESSION['rate_limit'][$clientIp],
            function ($timestamp) {
                return $timestamp >= time() - $this->timeFrame;
            }
        );

        // Check if limit is exceeded
        if (count($_SESSION['rate_limit'][$clientIp]) >= $this->maxRequests) {
            wp_die('Rate limit exceeded. Please try again later.', 429);
        }

        // Record the current request timestamp
        $_SESSION['rate_limit'][$clientIp][] = time();
    }
}
