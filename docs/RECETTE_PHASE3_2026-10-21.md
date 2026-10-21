# Phase 3 — sections, accessibilité et WooCommerce isolé

## Portée

**Bilan final : 300 assertions ciblées réussies dans le [run 39610664542](https://github.com/mycomane/alimentsdistinction/actions/runs/39610664542), aucun fatal/avertissement/dépréciation PHP compté et zéro commande créée.** P0 corrigé dans la branche, non déployé : voir `P0_MOBILE_2026-10-21.md`. Cette phase P1/P2 a exécuté les vérifications ci-dessous sur WordPress/WooCommerce jetable, sans accès GoDaddy. Les conclusions hébergées restent celles du run privé `39531003568`; ne pas les étendre à des scénarios non exécutés. Les validations de palette et de déploiement restent attendues.

## Scénarios exécutés et portée

| Domaine | Contrôle exécuté | Limite |
| --- | --- | --- |
| Accueil | 14 régions, dont le bloc de garantie, titres et limites horizontales à cinq largeurs, captures 390/1440 | Pas de certification visuelle de chaque pixel |
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

Pour la navigation actuellement transparente sur le héro, un simple remplacement de la couleur des liens ne garantit pas le contraste sur la photo. Proposition à valider : fond crème opaque pour la barre de navigation, liens rose encre/charbon et focus visible. Cette modification d’aspect n’est pas appliquée ici; la planche de couleurs teste des fonds unis, pas la photo.

## Anomalies et actions à traiter

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

## Recette étendue réussie

[Run 39608791472](https://github.com/mycomane/alimentsdistinction/actions/runs/39608791472), job `119251872664`, commit `e13c89076e4fb6cd98dd85742cc25cf173795057` : **succès**.

- 11 assertions PHP historiques + 41 navigateur historiques + 61 géométrie P0 + 55 sections/liens/clavier + 130 WooCommerce étendu = **298 assertions ciblées réussies**.
- 25 combinaisons page/taille WooCommerce (catalogue, fiche simple, fiche variable, panier, checkout × cinq largeurs) : aucun débordement horizontal ni image locale cassée, un H1, contrôles visibles libellés.
- Dix sondes clavier sur contrôles WooCommerce à 390/1440 px : le focus revient au contrôle et un contour solide de 3 px est présent. Le rapport suivant renomme le champ de largeur du contour pour conserver distinctement la largeur de viewport.
- Tri numérique ascendant et descendant confirmé sur les douze produits, quatre pages sans doublon; filtre 20–40 donnant exactement cinq produits sur deux pages.
- Variations 250/500 g et prix, ajout de 500 g avec sous-total 35, blocage de la variation indisponible et refus serveur; quantités au-delà du stock refusées sans nouvelle ligne de panier.
- Formulaire classique de checkout, champs requis et nonce présents; **aucune soumission** et aucune passerelle disponible. Vérification finale : zéro commande et aucun paiement actif. Les trois refus PHP des quantités 0, -1, 6 passent aussi.
- 14 couples de couleurs proposés mesurés, tous au-dessus de 4,5:1 pour leurs fonds unis; proposition non appliquée.

La dernière relance ajoute une synthèse des journaux PHP fatals sans publier les journaux bruts, le contrôle de l’état d’erreur d’un champ obligatoire vide sans soumission, et le bloc de garantie parmi les régions mesurées. Ces résultats supplémentaires sont en attente; ils ne sont pas anticipés ici.

## Revue des captures isolées

Les captures desktop 1440 et téléphone 390 du run `39608086528` ont été examinées : héro, collections, engagements, produit mis en avant, histoire, réassurance, recettes, processus, témoignages, CTA, garantie, infolettre et pied de page. Le flux est cohérent et les contenus s’empilent sur téléphone. Le manque de contraste de l’infolettre est confirmé visuellement. Il ne s’agit pas d’une comparaison pixel par pixel des sections secondaires à `index.html`.

- [Accueil 390 px](evidence/homepage-390.jpg)
- [Accueil 1440 px](evidence/homepage-1440.jpg)
- [Planche de contraste non appliquée](evidence/contrast-proposal.jpg)
- Comparaison du héro corrigé : `P0_MOBILE_2026-10-21.md`.

Ces images sont issues du WordPress jetable sans session privée, ni cookie ou donnée de GoDaddy. Les copies complètes des preuves JSON/JPEG sont aussi dans l’artefact `isolated-design-evidence` (rétention 14 jours); les rapports et images référencés ici sont conservés dans la branche.

## Validation finale — 21 octobre 2026

[Run 39610664542](https://github.com/mycomane/alimentsdistinction/actions/runs/39610664542), job `119258365224`, commit testé [`aab5e712eb72acc9622b92a8bfb05c9c97bbb587`](https://github.com/mycomane/alimentsdistinction/commit/aab5e712eb72acc9622b92a8bfb05c9c97bbb587) : **succès confirmé**. Versions réellement affichées : WordPress 7.1.3 / WooCommerce 11.2.1 / PHP 8.3.6, installation jetable 127.0.0.1 uniquement.

| Groupe | Assertions réussies |
| --- | ---: |
| Intégration PHP historique | 11 |
| Navigation/panier HTTP historique | 41 |
| Géométrie/focus P0 | 61 |
| Sections/liens/clavier P1 | 55 |
| WooCommerce étendu et responsive | 132 |
| **Total de ces groupes** | **300** |

S’y ajoutent les trois refus PHP de quantités 0/-1/6, les 14 propositions de couples de couleurs mesurées, le contrôle final zéro commande et zéro passerelle disponible, et l’analyse des journaux PHP. Les compteurs ne constituent pas une couverture exhaustive du commerce ou une certification d’accessibilité.

- **PHP** : journaux du serveur isolé et de WordPress présents; zéro erreur fatale/de parsing/exception non interceptée détectée, zéro avertissement et zéro dépréciation comptés par le scanner. Aucun journal brut publié.
- **JavaScript** : aucun `pageerror` dans les scénarios de ce run.
- **Commande sans soumission** : le champ prénom vide déclenche l’état d’erreur natif et `aria-invalid`; sa correction supprime cet état. Aucun POST de checkout ni commande.
- **Accueil** : 14 régions, dont le bloc de garantie, mesurées aux cinq tailles. Les 25 combinaisons page/taille WooCommerce passent, avec noms de contrôles et H1 vérifiés.
- **Focus WooCommerce** : dix sondes à 390/1440 conservent un contour solide de 3 px et un retour clavier au contrôle. Cela ne certifie pas le contraste du focus sur toutes les surfaces.
- **P0** : écart final de 0,00 px à 320/390/768/1024/1440 par rapport aux principales mesures de référence. Le seul changement au thème depuis la base est la suppression de la ligne CSS de retour à la ligne. CSS original, `index.html` et images intacts.

Preuves structurées pérennes : [qa-summary-2026-10-21.json](evidence/qa-summary-2026-10-21.json). [Planche WooCommerce 390 px](evidence/commerce-390.jpg), issue du run final et examinée manuellement : fiche simple, fiche variable, panier non vide et formulaire de commande sans paiement. Les captures ne contiennent que des produits de test et des formulaires invités; aucun client réel.

La planche montre aussi les états natifs explorés : variation non sélectionnée et champs obligatoires marqués en erreur par la navigation clavier. Ces états n’impliquent aucune commande soumise. Les libellés anglais et le pays par défaut proviennent du runner en_US et ne sont pas présentés comme configuration commerciale approuvée du site GoDaddy.

### Défauts et limites restant à traiter

| Niveau | Élément | Action / décision |
| --- | --- | --- |
| Bloquant pour ouverture | Téléphone, coordonnées, modalités livraison/retours/confidentialité et contenu contractuel non validés | Fournir les données et textes approuvés; les liens de repli ne remplacent pas les pages |
| Bloquant pour ouverture | Témoignages, garantie de remboursement, origine et allégations alimentaires/financement/chiffres marketing | Validation de Julien/du propriétaire avant publication |
| Majeur | Contrastes des textes/boutons et infolettre sombre | Valider la proposition; appliquer ensuite les styles ciblés avec recette de survol/focus |
| Majeur | Navigation transparente sur photo | Valider un fond crème opaque ou une autre solution de contraste par contexte |
| Majeur fonctionnel | Recettes incomplètes et infolettre sans service/formulaire approuvé | Rédaction et choix du service, aucune connexion réalisée |
| Mineur de cohérence | Bouton de variation désactivée encore violet natif WooCommerce dans la capture isolée | Harmoniser cet état lors du lot de palette; ne modifie pas le blocage fonctionnel |
| À confirmer | Textes WooCommerce anglais dans le runner en_US | Résultat de la langue de test, **pas preuve d’un défaut GoDaddy**; vérifier fr_CA/fr_FR et traductions avant ouverture |
| Hors couverture actuelle | URL finale exacte du checkout GoDaddy vide, panier hébergé non vide, erreurs serveur de commande, blocs Panier/Commande | Recette distincte autorisée; aucun nouveau compte ni produit GoDaddy créé ici |
| Avant production | Sauvegarde hors site, restauration testée, confirmation SSH indépendante, performance/CWV | Préparer les contrôles; aucun résultat de performance ou reprise totale revendiqué |

**Aucune erreur bloquante de mise en page ou de JavaScript observée dans les scénarios exécutés.** Les blocages commerciaux et d’accessibilité ci-dessus empêchent de déclarer le site prêt à l’ouverture. Le déploiement P0 et l’intégration des propositions graphiques restent soumis à validation distincte. Coming Soon, DNS, site commercial, paiements et Zoho n’ont fait l’objet d’aucune opération dans cette phase.
