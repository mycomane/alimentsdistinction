# P1 — intégration isolée du correctif approuvé

Directive [Coordination #6351044184](https://github.com/mycomane/alimentsdistinction/issues/2#issuecomment-6351044184), décisions de Julien conservées dans [le registre](DECISIONS_JULIEN_2026-10-22.md). Base exacte : `0771856d2251620fcf578143f353877e9cd2ca36`. Aucun déploiement ni accès GoDaddy autorisé ou exécuté.

## Intégration

Les quatre sources locales ont été comparées octet pour octet aux fichiers du HEAD GitHub avant application. `git apply --check` réussi, puis [patch déjà approuvé](approved-copy-newsletter.patch) appliqué sans conflit aux fichiers du thème de la branche de développement :

- `footer.php` : panneau d'infolettre crème/charbon, message neutre en l'absence d'URL, destination existante conservée si configurée; unique « Nous joindre », intitulés français harmonisés.
- `style.css` : deux règles locales au panneau, variables existantes seulement. Aucun sélecteur du hero ou changement des variables globales, polices, images ou commandes WooCommerce.
- `front-page.php` / `template-parts/navigation.php` : casse/intitulés et alt français approuvés. Texte des recettes et promesses commerciales **inchangé**, liens provisoires non ajoutés.

**Aucune correction du texte anglais persistant de la commande** : le brouillon français reste validé, mais son intégration au contenu enregistré/politique est un lot séparé. Aucun filtre global ni écriture d'option introduit dans le thème.

## Contrôles et périmètre CI

Local : syntaxe PHP/JS et validation existante `scripts/check-theme.py` réussies, CSS officiel identique à `index.html`, sept ressources originales identiques, diff sans erreur. Le patch inverse est vérifiable et ses empreintes de candidat sont consignées dans `tests/p1-copy-isolated/scope.json`.

Nouveau test navigateur **sur WordPress jetable fr_CA loopback**, réutilisant les préparateurs et fixtures existants, sans authentification ni parcours de commande : comparaison thème de base / candidat, avec et sans URL d'infolettre, **390 et 1440 px**, captures d'avant/après; contraste calculé sur les couleurs réellement rendues, focus/hover, destination simulée locale, contact/ancre, textes et comparaison des rectangles du hero. Un échec de géométrie ou débordement bloque la recette et justifierait un diagnostic supplémentaire, notamment à 320. Pas de nouvelle recette de 300 assertions.

Les ressources externes de la maquette (photos/fontes) sont bloquées dans cette recette ciblée pour garder la comparaison déterministe; leurs déclarations/fichiers ne changent pas. Le navigateur utilise le repli local des polices et le mode mouvement réduit. Ces captures ne constituent pas une nouvelle validation des photos externes, de l'animation normale ou du site hébergé.

CSS analysé par PostCSS, portée exacte protégée par les empreintes des quatre fichiers et du patch, refus de toute modification du CSS officiel, contrôleurs de déploiement, recettes/promesses hors patch ou workflows protégés. Diagnostics PHP et absence de commandes/passerelles vérifiés en fin de fixture.

**Routage de tests, sans protection hébergée modifiée :** les seuls événements push des quatre fichiers et du routage sont dirigés vers `qa-p1-approved-copy.yml`. `qa-wordpress-isolated.yml` conserve ses jobs et ses entrées `workflow_call` / `workflow_dispatch` pour une suite complète manuelle ou appelée par un futur processus protégé. Toute autre modification de fichiers WordPress continue de déclencher la suite générale. La recette ciblée refuse des octets différents du correctif approuvé : un changement futur plus large impose une nouvelle analyse et les tests appropriés, pas une acceptation silencieuse. Aucun script `scripts/p1/**` n'est modifié et aucune variante A/B n'est relancée par ce lot.

Résultats CI et captures : à compléter après vérification du nouveau SHA exécuté; les preuves historiques restent attribuées à leurs SHA. Aucun succès anticipé.

## Version, cache et future décision

La version du thème reste **0.1.2 dans cette préparation**, afin de respecter exactement le patch revu; il s'agit de code de développement, **pas d'une nouvelle livraison 0.1.2**. Aucun tag, release GitHub, publication ou installation créé. Le build automatique éventuel est uniquement un ZIP de revue attaché au commit, non installable implicitement sur l'hôte.

Pour une livraison ultérieure : proposer **0.1.3**, confirmer d'abord le périmètre complet, ajouter l'incrément de version/cache dans un commit de préparation distinct, générer un nouveau manifeste correspondant et faire valider les fichiers réellement changés. La livraison existante limitée à la paire CSS 0.1.2 **ne convient pas** à ce correctif de quatre fichiers : elle doit rester intacte et ne doit pas être utilisée pour le publier. Une future procédure devra couvrir sauvegarde fraîche SQL/thème/langues/manifestes, répétition de récupération des quatre fichiers, vérification d'identité et Coming Soon, approbation humaine du run exact et validation des sources servies. Ne pas prétendre ces étapes réalisées ici.

À décider avant toute livraison : autorisation explicite du périmètre et de la version, puis revue GitHub du nouveau run. Les textes/politiques anglais, coordonnées et promesses commerciales restent séparés et ne sont pas publiés implicitement. Recettes futures : grande sélection **par produit** vers la section Recettes; promesses à discuter avec les propriétaires avant ouverture. Palette Actuel maintenue; contrastes des autres zones toujours ouverts, aucune certification WCAG globale. Aucun changement GoDaddy/site commercial/master/DNS/Zoho/paiements/taxes/livraison/produits/clients/commandes ni désactivation de Coming Soon.
