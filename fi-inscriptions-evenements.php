<?php
/**
 * Plugin Name: FI Inscriptions Événements
 * Description: Inscriptions individuelles ou groupes pour les événements.
 * Version: 1.0
 * Author: Elodie
 */
if (!defined('ABSPATH')) exit;
require_once plugin_dir_path(__FILE__) . 'includes/cpt-inscription.php';
require_once plugin_dir_path(__FILE__) . 'includes/event-settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/form-display.php';
require_once plugin_dir_path(__FILE__) . 'includes/form-handler.php';
require_once plugin_dir_path(__FILE__) . 'includes/emails.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-columns.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-metabox.php';
require_once plugin_dir_path(__FILE__) . 'includes/admin-save.php';

add_action('wp_enqueue_scripts', function () {
    if (!is_singular('evenement')) return;

    wp_enqueue_script(
        'fi-inscription-toggle',
        plugin_dir_url(__FILE__) . 'assets/js/inscription.js',
        [],
        '1.0.0',
        true
    );
});