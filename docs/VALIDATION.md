# Validation de la version 0.1.0 — 20 octobre 2026

## Accès constatés

- GitHub : dépôt accessible en lecture et écriture, branche autorisée présente; clonage effectué sur `feat/wordpress-woocommerce-theme` à partir de `c9d6d88`.
- Codex cloud : le navigateur disponible affiche la page publique Codex avec « Se connecter ». Aucune session ni tâche cloud n’a été lancée. Développement effectué directement dans Work.
- WordPress temporaire : le site répond et affiche la page de connexion avec les options GoDaddy et nom d’utilisateur/mot de passe. Aucun accès administrateur authentifié n’a été confirmé; la délégation GoDaddy et l’activation de WooCommerce restent à contrôler.

## Contrôles terminés

- Syntaxe : les 12 fichiers PHP du thème et le JavaScript passent leurs contrôles.
- Fidélité : CSS original extrait à l’identique, sept ressources locales identiques octet par octet; `index.html` et les images originales non modifiés.
- Intégration réelle isolée : WordPress 7.1.3, WooCommerce 11.2.1, PHP 8.3.6, base SQLite de test. Cette base n’est pas celle de GoDaddy.
- Support WooCommerce du thème; repli d’une collection absente; produit vedette configuré/non configuré; ajout d’un produit simple; sous-total 2 × 225 = 450; refus d’un produit en rupture; ajout d’une variation; compteur du panier; fragment AJAX; panier vide. Tests réussis avec des produits fictifs. Aucune passerelle de paiement active dans l’installation isolée.
- Six vues générées sans erreur de rendu : accueil, catalogue, produit simple, produit variable, panier rempli et commande.
- Inspection au navigateur de l’accueil, du catalogue, du produit simple, du panier et de la commande à environ 1348 px; accueil et catalogue inspectés en cadres de 390 px. Ouverture du menu mobile vérifiée (`aria-expanded=true`).
- Conflit des largeurs de cartes WooCommerce identifié visuellement puis corrigé et revérifié.
- ZIP installable préparé avec un dossier racine unique `aliments-distinction/`.

Les tests d’intégration reproductibles sont dans `tests/woocommerce-integration.php`. Ils exigent `AD_TEST_WP_ROOT`, `AD_ALLOW_TEST_FIXTURES=1` et un WordPress déclaré `local`. Ils créent puis retirent leurs produits fictifs; ils vident le panier de cette installation de test. Ne pas les lancer sur GoDaddy ni sur une base de travail à conserver.

## Limites explicites

Le superviseur de prévisualisation n’a pas pu lancer le PHP local. Les captures au navigateur sont donc des instantanés HTML effectivement générés par WordPress, servis par la prévisualisation statique. Les comportements PHP du panier ont été testés séparément en ligne de commande. La soumission HTTP des formulaires, la persistance de session entre requêtes, les blocs Panier/Commande et la création complète d’une commande ne sont pas validés de bout en bout. Aucun paiement ni envoi de courriel n’a été effectué. Les extensions du site GoDaddy, son cache et ses versions restent à tester après accès et approbation.

La langue de l’installation isolée est l’anglais : les libellés natifs WooCommerce y apparaissent en anglais; les traductions françaises dépendront de la langue et des packs installés sur le WordPress cible. Les textes propres au thème sont en français.

Les coordonnées, pages légales, recettes complètes, catalogue réel et formulaire d’infolettre ne sont pas fournis dans la maquette. Les liens de repli évitent les destinations `#` vides, mais ne remplacent pas la rédaction des pages correspondantes. Les photographies externes et les textes marketing nécessitent validation du responsable avant ouverture commerciale.

Aucune intervention sur `master`, `alimentsdistinction.com`, DNS, domaines, Zoho ou configuration WooCommerce de GoDaddy. Aucun déploiement.
