# P1 — prêt pour revue visuelle, aucun déploiement

Mission [#2](https://github.com/mycomane/alimentsdistinction/issues/2). P0 est clos; le thème installé reste 0.1.1. Cette phase prépare uniquement des fichiers de prévisualisation séparés. Aucun accès GoDaddy, compte, session privée, palette hébergée ou workflow protégé modifié.

## Planche de choix

Ouvrir **[choice.html](choice.html)** : fichier autonome léger avec boutons pour comparer ordinateur, téléphone, infolettre et états WooCommerce. Les exemples de boutons montrent leurs états de repos/survol/focus; ils n'envoient pas de décision. [JPEG ordinateur](evidence/choice-desktop.jpg), [téléphone](evidence/choice-mobile.jpg), [infolettre](evidence/choice-newsletter.jpg), [WooCommerce](evidence/choice-woo-states.jpg).

[Run de captures 39690653253](https://github.com/mycomane/alimentsdistinction/actions/runs/39690653253), job 119535722196 : succès. Captures isolées en pointeur neutre, CSS identiques à ceux testés. Les petites différences de hauteur des légendes des cartes n'affectent pas la géométrie du site. Planches revues manuellement : B montre bien le CTA charbon au repos; l'infolettre est lisible dans les deux propositions et le bouton inactif reste visuellement distinct.

## Deux variantes à choisir

| | A — Évolution discrète | B — Lecture renforcée |
| --- | --- | --- |
| Navigation | Menu aux mêmes positions; petites surfaces crème opaques derrière liens/icônes | Barre crème entièrement opaque, liens et icônes charbon |
| Bouton principal au repos | Rose encre `#94544B`, blanc | Charbon `#2C2A27`, blanc |
| Survol | Rose sombre `#713E37` | Rose sombre `#713E37` |
| Textes secondaires | Gris `#6E6964` | Gris plus sombre `#625D57` |
| Textes sur texture/feuilles | Encre profonde `#6B3832` localement | Idem |
| Commun | Infolettre claire `#E8E2DA`, focus bicolore, états WooCommerce lisibles | Idem |
| Compromis | Proche du design actuel; petites surfaces visibles dans la navigation | Surface plus homogène et robuste à de futures photos; changement visuel plus affirmé |

**Recommandation : B** pour sa navigation continue et prévisible. A reste une option techniquement testée si la proximité visuelle prime. Le rose de marque `#C4857A`, les photos, les ornements et les variables de maquette restent inchangés. Aucun changement de police/taille du texte ou de géométrie du héro.

L'aspect doit être approuvé par Julien. Le choix d'une variante autorisera la préparation de son intégration, **pas un déploiement implicite**. Celui-ci exigera une autorisation et une revue GitHub distinctes, avec les protections existantes.

## Tests ciblés et preuves

Commit testé : [`61d940e2e0847b6f3358f4c98aa16c572f593148`](https://github.com/mycomane/alimentsdistinction/commit/61d940e2e0847b6f3358f4c98aa16c572f593148). [Run 39690003471](https://github.com/mycomane/alimentsdistinction/actions/runs/39690003471), job `119533553118` : **succès, 254 contrôles ciblés, zéro échec**.

- Géométrie P0 : actuel/A/B × 320/390/768/1024/1440 = **15 comparaisons, écart maximal 0,00 px**.
- Six zones du héro, réassurance sans découpe/chevauchement, images locales, absence de débordement, lien d'évitement et menu clavier contrôlés.
- Contrastes : fonds photographiques/texturés/transparents réellement rendus, texte normal 4,5:1, grand texte applicable et icônes 3:1. À 390 et 1440 px, **aucun texte/icône audité sous son seuil dans A ou B**; 194/199 éléments visibles analysés par accueil. Les contrôles WooCommerce stock/checkout à 390 passent aussi.
- États : bouton variable non sélectionné, activé, survol, focus et variation indisponible; disponibilité/rupture, bordures d'erreur de champ, formulaire de commande sans soumission. État désactivé conservé, texte plus lisible et violet natif remplacé dans la proposition uniquement.
- Focus : anneau bicolore de 3 px avec halo blanc, visible sur navigation et boutons; bordure d'erreur vérifiée à au moins 3:1. Aucun changement de rôle/validation/paiement.
- Vérification finale : **aucune commande créée, aucune passerelle active**. Logs PHP isolés présents : zéro fatal, avertissement ou dépréciation compté; aucune erreur JavaScript relevée.

### Minima contextuels de groupes audités

Les minima suivants utilisent les pixels du fond rendu sous les rectangles de texte/icône, y compris les photos. Ils ne sont pas de simples paires de couleurs sur fond uni.

| Groupe / largeur | Actuel | A | B |
| --- | ---: | ---: | ---: |
| Navigation / 390 | 2,60:1 | 5,42:1 | 13,39:1 |
| Navigation / 1440 | 1,00:1 | 5,42:1 | 5,42:1 |
| Textes secondaires / 390 et 1440 | 1,92:1 | 4,67:1 | 5,61:1 |
| Boutons / 390 | 1,92:1 | 5,42:1 | 5,42:1 |
| Boutons / 1440 | 1,82:1 | 5,69:1 | 5,69:1 |

Les groupes rassemblent plusieurs éléments : par exemple le minimum de navigation B desktop provient du sous-titre de marque rose, les liens/icônes charbon étant plus contrastés; les boutons incluent les liens transparents, pas seulement le CTA plein. Les 110/115 mesures insuffisantes de l'actuel à 390/1440 ne sont **pas** autant de défauts uniques; plusieurs nœuds et tailles réévaluent les mêmes styles.

L'infolettre actuelle présente le texte indisponible à environ 1,55:1; les deux propositions le rendent clair. La paire opaque proposée `#E8E2DA` / `#2C2A27` a un contraste calculé de 11,12:1; elle est contrôlée dans l'audit du fond réel. Aucun faux formulaire d'inscription ajouté.

## Ce que le premier passage a révélé

[39689556380](https://github.com/mycomane/alimentsdistinction/actions/runs/39689556380) a correctement refusé les propositions initiales sur certains textes placés sur textures/feuilles : `#94544B`, satisfaisant sur crème uni, descendait à **2,96:1** pour un petit libellé. Correction ciblée : `#6B3832` dans ces seuls contextes et contraste des initiales d'avatar. Les 15 géométries étaient déjà à zéro.

Le second passage stabilise la navigation après défilement avant échantillonnage et exclut uniquement les textes de lecteur d'écran réellement masqués par clipping. Ils restent dans le DOM accessible. Il ajoute les icônes au seuil non textuel de 3:1. Les états d'erreur sont explicitement bordés en rouge sombre. Résultat final : aucune mesure sous le seuil pour les deux propositions dans les scénarios.

## Budget et limites

Réutilisation de l'installation jetable, fixtures, comparateur P0 et scanner PHP existants. **Deux passages ciblés, aucune relance des 300 assertions.** Les planches sont régénérées séparément avec pointeur neutre pour montrer correctement les états de repos, sans retester la palette ni modifier les CSS. Aucun workflow `godaddy-temp` modifié.

Échantillonnage au pas de 3 px, minimum conservateur sur rectangles de texte; anticrénelage évité en masquant seulement la peinture des glyphes. Les ratios sont comparés au seuil avant arrondi. Cette méthode et ces scénarios ne constituent **pas une certification WCAG complète**. Pas d'audit exhaustif des lecteurs d'écran, du contraste de chaque état possible, des cibles tactiles ou de performance.

Les pages WooCommerce sont des fixtures isolées en_US : libellés anglais/pays de test ne sont pas présentés comme configuration commerciale GoDaddy. Aucun test transactionnel réel. Images/captures uniquement issues du runner, sans session ou donnée privée.

## Décisions à prendre

1. Choisir A ou B, ou demander un ajustement ciblé.
2. Valider les encres contextuelles plus profondes, l'infolettre claire et le focus bicolore.
3. Après accord graphique seulement : intégrer la variante choisie dans le thème et préparer la validation nécessaire. Aucun déploiement à cette étape.

Coordination conserve la priorisation. Les contenus contractuels, coordonnées, témoignages/allégations, recettes et service d'infolettre restent à valider séparément.
