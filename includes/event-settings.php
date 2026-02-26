<?php
if (!defined('ABSPATH')) exit;
// Ajouter champs capacité + clôture sur les événements
add_action('add_meta_boxes', function () {
    add_meta_box(
        'fi_event_settings',
        'Inscriptions événement',
        'fi_event_settings_box',
        'evenement',
        'side'
    );
});
function fi_event_settings_box($post)
{
    $capacity = get_post_meta($post->ID, 'fi_capacity', true);
    $closed   = get_post_meta($post->ID, 'fi_closed', true);
    ?>
    <p>
        <label>Capacité max :</label><br>
        <input type="number" name="fi_capacity" value="<?php echo esc_attr($capacity); ?>">
    </p>
    <p>
        <label>
            <input type="checkbox" name="fi_closed" value="1" <?php checked($closed, 1); ?>>
            Clôturer les inscriptions
        </label>
    </p>
    <?php
}
// Sauvegarde 
add_action('save_post_evenement', function ($post_id) {
    if (isset($_POST['fi_capacity'])) {
        update_post_meta($post_id, 'fi_capacity', intval($_POST['fi_capacity']));
    }
    update_post_meta($post_id, 'fi_closed', isset($_POST['fi_closed']) ? 1 : 0);
});