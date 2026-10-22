# P1 — deux propositions isolées, à approuver

> **État au 21 octobre, 19 h 57 HAE : styles 0.1.2 déployés avec succès après revue humaine GitHub.** [Preuve](evidence/release-0.1.2-deployment.json). Palette « Actuel » conservée, seuls les contrôles validés de A ont été livrés. **GoDaddy est encore en `en_US` : localisation à compléter; inventaire des paquets français non établi par le premier diagnostic.** Les sections de propositions et tests isolés ci-dessous conservent l’historique; voir [PROJECT_STATUS.md](../PROJECT_STATUS.md) pour l’état courant.

> **Décision de Julien : conserver « Actuel », palette choisie par Jean-François et Meggy.** Seuls le bouton d’ajout au panier et le sélecteur de variation reprennent A. Les variantes complètes ci-dessous sont archivées pour référence, sans intégration. Site en français; vérification dédiée avec les traductions officielles WordPress/WooCommerce. Aucun déploiement autorisé par ce choix.

Mission : [issue #2](https://github.com/mycomane/alimentsdistinction/issues/2). Référence installée : thème 0.1.1, déployé par `39685183348`, recette hébergée P0 `39686849316`. P0 est clôturé par la Coordination. Cette phase ne se connecte pas à GoDaddy.

Les fichiers `common.css`, `variant-a.css` et `variant-b.css` ne sont pas chargés par WordPress. Le runner injecte uniquement ces propositions dans un WordPress jetable, sous `html[data-p1-variant]`. Aucun changement à `index.html`, au CSS de référence ou au thème installable.

| | A — Évolution discrète | B — Lecture renforcée |
| --- | --- | --- |
| Menu | Positions conservées; petites surfaces crème opaques derrière liens/icônes | Barre crème entièrement opaque; liens et icônes charbon |
| Boutons principaux | Rose encre `#94544B` | Charbon `#2C2A27` |
| Texte secondaire | `#6E6964` | `#625D57`, plus sombre |
| Commun | Rose encre pour petits textes, infolettre claire, focus bicolore, états WooCommerce lisibles | Idem |
| Rose décoratif | Conservé dans les variables de marque, ornements et bordures décoratives | Idem |
| Compromis | Aspect plus proche, surfaces locales visibles sur la photo | Aspect de navigation plus affirmé et surface stable sur toute image |

Les variantes attendent l'accord esthétique de Julien. Aucun déploiement n'est autorisé par cette proposition.

## Vérification et budget

Nouveau workflow **non protégé et sans secrets** `qa-p1-visual.yml` : réutilise `prepare.py`, `seed.php`, `fixtures.php`, `extended-fixtures.php` et le comparateur de géométrie P0. Il ne modifie aucun workflow protégé, n'utilise pas `godaddy-temp` et ne relance pas les 300 assertions.

Scénarios ciblés : trois aspects (actuel/A/B) à 320/390/768/1024/1440; géométrie P0 et menu/clavier. Capture desktop/téléphone, infolettre et états WooCommerce; échantillonnage du fond pixel réel à 390/1440. Bouton variable désactivé/actif/survol/focus, variation indisponible, stock, formulaire de commande isolé et champ requis vide, sans soumission ni commande. Vérification finale zéro commande et passerelle.

Le contraste sRGB utilise les couleurs de texte calculées par le navigateur. Les glyphes sont temporairement rendus transparents, sans changer la mise en page, pour échantillonner les **pixels du vrai fond** (photo, texture et transparence incluses) sous les rectangles de texte, au pas de 3 pixels. Le seuil est comparé avant arrondi : 4,5:1 normal, 3:1 pour grand texte applicable. Les résultats de l'aspect actuel sont des constats, pas une porte bloquante des propositions. Textes de logo et contrôles désactivés sont classés à part; le texte désactivé est néanmoins visé à 4,5:1 pour la lisibilité.

Focus : anneau bicolore blanc/charbon (contraste interne élevé), vérification de son affichage clavier et des bordures de champs à 3:1. Pas de changement de taille des textes ni de la géométrie du héro. L'absence de régression P0 est mesurée à 0 px.

Limites : échantillonnage conservateur sur rectangles et non certification WCAG; lecteurs d'écran, cibles tactiles, contenu et performance restent distincts. Les images de test sont isolées et ne contiennent aucune session ou donnée GoDaddy. Les exemples WooCommerce sont des produits fictifs. L'infolettre reste indisponible et aucune connexion n'est simulée.

Références : [contraste texte](https://www.w3.org/WAI/WCAG22/Understanding/contrast-minimum.html), [contraste non textuel](https://www.w3.org/WAI/WCAG22/Understanding/non-text-contrast.html), [focus visible](https://www.w3.org/WAI/WCAG22/Understanding/focus-visible.html). Résultats et mesures dans [REPORT.md](REPORT.md). Ouvrir [choice.html](choice.html), prévisualisation autonome avec vues ordinateur/téléphone/infolettre/WooCommerce, ou les JPEG dans `evidence/`.

## Premier passage contextualisé

Run `39689556380` : les 15 géométries (actuel/A/B × cinq largeurs) sont restées à **0,00 px**, et les états/focus ont passé. Les seuils ont correctement bloqué les propositions sur certains textes posés sur des textures/feuilles : rose encre `#94544B` mesuré jusqu'à 2,96:1 dans ce contexte, malgré 5,41:1 sur crème uni. Un rose plus profond `#6B3832` est donc proposé **localement** pour ces libellés/citations et liens transparents, en conservant le rose encre ailleurs et le rose décoratif.

Le test est aussi précisé pour ignorer les textes réellement masqués par clipping de lecteur d'écran (ils restent accessibles), attendre la fin de transition de la navigation avant l'échantillonnage et couvrir les icônes de navigation au seuil non textuel de 3:1. Ces précisions évitent d'analyser des rectangles qui ne correspondent pas à un élément visible/stabilisé. Le second passage doit confirmer les contrastes corrigés; pas de réussite anticipée.

## Résultat final

**Prêt pour revue visuelle, aucun déploiement.** [Run ciblé 39690003471](https://github.com/mycomane/alimentsdistinction/actions/runs/39690003471), job 119533553118 : **254 contrôles réussis**, zéro écart P0 aux cinq largeurs, aucune mesure audité sous le seuil pour A/B. [Planches 39690653253](https://github.com/mycomane/alimentsdistinction/actions/runs/39690653253), job 119535722196 : succès, captures compactes en état de repos (pointeur neutre), CSS inchangés. Cette capture séparée ne relance pas les 300 assertions.

[Planche de choix](choice.html) · [Rapport détaillé](REPORT.md) · [Preuve JSON](evidence/report.json).

La comparaison de styles utilise le thème 0.1.1 correspondant aux 23 empreintes validées par la recette P0 hébergée : `style.css` SHA256 `5a4c3a5a8ae6fccd66437151555b66d05c1f5c18dc56cfff3de3f66a92bde780`. Aucune nouvelle session GoDaddy n’est ouverte pour P1.
