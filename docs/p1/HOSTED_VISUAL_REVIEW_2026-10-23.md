# P1 0.1.3 — revue visuelle hébergée bloquée par l'accès

Date : **23 octobre 2026 (UTC)**. Directive [Coordination #6354023475](https://github.com/mycomane/alimentsdistinction/issues/2#issuecomment-6354023475). Branche exclusive : `feat/wordpress-woocommerce-theme`; tête constatée avant documentation : `5fd06c708c8a73e727e4b995dcfc23a0565f3037`.

## Contrôle réellement effectué

Lecture intégrale de la dernière directive et vérification de la tête GitHub. Métadonnées du [déploiement 39873774537](https://github.com/mycomane/alimentsdistinction/actions/runs/39873774537) revérifiées : completed/success, SHA livré `6163fa04069b3390a03156ae97cc44df5b0c8f20`.

Inventaire des surfaces navigateur accessibles à Work : **un navigateur Chrome cloud, zéro onglet; aucune application native disponible**. Aucune session WordPress déjà autorisée et réutilisable n'est identifiable depuis cet inventaire. Cela ne prouve pas l'absence de cookies conservés ou l'absence d'une session sur l'ordinateur de Julien : les cookies n'ont pas été inspectés et le navigateur local de Julien n'est pas exposé par cet inventaire.

**Arrêt au point 4 de la directive :** « Si aucune session autorisée n'est disponible, s'arrêter ». Aucun nouvel onglet, nouveau navigateur de contournement, essai de connexion, accès SSH ou workflow lancé. Aucun mot de passe/token demandé ou inspecté. Aucun compte créé, réglage modifié, purge de cache ou désactivation Coming Soon.

Ce contrôle ne reproduit pas une erreur réseau : aucun ERR_SOCKS_CONNECTION_FAILED nouveau n'a été observé. Le blocage constaté aujourd'hui est l'absence de session autorisée identifiable, pas une preuve d'indisponibilité du site.

## État des preuves

| Catégorie | Résultat et portée |
| --- | --- |
| Confirmé sur GoDaddy par le workflow du 22 octobre | Thème 0.1.3 livré, quatre fichiers et sources statiques contrôlés, fr_CA et Coming Soon conservés, sauvegarde privée et répétition de rollback. Preuve historique du déploiement; aucune nouvelle vérification visuelle hébergée aujourd'hui. |
| Validé seulement en environnement isolé | [CI 39873774433](https://github.com/mycomane/alimentsdistinction/actions/runs/39873774433), même SHA livré : 62 assertions, 390/1440, avant/après, deux états d'infolettre, focus/survol/contact/liens, hero et version/cache. [Preuves existantes](evidence/second-protected-review-2026-10-22.json). Pas de relance coûteuse. |
| Non vérifiable sans session autorisée | Accueil réellement servi, images/fontes, menu/clavier, sections, panneau d'infolettre dans son état hébergé, Contact, textes français, navigation catalogue/fiche/panier/commande derrière Coming Soon; largeurs 1440, 390 et 320. |
| À corriger | **Aucune nouvelle anomalie visuelle hébergée démontrée.** Ne pas transformer l'absence d'accès en défaut de rendu. Texte anglais persistant de confidentialité déjà signalé; correctif séparé non autorisé ici. |

**Captures nouvelles : zéro. Comparaisons visuelles hébergées avec la maquette : zéro.** Aucun ancien aperçu isolé n'est présenté comme capture GoDaddy. Les preuves isolées restent consultables dans le dossier P1; elles ne terminent pas cette mission.

## Dossier de captures à compléter une fois l'accès disponible

Référence : `index.html` du dépôt et lot P1 approuvé, palette Actuel, thème livré 0.1.3. Aucun recours à l'ancien site commercial.

1. **Ordinateur 1440 px** : accueil complet; détails header/logo/hero, sections, infolettre dans son état réel et pied de page/« Nous joindre ».
2. **Téléphone 390 puis 320 px** : hero, menu ouvert, sections et infolettre/pied de page; noter débordements, coupures et chevauchements.
3. **Navigation clavier** : lien d'évitement, focus visible, ouverture/fermeture menu et lien Contact; capture du focus et note de destination sans soumettre de formulaire.
4. **WooCommerce en consultation** : catalogue, fiche existante si disponible (sinon noter l'absence de produit), panier vide et résultat d'accès à la commande sans transaction. Ne rien ajouter au panier ni soumettre.
5. **Tablette 768/1024 si pertinent** : seulement en cas de différence de comportement nécessitant cette vérification.

Les captures doivent montrer uniquement le frontend non sensible : exclure barre d'administration/identité du compte, données clients, cookies, accès et secrets. Pour chaque image : largeur, page, état et date. Une capture seule ne valide pas une interaction; les contrôles clavier/liens doivent être décrits séparément. Noter tout soupçon de CDN/cache sans purge.

## Décision nécessaire

La Coordination doit faire rendre disponible **une session WordPress déjà autorisée** dans un navigateur accessible à Work, ou convenir avec Julien d'un petit dossier de captures frontend non sensibles et de notes d'interaction. Aucune création de compte ni nouveau droit n'est proposé sous ce mandat. La connexion déléguée GoDaddy sur l'ordinateur de Julien peut exister sans être accessible à Work.

Si seul un dossier d'images est fourni, la comparaison visuelle sera possible, mais la validation interactive restera limitée aux notes disponibles. **P1 reste ouvert et la recette hébergée non effectuée.** Aucune modification GoDaddy ni nouvelle exécution n'est autorisée ou déclenchée par ce rapport.
