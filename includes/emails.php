<?php
if (!defined('ABSPATH')) exit;
function fi_send_confirmation($email, $event_id)
{
    $subject = "Confirmation d’inscription";
    $message = "Merci, votre inscription à l’événement est bien enregistrée.\n\n";
    $message .= "Événement : " . get_the_title($event_id);
    wp_mail($email, $subject, $message);
}