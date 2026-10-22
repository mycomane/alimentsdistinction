# P1 — propositions, choix retenu et livraison 0.1.2

> **État au 21 octobre, 19 h 57 HAE : styles 0.1.2 déployés avec succès après revue humaine GitHub.** [Preuve](evidence/release-0.1.2-deployment.json). Palette « Actuel » conservée, seuls les contrôles validés de A ont été livrés. **GoDaddy est encore en `en_US`, sans paquets français : localisation à compléter.** Les sections de propositions et tests isolés ci-dessous conservent l’historique; voir [PROJECT_STATUS.md](../PROJECT_STATUS.md) pour l’état courant.

> **Décision de Julien : conserver « Actuel », palette choisie par Jean-François et Meggy.** Seuls le bouton d’ajout au panier et le sélecteur de variation reprennent A. Les variantes complètes ci-dessous sont archivées pour référence, sans intégration. Site en français; vérification dédiée avec les traductions officielles WordPress/WooCommerce. Aucun déploiement autorisé par ce choix.

Mission [#2](https://github.com/mycomane/alimentsdistinction/issues/2). P0 est clos; le thème installé reste 0.1.1. Cette phase prépare uniquement des fichiers de prévisualisation séparés. Aucun accès GoDaddy, compte, session privée, palette hébergée ou workflow protégé modifié.

## Intégration retenue et résultat

Code `206972b1ab2228b17b6131c66a9475e7f085f3da` : seulement `commerce.css` pour le bouton d’ajout au panier des fiches produits et la bordure du sélecteur. Actif `#94544B`, survol `#713E37`, désactivé `#E4D9CE` / texte `#4A4744`, bordure `#6E6964`. Palette globale, menu, accueil et infolettre conservés. Aucun remplacement global de traduction : WooCommerce utilise ses paquets français officiels.

[Run 39692488831](https://github.com/mycomane/alimentsdistinction/actions/runs/39692488831), job 119541795281 : succès. Recette existante + **45 nouveaux contrôles français réussis**, aux cinq largeurs, libellés natifs « Ajouter au panier » et « Choisir une option », états désactivé/actif/survol/focus et absence de débordement. Zéro commande créée; aucun paiement; logs PHP sans fatal/avertissement/dépréciation et aucune erreur JS détectée. [Build 39692489014](https://github.com/mycomane/alimentsdistinction/actions/runs/39692489014) réussi.

[Preuve JSON](evidence/french-controls.json) · [Téléphone](evidence/french-controls-390.jpg) · [Ordinateur](evidence/french-controls-1440.jpg). Les captures montrent un produit synthétique sélectionné avec focus clavier. Les contenus et formats hérités des fixtures (catégorie « Uncategorized », monnaie) ne représentent pas les contenus français à publier. Cette vérification ne certifie pas la localisation complète de GoDaddy, auquel aucun accès n’a été effectué.

Aucun déploiement. Une future livraison devra obtenir l’autorisation distincte de Julien, une nouvelle approbation GitHub et les protections de retour arrière; prévoir également l’incrément de version/cache du thème.

## Planche de choix historique

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
