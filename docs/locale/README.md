# Localisation française — préparation fr_CA

21 octobre 2026, America/Toronto. Le thème 0.1.2 est installé avec succès; l’interface native observée est en `en_US`. Julien demande de continuer vers le français.

## Rectification du diagnostic précédent

Les champs du premier diagnostic nommés `core_fr_pack` / `woo_fr_pack` vérifiaient le chemin construit depuis la locale actuelle **en_US**. Leur résultat `false` ne prouve pas l’absence des paquets français. Les logs bruts restent conservés; leur interprétation a été corrigée dans le suivi et la preuve JSON. Le nouvel inventaire ciblera explicitement `fr_CA` et `fr_FR`.

## Choix technique à tester

**Français du Canada (`fr_CA`)**, avec les traductions officielles correspondant aux versions installées WordPress 7.1.3 / WooCommerce 11.2.1. Sources vérifiées :

- https://api.wordpress.org/translations/core/1.0/?version=7.1.3
- https://api.wordpress.org/translations/plugins/1.0/?slug=woocommerce&version=11.2.1

[ZIP et empreintes épinglés](fr-ca-packages.json). Aucun téléchargement flottant lors d’une future opération : les SHA256 approuvés doivent correspondre, sinon arrêt et nouvelle revue. Préparateur `scripts/locale/prepare-packages.py` : chemins exclusivement fr_CA core et `plugins/woocommerce-fr_CA`, extensions MO/PO/JSON/L10N.PHP, refus des traversées/symlinks/doublons, volumes bornés et inventaire complet. Les paquets officiels contiennent des fichiers PHP de traduction; aucun code tiers ajouté au thème.

## Périmètre de configuration envisagé

- Installer les traductions officielles épinglées si nécessaire et modifier uniquement la langue WordPress `WPLANG` vers `fr_CA`.
- Conserver thème 0.1.2, Coming Soon, utilisateurs, produits et toutes les options commerciales (devises, adresses, taxes, livraison, paiements).
- Ne pas réécrire les textes de confidentialité, pages, noms de catégories ou produits enregistrés. Les contenus français restent un chantier éditorial distinct à valider.
- Nouvelle revue humaine GitHub `godaddy-temp` avant toute opération distante, sauvegarde privée fraîche SQL/fichiers concernés, contrôle d’inventaire avant/après et retour arrière précis de la langue et des seuls fichiers de traduction touchés.
- Aucun compte de recette ou produit fictif sur GoDaddy. Tests fonctionnels seulement dans l’installation jetable; aucune commande soumise.

## État

Payload officiel de 311 fichiers vérifié localement; tests isolés fr_CA à exécuter. Aucune nouvelle opération GoDaddy. Aucun résultat de recette anticipé.
