<?php
if (!defined('ABSPATH')) exit;

add_action('init', function () {
    if (!isset($_POST['fi_submit'])) return;
    if (!wp_verify_nonce($_POST['fi_nonce'], 'fi_inscription_action')) return;

    $event_id = (int) ($_POST['fi_event_id'] ?? 0);
    if ($event_id <= 0) return;

    $capacity = (int) get_post_meta($event_id, 'fi_capacity', true);
    $inscrits = fi_count_participants($event_id);

    // PLUS DE CHECKBOX : on lit directement les quantités
    $adult_nb = max(1, (int) ($_POST['fi_adult_nb'] ?? 1));
    $child_nb = max(0, (int) ($_POST['fi_child_nb'] ?? 0));

    $demande = $adult_nb + $child_nb;

    if ($demande <= 0) {
        wp_die("Veuillez sélectionner au moins 1 participant.");
    }

    if ($capacity > 0 && ($inscrits + $demande) > $capacity) {
        wp_die("Limite atteinte ou événement complet.");
    }

    if (!isset($_POST['fi_rgpd'])) {
        wp_die("Consentement RGPD obligatoire.");
    }

    $email = sanitize_email($_POST['fi_email'] ?? '');
    $nom   = sanitize_text_field($_POST['fi_nom'] ?? '');
    $prenom= sanitize_text_field($_POST['fi_prenom'] ?? '');

    $id = wp_insert_post([
        'post_type'   => 'fi_inscription',
        'post_title'  => $nom . " " . $prenom,
        'post_status' => 'publish',
        'meta_input'  => [
            'fi_type'        => sanitize_text_field($_POST['fi_type'] ?? 'individuelle'),
            'fi_event_id'    => $event_id,
            'fi_event_title' => get_the_title($event_id),
            'fi_email'       => $email,
            'fi_tel'         => sanitize_text_field($_POST['fi_tel'] ?? ''),

            // participants
            'fi_nb'        => $demande,   // total (capacité + admin)
            'fi_adult_nb'  => $adult_nb,
            'fi_child_nb'  => $child_nb,

            // (tu gardes le reste si tu veux le stocker)
            'fi_org'          => sanitize_text_field($_POST['fi_org'] ?? ''),
            'fi_commune'      => sanitize_text_field($_POST['fi_commune'] ?? ''),
        ]
    ]);

    fi_send_confirmation($email, $event_id);

    // message succès via transient
    $token = wp_generate_uuid4();
    set_transient('fi_insc_' . $token, [
        'nom'    => $nom,
        'prenom' => $prenom,
        'nb'     => $demande,
    ], 10 * MINUTE_IN_SECONDS);

    $redirect = wp_get_referer();
    if (!$redirect) $redirect = get_permalink($event_id);

    $redirect = add_query_arg([
        'inscription' => 'ok',
        'fi_token'    => $token,
    ], $redirect);

    wp_safe_redirect($redirect);
    exit;
});

function fi_count_participants($event_id)
{
    $q = new WP_Query([
        'post_type'      => 'fi_inscription',
        'posts_per_page' => -1,
        'meta_key'       => 'fi_event_id',
        'meta_value'     => $event_id,
    ]);

    $total = 0;
    foreach ($q->posts as $p) {
        $total += (int) get_post_meta($p->ID, 'fi_nb', true);
    }
    return $total;
}