<?php
/**
 * CreateAI Hostinger Pulse functions and definitions
 */

add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    register_nav_menus([
        'primary' => __('Primary Menu', 'cai'),
    ]);
});
