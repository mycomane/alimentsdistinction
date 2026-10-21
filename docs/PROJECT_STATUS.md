# Aliments Distinction — suivi Work / Coordination

Mise à jour : 21 octobre 2026 (America/Toronto).

## Référence et phase en cours

Base vérifiée : `82eccba3bdd3e5fc1b1920d6ce33a882291374f7` sur `feat/wordpress-woocommerce-theme`. La feuille de route, la passation, les recettes privée et isolée et le bilan d’activation WooCommerce ont été lus intégralement. Dernières exécutions existantes : QA isolé `39531003567` et recette privée `39531003568`, réussies.

Dernier commit de correction validé : `9618095516d982e10eebfe5c7aa0cdcc4bf85be3`.

**P0 terminé en développement : correctif CSS réel validé, non déployé.** P1/P2 : recette complémentaire et propositions de contraste préparées, nouveau run en attente, en environnement isolé uniquement.

## Terminé

- État existant conservé : thème 0.1.0 et WooCommerce 11.2.1 déjà actifs, quatre pages techniques, Coming Soon.
- Copie de travail propre créée au commit de base; modification locale préexistante de `volaille.jpg` préservée et exclue.
- Scénario de géométrie préparé aux cinq largeurs 320/390/768/1024/1440 : enfants du bloc de réassurance, photo, titre, CTA, débordement et focus du menu.
- Captures comparatives prévues uniquement depuis le runner isolé, sans accès ni données GoDaddy.
- Diagnostic `9f567b6cde56ff1c9a86e089cd0fec68e14e7d55`, [run 39605089716](https://github.com/mycomane/alimentsdistinction/actions/runs/39605089716) réussi : le retour à la ligne du troisième indicateur ajoute 52,6 px. Son retrait retrouve exactement les mesures de référence aux cinq largeurs. Rapport : `P0_MOBILE_2026-10-21.md`.
- Correction CSS : suppression de la seule règle mobile `flex-wrap: wrap` de `.hero__trust`; aucun changement à la maquette originale.
- Régression du correctif réel réussie : [QA 39605707095](https://github.com/mycomane/alimentsdistinction/actions/runs/39605707095), 61 assertions de géométrie/focus + 41 navigateur + 11 PHP. Écart final de 0,00 px aux cinq largeurs.
- Build/ZIP candidat [39605707177](https://github.com/mycomane/alimentsdistinction/actions/runs/39605707177) réussi, sans déploiement.
- P1/P2 : scénarios ajoutés pour les sections, liens, clavier, pagination, ordre numérique, filtre de prix, variations multiples et quantités invalides. Proposition de contraste séparée du thème dans `docs/previews/contrast-options.html`. Rapport de portée : `RECETTE_PHASE3_2026-10-21.md`.

## Tests et preuves

- Historique : 11 assertions PHP + 41 navigateur isolées; 33 assertions frontend GoDaddy. Voir les rapports sources pour leurs limites.
- Diagnostic et régression du correctif réel réussis (113 assertions ciblées par run). Recette étendue P1/P2 : résultats en attente. Aucun secret GoDaddy ni environnement `godaddy-temp` utilisé.

## Anomalies et corrections envisagées

- P0 : cause confirmée, suppression ciblée validée; pas encore déployée.
- P1 : contrastes insuffisants; propositions de palette à présenter sans les appliquer au thème.
- P1 : contenu FAQ/contact/téléphone/livraison/recettes et infolettre à compléter/valider.
- P2 : ordre numérique des tris, pagination, variations multiples et erreurs de quantités à couvrir isolément.

## Décisions et prochaines étapes

1. Examiner les résultats P1/P2 et les captures isolées, puis classer les défauts sans masquer les limites de couverture.
2. Présenter la correction P0 validée et la proposition de contraste avec les preuves avant toute demande de déploiement.
3. **Autorisation explicite de Julien requise avant tout déploiement sur GoDaddy.** Aucun changement de palette important, configuration commerciale ou nouveau compte distant inclus.

Work publie les commits et preuves dans ce dépôt; la conversation de Coordination doit lire ce fichier pour se synchroniser. Le commit qui contient ce document est accessible dans son historique; aucun SHA auto-référentiel n’est inventé.
