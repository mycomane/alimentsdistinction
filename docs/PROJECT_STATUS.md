# Aliments Distinction — suivi Work / Coordination

Mise à jour : **21 octobre 2026**, fuseau America/Toronto.

## Phase et derniers commits

**P0 : diagnostic hébergé réussi; cause établie (deux LF finaux). Correction ciblée du parseur testée dans GitHub. Nouveau run 39685183348 PRÊT POUR APPROBATION, sans nouvelle opération distante. Aucun déploiement effectué par le diagnostic.**

- Branche exclusive : `feat/wordpress-woocommerce-theme`.
- Dernier commit d’implémentation/tests vérifié : [`be5703eeaa434ada55cb33a961a0b08e11f6a862`](https://github.com/mycomane/alimentsdistinction/commit/be5703eeaa434ada55cb33a961a0b08e11f6a862), correction bornée des fins de ligne. Aucun changement au paquet du thème 0.1.1.
- Commit du correctif CSS : [`9618095516d982e10eebfe5c7aa0cdcc4bf85be3`](https://github.com/mycomane/alimentsdistinction/commit/9618095516d982e10eebfe5c7aa0cdcc4bf85be3).
- Les commits de compte rendu et de captures suivent ces commits; leur SHA exact est visible dans l’historique de ce fichier, sans référence auto-inventée.
- `master` observé à `c9d6d888a2aa07e375c86a4cd271cae7b53987da`, inchangé par ce travail.

## État courant — diagnostic réussi, cause établie

[Diagnostic 39639769111](https://github.com/mycomane/alimentsdistinction/actions/runs/39639769111), job `119360202463` : **succès après approbation**. Identité exacte, thème actif attendu, les **23 fichiers originaux 0.1.0** et Coming Soon sur accueil + quatre pages ont été contrôlés après les échecs précédents.

- WP-CLI hébergé : **2.12.0**.
- L'agrégat SQL et la sonde de constantes renvoient chacun 14 octets : **10 chiffres, 2 tabulations et 2 LF finaux**, aucune lettre, espace, autre caractère ou stderr. Trois cellules numériques de longueurs 2/7/1.
- La taille native sans CSV renvoie 7 chiffres et un LF; aucun stderr. Aucune valeur de base, nom ou sortie brute publié.
- **Cause du second échec prouvée :** le parseur n'autorisait qu'une fin de ligne après la ligne TSV valide; la chaîne WP-CLI hébergée en ajoute deux.
- Correction bornée : autoriser zéro, une ou deux fins de ligne LF/CRLF complètes après une seule ligne numérique. Aucun `trim()` général, aucun avertissement ignoré, aucune donnée supplémentaire acceptée. Trois fins de ligne, lignes numériques supplémentaires, espaces/tabulations ajoutés et autres formats restent rejetés.
- **46 assertions parseur/espace réussies localement et sur GitHub** (39 précédentes + 7 nouveaux cas), ainsi que les 17 gardes fichiers/visibilité. Les **six scénarios MySQL** passent, dont double LF hébergé et double CRLF; wrapper de capture/parseur/nettoyage de production exercé avec transport WP-CLI simulé.
- [Preuve structurelle hébergée](evidence/p0-hosted-db-shape.json). Aucune nouvelle sauvegarde, purge, mutation DB, création de compte ou mise à jour de thème par le diagnostic.
- **[Nouveau run 39685183348 — PRÊT POUR APPROBATION](https://github.com/mycomane/alimentsdistinction/actions/runs/39685183348)**, commit `be5703eeaa434ada55cb33a961a0b08e11f6a862`.
- Job `prepare` **119517310652 : succès** : ShellCheck, paquet exact, 46 + 17 assertions et six scénarios MySQL.
- Job `isolated-qa` **119517311211 : succès** : **300 assertions** (11 PHP + 41 navigateur + 61 P0 + 55 sections + 132 WooCommerce), écart 0,00 px aux cinq largeurs; zéro commande et zéro fatal/avertissement/dépréciation PHP compté.
- Job distant **119518254594 : waiting**, environnement `godaddy-temp`, reviewer `mycomane` confirmé. **Aucune opération GoDaddy de ce nouveau run.**
- [Preuve structurée du candidat](evidence/p0-newlines-qa.json). ZIP 0.1.1 inchangé : SHA256 `8162bc571bb5dea232badd562b5c9085c30187b1b4ff80f567f1f5e9fdb8497e`.
- Après revue Coordination, Julien approuve ce **nouveau run** via Review deployments → godaddy-temp → Approve and deploy. Les préconditions, la sauvegarde fraîche et la répétition du rollback devront encore réussir avant la bascule. Aucun succès distant n'est anticipé.
- L'accord du diagnostic ne vaut pas accord de déploiement; aucun Abonné autorisé ou créé.

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
| Passage GitHub obligatoire | Correction des deux LF testée, run 39685183348 en attente | Revue Coordination puis nouvelle approbation `godaddy-temp` |
| Validation Julien | Nouvelle recette géométrique hébergée après mise à jour | Autoriser un Abonné temporaire de 30 minutes avec suppression |
| Majeur accessibilité | Textes rose/gris, boutons et infolettre insuffisamment contrastés | Valider les propositions graphiques avant leur intégration |
| Majeur accessibilité | Navigation transparente sur photo | Valider une solution par contexte, par exemple une barre à fond crème opaque |
| Bloquant commercial | Téléphone/coordonnées, pages de livraison/retours/confidentialité, garanties et affirmations commerciales | Contenus et validation du propriétaire/Julien nécessaires |
| Majeur contenu | Recettes et service d’infolettre incomplets | Rédaction et service approuvés; aucune fausse inscription |
| Mineur cohérence | Bouton WooCommerce de variation désactivée violet natif | Harmoniser lors du lot de palette |
| À confirmer avant ouverture | Localisation française WooCommerce et URL finale hébergée de checkout vide | Nouvelle recette hébergée autorisée; ne pas conclure depuis le runner anglais |
| Avant production | Hors site/restauration intégrale, confirmation SSH indépendante, performance/CWV et règles commerciales | Préparer la validation, sans connexion ou configuration non autorisée |

## Prochaines étapes recommandées

1. Faire revoir puis approuver le run 39685183348; les tests du parseur corrigé et de transport sont réussis. Ne pas réutiliser l’approbation du diagnostic.
2. Après diagnostic, préparer et tester la correction nécessaire. Un déploiement ultérieur exige sa propre nouvelle approbation GitHub, puis sauvegarde fraîche/rollback et vérifications. L’approbation du diagnostic ne déclenche aucun déploiement. Recette authentifiée après autorisation distincte du compte temporaire. Ne pas relancer le premier installateur.
3. Après validation graphique : intégrer les ajustements ciblés de contraste, états désactivés/survol/focus et navigation, puis refaire les vérifications isolées appropriées.
4. Compléter les contenus commerciaux approuvés et les tests non couverts. Le formulaire de commande n’a jamais été soumis; les blocs Panier/Commande, lecteurs d’écran, pixels des sections secondaires et performance ne sont pas certifiés.
5. Aucun nouveau compte, produit, commande ou réglage GoDaddy sans motif et autorisation correspondant à la prochaine recette. Conserver Coming Soon.

Ce fichier est le point de synchronisation avec ChatGPT Coordination; aucun message de cette conversation Work n’y est supposé reçu automatiquement.
