<?php
if (!defined('ABSPATH')) exit;

add_action('save_post_fi_inscription', function ($post_id) {

    // Autosave / révision
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (wp_is_post_revision($post_id)) return;

    // Droits
    if (!current_user_can('edit_post', $post_id)) return;

    // Nonce
    if (
        !isset($_POST['fi_inscription_nonce']) ||
        !wp_verify_nonce($_POST['fi_inscription_nonce'], 'fi_inscription_save')
    ) {
        return;
    }

    // Helpers
    $update_text = function($key) use ($post_id) {
        if (!isset($_POST[$key])) return;
        update_post_meta($post_id, $key, sanitize_text_field($_POST[$key]));
    };

    // Champs
    if (isset($_POST['fi_event_id'])) {
        update_post_meta($post_id, 'fi_event_id', (int) $_POST['fi_event_id']);
        // utile si tu as ajouté le tri par meta (optionnel)
        $eid = (int) $_POST['fi_event_id'];
        update_post_meta($post_id, 'fi_event_title', $eid ? get_the_title($eid) : '');
    }

    if (isset($_POST['fi_type'])) {
        $type = ($_POST['fi_type'] === 'groupe') ? 'groupe' : 'individuelle';
        update_post_meta($post_id, 'fi_type', $type);
    }

    if (isset($_POST['fi_nb'])) {
        update_post_meta($post_id, 'fi_nb', max(1, (int) $_POST['fi_nb']));
    }

    if (isset($_POST['fi_email'])) {
        update_post_meta($post_id, 'fi_email', sanitize_email($_POST['fi_email']));
    }

    $update_text('fi_tel');
    $update_text('fi_org');
    $update_text('fi_responsable');
    $update_text('fi_commune');
  


}, 10, 1);