<?php
if (!defined('ABSPATH')) exit;
add_action('init', function () {
    register_post_type('fi_inscription', [
        'label' => 'Inscriptions',
        'public' => false,
        'show_ui' => true,
        'menu_icon' => 'dashicons-forms',
        'supports' => ['title'],
    ]);
});