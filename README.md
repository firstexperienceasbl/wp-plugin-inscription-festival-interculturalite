# Plugin Inscriptions Festival Interculturalité

## 📌 Fonctionnalités principales

- Création d’un Custom Post Type **Inscriptions**
- Affichage d’un formulaire d’inscription côté public
- Gestion des inscriptions individuelles et groupes
- Traitement et validation des données
- Envoi d’emails automatiques
- Colonnes personnalisées dans l’admin
- Métabox personnalisée dans l’admin
- Sauvegarde sécurisée des données

## ⚙️ Fonctionnement général

1. Le CPT `inscription` stocke les demandes.
2. Le formulaire est généré via `form-display.php`.
3. Les données sont traitées via `form-handler.php`.
4. Les emails sont envoyés via `emails.php`.
5. L’administration est enrichie via :
   - Colonnes personnalisées
   - Métabox dédiée
   - Sauvegarde sécurisée

## 🧠 Logique technique

- Architecture modulaire (séparation claire des responsabilités)
- Séparation front / admin
- JavaScript dédié aux interactions dynamiques
- Traitement serveur sécurisé
- Respect de la structure WordPress (hooks, includes, CPT)

## 🚀 Installation

1. Placer le dossier dans : wp-content/plugins/
2. Activer le plugin depuis l’admin WordPress.
3. Intégrer le formulaire selon l’implémentation prévue dans le thème.

---

## 📦 Dépendances

- WordPress 6+
- PHP 8+

---

## 👩‍💻 Projet

Plugin développé dans le cadre du projet  
**Festival de l’Interculturalité – First Experience**

---