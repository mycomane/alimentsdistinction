# P1 — deux propositions isolées, à approuver

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

Références : [contraste texte](https://www.w3.org/WAI/WCAG22/Understanding/contrast-minimum.html), [contraste non textuel](https://www.w3.org/WAI/WCAG22/Understanding/non-text-contrast.html), [focus visible](https://www.w3.org/WAI/WCAG22/Understanding/focus-visible.html). Rapport et planche de choix à compléter après exécution réelle.
