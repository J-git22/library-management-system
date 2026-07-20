<?php

namespace App\Controllers;

/**
 * Base Controller
 * Loads the models and views
 */
class BaseController {
    
    // Load model
    public function model($model) {
        // Require model file
        require_once APPROOT . '/app/models/' . $model . '.php';
        
        $modelClass = 'App\\Models\\' . $model;
        return new $modelClass();
    }
    
    // Load view
    public function view($view, $data = []) {
        // Check for view file
        if (file_exists(APPROOT . '/views/' . $view . '.php')) {
            require_once APPROOT . '/views/' . $view . '.php';
        } else {
            // View does not exist
            die('View does not exist: ' . $view);
        }
    }
}
