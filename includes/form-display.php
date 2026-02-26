<?php
if (!defined('ABSPATH')) exit;

add_shortcode('fi_inscription_evenement', function () {

    if (!is_singular('evenement')) return "";

    $event_id = get_the_ID();

    if (get_post_meta($event_id, 'fi_closed', true)) {
        return '<div class="alert alert-warning mb-0"><strong>Inscriptions clôturées.</strong></div>';
    }

    // Message succès si inscription OK (après redirection)
    if (isset($_GET['inscription'], $_GET['fi_token']) && $_GET['inscription'] === 'ok') {

        $token = sanitize_text_field($_GET['fi_token']);
        $data  = get_transient('fi_insc_' . $token);

        if (is_array($data)) {
            delete_transient('fi_insc_' . $token);

            $nom    = esc_html($data['nom'] ?? '');
            $prenom = esc_html($data['prenom'] ?? '');
            $nb     = (int) ($data['nb'] ?? 0);

            return '
        <div class="fi-inscription mt-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="alert alert-success mb-0">
                        <strong>Inscription réussie !</strong><br>
                        Au nom de ' . $nom . ' ' . $prenom . ', pour ' . $nb . ' participant(s).
                    </div>
                </div>
            </div>
        </div>';
        }
    }
    ob_start();
?>

    <div class="fi-inscription mt-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="h3 mb-4">Inscris toi !</h3>
                <p class="text-muted small">Pour les inscriptions demandant une encadrement spécifique, veuillez passer par le 
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>">formulaire de contact</a>
                </p>
                <form method="post" class="row g-3">

                    <!-- Type -->
                    <div class="col-12">
                        <label class="form-label">Type d’inscription</label>
                        <select name="fi_type" id="fi_type" class="form-select" required>
                            <option value="individuelle">Individu.s./famille (max. 5 personnes)</option>
                            <option value="groupe">Groupes (à partir de 6 personnes)</option>
                        </select>
                    </div>
                    <hr class="hr hr-blurry" />
                    <P class="text-muted small">Personne de contact / responsable</P>
                    <!-- Nom / Prénom -->
                    <div class="col-12 col-md-6">
                        <label class="form-label">Nom</label>
                        <input type="text" name="fi_nom" class="form-control" required>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Prénom</label>
                        <input type="text" name="fi_prenom" class="form-control" required>
                    </div>

                    <!-- Email / Téléphone -->
                    <div class="col-12 col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="fi_email" class="form-control" required>
                    </div>

                    <div class="col-12 col-md-6">
                        <label class="form-label">Téléphone <span class="text-muted">(optionnel)</span></label>
                        <input type="text" name="fi_tel" class="form-control">
                    </div>
                    <hr class="hr hr-blurry" />
                    <!-- Participants -->
                    <div class="row g-3">

                        <div class="col-12 col-md-6">
                            <label class="form-label" for="fi_adult_nb">Adulte(s)</label>
                            <input type="number"
                                class="form-control"
                                name="fi_adult_nb"
                                id="fi_adult_nb"
                                min="0"
                                value="0">
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label" for="fi_child_nb">Enfant(s)</label>
                            <input type="number"
                                class="form-control"
                                name="fi_child_nb"
                                id="fi_child_nb"
                                min="0"
                                value="0">
                        </div>

                        <div class="col-12">
                            <div class="mt-2">
                                Nombre total de participants :
                                <strong><span id="fi_total">0</span></strong>
                            </div>
                        </div>

                    </div>

                    <!-- Groupe -->
                    <div id="fi-bloc-groupe" class="row g-3" hidden>
                        <hr class="" />
                        <!-- Groupe -->
                        <div class="col-12 col-md-6">
                            <label class="form-label">Établissement / Organisation</label>
                            <input type="text" name="fi_org" class="form-control">
                        </div>

                        <div class="col-12 col-md-6">
                            <label class="form-label">Adresse </label>
                            <input type="text" name="fi_adresse" class="form-control">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Commune</label>
                            <input type="text" name="fi_commune" class="form-control">
                        </div>
                    </div>

                    <!-- RGPD -->
                    <div class="col-12">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="fi_rgpd" value="1" id="fi_rgpd" required>
                            <label class="form-check-label" for="fi_rgpd">
                                J’accepte le traitement des données (RGPD)
                            </label>
                        </div>
                    </div>

                    <?php wp_nonce_field('fi_inscription_action', 'fi_nonce'); ?>
                    <input type="hidden" name="fi_event_id" value="<?php echo esc_attr($event_id); ?>">

                    <!-- Submit -->
                    <div class="col-12">
                        <button type="submit" name="fi_submit" class="btn btn-primary w-100">
                            Envoyer l’inscription
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
<?php

    return ob_get_clean();
});
