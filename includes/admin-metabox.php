<?php
if (!defined('ABSPATH')) exit;

add_action('add_meta_boxes', function () {
    add_meta_box(
        'fi_inscription_details',
        'Détails de l’inscription',
        'fi_inscription_details_box',
        'fi_inscription',
        'normal',
        'high'
    );
});

function fi_inscription_details_box($post)
{
    // Nonce de sécurité
    wp_nonce_field('fi_inscription_save', 'fi_inscription_nonce');

    // Récup metas
    $type        = get_post_meta($post->ID, 'fi_type', true);
    $nb          = get_post_meta($post->ID, 'fi_nb', true);
    $email       = get_post_meta($post->ID, 'fi_email', true);
    $tel         = get_post_meta($post->ID, 'fi_tel', true);
    $event_id    = get_post_meta($post->ID, 'fi_event_id', true);

    $org         = get_post_meta($post->ID, 'fi_org', true);
    $resp        = get_post_meta($post->ID, 'fi_responsable', true);
    $commune     = get_post_meta($post->ID, 'fi_commune', true);


    ?>
    <style>
        .fi-grid { display:grid; grid-template-columns: 220px 1fr; gap:10px 16px; align-items:center; }
        .fi-grid label { font-weight:600; }
        .fi-grid input[type="text"], .fi-grid input[type="email"], .fi-grid input[type="number"], .fi-grid select, .fi-grid textarea { width:100%; }
        .fi-grid textarea { min-height: 90px; }
        .fi-sep { grid-column: 1 / -1; margin: 10px 0; }
    </style>

    <div class="fi-grid">

        <label for="fi_event_id">Événement</label>
        <select id="fi_event_id" name="fi_event_id">
            <option value="">— Sélectionner —</option>
            <?php
            $events = get_posts([
                'post_type'      => 'evenement',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'orderby'        => 'title',
                'order'          => 'ASC',
            ]);
            foreach ($events as $ev) :
                ?>
                <option value="<?php echo (int) $ev->ID; ?>" <?php selected((int)$event_id, (int)$ev->ID); ?>>
                    <?php echo esc_html($ev->post_title); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="fi_type">Type</label>
        <select id="fi_type" name="fi_type">
            <option value="individuelle" <?php selected($type, 'individuelle'); ?>>Individuelle</option>
            <option value="groupe" <?php selected($type, 'groupe'); ?>>Groupe</option>
        </select>

        <label for="fi_nb">Nombre de participants</label>
        <input id="fi_nb" type="number" name="fi_nb" min="1" value="<?php echo esc_attr($nb); ?>">

        <label for="fi_email">Email</label>
        <input id="fi_email" type="email" name="fi_email" value="<?php echo esc_attr($email); ?>">

        <label for="fi_tel">Téléphone</label>
        <input id="fi_tel" type="text" name="fi_tel" value="<?php echo esc_attr($tel); ?>">

        <div class="fi-sep"><hr></div>

        <label for="fi_org">Établissement / Organisation</label>
        <input id="fi_org" type="text" name="fi_org" value="<?php echo esc_attr($org); ?>">

        <label for="fi_responsable">Responsable</label>
        <input id="fi_responsable" type="text" name="fi_responsable" value="<?php echo esc_attr($resp); ?>">

        <label for="fi_commune">Adresse / commune</label>
        <input id="fi_commune" type="text" name="fi_commune" value="<?php echo esc_attr($commune); ?>">



    </div>
    <?php
}