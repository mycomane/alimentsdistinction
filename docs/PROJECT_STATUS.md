# Aliments Distinction — suivi Work / Coordination

Mise à jour : **21 octobre 2026**, fuseau America/Toronto.

## Phase et derniers commits

**P0 clôturé. P1 : Julien conserve « Actuel », palette choisie par Jean-François et Meggy. Intégration limitée au bouton d’ajout au panier et au sélecteur de variation de A. Site en français. Aperçu validé par Julien. Recette isolée et 45 contrôles français réussis, puis complément fonctionnel de 23 contrôles réussi; contenus français à compléter dans les fixtures. Aucun accès ou déploiement GoDaddy.**

- Branche exclusive : `feat/wordpress-woocommerce-theme`.
- Dernier commit P1 testé : [`61d940e2e0847b6f3358f4c98aa16c572f593148`](https://github.com/mycomane/alimentsdistinction/commit/61d940e2e0847b6f3358f4c98aa16c572f593148); captures seules : [`80bd8201a9a61b3c7e9225f31dca81c3296dc9b7`](https://github.com/mycomane/alimentsdistinction/commit/80bd8201a9a61b3c7e9225f31dca81c3296dc9b7). Code GoDaddy toujours `be5703eeaa434ada55cb33a961a0b08e11f6a862`, thème 0.1.1.
- Commit du correctif CSS : [`9618095516d982e10eebfe5c7aa0cdcc4bf85be3`](https://github.com/mycomane/alimentsdistinction/commit/9618095516d982e10eebfe5c7aa0cdcc4bf85be3).
- Les commits de compte rendu et de captures suivent ces commits; leur SHA exact est visible dans l’historique de ce fichier, sans référence auto-inventée.
- `master` observé à `c9d6d888a2aa07e375c86a4cd271cae7b53987da`, inchangé par ce travail.

## Validation de Julien et complément de recette française

- Julien a validé l’aperçu et demandé de continuer. L’issue #2 exige toujours un accord de déploiement distinct; aucun workflow protégé modifié ou déclenché.
- Code de recette complémentaire : [`0563c3a5a793e169959b6835b6316c0c3275ca25`](https://github.com/mycomane/alimentsdistinction/commit/0563c3a5a793e169959b6835b6316c0c3275ca25). [Run 39694834148](https://github.com/mycomane/alimentsdistinction/actions/runs/39694834148), job `119549577969` : **23 contrôles réussis**, zéro échec, zéro erreur JS/PHP comptée. Catalogue/tri, stock, variations, panier vide, ajout synthétique, quantité 2 → 1, champs de facturation et écran de commande. Aucun checkout soumis, paiement ou commande créée.
- Le premier [run 39694636299](https://github.com/mycomane/alimentsdistinction/actions/runs/39694636299) a échoué sur deux attentes de test : libellé natif « actuellement vide » et sous-total mobile dans un pseudo-élément `data-title`. Tests corrigés selon le rendu réel, aucun correctif de thème nécessaire.
- **Localisation complète non certifiée :** confidentialité de commande et catégorie de la fixture anglaise restent en anglais après changement de langue. Valeur persistée de confidentialité confirmée dans la base locale en lecture seule; pas de réécriture automatique. État GoDaddy inconnu dans cette phase. Les textes commerciaux et légaux français restent à valider.
- [Rapport français et preuves](p1/FRENCH_REVIEW.md). [Périmètre proposé 0.1.2](p1/DEPLOYMENT_SCOPE.md) : bloc `commerce.css` validé et seule ligne de version dans `style.css` pour les caches. Release non encore créée; mécanisme P0 non réutilisable pour ce nouveau périmètre. Autorisation distincte requise avant nouvelle livraison et approbation GitHub du nouveau run.
- Budget : recette existante réutilisée; deux passages ciblés seulement, aucun nouveau changement de thème ou relancement des 300 assertions. Coming Soon, protections GitHub, master et site commercial intacts.

## Décision P1 — intégration ciblée testée, non déployée

- Base de cette phase : `6fe6febb220889059db21af463ff0968401e2aa1`. Palette globale, accueil, navigation et infolettre conservés.
- CSS limité au bouton d’achat de la fiche produit (actif, survol, désactivé) et à la bordure du sélecteur de variations. Aucun filtre de traduction global ni changement des règles WooCommerce.
- Les anciens exemples étaient exécutés avec une fixture anglaise; nouvelle recette dédiée `fr_FR` avec paquets officiels WordPress/WooCommerce pour vérifier « Ajouter au panier » et « Choisir une option » aux cinq largeurs.
- Aucun réglage de langue GoDaddy effectué; langue et traductions de l’environnement hébergé restent à vérifier dans une future opération autorisée.
- Code testé : [`206972b1ab2228b17b6131c66a9475e7f085f3da`](https://github.com/mycomane/alimentsdistinction/commit/206972b1ab2228b17b6131c66a9475e7f085f3da).
- [Recette isolée 39692488831](https://github.com/mycomane/alimentsdistinction/actions/runs/39692488831), job `119541795281` : **succès**, recette existante réutilisée et **45 contrôles français supplémentaires réussis** sur 320/390/768/1024/1440. Libellés natifs, bouton désactivé/actif/survol/focus, bordure du sélecteur, absence de débordement. Aucune erreur JavaScript ni erreur PHP fatale/avertissement/dépréciation comptée; zéro commande créée et aucune passerelle active.
- [Construction 39692489014](https://github.com/mycomane/alimentsdistinction/actions/runs/39692489014) : **succès**. Source officielle, sept images, syntaxe PHP/JS et structure vérifiées. Aucun workflow protégé modifié.
- [Preuve des 45 contrôles](p1/evidence/french-controls.json), [capture téléphone](p1/evidence/french-controls-390.jpg), [capture ordinateur](p1/evidence/french-controls-1440.jpg). Captures synthétiques examinées; libellés français validés, pas certification de toute la localisation hébergée.
- Le contenu dynamique des fixtures reste synthétique (dont catégorie initiale « Uncategorized » et format monétaire de test). Il ne constitue pas un contenu commercial à publier. Le futur site doit être en français, contenus et traductions compris.
- Les 254 contrôles antérieurs concernent les propositions A/B, pas cette intégration. Version d’en-tête encore 0.1.1 dans cette préparation : incrément et invalidation des caches à prévoir dans un futur lot de déploiement autorisé.

## P1 — historique des variantes, non retenues globalement

[Issue #2](https://github.com/mycomane/alimentsdistinction/issues/2) exécutée en propositions isolées. P0 est clôturé par la Coordination; aucun nouveau compte ou accès GoDaddy.

- **A — évolution discrète :** menu aux mêmes positions, petites surfaces crème opaques derrière liens/icônes, boutons rose encre et gris secondaire assombri.
- **B — lecture renforcée (historique, non retenue) :** barre crème opaque continue, boutons et liens charbon, gris secondaire plus profond.
- Commun : rose décoratif/photos/mise en page conservés, rose encre `#94544B` sur surfaces unies, **`#6B3832` localement sur les textures/feuilles réelles**, infolettre claire, focus bicolore et états WooCommerce lisibles.
- **[Planche autonome A/B](p1/choice.html)** avec vues ordinateur/téléphone/infolettre/WooCommerce; [rapport des compromis et limites](p1/REPORT.md), [captures JPEG](p1/evidence/choice-desktop.jpg) et [preuves JSON](p1/evidence/report.json).
- **[Run ciblé 39690003471](https://github.com/mycomane/alimentsdistinction/actions/runs/39690003471), job 119533553118 : succès, 254 contrôles ciblés**, zéro erreur. Géométrie actuel/A/B aux cinq largeurs : **écart maximal 0,00 px**; clavier/menu/images sans régression P0.
- Contraste sur pixels de fonds réellement rendus à 390/1440 : 194/199 éléments visibles par accueil; **aucune mesure auditée sous son seuil pour A/B** (4,5:1 texte normal, 3:1 grand texte/icônes). Navigation minimum desktop actuel 1,00:1 → **5,42:1** pour les deux variantes. Textes secondaires minimum **4,67:1 A / 5,61:1 B**. Échantillonnage, pas certification WCAG globale.
- États WooCommerce : non sélectionné/actif/survol/focus, variation indisponible, stock et erreur de champ requis; aucun checkout soumis, commande ou paiement. Logs PHP présents, zéro fatal/avertissement/dépréciation compté.
- **[Run de captures 39690653253](https://github.com/mycomane/alimentsdistinction/actions/runs/39690653253), job 119535722196 : succès**, vues compactes régénérées avec pointeur neutre pour montrer les styles de repos; aucune modification CSS ni relance des 300 assertions. Planches examinées.
- Budget : deux passages ciblés (le premier a identifié des textes trop clairs sur les textures réelles), une capture seule; fixtures/comparateur P0/scanner PHP réutilisés. Aucun changement de workflow protégé, de `index.html` ou du thème `wordpress/`.
- **Décision Julien reçue : « Actuel », seuls les deux contrôles d’achat de A sont retenus.** L'accord graphique permettra de préparer l'intégration dans le thème; aucun déploiement implicite. Tout futur déploiement nécessitera autorisation distincte, sauvegarde et approbation GitHub.

## Recette hébergée post-déploiement — réussie

**[Run 39686849316 — succès](https://github.com/mycomane/alimentsdistinction/actions/runs/39686849316)**, job `119523045267`, code `6b3031a74792827836d3bab57d181896b7d15d40`. Préparation `119522989146` réussie, nouvelle approbation `mycomane` du run exact vérifiée. Fin : **21 octobre, 18 h 34 HAE**.

- **88 contrôles frontend réussis**, zéro échec, zéro erreur JavaScript `pageerror`.
- Rendu réel à **320/390/768/1024/1440** : écart maximal **0,00 px** sur positions/dimensions des six zones du héro comparées à `index.html`, dont réassurance; aucune découpe/chevauchement, débordement ou image locale cassée détectée.
- HTML authentifié demandant CSS 0.1.1 et SHA256 du fichier reçu conformes au déploiement; menu Enter/Échap/focus, ancres et quatre pages techniques contrôlés.
- Checkout **vide renvoie au panier**, sans formulaire de commande. Aucun produit ajouté, paiement ou commande; le commerce hébergé complet n'est pas déclaré validé.
- **Coming Soon anonyme avant/après**, compte Abonné de ce run **supprimé et sessions révoquées**, fichiers sensibles du runner supprimés, réglages et compteurs produits/commandes protégés inchangés.
- Aucune capture/HTML/cookie authentifié publié; preuve limitée aux compteurs/géométries. Cette recette automatisée n'est pas une comparaison manuelle exhaustive de toutes les sections.
- QA isolé parallèle **[39686849309](https://github.com/mycomane/alimentsdistinction/actions/runs/39686849309)**, job `119522988346` : **300 assertions réussies**, zéro fatal/avertissement/dépréciation PHP compté.
- [Rapport final de recette P0](P0_POSTDEPLOY_REVIEW_2026-10-21.md) et [preuve structurée hébergée](evidence/p0-hosted-review-result.json).

Les critères techniques P0 de l'issue #1 sont remplis. Aucun nouveau compte ou autre déploiement nécessaire pour P0. La Coordination conserve la décision de clôture de l'issue et les priorités P1; aucune permission de palette/configuration commerciale n'est déduite de cette recette.

## État courant — déploiement technique réussi

Site : **https://1302636.us22.myftpupload.com/** uniquement. [Run 39685183348](https://github.com/mycomane/alimentsdistinction/actions/runs/39685183348), job distant **119518254594 : succès** après nouvelle approbation `mycomane` vérifiée pour ce run exact. Code exécuté : `be5703eeaa434ada55cb33a961a0b08e11f6a862`. Fin des contrôles : 21 octobre, 18 h 21 HAE.

| Contrôle distant | Résultat réellement vérifié |
| --- | --- |
| Identité, thème et version initiale | `siteurl`/`home` exacts, `aliments-distinction` 0.1.0 actif, 23 empreintes anciennes conformes |
| Mesure DB et espace | Mesure valide; marge 4 thèmes + 3 bases + 100 Mio satisfaite |
| Sauvegarde fraîche | `~/.ad-private-backups/p0-011-39685183348/` : thème complet 0.1.0, archive, SQL gzip et SHA256 vérifiés |
| Retour arrière | Extraction privée du thème complet conforme; échange CSS aller/retour réellement répété dans le dossier privé avant bascule |
| Mise à jour | Seul `style.css` remplacé atomiquement : suppression de la règle P0 et en-tête 0.1.1; les 22 autres fichiers inchangés |
| Cache | Seule clé d'en-tête de ce thème invalidée; aucune purge globale ou CDN |
| État final | WordPress **7.1.3**, PHP **CLI 8.2.35**, WooCommerce **11.2.1 actif**, thème **0.1.1 actif** |
| CSS servi | HTTP : octets exacts du nouveau `style.css?ver=0.1.1`, SHA256 attendu confirmé |
| Visibilité | Accueil + quatre pages techniques : HTTP 200 et **Coming Soon** avant/après |
| Réglages et données protégés | Empreintes commerciales/visibilité/pages, tables taxes/livraisons et compteurs produits/commandes/utilisateurs inchangés |

Aucun rollback réel requis après la bascule. La répétition a porté sur les copies privées; aucune restauration SQL exécutée. La sauvegarde reste sur le même hébergement, sans restauration intégrale/offsite certifiée. PHP web n'a pas été resondé; la valeur 8.2.35 ci-dessus est celle du CLI.

- **Tests isolés du même commit :** préparation 119517310652 et QA 119517311211 réussis : 46 assertions mesure/espace, 17 gardes fichiers/visibilité, six scénarios MySQL et **300 assertions WordPress**, géométrie à 0,00 px aux cinq largeurs.
- **Validation hébergée complétée :** run 39686849316, 88 contrôles et géométrie réelle sans écart aux cinq largeurs, nettoyage confirmé; voir rapport final ci-dessus.
- **Autorisation exécutée et terminée :** un Abonné temporaire créé pour cette recette, session révoquée et compte supprimé. Aucun compte de recette actif à réutiliser.
- Preuves : [déploiement technique](evidence/p0-deployment-result.json), [QA du candidat](evidence/p0-newlines-qa.json), [diagnostic structurel hébergé](evidence/p0-hosted-db-shape.json), [procédure/rollback](P0_DEPLOYMENT_2026-10-21.md).
- ZIP 0.1.1 : SHA256 `8162bc571bb5dea232badd562b5c9085c30187b1b4ff80f567f1f5e9fdb8497e`; CSS installé : `5a4c3a5a8ae6fccd66437151555b66d05c1f5c18dc56cfff3de3f66a92bde780`.

Le diagnostic protégé `39639769111` avait confirmé WP-CLI 2.12.0, l'ancien thème et Coming Soon, puis deux LF finaux après une ligne TSV valide. Le correctif de parsing autorise seulement 0–2 fins LF/CRLF complètes; toutes les autres protections restent actives.

## Historique — première correction de mesure DB

[Directive Coordination](https://github.com/mycomane/alimentsdistinction/issues/1#issuecomment-6321369680) lue et journaux du job `119277446903` vérifiés. Le run `39615680576` a échoué après approbation à `Database size not measurable`, avant création de la sauvegarde P0 et remplacement CSS. Bundle privé/lock possibles; pas de contrôle distant post-échec dans cette reprise.

- Base de travail : `4b0c1d0c54578213dc9fa031692024be4ed44b51`. Ancien run non relancé.
- Incompatibilité CSV/unité `B` établie dans le code officiel WP-CLI; cause probable de la sortie non numérique. Version/sortie exacte GoDaddy encore inconnues.
- Remplacement par SELECT agrégé en octets sur la DB courante, parseur strict, stderr/statut contrôlés et journalisation expurgée. Aucune estimation arbitraire, aucun contournement en cas de mesure impossible.
- Marge inchangée : 4 thèmes + 3 bases + 100 Mio; valeurs et arithmétique bornées. Toutes les protections de mise à jour et rollback conservées.
- Tests locaux : **39 assertions mesure/espace + 17 gardes filesystem/Coming Soon réussies**; syntaxe vérifiée.
- **Nouveau run : [39638388563 — approuvé puis échoué](https://github.com/mycomane/alimentsdistinction/actions/runs/39638388563)**, commit `ee9cd17655d5454483be339fdb5a7d4828305d58`.
- Job `prepare` **119355138291 : succès**. ShellCheck/lint/paquet exact; **39 assertions mesure/espace et 17 gardes fichiers/visibilité**. MySQL 8.0 jetable : requête réelle puis **quatre scénarios** capture/parseur/nettoyage réussis (normal, vide, avertissement, indisponibilité).
- Job `isolated-qa` **119355138814 : succès**. **300 assertions ciblées** (11 PHP + 41 navigateur + 61 P0 + 55 sections + 132 WooCommerce); écart **0,00 px aux cinq largeurs**; aucune commande créée. Zéro fatal/avertissement/dépréciation PHP compté.
- Job distant **119356247301 : échec après approbation**, sur la forme de sortie de mesure; voir l’incident courant ci-dessus. Aucun remplacement du CSS atteint dans ce chemin.
- [Preuves structurées de cette reprise](evidence/p0-db-measurement-qa.json). Artefact `p0-011-review-package` disponible dans le run, SHA du ZIP inchangé.
- Cet ancien run ne doit pas être relancé; le nouveau diagnostic a sa propre approbation.
- Aucun changement du thème/couleurs/contenus; ZIP 0.1.1 identique. Aucun nouvel accès GoDaddy réalisé pendant cette correction.
- [Diagnostic, stratégie et limites](P0_DB_MEASUREMENT_2026-10-21.md). [Procédure de déploiement/rollback](P0_DEPLOYMENT_2026-10-21.md).
- **Nouvelle approbation reviewer requise avant toute opération distante.** L'ancien accord n'est pas réutilisé. La Coordination réexaminera le nouveau workflow.
- Abonné temporaire de recette toujours **non autorisé**; aucun compte créé.

## Historique — travaux P0/P1/P2 déjà terminés

- Passation, feuille de route, rapports de recettes et bilan d’activation lus intégralement; derniers commits et runs contrôlés avant intervention.
- Écart mobile reproduit : le troisième indicateur de réassurance retourne à la ligne et ajoute **52,60 / 52,59 px** à 320/390, précédemment arrondis à 53.
- **Seul changement au thème : suppression d’une ligne CSS** qui forçait `flex-wrap: wrap` sur `.hero__trust`. `index.html`, le CSS original, les sept images, les textes et la palette sont intacts.
- Écart corrigé à **0,00 px** aux cinq tailles 320/390/768/1024/1440; contrôles des enfants, CTA, photo, absence de découpe/chevauchement et menu/focus.
- 14 régions d’accueil contrôlées, liens internes, noms des contrôles, images et navigation clavier de base; captures desktop/téléphone examinées.
- WooCommerce : ordre numérique ascendant/descendant, pagination sur douze produits synthétiques, filtre de prix, variations disponibles/indisponible, quantités et panier; 25 combinaisons page/taille, formulaire classique de commande et erreurs de champ sans soumission.
- Proposition de contraste avec couleurs d’encre plus sombres, texte clair pour l’infolettre et pistes de focus/navigation; **non appliquée au thème**.
- Modification locale préexistante de `volaille.jpg` préservée dans la copie initiale et exclue; travail réalisé dans une copie propre.

## Historique — tests de la phase précédente

Dernier run isolé : [**39610664542 — succès**](https://github.com/mycomane/alimentsdistinction/actions/runs/39610664542), job `119258365224`.

Environnement : WordPress **7.1.3**, WooCommerce **11.2.1**, PHP **8.3.6**, runner jetable 127.0.0.1 uniquement.

| Groupe | Assertions réussies |
| --- | ---: |
| Intégration PHP existante | 11 |
| Navigateur/panier existants | 41 |
| Géométrie et focus P0 | 61 |
| Sections, liens et clavier | 55 |
| WooCommerce étendu/responsive | 132 |
| **Total de ces groupes** | **300** |

Contrôles complémentaires : quantités PHP 0/-1/6 refusées; 14 couples de couleurs proposés mesurés; **zéro commande créée**, aucune passerelle disponible. Journaux PHP serveur/WordPress : **0 fatal, 0 avertissement, 0 dépréciation** comptés. Aucun `pageerror` dans les scénarios exécutés.

Autres preuves :
- [Diagnostic contrôlé 39605089716](https://github.com/mycomane/alimentsdistinction/actions/runs/39605089716), réussi.
- [Régression du correctif réel 39605707095](https://github.com/mycomane/alimentsdistinction/actions/runs/39605707095), réussie.
- [Build/ZIP candidat 39605707177](https://github.com/mycomane/alimentsdistinction/actions/runs/39605707177), réussi, **aucun déploiement**.
- [Rapport P0 et comparaison avant/après](P0_MOBILE_2026-10-21.md).
- [Rapport Phase 3, limites et anomalies classées](RECETTE_PHASE3_2026-10-21.md).
- [Mesures structurées et rapports du run final](evidence/qa-summary-2026-10-21.json).
- [Contrastes proposés](evidence/contrast-proposal.jpg), [accueil téléphone](evidence/homepage-390.jpg), [accueil ordinateur](evidence/homepage-1440.jpg), [écrans WooCommerce](evidence/commerce-390.jpg).

Les échecs intermédiaires de fixture/synchronisation sont expliqués dans le rapport Phase 3; le dernier run a corrigé ces causes dans les tests, sans changement du moteur WooCommerce.

## Historique — état GoDaddy avant les tentatives de déploiement

La phase isolée initiale n’avait exécuté **aucune opération GoDaddy**, aucun compte créé, aucune connexion Zoho, aucune configuration commerciale, DNS ou domaine.

Dernier état hébergé vérifié antérieurement : thème **0.1.0** actif, WooCommerce **11.2.1** actif, quatre pages affectées. [Recette privée 39531003568](https://github.com/mycomane/alimentsdistinction/actions/runs/39531003568) : 33 assertions réussies, Coming Soon anonyme avant/après, compte Abonné supprimé et sessions révoquées. Ne pas extrapoler cette preuve aux modifications non déployées.

La sauvegarde privée préactivation `wc-preactivate-39515208953` et les protections `godaddy-temp` restent celles documentées. Aucun installateur, activation ou sauvegarde existante n’a été relancé.

## Anomalies et décisions en attente

| Priorité | Élément restant | Décision / action |
| --- | --- | --- |
| P0 technique terminé | Déploiement 0.1.1 réussi, sauvegarde et CSS vérifiés | Aucune nouvelle approbation de déploiement requise pour cette opération déjà terminée |
| P0 validé | Recette hébergée et nettoyage réussis | Coordination peut valider la clôture de l’issue #1 |
| Choix esthétique P1 | Deux variantes testées; palette hébergée toujours inchangée | Actuel conservé; seulement deux contrôles A |
| Choix esthétique P1 | A : surfaces locales; B : barre crème opaque | Variantes complètes archivées, non retenues |
| Bloquant commercial | Téléphone/coordonnées, pages de livraison/retours/confidentialité, garanties et affirmations commerciales | Contenus et validation du propriétaire/Julien nécessaires |
| Majeur contenu | Recettes et service d’infolettre incomplets | Rédaction et service approuvés; aucune fausse inscription |
| Préparé en développement | Bouton de variation désactivée harmonisé et deux libellés français testés | Déploiement distinct à autoriser; GoDaddy inchangé |
| À confirmer avant ouverture | Localisation française et commerce hébergé avec panier non vide | Checkout vide vers panier maintenant confirmé; autres scénarios restent distincts |
| Avant production | Hors site/restauration intégrale, confirmation SSH indépendante, performance/CWV et règles commerciales | Préparer la validation, sans connexion ou configuration non autorisée |

## Prochaines étapes recommandées

1. Accord graphique reçu. Julien décide du déploiement temporaire décrit dans `p1/DEPLOYMENT_SCOPE.md`; ensuite seulement préparer un nouveau run protégé avec revue obligatoire.
2. Lors d’une prochaine intervention autorisée, relever langue, paquets français et textes enregistrés sur GoDaddy; proposer les corrections de contenu nécessaires sans modifier les réglages commerciaux.
3. Tout futur déploiement GoDaddy sera préparé avec autorisation distincte, sauvegarde/rollback et nouvelle revue `godaddy-temp`.
4. Valider contenus commerciaux, coordonnées, contrats/garanties, recettes et service d'infolettre. La recette transactionnelle et les réglages métier restent distincts.
5. Conserver Coming Soon et restrictions site commercial/master/DNS/paiements/Zoho. Avant production, sauvegarde hors site/restauration intégrale, sécurité SSH et performance restent à traiter.

Ce fichier est le point de synchronisation avec ChatGPT Coordination; aucun message de cette conversation Work n’y est supposé reçu automatiquement.
