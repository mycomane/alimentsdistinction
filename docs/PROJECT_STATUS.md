# Aliments Distinction — suivi Work / Coordination

Mise à jour : **22 octobre 2026**, audit de sécurité en lecture seule.

## Vérification de sécurité du WordPress temporaire — 22 octobre, 11 h 54 HAE

**Le runtime PHP Web du WordPress temporaire a été vérifié par Julien dans Santé du site. La version observée satisfait aux correctifs applicables aux CVE-2026-109618 et CVE-2026-109619. Aucune mise à jour supplémentaire n'est nécessaire pour ces deux vulnérabilités sur la base de cette observation.**

- Source : capture utilisateur `image(7).png`, Outils → Santé du site → Informations → Serveur. Horloge visible : `2026-10-22T15:54:28+00:00` (11 h 54 min 28 s HAE). Observation par Julien dans son navigateur; pas une mesure automatisée Work. L'URL n'est pas visible sur cette capture; rattachement au temporaire fondé sur la séquence GoDaddy/SSO montrée, dont l'hôte temporaire figurait sur les captures précédentes.
- Par précaution, les valeurs techniques exactes du runtime et du serveur ne sont pas reproduites dans ce document de suivi public. La preuve d'audit demeure conservée séparément dans le dépôt, qui est actuellement public; cela ne constitue donc pas un retrait complet de ces données de toutes les sources.
- Le constat est cohérent avec les [versions corrigées officiellement publiées par PHP](https://www.php.net/archive/2026.php). Il remplace l'incertitude de version du bilan initial ci-dessous, conservé comme historique. La capture n'affiche pas l'URL du site; son attribution au temporaire repose sur la navigation administrative précédente.
- Aucun changement serveur, déploiement ou test d'exploitation effectué. Cela ne constitue pas un audit de compromission ni une certification de toutes les extensions ou de tous les workers. Le site commercial reste hors périmètre.
- Blocage de version Web levé; **issue #3 clôturée par la Coordination pour le WordPress temporaire, reprise de P1 autorisée en développement isolé**. Aucun workflow protégé déclenché par cette décision. Toute opération GoDaddy, dont une éventuelle configuration fr_CA, demeure soumise à une autorisation et à la revue GitHub propres à son périmètre.

## Historique — audit initial PHP2Shell, avant preuve Web

**P1 et la configuration française sont suspendues. Protection PHP Web NON CONFIRMÉE : aucune mise à jour ni nouvelle opération GoDaddy exécutée.**

- Base documentaire auditée : `421b582ff3a127e577aeab515ef990d3cd2334c7`; le commit de ce compte rendu figure dans l'historique, sans SHA auto-inventé. Branche exclusive inchangée.
- Preuve Web historique antérieure à la vérification actuelle : [run 39515208953, job 118945599076](https://github.com/mycomane/alimentsdistinction/actions/runs/39515208953/job/118945599076), **2026-10-21 00:17:35 UTC**. Elle ne remplace pas une mesure récente et n'était pas suffisante pour conclure au correctif.
- Preuve CLI plus récente : [déploiement 39696116068](https://github.com/mycomane/alimentsdistinction/actions/runs/39696116068), **2026-10-21 23:57:44 UTC**. WordPress 7.1.3, WooCommerce 11.2.1 actif, thème 0.1.2. **CLI ne prouve pas Web**, d'où le contrôle séparé du runtime HTTP.
- Tentative GoDaddy en lecture seule **2026-10-22 04:09:34 UTC** : `ERR_SOCKS_CONNECTION_FAILED`; aucun tableau de bord ou SSO WordPress chargé. GET HTTPS public distinct vers le temporaire : **403**. Aucun contournement ni endpoint PHP créé.
- Avis officiels PHP/Centre canadien vérifiés : seuils corrigés **8.2.35 / 8.3.36 / 8.4.27 / 8.5.12**. L'ancien 8.2.34 observé impose une vérification urgente, pas une conclusion sur l'état actuel.
- P1 : run de configuration [39699480039](https://github.com/mycomane/alimentsdistinction/actions/runs/39699480039) terminé en échec de préparation avant SSH; [QA isolée 39699479899](https://github.com/mycomane/alimentsdistinction/actions/runs/39699479899) réussie. Aucun déploiement en attente lancé par cet audit; français hébergé toujours non configuré par ce lot.
- Prochaine étape bloquante : rétablir l'accès administratif ou obtenir une attestation GoDaddy du **PHP Web exact, SAPI, build/correctifs des deux CVE et renouvellement des workers** de ce seul site temporaire. Ni le sélecteur « 8.2 » ni le CLI ne suffisent.
- Si correction nécessaire : présenter le périmètre et les risques à Julien, vérifier une sauvegarde fraîche réellement restaurable, compatibilité et retour arrière avec GoDaddy, puis obtenir l'approbation distincte avant mutation. Ne pas revenir à un runtime vulnérable sans confinement approuvé.
- Aucune intervention sur le commercial, master, DNS, Coming Soon, paiements ou Zoho. Audit de compromission et inventaire frais exhaustif des extensions **non réalisés**. Issue #3 non clôturée.
- [Rapport, preuves, limites et plan conditionnel](SECURITY_PHP2SHELL_2026-10-22.md).

## Phase et derniers commits

Les sections P1 ci-dessous sont l'historique antérieur à la suspension de sécurité.

**P0 clôturé. P1 : thème 0.1.2 déployé avec succès sur GoDaddy temporaire après autorisation de Julien et nouvelle revue GitHub. Palette « Actuel » conservée; seuls les deux contrôles approuvés et la version/cache ont changé. Coming Soon maintenu, sauvegarde et état protégé vérifiés. Point restant : WordPress hébergé est en `en_US`; l’inventaire des paquets français n’est pas établi par le premier diagnostic.**

- Branche exclusive : `feat/wordpress-woocommerce-theme`.
- Code P1 déployé/testé : [`ad3006ca7c618b9d71617232bec67fe7c9cd322a`](https://github.com/mycomane/alimentsdistinction/commit/ad3006ca7c618b9d71617232bec67fe7c9cd322a), thème **0.1.2**. Précédent code P0 : `be5703eeaa434ada55cb33a961a0b08e11f6a862`, thème 0.1.1. Les propositions A/B complètes n’ont pas été intégrées.
- Commit du correctif CSS : [`9618095516d982e10eebfe5c7aa0cdcc4bf85be3`](https://github.com/mycomane/alimentsdistinction/commit/9618095516d982e10eebfe5c7aa0cdcc4bf85be3).
- Les commits de compte rendu et de captures suivent ces commits; leur SHA exact est visible dans l’historique de ce fichier, sans référence auto-inventée.
- `master` observé à `c9d6d888a2aa07e375c86a4cd271cae7b53987da`, inchangé par ce travail.

## Localisation française — configuration protégée fr_CA préparée

- Julien demande de continuer après le bilan de livraison. Préparation limitée aux traductions officielles et à **WPLANG → fr_CA**, avec **aucun changement de règles commerciales ou texte légal**, Coming Soon conservé. Nouvelle revue GitHub obligatoire avant toute opération GoDaddy. Thème toujours 0.1.2, aucune nouvelle livraison de thème.
- **Rectification du diagnostic P1 :** `en_US` et les libellés anglais sont confirmés. Les clés historiques `core_fr_pack` / `woo_fr_pack` ciblaient en réalité `get_locale()` (en_US), donc ne prouvent **pas** l’absence de fr_CA/fr_FR. Valeurs brutes conservées, interprétation corrigée. Le nouveau diagnostic vérifie explicitement les chemins français canadien et français de France.
- APIs officielles WordPress.org vérifiées : paquets **fr_CA WordPress 7.1.3 / WooCommerce 11.2.1** disponibles, deux ZIP épinglés par SHA256; **311 fichiers** validés. Tout changement de paquet en amont exige une nouvelle revue. [Sources/périmètre](locale/README.md), [empreintes](locale/fr-ca-packages.json).
- Contrôleur nouveau, séparé de P0/P1 : sauvegarde fraîche SQL/thème/langues/état de WPLANG, empreintes, essai privé des fichiers et journal; retour arrière des seuls fichiers/option ciblés. Aucune restauration SQL intégrale. Les 23 fichiers de thème et les anciens gardes sont vérifiés inchangés.
- Tests locaux réussis : **30 assertions réelles sur WordPress/SQLite** (valeur et autoload d’origine de WPLANG, refus de changement commercial et de locale inconnue), vérification fr_CA/libellés dans un processus neuf, **28 assertions fichiers**, **7 assertions Coming Soon bilingue**, **4 scénarios du vrai shell avec transports simulés**. Ne pas les qualifier de tests GoDaddy.
- Premier run [39697667048](https://github.com/mycomane/alimentsdistinction/actions/runs/39697667048) : attentes lexicales fr_FR inadaptées à fr_CA; traductions officielles conservées. Second [39698258841](https://github.com/mycomane/alimentsdistinction/actions/runs/39698258841) : injection de faute de test neutralisée par l’API de langue, corrigée dans la base jetable. Les tests ont également révélé et permis de corriger la restauration exacte d’une ancienne valeur en_US normalisée par WordPress. Aucune de ces erreurs n’a touché GoDaddy.
- Nouveau workflow `configure-locale-fr-ca.yml` soumis à `godaddy-temp` / `mycomane`, contrôle de revue du run exact, empreinte SSH, identité temporaire, concurrence identiques. Attend le workflow isolé fr_CA du même commit, sans relancer la suite générale. Résultats GitHub à compléter après exécution; aucun nouveau succès hébergé anticipé.
- Les textes de confidentialité restent inchangés et à valider en français. Aucun nouveau compte/produit, aucune commande, aucun paiement, aucune modification de devise/taxes/livraison/pays commercial ou Zoho.

## P1 — déploiement 0.1.2 réussi; français hébergé à compléter

- **[Run 39696116068](https://github.com/mycomane/alimentsdistinction/actions/runs/39696116068) : succès**, job de déploiement `119554780896`. Approbation humaine `mycomane` de `godaddy-temp` enregistrée et vérifiée pour cette exécution précise. Marqueur de réussite : **21 octobre 2026, 19 h 57 HAE**. Aucune auto-approbation.
- Identité temporaire contrôlée avant téléversement : home/siteurl strictement `https://1302636.us22.myftpupload.com`. WordPress **7.1.3**, WooCommerce **11.2.1** actif; thème actif de **0.1.1 à 0.1.2**. À l'époque, la version PHP Web n'avait pas encore été vérifiée séparément.
- Deux fichiers seulement : `assets/css/commerce.css` (contrôles approuvés) et `style.css` (ligne Version/cache). **Les 23 empreintes initiales puis finales sont vérifiées.** Source officielle, images et palette globale préservées; ancien workflow P0 intact.
- **Sauvegarde fraîche privée** : `~/.ad-private-backups/p1-012-39696116068/`, thème complet et SQL compressé avec SHA256 vérifiés. Archive du thème extraite et vérifiée; mise à jour puis retour arrière de la paire répétés sur cette copie privée, sauvegarde originale préservée. Sauvegarde sur l’hôte seulement, pas de restauration SQL intégrale/hors site certifiée.
- Remplacement réel journalisé de la paire; invalidation de la seule clé de cache d’en-tête du thème. Les **deux CSS sont servis en HTTP avec leurs empreintes exactes avant/après**. Aucune restauration de la paire réelle nécessaire : tous les contrôles finaux passent.
- Options protégées, pages techniques, comptes/produits/commandes et règles de taxes/livraison vérifiés inchangés par le script. **Accueil et quatre pages techniques anonymes : HTTP 200 et Coming Soon avant/après**. Aucun nouveau compte, produit, commande, paiement ou réglage commercial.
- **Diagnostic de langue en lecture seule : `en_US`, présence des paquets français non établie**, libellés renvoyés « Add to cart » / « Choose an option ». Texte de confidentialité enregistré avec marqueur anglais. **La version 0.1.2 déploie les styles; elle ne rend pas encore le WordPress hébergé français.** Aucun réglage de langue, paquet ou texte légal modifié par ce lot. Préparer une phase française distincte avec protection des réglages commerciaux et validation du contenu.
- **Limite : aucun nouveau contrôle visuel authentifié sur GoDaddy**, aucune capture hébergée ni compte de recette créé. Recette visuelle/transactionnelle isolée réussie; ne pas en déduire un parcours transactionnel complet hébergé ou une certification globale.
- [Preuve de déploiement](p1/evidence/release-0.1.2-deployment.json). La [preuve de préparation/revue](p1/evidence/release-0.1.2-review.json) conserve le statut historique avant approbation.
- QA du commit exact : [39696116033](https://github.com/mycomane/alimentsdistinction/actions/runs/39696116033), job `119553783106`, **succès**; suite complète + 45 contrôles français aux cinq largeurs, aucune erreur JS/PHP détectée et aucune commande/passerelle active. Job barrière `119553783373` réussi, aucune seconde suite complète inutile.
- Préparation `119553783218` : **succès**, 17 gardes fichiers existants, 23 assertions paire/interruption/retour arrière, 4 simulations shell, 46 assertions SQL/marge et 6 scénarios MySQL. [Build 39696115899](https://github.com/mycomane/alimentsdistinction/actions/runs/39696115899) réussi. ZIP SHA256 `45aae70781a815e4248ea04561f5e14a28917fc0ebc0851aebd883db533f106f`.

## Historique — validation de l’aperçu et recette française isolée avant déploiement

- Julien a validé l’aperçu et demandé de continuer. L’issue #2 exige toujours un accord de déploiement distinct; aucun workflow protégé modifié ou déclenché.
- Code de recette complémentaire : [`0563c3a5a793e169959b6835b6316c0c3275ca25`](https://github.com/mycomane/alimentsdistinction/commit/0563c3a5a793e169959b6835b6316c0c3275ca25). [Run 39694834148](https://github.com/mycomane/alimentsdistinction/actions/runs/39694834148), job `119549577969` : **23 contrôles réussis**, zéro échec, zéro erreur JS/PHP comptée. Catalogue/tri, stock, variations, panier vide, ajout synthétique, quantité 2 → 1, champs de facturation et écran de commande. Aucun checkout soumis, paiement ou commande créée.
- Le premier [run 39694636299](https://github.com/mycomane/alimentsdistinction/actions/runs/39694636299) a échoué sur deux attentes de test : libellé natif « actuellement vide » et sous-total mobile dans un pseudo-élément `data-title`. Tests corrigés selon le rendu réel, aucun correctif de thème nécessaire.
- **Localisation complète non certifiée :** confidentialité de commande et catégorie de la fixture anglaise restent en anglais après changement de langue. Valeur persistée de confidentialité confirmée dans la base locale en lecture seule; pas de réécriture automatique. État GoDaddy inconnu dans cette phase. Les textes commerciaux et légaux français restent à valider.
- [Rapport français et preuves](p1/FRENCH_REVIEW.md). [Périmètre proposé 0.1.2](p1/DEPLOYMENT_SCOPE.md) : bloc `commerce.css` validé et seule ligne de version dans `style.css` pour les caches. Release non encore créée; mécanisme P0 non réutilisable pour ce nouveau périmètre. Autorisation distincte requise avant nouvelle livraison et approbation GitHub du nouveau run.
- Budget : recette existante réutilisée; deux passages ciblés seulement, aucun nouveau changement de thème ou relancement des 300 assertions. Coming Soon, protections GitHub, master et site commercial intacts.

## Historique — intégration ciblée avant livraison

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
| État final | WordPress **7.1.3**, WooCommerce **11.2.1 actif**, thème **0.1.1 actif** |
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

1. Coordination examine les preuves du déploiement réussi et conserve les limites de recette hébergée; aucune nouvelle livraison de ce lot requise.
2. Préparer la localisation française du WordPress temporaire : langue, paquets officiels core/WooCommerce et contenus enregistrés. État observé `en_US`; ne pas confondre le succès CSS avec l’installation du français. Le choix de locale et les textes de confidentialité doivent être présentés avant modification hors du lot approuvé.
3. Si nécessaire, autoriser séparément une recette visuelle privée hébergée; ne pas créer implicitement de compte ou de produit sur GoDaddy. Coming Soon reste actif.
4. Valider les contenus commerciaux, coordonnées, contrats/garanties, recettes et service d’infolettre. Taxes, livraison, devises et paiements restent hors périmètre.
5. Conserver sauvegardes, revue GitHub, restrictions production/master/DNS/Zoho. Avant production, sauvegarde hors site/restauration intégrale, sécurité SSH et performance restent à traiter.

Ce fichier est le point de synchronisation avec ChatGPT Coordination; aucun message de cette conversation Work n’y est supposé reçu automatiquement.
