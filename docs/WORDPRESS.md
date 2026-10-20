# Thème Aliments Distinction — développement

Source de référence : `index.html` et les sept ressources à la racine, conservés sans modification. Point de départ : `c9d6d888a2aa07e375c86a4cd271cae7b53987da`. Destination exclusive : `feat/wordpress-woocommerce-theme`.

## Livrable

`wordpress/aliments-distinction/` est un thème classique autonome, sans thème parent, sans constructeur de pages et sans service payant. `assets/css/design.css` reprend exactement le CSS de la maquette; les adaptations WordPress, mobiles et commerce sont séparées. Les sept ressources originales sont copiées à l’identique. Les photographies Unsplash et les polices Google de la maquette restent externes.

L’accueil reprend la composition, les textes et les décorations de la maquette. Les gabarits communs ajoutent `wp_head`, `wp_footer`, les classes WordPress, un lien d’évitement, la navigation mobile et la gestion de la barre d’administration. Le contenu reste visible sans JavaScript. Les prix et stocks des produits vendus sont fournis exclusivement par WooCommerce.

`woocommerce.php` habille le catalogue, les catégories et les produits tout en laissant WooCommerce générer ses modèles natifs (galeries, variations, ruptures, notices, tris, pagination). Les pages normales prennent en charge les blocs Panier/Commande existants. Deux gabarits classiques facultatifs sont aussi disponibles : `page-templates/cart.php` et `page-templates/checkout.php`. Aucun paramètre de paiement, taxe, livraison, pays, devise ou commande n’est modifié par le thème.

Dans Apparence → Personnaliser → Accueil Distinction, un administrateur peut choisir l’identifiant du produit vedette et un lien de formulaire d’infolettre. Le produit vedette choisi fournit son nom, son image, sa description et son prix réels. À défaut, la section éditoriale de la maquette renvoie au catalogue sans simuler un ajout au panier et sans afficher le prix statique de 225 $. L’infolettre reste explicitement indisponible tant qu’aucun formulaire n’est relié.

## Préparation et contrôles

```sh
python scripts/check-theme.py
python scripts/package-theme.py
```

PHP 8.1+ et Node sont requis pour les contrôles. L’archive produite est `dist/aliments-distinction-0.1.0.zip`, contenant uniquement le thème. Le script de paquetage est déterministe. Ne pas téléverser le dépôt entier comme thème.

## Séquence opératoire GoDaddy — soumise à approbation

Ce document prépare une intervention de l’agent; il ne demande pas au responsable de transférer des instructions.

1. Obtenir l’approbation écrite pour installer ET activer cette version uniquement sur `1302636.us22.myftpupload.com`.
2. Se connecter par la délégation GoDaddy et vérifier le site temporaire dans le tableau de bord. Relever les versions PHP, WordPress et WooCommerce, l’état d’activation de WooCommerce, le thème actif et la disponibilité d’un point de restauration. Ne pas basculer sur le site actuel.
3. Créer un point de restauration si cette opération est disponible sans coût. Importer le ZIP, puis prévisualiser avant activation. Ne pas écraser de thème existant du même nom sans préserver sa version.
4. Installer/activer uniquement dans la portée approuvée. Une activation de WooCommerce ou une modification de sa configuration nécessite une approbation distincte si non comprise dans l’accord.
5. Vérifier les pages WooCommerce déjà assignées. Conserver leurs blocs actuels si fonctionnels. Ne pas créer automatiquement de pages ou de produits par activation du thème.
6. Refaire les contrôles visuels et le parcours panier/commande sur l’hébergement réel, en mode de test autorisé. Aucun paiement réel, aucune notification client, aucune connexion Zoho.
7. En cas d’échec, réactiver le thème précédent et utiliser le point de restauration approuvé si nécessaire. Ne jamais restaurer une sauvegarde du site actuel sur le temporaire.

## Correspondance du contenu

| Élément | Source / comportement |
| --- | --- |
| Collections | Catégories WooCommerce : `viandes`, `fruits-de-mer-poissons`, `veau-volaille-agneau`, `produits-gourmets`, `desserts`; repli vers le catalogue si absentes |
| Recherche, compte et panier | Liens WooCommerce natifs; recherche présente sur le catalogue |
| Histoire, livraison, contact | Pages publiées correspondantes si présentes; repli vers les sections de l’accueil |
| Confidentialité | Lien affiché uniquement si une politique WordPress est définie |
| Recettes | Inspirations de la maquette; lien général uniquement si une page publiée `recettes` existe |
| Infolettre | Aucun stockage d’adresse ni fausse confirmation; URL de formulaire à définir |

## Décisions avant ouverture commerciale

Valider les produits, SKU, prix, unités/portions et photos dans le nouveau catalogue, sans migration de l’ancien site. Confirmer les coordonnées de contact, la FAQ, les politiques de livraison, de confidentialité et de remboursement, les droits d’utilisation des photos, les témoignages et les affirmations commerciales de la maquette (100 % locaux, nombre de produits, financement, paiement à la livraison, etc.). Fournir les recettes complètes si ces pages sont souhaitées. Les textes de référence sont conservés, pas vérifiés commercialement.

Les taxes, zones et frais de livraison, modes de paiement, courriels transactionnels, devise et conditions commerciales seront traités dans une étape WooCommerce distincte approuvée. Zoho Inventory est hors périmètre de cette version.

## Références techniques

- https://developer.wordpress.org/themes/core-concepts/including-assets/
- https://developer.woocommerce.com/docs/theming/theme-development/classic-theme-developer-handbook/
- https://developer.woocommerce.com/docs/theming/theme-development/template-structure/
