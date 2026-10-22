# P1 — décisions de Julien, 22 octobre 2026

Source : réponse explicite de Julien dans Work après examen des cinq sections de `content-ux-proposals.html`. Ces décisions doivent être reprises par la Coordination et par tout prochain intervenant; elles ne doivent pas être redemandées comme si elles étaient nouvelles. Base GitHub avant ce suivi : `15f06d99b3888c1b3e26a9904ed366db9833690e`.

## Validations acquises

| Section examinée | Décision de Julien | Suite autorisée / état |
| --- | --- | --- |
| 1 — contrôles d'achat français | « Parfait » | Conserver le bouton/sélecteur et les catalogues fr_CA déjà validés |
| 2 — texte de confidentialité proposé | Approuvé | Formulation du brouillon acceptée; partie anglaise persistée à traiter dans un lot de contenu séparé, sans écriture GoDaddy par cette approbation |
| 3 — proposition distincte d'infolettre | Approuvée | Panneau crème, texte charbon, couleurs déjà présentes; message sans promesse de date. Correctif préparé isolément |
| 4 — Contact | Approuvé | Un unique « Nous joindre », retrait du libellé téléphone trompeur tant que les coordonnées/destinations ne sont pas fournies |
| 4 — cohérence du français | Acceptée | Casse française, « et » dans les intitulés, texte alternatif générique français; aucune nouvelle palette |
| 5 — capture/parcours présenté | Satisfaisant | Conserver les acquis; la partie anglaise reste à corriger, pas à déclarer résolue |

Texte de confidentialité approuvé par Julien :

> Vos renseignements personnels seront utilisés pour traiter votre commande et vous accompagner dans votre utilisation de ce site. Pour connaître les autres utilisations, consultez notre politique de confidentialité.

Cette approbation porte sur la formulation présentée. La politique complète, ses traitements réels et la destination du lien restent à renseigner/valider dans la préparation éditoriale. Ne pas substituer un filtre de traduction global au contenu enregistré, ni prétendre que ce texte est déjà en ligne. Aucune option WordPress n'est modifiée par le correctif ci-dessous.

## Décisions différées à conserver impérativement

**Recettes :** Julien demande de reprendre ce chantier plus tard, avec une **grande sélection de recettes pour chaque produit**, menant à la **section Recettes du site**. Ce n'est pas l'approbation du petit changement de légende proposé dans l'audit. **Ne pas appliquer « Aperçu de recette » maintenant**, ne pas créer de recettes/pages ou de nouveaux liens provisoires. Préparer ultérieurement l'organisation et les relations produits/recettes avec la Coordination; rédaction, images et consignes à valider dans ce chantier.

**Promesses commerciales :** retravailler les formulations **plus tard, avant le déploiement/publication du site**, après échange avec **Jean-François et Meggy, les propriétaires**. Conserver ce point comme une condition de validation avant ouverture commerciale : origine/« 100 % local », chiffres, vieillissement, qualité/certifications, garanties/retours/remboursement, financement/paiement/livraison, avis et autres affirmations engageantes. **Ne rien inventer ni changer automatiquement.** La validation des couleurs ou du français ne vaut pas confirmation des promesses.

**Palette/identité :** Actuel conservé; aucun retour aux variantes globales A/B. Typographie, illustrations et contrôles d'achat approuvés inchangés.

## Correctif préparé, sans application au thème suivi

[Patch limité français/contact/infolettre](approved-copy-newsletter.patch), préparé contre le thème actuel 0.1.2 :

- `footer.php` : panneau crème commun aux deux branches existantes de l'infolettre, message neutre si aucun lien, lien existant conservé s'il est configuré; retrait du doublon « Commander par téléphone » et casse des collections.
- `style.css` : deux règles limitées à `.newsletter .ad-newsletter-panel`, utilisant uniquement `var(--cream)` et `var(--charcoal)`; aucun changement de variable, de police ou du CSS officiel.
- `front-page.php` et `template-parts/navigation.php` : corrections de français approuvées et alt générique; URLs/images et textes de recettes/promesses inchangés.

**Ce fichier est un patch de préparation, pas une application aux fichiers du thème dans GitHub ni une livraison.** Il n'est consommé par aucun workflow. Le thème suivi reste inchangé et aucun ZIP installable de nouvelle version n'est publié. L'application future devra préciser la version/cache et le périmètre exact avant son éventuel déploiement autorisé.

Contrôles isolés réalisés : syntaxe des trois gabarits PHP; **huit assertions du rendu du pied de page** (avec et sans URL d'infolettre), panneau unique, contact sans doublon, absence de promesse « bientôt », texte/lien attendu. Fonctions de rendu minimales simulées localement, sans WordPress/base/serveur distant. Applicabilité du patch vérifiée par `git apply --check`; contraste crème/charbon 13,3903:1 déjà calculé. Ces contrôles ne sont pas une recette navigateur du thème patché; les 28 vérifications de la planche restent attribuées à leur ancien SHA.

## Prochain passage de Coordination

1. Conserver ces choix comme validés, programmer l'intégration isolée du patch et sa recette pertinente sans relance inutile des variantes A/B.
2. Préparer séparément le remplacement du contenu anglais persistant selon la formulation approuvée, avec politique/lien complet et périmètre de changement explicite; aucune écriture hébergée implicite.
3. Garder les chantiers Recettes et Promesses commerciales dans le backlog, avec validation des propriétaires avant ouverture.
4. Toute nouvelle opération GoDaddy, configuration majeure et déploiement exige une autorisation distincte et les protections GitHub. Aucun changement au site commercial/master/DNS/Zoho/paiements ni désactivation de Coming Soon.
