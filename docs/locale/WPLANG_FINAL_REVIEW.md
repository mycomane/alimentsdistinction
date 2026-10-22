# WPLANG — protocole final et dossier de revue

22 octobre 2026. **Préparation documentaire uniquement, aucune autorisation de configuration.**

Directive : [validation de la Coordination](https://github.com/mycomane/alimentsdistinction/issues/4#issuecomment-6348793088). Base de cette revue : `61ca2879edc3e10bdcd2149212ac1f910bd8bd0a`. L'issue #4 reste ouverte. La dernière preuve hébergée est un retour arrière vérifié; **fr_CA n'est pas déclaré installé sur GoDaddy**.

## Diff soumis à la revue

Le correctif exécutable est celui du [diff 09b3870…71058a9](https://github.com/mycomane/alimentsdistinction/compare/09b3870364348b29d3f95ebb4193201359350469...71058a956e63980a7ac96f116c64478003a3176b). Pour une revue ciblée, examiner `scripts/locale/wp-functions.php`, `state.php` et les trois appels supplémentaires de découverte/rollback dans `update-temp.sh`; les autres fichiers de ce lot concernent les tests, leur CI isolée et la documentation.

Cette livraison ajoute uniquement le présent dossier et le suivi projet. Elle ne modifie ni ce correctif testé ni un workflow. Le protocole d'observation ci-dessous est une spécification exacte pour la prochaine révision du contrôleur : **ces nouvelles observations ne sont pas encore instrumentées ni présentées comme testées**. Son implémentation locale et sa petite recette devront précéder toute future demande de déploiement. Le présent document ne réactive pas le job protégé.

## Preuves réutilisées

[Run 39831592138](https://github.com/mycomane/alimentsdistinction/actions/runs/39831592138), job `120008848791`, statut `completed/success` revérifié le 22 octobre. SHA exact : `71058a956e63980a7ac96f116c64478003a3176b`.

| Contrôle isolé | Résultat déjà établi |
| --- | --- |
| Diagnostic WordPress réel WPLANG | 43 assertions réussies |
| WPLANG/autoload et retour arrière exact | 30 assertions réussies |
| Contrôleur shell : succès et trois échecs | 4 simulations réussies |
| Contrats des workflows | 7 contrôles réussis |
| Processus neuf | fr_CA et deux libellés natifs WooCommerce vérifiés |
| Intégrité | 311 fichiers de langue épinglés; 23 fichiers de thème inchangés |

Artefact du run : `wplang-diagnostic-isolated-evidence`, ID `12449660554`. [Analyse détaillée des cas et limites](WPLANG_DIAGNOSTIC_2026-10-22.md). Aucun test lourd ou navigateur n'est relancé pour ce dossier documentaire. Ces preuves ne valent pas validation du comportement du cache sur GoDaddy.

Contrôles complémentaires de ce lot : les sept contrats `tests/locale-workflow.py` ont été réexécutés localement avec succès; ordre shell/application/inventaire, séparation des invocations WP-CLI, blocage du job, liens relatifs et `git diff --check` vérifiés. Aucun code exécutable n'est modifié par ce lot.

## Préconditions bloquantes d'une future exécution

1. Accord explicite de Julien pour réactiver le job, puis nouvelle exécution sur `feat/wordpress-woocommerce-theme`, sans réutiliser ni relancer un ancien run. Revue GitHub humaine de **ce run exact** pour `godaddy-temp`; l'agent n'approuve jamais.
2. Revue du SHA final immuable et du diff complet depuis le SHA testé. Les tests réutilisés restent attribués à leur SHA d'origine; toute nouvelle instrumentation doit avoir ses propres preuves isolées. La préparation et la QA requises du futur workflow doivent réussir sur son nouveau SHA.
3. Vérifier le checkout contre `GITHUB_SHA`, l'identité SSH épinglée existante avant authentification, puis les deux options `home` et `siteurl` exactement égales à `https://1302636.us22.myftpupload.com`. Aucune redirection vers le domaine commercial n'est admise.
4. Identité et versions attendues par le contrôleur inchangées : WordPress 7.1.3, WooCommerce 11.2.1 déjà actif, thème Aliments Distinction 0.1.2 inchangé. Dérive, constante WPLANG non vide ou racine des langues personnalisée : refus, nouvelle revue.
5. Paquets officiels core/WooCommerce vérifiés contre les SHA-256 et tailles épinglés dans `fr-ca-packages.json`; inventaire exact des 311 fichiers. Thème comparé au manifeste épinglé des 23 fichiers. Aucun téléchargement « dernière version » accepté à la place.
6. Verrou exclusif, espace disque/mesure SQL stricte, volumes et droits sûrs, aucun état de run préexistant réutilisé. Sauvegarde **fraîche** privée SQL compressée, thème, langues et baseline avant toute mutation de langue. Vérifications gzip/SHA, extraction et répétition privée application/rollback réussies. Les anciens backups ne remplacent pas cette condition.
7. Coming Soon et réponses HTTP 200 de l'accueil et des quatre pages techniques avant mutation. Baseline protégée et inventaires conformes juste avant armement du retour arrière.

Les protections actuelles `workflow_dispatch`, `needs: [prepare, isolated-qa]`, branche exclusive, revue humaine, concurrence sans annulation et `&& false` sont conservées. L'état des réglages d'approbation de l'environnement devra être reconfirmé lors de la future demande; ce dossier ne prétend pas l'avoir relu via un endpoint indisponible.

## Protocole exact d'observation proposé

Les observations appartiendront à `state.php activate` / `ad_locale_activate()`, appelé par le contrôleur existant **après** l'application vérifiée des fichiers. Aucun endpoint Web, phpinfo, compte de test ou action SSH distincte. Format de sortie fermé : `WPLANG_OBSERVE <CLÉ>=0|1`. Seules les clés ci-dessous sont autorisées, valeur booléenne stricte convertie en `0` ou `1`; aucun objet ni exception sérialisé. Une observation non atteinte n'est pas remplacée par une valeur inventée. Le code final de refus explique l'interruption.

| Ordre / clé fixe | Expression ou condition à observer | Condition de poursuite |
| --- | --- | --- |
| 1 `CORE_READABLE` | `is_readable(WP_LANG_DIR.'/fr_CA.mo') || is_readable(WP_LANG_DIR.'/fr_CA.l10n.php')` | true, sinon FR_CA_NOT_AVAILABLE |
| 2 `RECOGNIZED_BEFORE` | `in_array('fr_CA', get_available_languages(), true)` avant invalidation | false autorisé : observation, pas preuve de cause historique |
| 3 `RECOGNIZED_AFTER` | Même expression après `ad_locale_refresh_language_files()` | true, sinon FR_CA_NOT_AVAILABLE |
| 4 `API_CHANGED` | Retour booléen strict de l'unique `update_option('WPLANG','fr_CA',$autoload)` existant | true requis, sans court-circuiter la relecture |
| 5 `WRITE_QUERY_OK` | `$wpdb->last_error === ''`, capturé immédiatement après l'API, avant SELECT | true requis, sinon WPLANG_WRITE_FAILED |
| 6 `RAW_QUERY_OK` | `ad_locale_raw()` réussit; false uniquement si WPLANG_QUERY_FAILED est intercepté puis relancé | true requis, sinon WPLANG_QUERY_FAILED |
| 7 `RAW_MATCH` | `$raw !== null && ($raw['option_value'] ?? null) === 'fr_CA'` | true requis, sinon WPLANG_RAW_MISMATCH |

Conserver la vérification baseline/état protégé avant toute observation. Conserver `suppress_errors` avec restauration en `finally`; ne jamais publier `last_error`, la requête ou la ligne. En cas d'exception API : uniquement `WPLANG_API_EXCEPTION`, aucun faux booléen de retour. Les avertissements et exceptions non autorisées restent convertis en codes fermés par `state.php`.

Ordre des refus après l'API inchangé : tenter la lecture brute même si l'API retourne false; une lecture indisponible refuse immédiatement, puis erreur d'écriture, retour API false, divergence brute et état protégé. Un retour API false reste bloquant même si la ligne semble conforme. Ne pas ajouter de tentative automatique, d'acceptation idempotente, d'UPDATE SQL forçant fr_CA, de filtre permissif ou de purge globale.

Une matrice avant=false/après=true démontrerait la reconnaissance après invalidation **dans cette future exécution seulement**. Elle ne prouverait ni le backend de cache utilisé ni la cause historique du run échoué. Avant=true/après=true ne constituerait pas une reproduction du défaut de découverte.

### Ordre fichiers / invalidation / processus : vérifié dans le code actuel

Dans `update-temp.sh`, `files.php apply` doit terminer avec succès avant `state.php activate`. `lf_apply()` termine par `lf_check(...,'new')` et écrit le journal `files-verified` avant de rendre la main : les empreintes de l'inventaire complet sont donc contrôlées **avant** l'invalidation dans `ad_locale_activate()`.

L'appel suivant `wp ... eval-file state.php verify ... new` est une **nouvelle invocation WP-CLI**, distincte de l'activation. Il contrôle ligne brute, `get_locale()`, deux libellés natifs et état protégé. Aucun `get_locale()` du processus qui vient de modifier l'option ne sert de preuve finale. Le shell `set -euo pipefail` et le piège de récupération restent actifs.

L'invalidation utilise seulement l'API publique `WP_Textdomain_Registry::invalidate_mo_files_cache()` pour les listes core et plugins. Aucun `wp cache flush`, purge globale ou contournement de filtres. Après rollback des fichiers, le contrôleur invalide à nouveau cette découverte pour éviter les langues fantômes.

### Petite recette exigée pour l'instrumentation future

Réutiliser les cas existants sans recréer une recette navigateur : catalogue absent; cache de découverte périmé; réussite; langue masquée; API false; exception API; écriture échouée; SELECT indisponible; ligne absente/divergente. Pour chacun, vérifier la séquence et les seuls booléens réellement atteints, le code terminal, la relecture malgré API false et le rollback existant. Vérifier la non-divulgation avec les chaînes sensibles factices déjà employées par la fixture.

Ajouter un contrat léger sur l'ordre `apply → activate → verify` et les processus distincts; conserver les sept contrats de protection. Ces assertions supplémentaires restent **à implémenter/exécuter** avant de présenter l'instrumentation comme validée. Aucun changement hébergé n'est nécessaire pour les préparer.

## Politique d'échec et critères finaux

Avant armement : toute précondition non confirmée interrompt sans mutation des langues/WPLANG. Après armement : tout code de refus, erreur d'observation, échec de vérification en processus neuf, dérive protégée, thème divergent ou Coming Soon non confirmé déclenche la récupération ciblée existante.

La récupération restaure la ligne WPLANG/autoload exacte si l'état est connu, puis les seuls fichiers de langues prévus, invalide la découverte et vérifie en processus neuf baseline, inventaires, thème et Coming Soon. Une dérive inconnue est refusée; ne pas écraser de force. `ROLLBACK VERIFIED` n'est annoncé que si tous les contrôles réussissent. Sinon incident critique, sauvegardes conservées, arrêt; aucun import SQL automatique.

Un futur succès exige simultanément observations complètes cohérentes, API true, lecture indépendante conforme, fr_CA et deux libellés en processus neuf, langues exactes, thème/état protégé inchangés et Coming Soon vérifié. Il ne vaut ni recette transactionnelle complète ni traduction de contenus éditoriaux déjà enregistrés.

## Risques résiduels et décisions

- Cause historique WPLANG non confirmée; SQLite/cache local ne reproduit pas nécessairement l'hébergement. Les observations futures distingueront les refus sans promettre la résolution.
- Filtres WordPress, droits de base, ACL/rename et concurrence externe peuvent encore refuser l'opération. Les gardes restent bloquants; aucune adaptation opportuniste sur l'hôte.
- Sauvegardes privées sur le même hébergement : restauration intégrale hors site non certifiée. Les preuves d'un ancien run ne prouvent pas le prochain backup.
- Le rollback connu est couvert; un état inconnu peut exiger une décision humaine. Aucun retour arrière complet automatique ou import SQL promis.
- À valider par la Coordination : ce protocole, le diff candidat et les risques. À autoriser séparément par Julien : réactivation du job; ensuite seulement nouvelle revue humaine du run exact. **Aucun run n'est actuellement préparé en attente d'approbation de déploiement par cette livraison.**

Site commercial, `master`, DNS, domaines, paiements, Zoho, taxes/livraison, produits, commandes et clients sont hors périmètre. Coming Soon reste maintenu.
