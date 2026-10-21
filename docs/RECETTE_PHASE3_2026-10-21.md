# Phase 3 — sections, accessibilité et WooCommerce isolé

## Portée

P0 corrigé dans la branche, non déployé : voir `P0_MOBILE_2026-10-21.md`. Cette phase P1/P2 prépare et exécute des vérifications sur WordPress/WooCommerce jetable, sans accès GoDaddy. Les conclusions hébergées restent celles du run privé `39531003568`; ne pas les étendre à des scénarios non exécutés.

## Scénarios ajoutés (résultats en attente du nouveau run)

| Domaine | Contrôle préparé | Limite |
| --- | --- | --- |
| Accueil | 13 régions, titres et limites horizontales à cinq largeurs, captures 390/1440 | Pas de certification visuelle de chaque pixel |
| Navigation | Liens d’ancres, noms accessibles, destinations internes HTTP, menu bureau/mobile | Réseaux sociaux non visités; aucun contenu tiers validé |
| Clavier | Premier arrêt sur lien d’évitement, visibilité, activation, menu Enter/Échap | Contrôle de base, pas audit exhaustif lecteur d’écran |
| Contenu | Replis FAQ/contact/téléphone/livraison; indisponibilité explicite de l’infolettre | Validation commerciale et rédaction requises |
| Catalogue | 12 produits synthétiques dans une catégorie isolée, tri numérique ascendant/descendant sur quatre pages | Aucun réglage catalogue GoDaddy modifié |
| Filtre | Prix minimum 20 / maximum 40, inclusifs, pagination | Le thème ne fournit pas encore de panneau de filtres avancés |
| Variations | 250 g / 500 g : identifiants et prix; ajout 500 g; 1 kg indisponible | Aucune variation réelle créée |
| Quantités | 0, -1 et dépassement de stock côté PHP; maximum natif et refus serveur HTTP | Ne remplace pas des règles commerciales approuvées |
| Commande | Formulaire classique avec panier synthétique non vide, champs requis, nonce, absence de paiement | Aucun clic de soumission ni commande; erreurs de paiement non testées |
| Absence de transaction | Vérification finale zéro commande et aucune passerelle disponible | Environnement jetable seulement |

Les pages actives GoDaddy et les scénarios précédents utilisent les formulaires classiques. Les blocs Panier/Commande ne sont pas déclarés validés; ils feront l’objet d’une recette distincte si leur usage est retenu.

## Contrastes : proposition à valider, non appliquée

Source normative et explication : [WCAG 2.2 — contraste minimal](https://www.w3.org/WAI/WCAG22/Understanding/contrast-minimum.html) : 4,5:1 pour le texte normal, 3:1 pour le grand texte applicable. Les seuils sont comparés avant arrondi. [Focus visible](https://www.w3.org/WAI/WCAG22/Understanding/focus-visible.html) : les contrôles clavier doivent disposer d’un indicateur visible.

Calculs sRGB reproductibles dans `scripts/qa/contrast-report.cjs`; planche comparative dans [previews/contrast-options.html](previews/contrast-options.html). Les fonds, les photos, les ornements et le rose de marque ne sont pas remplacés globalement. La proposition sépare la couleur décorative des couleurs de texte et de bouton.

| Usage | État actuel | Proposition | Contraste proposé |
| --- | --- | --- | ---: |
| Texte rose sur crème `#FAF7F2` | `#C4857A` : 2,81:1 | Rose encre `#94544B` | 5,41:1 |
| Texte blanc sur bouton rose | 3,01:1 | Fond `#94544B`, texte blanc | 5,79:1 |
| Texte secondaire sur crème | `#8C8884` : 3,29:1 | Gris texte `#6E6964` | 5,08:1 |
| Texte secondaire sur crème 2 `#F4EDE3` | Gris trop clair | Gris texte `#6E6964` | 4,67:1 |
| Rose sur fond rose pâle `#F5E8E6` | Rose décoratif insuffisant pour petits textes | Rose encre `#94544B` | 4,84:1 |
| Infolettre sur charbon `#2C2A27` | Texte `#4A4744` : environ 1,55:1 | Texte clair `#E8E2DA` | 11,12:1 |
| Texte couleur terre sur blanc | `#B8956A` : 2,78:1 | Terre encre `#806342` | 5,55:1 |
| Texte sauge sur sauge pâle `#EBF2EA` | Teinte décorative trop claire | Sauge encre `#52694F` | 5,28:1 |

Le rose encre proposé garde une marge sur les fonds crème, crème 2, rose pâle, sauge pâle et blanc; un rose moins foncé `#9B5B52` aurait échoué pour le petit texte sur rose pâle (4,38:1), il n’est donc pas retenu pour ces usages.

**Points de conception à faire approuver :** textes et boutons plus foncés; texte et lien d’infolettre clairs sur fond charbon; focus bicolore pour les fonds sombres. Les états survol/focus, bordures utiles, messages et surfaces photographiques doivent encore être contrôlés en contexte. Ces seuls rapports de contraste ne constituent pas une conformité WCAG globale. Aucun changement de palette n’est appliqué dans le thème.

## Anomalies connues avant validation finale du run

- **Majeur pour ouverture commerciale** : contrastes des textes/boutons et contenu sombre de l’infolettre; le futur lien configuré utilise également une classe sombre sur fond sombre.
- **Bloquant pour exploitation commerciale** : coordonnées et téléphone, modalités livraison/retours/confidentialité, témoignages et affirmations marketing non validés. Les liens de repli sont des destinations techniques, pas des pages de contenu complètes.
- **Majeur fonctionnel** : recettes non rédigées et aucun service d’infolettre choisi. Le formulaire d’inscription n’est pas simulé.
- **Mineur / adaptation attendue** : navigation mobile fonctionnelle différente de la simple maquette statique; le menu et les icônes WooCommerce sont des adaptations WordPress existantes.
- **Exploitation** : sauvegarde hors site/restauration intégrale et confirmation indépendante de l’empreinte SSH restent à traiter avant production.

## Approbations

Aucune permission supplémentaire requise pour ces tests isolés et calculs. **Julien doit approuver tout déploiement GoDaddy et toute modification importante de palette**. Aucune nouvelle recette distante nécessitant un compte n’est lancée. Les URL finales GoDaddy, notamment le renvoi de checkout vide, restent non confirmées par le relevé précédent; ne pas inventer ce résultat.

## Premier run étendu : résultats partiels et correction de la fixture

[Run 39607114646](https://github.com/mycomane/alimentsdistinction/actions/runs/39607114646), commit `a6e9482728b62367e80d82d18ae1dac6fdbe09fa` : P0 et les 52 contrôles existants réussis; **55 assertions sections/liens/clavier réussies** et **14 couples de couleurs proposés valides pour le texte normal sur leurs fonds unis**. L’échantillonnage du DOM confirme le texte sombre `#4A4744` sur charbon `#2C2A27` pour le paragraphe d’infolettre indisponible.

Le job global échoue dans le nouveau test de variation : la fixture ne persistait pas l’option supplémentaire « 1 kg ». Cause reproduite localement : l’attribut WooCommerce avait été modifié sur le même objet, ce qui contournait la détection de changement du modèle CRUD. Le rechargement depuis la base ne renvoyait que « 250 g » et « 500 g ». La fixture clone désormais l’attribut avant modification et vérifie sa persistance après rechargement; ce contrôle est passé localement. Aucun correctif du thème ou du moteur WooCommerce n’est impliqué dans cet échec de données synthétiques.

Les refus PHP des quantités 0, -1 et 6 ont réussi. La vérification finale confirme zéro commande créée et aucune passerelle disponible. Les assertions navigateur WooCommerce interrompues ne sont pas déclarées réussies; elles doivent passer dans la relance. Les rapports sont maintenant conservés même si un scénario navigateur s’interrompt.

La relance `39608086528` au commit `1dacbd019bdae0670fc499a6f3a1d9255bb30605` confirme la persistance de la fixture et les tris réels (12 produits dans l’ordre attendu sur quatre pages), le filtre 20–40 sur deux pages, l’ajout de la portion 500 g et son sous-total 35, la variation indisponible, le refus de quantité excessive et l’affichage de checkout sans paiement. Sur 20 assertions nouvelles, une échoue : le test lit le prix de 500 g avant la mise à jour différée de la zone de prix native, alors que le bouton de la variation précédente est encore activé. Le test attend désormais le prix attendu avant de le mesurer. La recette finale ajoute en outre les cinq tailles sur catalogue, deux fiches, panier et checkout, avec libellés et focus de base. Résultat global de cette nouvelle exécution à confirmer.
