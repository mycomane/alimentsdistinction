# Phase 2 — recette et préparation WooCommerce

## Périmètre et état initial

Passation `WORK_HANDOFF_2026-10-20.md` lue intégralement. Thème 0.1.0 déjà activé; ne pas réinstaller. Mode Coming Soon à conserver. WooCommerce 11.2.1 inactif selon le dernier audit. Les opérations sur GoDaddy sont limitées à l’audit en lecture seule, via l’environnement protégé `godaddy-temp`.

Preuves existantes vérifiées via les métadonnées GitHub Actions : installation `39504562223` réussie, y compris contrôle de sauvegarde et vérification du thème; contrôle HTTP public `39505088728` réussi au sens du job, mais Coming Soon empêche de conclure sur le rendu. Nouvel audit après activation : `39506364823`, approuvé puis terminé avec succès le 20 octobre 2026 à 19 h 18 (Toronto).

Le code du thème et `index.html` sont inchangés depuis `cf17c10`. Les nouvelles modifications de cette phase concernent exclusivement l’audit, les tests et cette documentation. Aucune activation de WooCommerce sur GoDaddy.

## Niveaux de preuve

| Niveau | Ce qu’il démontre | Limites |
| --- | --- | --- |
| Vérification statique | PHP/JS valides, CSS de maquette identique, sept images locales identiques | Ne prouve pas le rendu ni la session HTTP |
| Tests isolés WordPress/WooCommerce | Rendu et comportement avec données synthétiques, sans secrets GoDaddy | Ne couvre pas PHP web, extensions et cache GoDaddy |
| Audit SSH protégé | Identité du site, thème actif, version et état WooCommerce, empreintes de six fichiers, configuration de pages | Ne prouve pas le rendu HTTP authentifié |
| Accueil HTTP anonyme | Accessibilité et maintien de Coming Soon | Ne prouve pas l’accueil du thème |
| Recette HTTP authentifiée sur GoDaddy | Rendu réel derrière Coming Soon et interactions de navigation | Nécessite une session existante autorisée et un navigateur accepté; non disponible actuellement dans Work |

## Matrice de recette

| Test | Critère d’acceptation | Sans activation WooCommerce GoDaddy |
| --- | --- | --- |
| Accueil ordinateur 1440 px | Héro, sections, logo, images et hiérarchie fidèles à `index.html` | Vérifiable isolément; hébergement en session authentifiée |
| Tablette 768 / 1024 px | Aucun débordement horizontal ni chevauchement bloquant | Même portée |
| Mobile 320 / 390 px | Sections lisibles, actions accessibles, aucun débordement | Même portée |
| Navigation mobile | Ouverture annoncée par `aria-expanded`, fermeture par Échap, liens utilisables au clavier | Automatisé isolément |
| Images, styles, polices, scripts | Ressources locales chargées; ressources externes à contrôler séparément | Automatisable isolément; contrôle GoDaddy authentifié restant |
| Ancres et CTA | Destination existante ou repli documenté; aucune fausse inscription ni faux ajout au panier | Inspection du code et recette isolée |
| Collections / recherche | Correspondance catégories, recherche, état vide, pagination et tri | WooCommerce actif uniquement dans l’installation isolée |
| Produit simple | Prix réel WooCommerce, quantité, ajout, confirmation | Données fictives sur runner uniquement |
| Produit variable | Choix de portion requis, variation résolue, prix cohérent | Données fictives sur runner uniquement |
| Rupture de stock | État visible, absence d’ajout d’un produit indisponible | Données fictives sur runner uniquement |
| Panier | Session entre requêtes, quantité 2 puis 3, calculs 450 puis 675 | Données fictives sur runner uniquement |
| Commande | Formulaire et récapitulatif accessibles, aucune passerelle active | Pas de création ni soumission d’une commande |
| Erreurs | Aucun `pageerror`; lecture des anomalies de rendu et contrôle PHP | Journaux synthétiques seulement; pas de publication de journaux clients |

## Corrections identifiées avant publication

Les points suivants proviennent de la lecture du code, pas d’une recette complète du GoDaddy authentifié.

1. **Contrastes de la maquette** : rose `#C4857A` sur crème `#FAF7F2` ≈ 2,81:1; texte blanc sur bouton rose ≈ 3,01:1; gris `#8C8884` sur crème ≈ 3,29:1. Les petits textes de navigation, labels, boutons et pied de page concernés n’atteignent pas 4,5:1. Préparer une palette de textes et boutons plus foncée; faire valider l’écart visuel à la maquette avant déploiement. Aucun changement de palette dans cette phase.
2. **Coordonnées et pages de contenu** : les replis FAQ/contact/livraison ne remplacent pas des pages complètes. Il manque notamment un numéro cliquable pour « Commander par téléphone » et les coordonnées validées.
3. **Recettes et infolettre** : les cartes sont des inspirations, pas des recettes complètes; aucune inscription n’est traitée sans URL de formulaire. Compléter les contenus et choisir le service existant avant de présenter ces parcours comme opérationnels.
4. **Affirmations commerciales** : valider témoignages, garantie, prix, nombre de produits, origine, financement et paiement à la livraison; aucune reprise de l’ancien site.
5. **Ressources externes** : Google Fonts et Unsplash sont conservés comme dans la maquette. Vérifier les droits, la disponibilité et les performances; pas d’achat ni de téléchargement/remplacement non approuvé.
6. **Chaîne d’accès** : clé SSH épinglée conservée; confirmation indépendante de son empreinte auprès de GoDaddy encore à obtenir. Ne jamais remplacer silencieusement une clé qui diverge.

Référence contraste : https://www.w3.org/WAI/WCAG22/Understanding/contrast-minimum

## Stratégie d’aperçu privé

- Conserver Coming Soon et la restriction des déploiements. Ne pas créer d’URL publique secrète, de compte, de jeton WordPress, de mot de passe applicatif, de règle d’exclusion ou d’extension de prévisualisation sans accord.
- Vérifier que les six empreintes du thème installé correspondent à la version GitHub. Réaliser les tests navigateur sur un WordPress jetable dans le runner à partir du même thème, avec seulement des données fictives.
- Une session administrateur déjà autorisée dans un navigateur accepté permettrait ensuite le contrôle HTTP réel du site temporaire derrière Coming Soon. L’indisponibilité de cette session ne doit pas être masquée par un statut « tests réels réussis ».
- Ne pas exporter la base distante ni des clients pour les tests. Ne pas publier dans les artefacts d’un dépôt public de HTML authentifié, de captures d’administration ou de sauvegardes : les artefacts GitHub ne constituent pas un coffre privé.
- Si l’aperçu authentifié doit être automatisé, définir une méthode d’authentification limitée et son cycle de révocation, puis obtenir l’accord avant sa création. Aucun mécanisme n’est créé ici.

## Proposition pour une activation WooCommerce ultérieure

**Approbation requise avant exécution sur GoDaddy.** L’activation n’est pas une simple modification visuelle : elle peut ajouter tables et options, pages, rôles et tâches planifiées, et proposer l’assistant de configuration. Désactiver l’extension ne retire pas ces données.

Séquence à soumettre après l’audit : vérifier les exigences exactes WP/PHP de l’extension installée et le PHP web; vérifier Coming Soon et le retour arrière; inventorier les pages et options existantes; conserver une sauvegarde fraîche avant toute mutation de base; activer uniquement WooCommerce 11.2.1 sur le temporaire; relever les changements automatiques; ne pas lancer l’assistant commercial, d’extension recommandée ou de paiement. Ne pas modifier taxes, devise ou livraison. Recontrôler Coming Soon avant tout autre essai.

L’autorisation d’activation doit préciser si elle comprend les pages WooCommerce manquantes et des produits fictifs identifiables sur le temporaire. Par défaut, ni produits, ni clients, ni commandes ne seront créés sur GoDaddy. Les tests synthétiques restent confinés au runner.

Retour arrière : la réactivation de Twenty Twenty-Five ne restaure pas les changements de base de WooCommerce. Une restauration de base correspondante ne doit être envisagée qu’après accord, vérification de la sauvegarde et absence de changements concurrents à préserver. La restauration complète antérieure n’a pas encore été testée.

Référence activation : https://woocommerce.com/document/installing-uninstalling-woocommerce/


## Résultats exécutés pendant cette phase

### Recette isolée réussie

Exécution : https://github.com/mycomane/alimentsdistinction/actions/runs/39506940563 — succès.

- Versions : WordPress 7.1.3, WooCommerce 11.2.1, PHP 8.3.6 dans un runner jetable, pas le PHP GoDaddy.
- 11 assertions PHP WooCommerce réussies et 31 assertions navigateur réussies; aucun `pageerror` signalé par ces scénarios.
- Accueil testé aux largeurs 320, 390, 768, 1024 et 1440 px : largeur du document égale à celle du viewport, aucune image locale cassée détectée.
- Menu testé à 320, 390 et 768 px : ouverture et fermeture clavier Échap.
- Référence HTML et accueil WordPress : titre attendu présent; CSS et images d’origine identiques via contrôle statique.
- Produit simple : formulaire natif; ajout de 2 unités puis navigation vers le panier; quantité conservée et sous-total 450; passage à 3 unités et sous-total 675 confirmé après requête HTTP.
- Rupture : message présent et absence de bouton d’ajout sur le produit indisponible.
- Variation : choix « 250 g » résolu en identifiant de variation réel.
- Commande : formulaire présent; aucune méthode de paiement active; aucune soumission de commande.
- Aucun produit de test ni client créé sur GoDaddy. Aucune capture/HTML authentifié publié.

Ce succès est une recette automatisée ciblée, pas une comparaison pixel par pixel, un audit complet d’accessibilité ou une recette GoDaddy authentifiée. Le catalogue, les états vides, la pagination, toutes les variations et les blocs Panier/Commande demandent des scénarios complémentaires. Le scénario exécuté utilise les formulaires classiques. L’ajout effectif d’une variation a été vérifié côté PHP; côté navigateur, seule sa sélection a été vérifiée.

### Visibilité du temporaire contrôlée sans authentification

Exécution : https://github.com/mycomane/alimentsdistinction/actions/runs/39507124507 — succès.

HTTP 200, 14 365 octets. Analyse sans émission du contenu : chemins du thème présents; classe du héro absente; texte du héro absent; texte Coming Soon présent. Cela confirme une réponse publique de type Coming Soon, même si ses ressources référencent le thème. Aucune redirection suivie, aucune option modifiée. L’état interne exact de la configuration GoDaddy reste à vérifier par l’accès protégé.

### Audit distant approuvé et réussi

https://github.com/mycomane/alimentsdistinction/actions/runs/39506364823 — succès, 20 octobre 2026 à 19 h 18 (Toronto).

- Identité `siteurl` : URL temporaire autorisée confirmée.
- WordPress 7.1.3; PHP CLI 8.2.34.
- `aliments-distinction` 0.1.0 actif; `twentytwentyfive` 1.5 disponible et inactif.
- WooCommerce 11.2.1 installé et inactif. Exigences déclarées de cette extension : PHP 7.4 minimum, WordPress 7.0 minimum; les versions WordPress et PHP CLI constatées satisfont ces minima. Cela ne valide pas le PHP web ni toutes les extensions requises.
- Les six empreintes SHA256 de `style.css`, `functions.php`, `front-page.php`, `assets/css/design.css`, `assets/css/commerce.css` et `assets/js/theme.js` correspondent exactement aux fichiers de la version GitHub contrôlée.
- `show_on_front=posts`, `page_on_front=0`; l’accueil est fourni par `front-page.php`.
- Les options des quatre pages WooCommerce (catalogue, panier, commande et compte) sont absentes : aucune page WooCommerce n’est assignée. L’audit ne permet pas d’affirmer que des pages non assignées n’existent pas déjà.
- Aucun fichier, thème, extension, réglage commercial ou visibilité modifié par cet audit.
- Aucun nouveau test de restauration complète : la sauvegarde existante et ses limites restent celles de la passation. La disponibilité du thème précédent est confirmée.

### Prochaine décision

Autorisation distincte nécessaire pour activer WooCommerce 11.2.1 sur le seul temporaire et assigner les pages techniques; réutiliser les pages existantes si adaptées, sinon créer seulement les pages manquantes expressément autorisées. Cette approbation ne comprend ni produits, clients, commandes, paiements, taxes, devises, livraison ou assistant commercial. Conserver Coming Soon.

Avant exécution : confirmer le PHP web et les prérequis applicables, vérifier la protection Coming Soon et prévoir une sauvegarde fraîche avec procédure de retour arrière comprise. Si ces conditions ne sont pas confirmables, arrêter avant mutation. La désactivation de WooCommerce seule ne restaure pas sa base.

Les adaptations de contraste et de contenu demeurent des corrections à faire valider avant tout nouveau déploiement du thème.

## Suite de recette — 21 octobre 2026

### État actualisé et accès privé

WooCommerce 11.2.1 a été activé avec les quatre pages techniques, après autorisation et sauvegarde fraîche, par l’exécution `39515208953`. Voir `WOOCOMMERCE_ACTIVATION_APPROVED.md`. Les mentions d’inactivité et de demande d’activation dans les sections précédentes constituent l’historique du 20 octobre, pas l’état actuel.

L’inventaire du navigateur Work, contrôlé le 21 octobre, ne contient aucun onglet ni session connectée. Les secrets SSH restent confinés à l’environnement GitHub protégé et ne sont pas des identifiants WordPress. Aucun nouveau compte, cookie d’authentification, lien de prévisualisation public ou exclusion Coming Soon n’a été créé. Les problèmes antérieurs d’accès GoDaddy ne sont pas contournés par un nouveau navigateur.

### Proposition d’accès à faire approuver avant création

Pour tester le rendu HTTP réel, une option est un compte de recette WordPress temporaire **Abonné**, sur le seul temporaire : aucun rôle administrateur ou gestionnaire de boutique, aucun droit ajouté au rôle, aucun changement de visibilité. Les identifiants aléatoires seraient utilisés uniquement dans le job GitHub protégé, jamais imprimés, committés ou communiqués dans la conversation. Une session serait limitée à 30 minutes. Les sessions seraient révoquées et le compte supprimé en fin de contrôle; une interruption brutale pourrait laisser le compte à nettoyer, même après expiration de la session. Un inventaire préalable et une vérification finale de nettoyage seraient nécessaires.

Cette option **n’est pas créée ni autorisée par cette documentation**. Il reste à confirmer qu’un Abonné peut voir le thème derrière le Coming Soon de cette installation. Si ce rôle est insuffisant, arrêter sans l’élever et sans modifier Coming Soon. La création d’un accès plus puissant nécessiterait une autre décision explicite.

Les contrôles pourraient relever des résultats synthétiques. Les captures/HTML authentifiés ne seraient pas publiés dans le dépôt ou ses artefacts publics. Un transfert privé des images devrait être défini et approuvé avant de déclarer une recette visuelle complète. Aucun produit ou commande de test sur GoDaddy n’est inclus.

### Compléments de tests isolés

Les nouveaux scénarios couvrent l’ajout d’une variation au panier depuis le navigateur, sa coexistence avec le produit simple, le maintien de la portion, la largeur du panier à 390/1440 px, l’affichage du catalogue, la conservation de la demande de tri par prix, la recherche sans résultat, une catégorie vide, la suppression de toutes les lignes et l’absence de formulaire de commande avec un panier vide.

Les premières exécutions `39521479294` et `39521635102` ont échoué sur les deux assertions de variation. Le formulaire contenait les bons identifiants et la bonne portion. Le code WooCommerce `add-to-cart-variation.js` renseigne l’identifiant avant de déclencher `show_variation` après 300 ms et de retirer la classe de blocage du bouton. Le test a été corrigé pour attendre explicitement cet état, et pour signaler les boîtes de dialogue inattendues. Aucun fichier du thème ni WooCommerce n’a été corrigé pour ce problème de synchronisation du test.

### Résultat final des compléments

Exécution [39521852170](https://github.com/mycomane/alimentsdistinction/actions/runs/39521852170), job `118967302187`, commit `6eac9f964f76a78436e14c9481210dd43c88291a` : **succès**. Syntaxe et intégrité du design d’origine validées; **11 assertions PHP et 41 assertions navigateur réussies**, aucune erreur JavaScript ni boîte de dialogue inattendue relevée par le scénario.

L’ajout réel de la variation dans la session navigateur et le maintien de la portion sont désormais validés dans l’environnement isolé. Le contrôle de tri vérifie la conservation du paramètre et de la sélection, pas encore l’ordre numérique de toutes les fiches. Les cinq largeurs d’accueil restent sans débordement horizontal ni image locale cassée; ce n’est pas une comparaison pixel par pixel. Aucune nouvelle inspection visuelle authentifiée sur GoDaddy n’a été réalisée; aucun paiement ni commande n’a été soumis.

Cette reprise ne déploie aucun fichier sur GoDaddy et n’y crée aucun compte ni donnée de test. Les seuls changements GitHub concernent les tests et ce compte rendu, sur la branche autorisée.
