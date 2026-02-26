<?php
if (!defined('ABSPATH')) exit;

/**
 * Colonnes dans la liste admin des inscriptions
 */
add_filter('manage_fi_inscription_posts_columns', function ($columns) {

    // On garde la checkbox + le titre si tu veux (ou tu peux le supprimer)
    $new = [];
    $new['cb'] = $columns['cb'];
    $new['title'] = 'Nom / Prénom';
    $new['fi_nb'] = 'Participants';
    $new['fi_type']  = 'Type';
    $new['fi_email'] = 'Email';
    // $new['fi_tel']   = 'Téléphone';
    $new['fi_event'] = 'Événement';
    $new['date']     = $columns['date'];

    return $new;
});

/**
 * Contenu des colonnes
 */
add_action('manage_fi_inscription_posts_custom_column', function ($column, $post_id) {

    if ($column === 'fi_type') {
        $type = get_post_meta($post_id, 'fi_type', true);
        echo $type ? esc_html($type) : '—';
    }

    if ($column === 'fi_email') {
        $email = get_post_meta($post_id, 'fi_email', true);
        echo $email ? esc_html($email) : '—';
    }

    // if ($column === 'fi_tel') {
    //     $tel = get_post_meta($post_id, 'fi_tel', true);
    //     echo $tel ? esc_html($tel) : '—';
    // }
    if ($column === 'fi_nb') {
        $nb = get_post_meta($post_id, 'fi_nb', true);
        echo $nb !== '' ? esc_html($nb) : '—';
    }

    if ($column === 'fi_event') {
        $event_id = (int) get_post_meta($post_id, 'fi_event_id', true);

        if ($event_id) {
            $title = get_the_title($event_id);
            $link  = get_edit_post_link($event_id);

            echo $link
                ? '<a href="' . esc_url($link) . '">' . esc_html($title) . '</a>'
                : esc_html($title);
        } else {
            echo '—';
        }
    }
}, 10, 2);

add_filter('manage_edit-fi_inscription_sortable_columns', function ($columns) {
    $columns['fi_event'] = 'fi_event_title';
    return $columns;
});

add_action('pre_get_posts', function ($query) {
    if (!is_admin() || !$query->is_main_query()) return;

    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if (!$screen || $screen->post_type !== 'fi_inscription') return;

    $orderby = $query->get('orderby');
    if ($orderby !== 'fi_event_title') return;

    $query->set('meta_key', 'fi_event_title');
    $query->set('orderby', 'meta_value'); // tri alphabétique
});