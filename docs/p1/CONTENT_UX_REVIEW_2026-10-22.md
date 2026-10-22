# P1 — contenus français et UX : dossier de décision

22 octobre 2026. **Prêt pour revue de propositions, aucune application au thème ni à GoDaddy.**

Directive : [reprise P1 par la Coordination](https://github.com/mycomane/alimentsdistinction/issues/2#issuecomment-6349987857). Base examinée : `21ae9f00ee35e25c70ed4f6d807ba6f89658f9ba`, branche exclusive `feat/wordpress-woocommerce-theme`. Issue #4 vérifiée clôturée; [installation fr_CA 39843113925](https://github.com/mycomane/alimentsdistinction/actions/runs/39843113925) completed/success sur `2949707b119f158461fdfa9a7a634a359af51a97`. Aucun nouveau contrôle GoDaddy, SSH, compte, accès authentifié, déploiement ou configuration commerciale dans cet audit.

**Palette « Actuel », polices, illustrations, maquette `index.html` et contrôles 0.1.2 approuvés conservés.** Les variantes globales A/B archivées ne sont pas réintroduites. Aucun service d'infolettre ou paiement connecté. Coming Soon reste en place.

## Livrable visuel

Ouvrir la [planche autonome de propositions](content-ux-proposals.html), qui contient les captures fr_CA synthétiques intégrées, et les exemples avant/proposé. Aucun fichier externe, formulaire ou script n'est chargé. Ce n'est pas une nouvelle maquette du site : les polices de la planche utilisent les familles prévues avec repli local, sans télécharger les fontes; les captures restent les images originales.

Captures réutilisées : artefact `locale-fr-ca-isolated`, ID `12453981820`, run 39843113925, job `120049184228`. Téléphone 390, ordinateur 1440 et écran de commande inspectés; capture de commande affichée sur demande dans la planche. Produits QA, prix, devise, pays/adresse par défaut et catégorie anglaise sont propres à la fixture, pas des décisions commerciales. Aucune donnée personnelle renseignée et aucun paiement/ordre soumis.

## Matrice priorisée pour Julien

« Prêt » désigne ici les éléments étayés; « À fournir » les informations manquantes; « À approuver » les propositions ou contenus engageants. Les priorités ci-dessous concernent l'ouverture commerciale, pas un ordre de mutation immédiat.

| ID / priorité | Sujet | État de décision | Constat et proposition minimale | Validation attendue |
| --- | --- | --- | --- | --- |
| FR-01 / acquis | Interfaces natives WooCommerce | **Prêt** | fr_CA et libellés d'achat vérifiés dans le run hébergé; 24 contrôles du parcours et 45 responsive sur fixture du même SHA | Conserver les catalogues officiels, aucun remplacement global de traductions |
| FR-02 / bloquant ouverture | Confidentialité de commande | **À approuver** | Marqueur anglais confirmé dans l'audit hébergé post-fr_CA déjà autorisé; anglais visible dans la fixture. Brouillon français présenté dans la planche, pas appliqué | Politique réelle, traitements et rédaction validés juridiquement/commercialement; lien effectif à contrôler ultérieurement |
| FR-03 / majeur | Titres/pages persistés | **À fournir** | WPLANG ne réécrit pas titres, contenus ou slugs. Noms actuels des quatre pages et autres contenus hébergés non inventoriés dans cette phase | Inventaire éditorial autorisé ultérieur; liste des titres/contenus français à fournir. Ne pas renommer les URL implicitement |
| FR-04 / majeur | Catégories/produits | **À fournir** | « Uncategorized » confirmé uniquement dans la fixture; noms, descriptions, attributs et catégories réels non inspectés ici | Arborescence et libellés français approuvés, sans importer les produits QA ni l'ancien site |
| FR-05 / mineur | Français du thème | **À approuver** | Textes de thème principalement français codés dans les gabarits, indépendants des catalogues : « Produits Locaux », « Fruits de mer & Poissons », « Produits Gourmets », alt « Produits premium… » | Harmoniser « Produits locaux », « Fruits de mer et poissons », « Produits gourmets », alt « Sélection de produits Aliments Distinction »; conserver les désignations commerciales approuvées |
| UX-01 / majeur | Infolettre lisible | **À approuver** | Paragraphe sombre sur charbon; lien de même couleur que le fond possible quand l'URL est configurée. Proposition distincte : panneau crème/texte charbon déjà présents dans la palette | Approuver ce nouvel usage local, sans changer les couleurs globales ni l'activer dans le thème |
| FR-06 / majeur | Disponibilité de l'infolettre | **À fournir** | Aucun formulaire natif : message d'indisponibilité si URL absente, lien externe si configurée; état hébergé non recontrôlé | Service, destination et consentement à fournir. Proposition de message neutre « L'inscription à l'infolettre n'est pas encore disponible. » sans date promise |
| UX-02 / majeur | Contact/téléphone | **À fournir** | Le code ne fournit aucun numéro; « Commander par téléphone » peut retomber sur l'ancre Contact si la page manque | Coordonnées officielles, horaires et canal souhaité. Proposition avant disponibilité : garder un unique « Nous joindre », sans faux lien téléphonique |
| FR-07 / bloquant ouverture | Livraison et paiement annoncé | **À approuver** | La maquette annonce paiement à réception Interac/Visa/Mastercard, livraison à domicile et financement. Les liens Zones/Politique peuvent retomber sur une section explicative | Régions, frais, seuils, délais, modalités et moyens réels à confirmer. Aucun réglage de livraison/paiement/taxes modifié |
| FR-08 / bloquant ouverture | Retours, remboursement, conditions | **À fournir** | « Rembourser — sans question, sans contrat » et garantie générale présents; pas de politique complète démontrée par les gabarits | Conditions exactes et texte approuvé. Ne pas inventer délai, admissibilité, remboursement intégral ou absence d'exclusion |
| FR-09 / bloquant ouverture | Origine/qualité/chiffres | **À approuver** | « 100 % locaux », « 102+ », « 50 jours », Canadian Prime/« moins de 2 % », sans antibiotiques/additif/conservateur, traçabilité et élevage éthique | Preuves et périmètre par produit. Ne pas présenter ces promesses comme validées par la traduction |
| FR-10 / bloquant ouverture | Témoignages | **À approuver** | Trois avis nommés figurent dans la maquette | Authenticité et autorisation de publication à confirmer; ne pas fabriquer de nouveaux avis |
| UX-03 / majeur | Recettes | **À fournir** | Cartes non cliquables, légende stylée comme un lien, absence d'ingrédients/étapes complètes; indications de cuisson présentes mais non validées dans cet audit | Contenus complets et consignes culinaires à faire valider. Proposition : texte simple « Aperçu de recette » tant qu'il n'existe pas de page publiée |
| UX-04 / majeur | Contrastes hors achat | **À approuver** | Petits textes rose/gris, navigation sur photo et focus sur fonds sombres restent des points ouverts de la recette antérieure | Revoir uniquement les usages/surfaces de couleurs existantes, sans appliquer les variantes globales ni annoncer conformité AA |
| UX-05 / acquis | Navigation, stock, variations, panier | **Prêt avec limites** | Menu/clavier/ancres et logique WooCommerce couverts par les recettes isolées existantes; contrôles d'achat approuvés conservés | Pas de recette hébergée authentifiée supplémentaire, blocs Panier/Commande non certifiés; rester sur les gabarits classiques testés |
| FR-11 / complément | Images, polices et textes alternatifs | **À fournir** | Ressources locales officielles et images Unsplash/Google Fonts de la maquette conservées; quelques alt sont génériques ou omettent une collection | Droits et disponibilité des ressources, descriptions alternatives pertinentes à confirmer; aucun remplacement d'illustration proposé sans accord |

## Origine des textes : ne pas confondre les périmètres

- **Traduit par les catalogues officiels** : boutons et messages natifs WooCommerce, tri, variations, stock, formulaire de commande. Certaines valeurs affichées viennent toutefois des réglages ou de la base, pas du catalogue.
- **Déjà français dans le thème** : accueil, menu, pied de page, recherche, page introuvable, infolettre et produit vedette. Les gabarits sont la source de ces textes, et non WPLANG. Les marques/certifications ne doivent pas être francisées arbitrairement.
- **À rédiger/renseigner dans les contenus persistés** : titres/pages, catégories, produits/attributs, coordonnées, recettes, destination d'infolettre. Le nom anglais de la fixture ne prouve pas un nom anglais sur GoDaddy.
- **À approuver juridiquement/commercialement** : confidentialité, conditions, livraison, financement/paiement, garantie/retours, témoignages et allégations. La traduction automatique ne valide ni leur exactitude ni leur applicabilité.

Sources de code examinées : `front-page.php` (réassurance, engagements, vedette de repli, histoire, recettes, processus, avis, garantie), `footer.php`, `functions.php` (`ad_page_url`, `ad_collection_url`, `ad_newsletter_url`, produit vedette), `template-parts/navigation.php`, `featured-product.php`, `index.php`, `404.php`, `header.php`, CSS et JS. Les ancres de repli sont conditionnelles : **le code ne prouve pas qu'une page éditoriale complète manque sur l'hébergement**. Une réponse HTTP 200 ne suffit pas à valider une politique ou une destination utile.

## Propositions et accessibilité : aucune nouvelle palette

1. **Lot de texte minimal**, à approuver : casse française, « et » dans les intitulés, alt générique français; libellés Contact/Recettes cohérents avec leurs destinations; neutraliser « bientôt » sans date. Aucun changement de géométrie, d'illustration, de prix ou de promesse commerciale.
2. **Brouillon de confidentialité**, à adapter et faire valider : « Vos renseignements personnels seront utilisés pour traiter votre commande et vous accompagner dans votre utilisation de ce site. Pour connaître les autres utilisations, consultez notre politique de confidentialité. » Ce brouillon n'est pas une politique complète, ne remplace pas un texte légal, et son futur lien doit être résolu vers la politique approuvée. Il ne sera ni injecté ni enregistré avant validation.
3. **Infolettre, proposition visuelle distincte** : panneau `#FAF7F2` sous le texte, caractères `#2C2A27`, couleurs déjà présentes. Calcul sRGB réutilisé : **13,3903:1**, contre **1,5508:1** pour `#4A4744` sur `#2C2A27`. Changement de surface local à approuver; ce n'est pas un changement de palette. Ne pas introduire les anciennes couleurs A/B non retenues.
4. **Focus/menu, proposition technique encore à détailler** : garder le focus visible, prévoir un contraste adapté à son fond avec les couleurs existantes; éventuellement une surface crème localisée pour les liens sur photo. L'apparence doit être présentée et approuvée avant intégration. La planche actuelle ne simule pas un nouveau menu complet.

Seuils de référence : texte normal AA 4,5:1, grand texte applicable 3:1; indicateur de focus visible. [WCAG contraste](https://www.w3.org/WAI/WCAG22/Understanding/contrast-minimum.html), [focus visible](https://www.w3.org/WAI/WCAG22/Understanding/focus-visible.html). Les calculs sur fonds unis ne certifient pas un rendu sur photo/transparence ni une conformité WCAG globale.

Contrastes historiques conservés : rose/crème **2,8141:1**, blanc/rose **3,0072:1**, gris/crème **3,2917:1**, lien infolettre charbon/charbon **1:1**. Bouton produit déjà approuvé : blanc/`#94544B` **5,7869:1**. La navigation photographique échouait dans la mesure contextualisée de l'état initial; l'audit actuel réutilise ce constat, sans prétendre à une nouvelle mesure pixel sur tous les fonds. La palette conservée **n'est pas déclarée conforme AA**.

## Tests et budget réellement consommé

**Réutilisés, pas relancés :** run 39843113925, même SHA 2949707b : 24 contrôles de parcours français, 45 contrôles responsive/états d'achat (320/390/768/1024/1440), 30 WPLANG/rollback et vérification en processus neuf, diagnostics PHP sans fatal/warning/deprecated; zéro commande/passerelle active. Les captures de cet artefact sont celles intégrées à la planche.

**Historique conservé :** `RECETTE_PHASE3_2026-10-21.md` et sa recette 300 assertions, menu/clavier/sections, tri, filtre, stock et variations; `docs/p1/evidence/report.json` pour les mesures contextualisées de contraste et la géométrie P0. Ces résultats restent attribués à leurs commits/runs d'origine; ils ne sont pas présentés comme une nouvelle recette du HEAD. Les 254 contrôles des variantes A/B ne valident pas une palette appliquée au site.

**Complément limité à la planche** : le navigateur local était absent; le premier essai s'est arrêté au lancement, avant toute assertion. Nouveau workflow isolé `qa-p1-content-preview.yml` sans secrets, environnement protégé, WordPress ou URL hébergée. Il vérifie seulement la planche statique : cinq largeurs, images intégrées, langue, titres, absence de formulaire/script, focus et ouverture clavier des captures; sorties et captures dans `p1-content-proposal-evidence`. Résultat du nouveau run à consigner après observation, sans annoncer de réussite anticipée. Aucun des workflows protégés existants n'est modifié.

Le calculateur `scripts/qa/contrast-report.cjs` existant a été réutilisé localement pour les fonds unis; aucun CSS n'est importé au thème. La paire crème/charbon et la paire du bouton approuvé sont calculées avec la même fonction. Une vérification de diff doit confirmer que le thème, la maquette, les contrôleurs de déploiement et les paramètres sont intacts.

## Trois décisions regroupées, puis prochaines étapes

1. **Julien — textes/UX** : valider ou modifier le lot minimal et le panneau crème de l'infolettre. Aucun accord global à une variante historique n'est inféré.
2. **Jean-François/Meggy via Coordination — informations** : coordonnées/horaires; livraison, retours/garanties, financement et modes de paiement; catalogue/catégories, preuves des chiffres/allégations, avis, recettes et service d'infolettre.
3. **Responsables compétents — textes engageants** : politique de confidentialité, conditions et promesses validées. Confirmer le périmètre exact avant toute rédaction finale ou configuration.

Après validation, préparer un diff séparé limité aux corrections choisies et ses tests pertinents. **Tout accès authentifié/écriture GoDaddy, configuration majeure et déploiement requiert une nouvelle autorisation distincte**, avec approbation GitHub du futur run exact. Aucune modification des workflows protégés, du site commercial, de master, DNS, paiements, Zoho, règles fiscales/livraison ou données clients/commandes par cette phase. Issue #2 reste ouverte pour la Coordination.
