<?php

namespace WpStaging\AbTesting;

if (!defined('ABSPATH')) {
    exit;
}

class ApiHandler
{
    private Database $database;

    public function __construct()
    {
        $this->database = new Database();
    }

    public function init()
    {
        add_action('init', [$this, 'handleRequests']);
    }

    public function handleRequests()
    {
        if (isset($_GET['wp-staging-ab-tests']) && $_GET['wp-staging-ab-tests'] === 'track') {
            $this->trackEvent();
            exit;
        }
    }

    private function validateApiKey(): bool
    {
        $headers = $this->getAllHeaders();
        $apiKey = $headers['X-Api-Key'] ?? '';

        return $apiKey === WP_STAGING_AB_TESTS_API_KEY;
    }

    private function trackEvent()
    {
        // Ensure the request is a POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('HTTP/1.1 405 Method Not Allowed');
            echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
            exit;
        }

        // Check if the API key is valid
        if (!$this->validateApiKey()) {
            header('HTTP/1.1 403 Forbidden');
            echo json_encode(['status' => 'error', 'message' => 'Invalid API key']);
            exit;
        }

        // Retrieve and validate JSON input
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['test']) || !isset($data['variant']) || !isset($data['event'])) {
            header('HTTP/1.1 400 Bad Request');
            echo json_encode(['status' => 'error', 'message' => 'Missing parameters']);
            exit;
        }

        // Insert data into the database using the Database class
        $result = $this->database->insertTestEvent($data['test'], $data['variant'], $data['event']);

        if ($result) {
            // Respond with success
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success']);
        } else {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['status' => 'error', 'message' => 'Failed to insert event']);
        }

        exit;
    }

    private function getAllHeaders()
    {
        if (!function_exists('getallheaders')) {
            $headers = [];
            foreach ($_SERVER as $name => $value) {
                if (substr($name, 0, 5) === 'HTTP_') {
                    $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
                }
            }
            return $headers;
        }
        return getallheaders();
    }
}
