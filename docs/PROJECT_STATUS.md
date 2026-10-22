# Aliments Distinction — suivi Work / Coordination

## État courant — 22 octobre 2026 : nouvelle préparation fr_CA réussie, Waiting / Review deployments

- Julien a explicitement répondu **oui** dans Work à l'ajout temporaire d'un déclenchement par commit sur la seule branche `feat/wordpress-woocommerce-theme`, sans approbation de déploiement. Cette autorisation remplace uniquement l'exigence de déclenchement manuel de la phase précédente. Base `deda077b81fb200044172f75d30c0db247ffce7a`.
- Déclencheur temporaire limité à cette branche et au seul nouveau marqueur `docs/locale/FR_CA_REVIEW_REQUEST_2026-10-22.txt`. Une nouvelle préparation est demandée; aucun ancien run relancé. Le déclencheur sera retiré dès la mise en file confirmée, en conservant le marqueur inchangé pour éviter un second événement correspondant.
- Aucun changement au contrôleur WPLANG instrumenté, thème, sauvegardes, rollback ou paquets. Revue `godaddy-temp`, `needs: [prepare, isolated-qa]`, garde humaine du run exact avant SSH, identité temporaire et Coming Soon conservés. **Arrêt obligatoire à Waiting / Review deployments; aucune approbation par Work.**
- **Nouvelle exécution [39843113925](https://github.com/mycomane/alimentsdistinction/actions/runs/39843113925)**, SHA exact `2949707b119f158461fdfa9a7a634a359af51a97`, tentative 1 : **Waiting / Review deployments**. `prepare` (`120049183824`) et `isolated-qa / french-storefront` (`120049184228`) **success sur ce même SHA**. Job protégé `120049875766` waiting; API pending_deployments confirme `godaddy-temp` et reviewer humain `mycomane`; API approvals vide à la vérification. **Aucune approbation par Work, aucun SSH ou changement GoDaddy.**
- Journaux contrôlés : préparation — huit contrats, 17 gardes fichiers P0, 46 mesures DB/espace, 28 langues, sept Coming Soon, 61 assertions complètes fichiers/propriétaire/volume/liens/droits/rollback, quatre simulations; QA — 30 assertions WPLANG/rollback, vérification fr_CA/libellés en processus neuf, **24 contrôles français + 45 responsive sans échec**, aucune commande/passerelle active, diagnostics PHP sans fatal/warning/deprecated. Inventaire 311 fichiers épinglés et thème 0.1.2 de 23 fichiers inchangé.
- CI ciblée du même SHA [39843113865](https://github.com/mycomane/alimentsdistinction/actions/runs/39843113865), job `120049182177`, **success** : 62 assertions WPLANG/traces, 30 rollback, quatre simulations, huit contrats et vérification en processus neuf. Cette CI ciblée n'exécute aucun navigateur; la QA réutilisable ci-dessus en exécute uniquement sur fixture locale.
- Artefacts du run protégé : `locale-fr-ca-review-package` (`12454231661`) et `locale-fr-ca-isolated` (`12453981820`). [Dossier du nouveau run, preuves et préconditions](locale/FR_CA_PROTECTED_RUN_2026-10-22.md). Deux observations éditoriales déjà connues restent limitées à la fixture : confidentialité enregistrée et catégorie en anglais; aucune réécriture hébergée ajoutée.
- Huit contrats locaux, syntaxe bash et diff vérifiés. Déclencheur push retiré par [065a2c7e](https://github.com/mycomane/alimentsdistinction/commit/065a2c7e559e1fa372ea2c8e48790d1430f47017) après création du run; le marqueur n'est pas modifié, donc ce retrait ne demande pas une deuxième préparation fr_CA. Workflow_dispatch et revue protégée conservés. Le run déjà créé reste épinglé à 2949707b; le nouveau HEAD documentaire/de retrait n'est pas son SHA. Ce retrait ne modifie pas le contenu du run en attente.
- Aucune opération GoDaddy ni sauvegarde distante fraîche prétendue. Les préconditions hébergées (identité exacte, espace SQL/disque, sauvegarde privée SQL/thème/langues/manifestes fraîche et vérifiée, répétition privée, Coming Soon, baseline protégée) s'exécuteront **seulement après une nouvelle approbation humaine**, avant mutation des langues/WPLANG. Cause historique, filtres/cache/ACL/DB et récupération intégrale hors site restent les risques documentés. **Préparation terminée; décision humaine sur ce run exact en attente.** Les sections suivantes sont historiques.

## État actuel — 22 octobre 2026 : workflow fr_CA réactivé, déclenchement manuel bloqué techniquement

- [Autorisation limitée de Julien / directive Coordination](https://github.com/mycomane/alimentsdistinction/issues/4#issuecomment-6349578366) lue intégralement. Base `80d32d69840676609cabc7690e03bf5847343afb`. Autorisation de préparer une nouvelle exécution et atteindre **Waiting / Review deployments uniquement**; aucune approbation ni modification GoDaddy autorisée à Work.
- Diff minimal : retrait du seul `&& false`, commentaire adapté et contrat correspondant. `workflow_dispatch` sans push, branche exclusive, `needs: [prepare, isolated-qa]`, `environment: godaddy-temp`, verrou de concurrence et vérification humaine du run exact avant SSH conservés. Le contrôleur de langue instrumenté, sauvegardes, rollback, thème et paquets épinglés restent inchangés.
- Commit de réactivation : [80910cf09a6c89583241ca1d59d4d0e7bf1b2b65](https://github.com/mycomane/alimentsdistinction/commit/80910cf09a6c89583241ca1d59d4d0e7bf1b2b65). Diff GitHub vérifié : seulement workflow, contrat et suivi projet; aucun contrôleur de langue/rollback/thème modifié. Huit contrats, contrôle de diff et syntaxe bash réussis localement avant publication.
- **Nouvelle CI ciblée [39841764338](https://github.com/mycomane/alimentsdistinction/actions/runs/39841764338) completed/success sur ce même SHA 80910cf0**, job `120044344039`, journal vérifié : 62 assertions WPLANG, 30 rollback, quatre simulations, huit contrats, vérification fr_CA/libellés en processus neuf, 311 fichiers officiels et 23 fichiers thème conformes. Aucun navigateur ni connexion GoDaddy dans cette CI. Ces résultats sont distincts de l'ancienne CI 196cf8f1.
- **Blocage du déclenchement :** le connecteur GitHub disponible ne fournit pas `workflow_dispatch`. Tentative de déclenchement par le navigateur cloud arrêtée sur `ERR_SOCKS_CONNECTION_FAILED`, avant accès à GitHub. Aucun clic Run workflow réussi, aucun ancien run relancé, aucune approbation. La liste API des runs au SHA 80910cf0 ne montre que la CI ciblée ci-dessus. **Aucun nouveau run fr_CA créé, aucun statut Waiting / Review deployments atteint.**
- Action minimale du responsable dans son navigateur : ouvrir [le workflow fr_CA](https://github.com/mycomane/alimentsdistinction/actions/workflows/configure-locale-fr-ca.yml), **Run workflow**, sélectionner exclusivement `feat/wordpress-woocommerce-theme`, puis **Run workflow une seule fois**. Alternative CLI autorisée : `gh workflow run configure-locale-fr-ca.yml --repo mycomane/alimentsdistinction --ref feat/wordpress-woocommerce-theme`. Ne pas cliquer Approve and deploy. Aucun nouvel accord de préparation n'est requis; cette intervention est nécessaire seulement à cause du blocage technique du navigateur cloud et de l'absence d'outil de déclenchement du connecteur.
- Le futur run prendra le HEAD de cette branche au déclenchement, incluant le présent suivi documentaire; **prepare et isolated-qa devront réussir sur son SHA exact**, sans substituer les résultats de 80910cf0. Work pourra ensuite contrôler par l'API le nouveau run, les deux jobs et `pending_deployments`, puis consigner le lien et le statut réel. Aucune sauvegarde distante fraîche revendiquée à ce stade : identité exacte, espace SQL/disque, sauvegarde privée SQL/thème/langues/SHA, répétition privée, Coming Soon et état protégé seront contrôlés **après** approbation humaine, avant mutation des langues/WPLANG.
- Risques inchangés : cause historique non confirmée, cache/filtres/ACL/base propres à l'hébergement, récupération intégrale hors site non certifiée. Fr_CA non déclaré installé. Aucun accès GoDaddy/SSH ni compte créé par cette préparation; master, site commercial, DNS, Zoho, paiements et paramètres commerciaux hors périmètre.
- Les sections suivantes sont historiques; les mentions du blocage `&& false` y décrivent les phases précédentes. Aucun clic **Approve and deploy** ne sera effectué par Work.

## État actuel — 22 octobre 2026 : sept observations WPLANG instrumentées, CI isolée verte

- [Directive Coordination](https://github.com/mycomane/alimentsdistinction/issues/4#issuecomment-6349384832) lue intégralement. Base GitHub `a5c20f1c2a65067b9134c6bbad5f3f8c694a951a`; branche exclusive conservée.
- Sept sorties `WPLANG_OBSERVE KEY=0|1` instrumentées dans le contrôleur existant : liste fermée, booléens stricts, aucune donnée SQL/chemin/identité. Les observations non atteintes ne sont pas émises. Les refus, la relecture indépendante même après API false et le rollback restent conservés.
- Tests existants réutilisés; assertions additionnelles sur les traces ordonnées, leur confidentialité, SELECT post-API échoué et refus pré-baseline. Huit contrats workflow réussis localement (sept existants et un contrôle d'ordre/invocations distinctes), syntaxe PHP et diff vérifiés.
- **CI fraîche [39840545859](https://github.com/mycomane/alimentsdistinction/actions/runs/39840545859) completed/success**, SHA instrumenté exact `196cf8f131b71c74b1287e59ae65de44ad3cbff3`, job `120040091571`. Journal vérifié : **62 assertions WPLANG (43 réutilisées + 19 supplémentaires), 30 rollback, quatre simulations shell et huit contrats réussis**. Vérification fr_CA/deux libellés en processus neuf, 311 fichiers épinglés et 23 fichiers du thème conformes. Aucune commande créée ni passerelle active dans la fixture; aucun navigateur exécuté malgré le libellé historique du contrôle de commandes.
- Artefact `wplang-diagnostic-isolated-evidence`, ID `12453261072`. [Rapport, matrice observations/codes, diff et projet de demande d'autorisation](locale/WPLANG_OBSERVATIONS_2026-10-22.md). Ce résultat prouve les scénarios synthétiques et la non-régression isolée, pas le comportement transitoire GoDaddy.
- Aucun changement des workflows, du thème, des fichiers de rollback ou des paramètres hébergés. `workflow_dispatch`, `deploy.if ... && false`, `godaddy-temp`, dépendance QA du même SHA et revue humaine du run exact restent présents. Aucun workflow GoDaddy/SSH/rerun/approbation.
- Cause historique GoDaddy non prouvée, fr_CA non déclaré installé, restauration intégrale hors site non certifiée. Diff instrumenté et preuves soumis à la Coordination; phase isolée terminée. Toute réactivation exige une nouvelle autorisation explicite de Julien, puis une nouvelle revue GitHub du run exact; aucune de ces autorisations n'est accordée par le présent développement isolé. Les sections suivantes décrivent l'historique documentaire et ne remplacent pas cet état.

## État actuel — 22 octobre 2026 : dossier final WPLANG soumis à revue

- [Validation Coordination](https://github.com/mycomane/alimentsdistinction/issues/4#issuecomment-6348793088) lue intégralement. Base/dernier commit avant ce lot documentaire : `61ca2879edc3e10bdcd2149212ac1f910bd8bd0a`; le commit de livraison est celui de l'historique de cette section. Branche exclusive `feat/wordpress-woocommerce-theme`.
- [Protocole final et dossier de revue](locale/WPLANG_FINAL_REVIEW.md) : préconditions, observations booléennes fermées proposées, politique d'arrêt/rollback, critères de succès et risques. L'instrumentation additionnelle est spécifiée, **pas encore appliquée ni testée**; elle devra avoir ses petites preuves isolées avant toute future configuration.
- Ordre confirmé par lecture du code : inventaire complet vérifié à la fin de `files.php apply`, puis invalidation native dans `state.php activate`; vérification de locale/libellés dans une invocation WP-CLI distincte. Aucun cache global purgé et aucun filtre contourné.
- Preuves réutilisées, sans nouvelle recette lourde : [39831592138](https://github.com/mycomane/alimentsdistinction/actions/runs/39831592138), statut success revérifié, SHA `71058a956e63980a7ac96f116c64478003a3176b`; **43 assertions WPLANG + 30 rollback + 4 simulations shell + 7 contrats**, vérification en processus neuf. Ces résultats restent attribués à ce SHA; ce lot ne change aucun code exécutable.
- Contrôles complémentaires locaux de ce dossier : sept contrats workflow réexécutés avec succès, contrôle statique de l'ordre application/activation/vérification et inventaire avant retour réussi, liens relatifs des deux documents vérifiés, `git diff --check` réussi. Aucun nouveau résultat hébergé revendiqué.
- Cause historique du refus GoDaddy toujours non prouvée. Dernier état hébergé prouvé : retour arrière vérifié; **fr_CA non déclaré installé**. Sauvegarde/restauration intégrale hors site non certifiée; ACL/filtres/base peuvent encore refuser une future opération.
- Aucun nouveau workflow GoDaddy, ancien run relancé, SSH, compte de test ou changement distant. Workflow de configuration conservé en `workflow_dispatch`, job mutatif bloqué par `&& false`; aucune réactivation ni approbation par Work.
- Décisions attendues : validation du dossier par la Coordination; accord distinct de Julien avant réactivation, puis nouvelle revue GitHub du run exact avant toute écriture. Prochaine préparation technique : instrumentation minimale et tests ciblés définis au dossier, sans modifier les gardes. L'issue #4 reste ouverte.
- Les sections ci-dessous sont l'historique et ne remplacent pas cet état courant. Site commercial, master, DNS, paiements, Zoho et Coming Soon hors intervention.

Mise à jour : **22 octobre 2026**, issue #4 : diagnostic fichiers fr_CA, mutations suspendues.

## Priorité courante — diagnostic WPLANG isolé, aucune opération GoDaddy

- [Directive Coordination](https://github.com/mycomane/alimentsdistinction/issues/4#issuecomment-6347458764) lue intégralement. Base `09b3870364348b29d3f95ebb4193201359350469`. Aucun ancien run relancé, aucune nouvelle connexion ou modification GoDaddy.
- **Mécanisme reproduit sur WordPress 7.1.3/SQLite :** après mise en cache d'une liste vide, l'installation des fichiers fr_CA hors upgrader ne suffit pas à les faire reconnaître. La sanitisation native conserve alors l'ancienne valeur et l'API renvoie false. **Ce n'est pas une preuve de la cause historique GoDaddy**; d'autres refus par filtre ou erreur de requête sont également reproduits.
- Correction candidate isolée : API publique `WP_Textdomain_Registry::invalidate_mo_files_cache()` limitée aux listes de traductions core/plugins, puis contrôle de disponibilité et API `update_option` conservés. Lecture SQL brute indépendante même si l'API renvoie false; codes fixes distincts pour disponibilité, retour API, erreur d'écriture/lecture, valeur brute divergente et exception API. Aucune écriture SQL pour forcer fr_CA; aucune neutralisation de sanitize_option ou des filtres.
- Sorties du contrôleur protégées : codes sur liste fermée, pas de trace privée ou erreur SQL publiée. Le rollback exact existant reste en place; invalidation ciblée supplémentaire après restauration des fichiers pour éviter une liste fantôme de langues.
- Local : **40 assertions ciblées initiales + 30 assertions réelles WPLANG/rollback existantes**, vérification fr_CA/libellés dans un processus neuf, sept contrats workflow, syntaxe et diff vérifiés. Trois contrôles supplémentaires de découverte après rollback ajoutés pour la CI fraîche. Aucun navigateur, paiement ou commande.
- **Configuration de mutation désactivée** : déclenchement automatique push retiré pendant le diagnostic, job deploy bloqué par `&& false`. Nouveau workflow `qa-wplang-isolated.yml` strictement local, sans secrets, environnement protégé, SSH ou navigateur; tests/API WordPress réels et quatre simulations shell, résultat à consigner après exécution. Aucun workflow de configuration n'est demandé par ce lot.
- Première CI légère [39831266514](https://github.com/mycomane/alimentsdistinction/actions/runs/39831266514) : sept contrats et syntaxe réussis, puis arrêt **avant les tests applicatifs** car le checkout superficiel ne contenait pas l'arbre du thème de référence nécessaire à `prepare-release.py`. Correction : historique complet (`fetch-depth: 0`), contrôle d'intégrité conservé. Nouveau commit/run isolé préparé, aucun ancien run relancé.
- **CI finale [39831592138](https://github.com/mycomane/alimentsdistinction/actions/runs/39831592138) : success**, job `120008848791`, commit exact [71058a956e63980a7ac96f116c64478003a3176b](https://github.com/mycomane/alimentsdistinction/commit/71058a956e63980a7ac96f116c64478003a3176b). **43 assertions WPLANG ciblées + 30 assertions existantes + 4 simulations shell + 7 contrats workflow réussis**, nouvelle vérification fr_CA/libellés dans un processus neuf. 311 fichiers épinglés, 23 fichiers du thème et gardes P0 inchangés. Aucune commande créée/passerelle active dans la fixture.
- Preuve JSON publiée dans l'artefact **wplang-diagnostic-isolated-evidence**, ID `12449660554`, de ce run. Aucun navigateur, workflow GoDaddy ou SSH exécuté. Le correctif candidat est prêt pour revue; le mécanisme de cache est reproduit en isolé, **pas confirmé comme cause historique de l'hébergement**. Aucune nouvelle approbation de configuration demandée par ce lot.
- [Rapport et protocole de validation proposé](locale/WPLANG_DIAGNOSTIC_2026-10-22.md). Toute future opération distante ou configuration reste soumise à une nouvelle autorisation et revue humaine; la lecture post-rollback ne prouve pas l'état transitoire au moment du refus.

## État courant — run 39824523597 échoué, rollback vérifié

Dernier état hébergé prouvé; aucune nouvelle mesure distante pendant le diagnostic isolé ci-dessus.

- Capture de Julien examinée puis [jobs et journal](https://github.com/mycomane/alimentsdistinction/actions/runs/39824523597/job/119985019616) relus sans nouvelle connexion GoDaddy. Après l'arrêt Work à Waiting, la nouvelle approbation humaine a été enregistrée pour ce run exact à **13 h 43 min 45 s HAE**. L'opération distante a ensuite échoué; l'état Waiting ci-dessous est désormais historique.
- Préparation et recette du commit `752fe13e7188c32cfd9d236f8be8cba43f1ec660` **réussies**. Sauvegarde fraîche SQL/thème/langues/SHA et répétition privée **réellement réussies** pour ce run avant écriture publique; les anciennes sauvegardes n'ont pas été consommées. Stockage privé sur le même hébergement, sans restauration intégrale hors site certifiée.
- **Le correctif de propriété a franchi l'application/vérification des fichiers de traduction.** Le nouvel échec apparaît ensuite dans `ad_locale_activate()` : **`WPLANG activation failed`**, à **13 h 44 min 51 s HAE**. Il ne s'agit donc pas du précédent refus d'ownership.
- Le garde associe deux conditions : retour false de `update_option('WPLANG','fr_CA',...)` **ou** valeur brute relue différente de fr_CA. Les preuves actuelles ne départagent pas ces deux cas et n'identifient pas la cause profonde. Ne pas conclure arbitrairement à une ACL, un cache, une base en lecture seule ou un filtre GoDaddy.
- **ROLLBACK VERIFIED à 13 h 45 min 02 s HAE** : WPLANG/locale anglaise et inventaire des langues d'origine restaurés, thème et état protégé vérifiés, accueil + quatre pages techniques HTTP 200 et Coming Soon maintenu. Aucun import SQL. Le run est **failure**, **fr_CA n'est pas installé/validé sur l'hébergement par cette opération**.
- Cette reprise après capture effectue uniquement la lecture des preuves et la mise à jour documentaire. **Aucun ancien run relancé, aucune nouvelle modification GoDaddy, aucun nouveau workflow lancé.** Tout prochain essai distant doit faire l'objet d'une nouvelle autorisation et revue humaine propres au nouveau périmètre/run.
- Prochaine proposition Coordination : séparer les états diagnostiques en booléens expurgés (locale disponible, retour d'API, valeur persistée conforme, résultat de requête) et reproduire les cas d'échec isolés, sans changer la politique de refus ni forcer une écriture SQL. Une observation de la ligne actuelle seule ne reconstitue pas son état au moment de l'échec.
- [Analyse du nouvel incident et limites](locale/INCIDENT_WPLANG_2026-10-22.md). Issue #4 reste ouverte; aucune certification de localisation française hébergée.

## Nouveau lot fr_CA — préparation autorisée, arrêt obligatoire à Review deployments

Historique de la préparation avant approbation. Le résultat réel après revue figure dans l'incident courant ci-dessus.

**PRÊT POUR VALIDATION FINALE : [run 39824523597 — Waiting / Review deployments](https://github.com/mycomane/alimentsdistinction/actions/runs/39824523597). Work s'arrête ici et n'approuve pas. Aucune opération GoDaddy n'a commencé pour cette nouvelle exécution.**

- **Commit exact soumis à la revue : [752fe13e7188c32cfd9d236f8be8cba43f1ec660](https://github.com/mycomane/alimentsdistinction/commit/752fe13e7188c32cfd9d236f8be8cba43f1ec660)**. Diff de workflow visible dans ce commit et expliqué dans [FR_CA_NEXT_REVIEW.md](locale/FR_CA_NEXT_REVIEW.md). Aucun script opérationnel ni thème modifié par rapport au correctif testé `d026569f`.
- Préparation `119984167951` **success** : six contrats workflow, ShellCheck/lints, 61 assertions complètes de propriété/liens/volume/permissions/rollback, 17 gardes P0, 46 assertions DB/marge, 28 gardes langues, sept contrôles Coming Soon et quatre simulations shell. Payload officiel épinglé de 311 fichiers, thème complet 23 fichiers et gardes P0 inchangés vérifiés.
- Recette native du **même commit** `119984168515` **success** : 30 assertions WPLANG/état protégé/retour exact, validation dans un nouveau processus PHP, **24 contrôles de parcours français et 45 contrôles aux cinq largeurs, zéro échec**, contrôle d'absence de commande/passerelle dans la fixture. Une seule recette navigateur, aucun doublon. Contenus persistés anglais de la fixture restent à traiter séparément.
- Job de mutation `119985019616` **waiting**, aucune étape démarrée. API pending_deployments vérifiée : **godaddy-temp**, reviewer humain **mycomane**; API approvals : **zéro approbation** au contrôle final. Les tests réussis et cette préparation ne valent pas approbation de déploiement.
- Sauvegarde **planifiée, pas encore créée** pour ce nouveau run : SQL/thème/langues/états/SHA dans le stockage privé hors racine Web du même hébergement, propre à cette exécution, après revue humaine et avant écriture publique. Empreintes, espace et répétition du rollback seront réellement vérifiés par le contrôleur après approbation. Refus ACL/rename ou dérive inconnue : arrêt, récupération ciblée si l'état est connu, aucun import SQL ni écrasement forcé. Aucune restauration intégrale hors site certifiée.

- [Directive Coordination/Julien](https://github.com/mycomane/alimentsdistinction/issues/4#issuecomment-6347149703) lue intégralement. Base `c6e423df7b2e2c0f63feb83976c2ce8283144fad`; branche exclusive conservée. Autorisation de préparation et création d'une nouvelle exécution seulement, **pas d'approbation ni de modification GoDaddy**.
- [Run correctif 39822268395](https://github.com/mycomane/alimentsdistinction/actions/runs/39822268395) désormais **completed/success** : préparation et recette fr_CA réutilisable du même commit `d026569f5092c1c1efb7c5d8050d47b02d295975` réussies; ancien job mutatif skipped. Les 61 assertions d'ownership sont confirmées. Confidentialité enregistrée et catégorie de fixture anglaises restent hors de la configuration de langue.
- Diff préparé : retrait du seul `&& false` du job de mutation et mise à jour du commentaire; test de contrat adapté à ce rétablissement. `needs: [prepare, isolated-qa]`, branche, `environment: godaddy-temp`, preuve de revue humaine du run exact avant SSH et tous les scripts opérationnels sont inchangés. Aucun nouveau workflow concurrent ou contournement; nouveau run du workflow existant.
- Vérifications locales : six contrats workflow, analyse YAML/bash et `git diff --check` réussis. Recette complète du nouveau SHA requise sur GitHub avant la demande de revue; aucun ancien run relancé. Aucune modification du correctif de fichiers déjà validé ni des 23 fichiers du thème.
- Le nouveau run est consigné ci-dessus et arrêté à **Waiting / Review deployments**. Work ne l'approuve pas. La sauvegarde ne sera créée qu'après approbation, dans un stockage privé de l'hébergement hors racine Web, propre à cette nouvelle exécution; aucune ancienne préparation n'est consommée.
- [Dossier de revue : diff, périmètre, sauvegarde, politique d'échec et risques](locale/FR_CA_NEXT_REVIEW.md). ACL/rename peut encore provoquer un arrêt; retour ciblé des langues/WPLANG uniquement, refus de dérive inconnue, aucune restauration SQL automatique. Restauration intégrale hors site toujours non certifiée.

## Issue #4 — mesure confirmée, correctif opérationnel en préparation isolée

Historique du correctif isolé; la préparation du nouveau lot ci-dessus rétablit seulement la possibilité d'une demande de revue humaine.

- Le [diagnostic lecture seule 39820612012](https://github.com/mycomane/alimentsdistinction/actions/runs/39820612012) a **réussi** après nouvelle approbation humaine vérifiée. Job `119970652449`, preuve du **22 octobre à 13 h 16 min 14 s HAE** : seule la comparaison propriétaire candidat/dossier est fausse; comparaison avec les fichiers existants, volume, nombre de liens, empreintes, canonicalité, droits et inventaire sont conformes. [Preuve expurgée](locale/issue4-filesystem-booleans.json). Aucune valeur UID/GID ni chemin privé résolu publié.
- La recette réutilisable de [39820611879](https://github.com/mycomane/alimentsdistinction/actions/runs/39820611879) a également **réussi**; le job de mutation est resté **skipped**. L'approbation lecture seule a uniquement autorisé la lecture de l'ancienne préparation; elle ne vaut pas approbation de configuration fr_CA.
- Le contrôle comparait des identités distinctes : celle des fichiers préparés/existants et celle du dossier d'hébergement. Correctif ciblé préparé : candidat appartenant à l'espace de travail privé sûr et au processus créant son journal exclusif; fichier existant du même propriétaire; volumes/liens/empreintes inchangés. Le propriétaire du dossier peut différer, mais son identité, ses droits et son volume sont vérifiés et doivent rester stables avant chaque remplacement. Aucun chown/chmod de dossier GoDaddy introduit.
- Retours arrière renforcés : refuser tout propriétaire/volume de fichier changé avant la première restauration/suppression. Tests du cas légitime avec traductions existantes **et nouvelles**, propriétaire étranger, changement de dossier en cours d'opération, propriétaire de fichier modifié, permissions dangereuses et rollback partiel ajoutés.
- Tests locaux du nouveau correctif : **39 assertions partielles**, 28 gardes langues, 6 contrats workflow et **4 simulations shell** réussis; suite complète GitHub à consigner après fin. Les 23 fichiers du thème, les 311 fichiers épinglés et les gardes P0 ont été vérifiés inchangés. La limitation locale sur les changements de propriétaire reste explicitement distinguée de la CI complète.
- Code du correctif : [d026569f](https://github.com/mycomane/alimentsdistinction/commit/d026569f5092c1c1efb7c5d8050d47b02d295975). [Nouvelle recette isolée 39822268395](https://github.com/mycomane/alimentsdistinction/actions/runs/39822268395) : préparation `119976329966` **réussie**, **61 assertions complètes** de fichiers/propriété/volume/liens/permissions/retour arrière, quatre simulations et gardes existants réussis. Recette navigateur du même commit `119976330761` **en cours au dernier contrôle**; sa réussite n'est pas anticipée. Aucun job de mutation activable dans ce lot.
- **Mutations toujours désactivées.** Nouveau code et recette du même commit à faire valider par la Coordination; aucun nouveau run GoDaddy de mutation lancé et aucun ancien run relancé. Le correctif ne sera activé qu'après accord distinct et nouvelle revue humaine GitHub.

## PRIORITÉ COURANTE — issue #4, précondition fichiers fr_CA

Historique de la préparation du diagnostic, remplacé pour la cause constatée par la mesure ci-dessus.

- Directive [issue #4](https://github.com/mycomane/alimentsdistinction/issues/4) et journaux `119960431819` lus. Base du correctif : `f450668419ba05549ae0ea747d9f63e647199478`. Aucun ancien run relancé, aucune nouvelle opération GoDaddy exécutée par cette phase.
- [Run 39817398299](https://github.com/mycomane/alimentsdistinction/actions/runs/39817398299) : préparation et recette réutilisable **réussies**, opération distante **échouée après revue humaine**. La synchronisation native a fonctionné. Échec du garde combiné dans `lf_apply()` avant journal/remplacement des fichiers publics; **condition exacte parmi nlink/uid/dev non prouvée par ce message**.
- Sauvegarde fraîche et répétition privée confirmées dans l'ancien journal; retour arrière ciblé vérifié (langue d'origine, inventaire des langues, thème et Coming Soon), aucune restauration SQL. **fr_CA n'est pas déclaré installé.** Aucune restauration intégrale hors site certifiée.
- Correctif préparé : trois codes de refus distincts, logique nlink/uid/dev inchangée, gardes P0 intacts. Diagnostic non mutatif séparé : uniquement des booléens autorisés sur liens, égalités de propriétaire/volume, canonicalité, droits et inventaire. Aucun UID/GID, chemin privé résolu ou sortie SQL publié.
- La source préparée est actuellement comparée au propriétaire du dossier, et non seulement à celui du fichier existant. Cette égalité peut être trop stricte dans certains hébergements, mais ce n'est qu'une hypothèse pour GoDaddy. **Aucun assouplissement de propriété ou de volume n'est livré sans la mesure qui départage les cas.**
- Tests locaux : **37 assertions fichiers partielles**, 28 assertions langues/retour arrière, 7 contrôles Coming Soon, 6 contrats workflow et **4 simulations shell** réussis. Les changements réels de propriétaire et l'exécution sous utilisateur non privilégié sont refusés par l'environnement local : ils ne sont pas présentés comme testés ici. Le runner GitHub jetable exécutera la suite complète sous sudo, sans option partielle. Syntaxe PHP/bash/YAML et `git diff --check` vérifiés.
- Code testé : [a2724994](https://github.com/mycomane/alimentsdistinction/commit/a27249945dc56457db2ef5ad41bd06524edaff7b). [Diagnostic 39820612012](https://github.com/mycomane/alimentsdistinction/actions/runs/39820612012) : tests isolés `119970586230` **réussis**, **49 assertions complètes** de propriétaire/volume/liens/permissions/rollback partiel, 28 assertions existantes et 6 contrats workflow réussis. Job **119970652449 en attente d'une NOUVELLE approbation**; API pending_deployments confirme `godaddy-temp`, reviewer humain `mycomane`. Work ne l'approuve pas et aucun SSH de cette phase n'a commencé.
- [Recette et préparation du même commit 39820611879](https://github.com/mycomane/alimentsdistinction/actions/runs/39820611879) : préparation `119970585696` **réussie** (311 fichiers épinglés, 23 fichiers de thème et gardes P0 inchangés, 49 nouveaux contrôles complets, gardes existants et 4 simulations). Recette réutilisable `119970586161` **en cours au dernier contrôle**; aucun succès navigateur anticipé. Job de mutation explicitement désactivé indépendamment du résultat de cette recette.
- Workflow de configuration : job de mutation **explicitement désactivé pour cette phase**, préparation + recette fr_CA réutilisable du même commit conservées. Nouveau workflow de diagnostic **lecture seule** préparé : tests isolés d'abord, puis **nouvelle revue humaine `godaddy-temp` du run exact** avant SSH. Script envoyé sur stdin, lecture des seuls candidats/sauvegardes existants, verrou partagé ouvert en lecture; aucun téléversement de fichier ou endpoint public.
- La mesure distante est indispensable pour conclure quel sous-test a échoué. **Validation Coordination/Julien demandée pour ce seul diagnostic en lecture seule**, pas pour une configuration fr_CA. Si aucun sous-test n'échoue désormais, la condition historique reste indéterminée : ne pas inventer une cause.
- [Analyse, méthode de diagnostic, tests et adaptations conditionnelles](locale/ISSUE4_FILESYSTEM_REVIEW.md). Sauvegardes existantes préservées; aucun chown/chmod ni nettoyage sur GoDaddy. Tout futur correctif de mutation nécessitera nouvelle preuve, recette et approbation distincte.

## P1 reprise — préparation française canadienne

Historique de la préparation précédente; l'état courant est celui de l'issue #4 ci-dessus.

### Synchronisation de la recette — correction du 22 octobre

- Base de correction : `587a4c7df117a74cef6db402d030e81f7ddc792c`. L'ancien run échoué **39813094381 n'est pas relancé**. Son job de préparation `119944335447` a réussi; sa barrière `119944335907` a arrêté après 900 secondes, avant le statut final disponible de la recette. Job distant `119951920138` ignoré, aucun accès GoDaddy.
- Recette [39813094307](https://github.com/mycomane/alimentsdistinction/actions/runs/39813094307) désormais **success** sur `5b05b745c45df8369af315b42b41426cc5781a1e`; job `119944333190` terminé le 22 octobre à **12 h 31 min 46 s HAE**, statut final du run enregistré à **12 h 44 min 27 s HAE**. La barrière avait échoué à **12 h 34 min 58 s HAE**. Les journaux confirment les tests WPLANG/rollback, libellés fr_CA, panier synthétique et contrôles aux cinq largeurs; aucune commande ni passerelle active dans la fixture.
- Correction : la recette devient un workflow réutilisable appelé localement au **même commit**; `needs: [prepare, isolated-qa]` est la dépendance native avant le job protégé. Plus d'attente inter-workflows de 15 minutes, ni second run autonome identique. Le script historique `wait-qa.py` reste dans l'historique de code mais n'est plus exécuté par ce workflow.
- Contrôles locaux : **5 tests de contrat de synchronisation/protections réussis**, YAML analysé, `git diff --check` réussi. Le nouveau run doit confirmer l'exécution native; aucun succès anticipé.
- Correctif enregistré : [b58b5432](https://github.com/mycomane/alimentsdistinction/commit/b58b5432db8425965a30ef3ad83a7174a9131bc4). [Exécution 39817398299](https://github.com/mycomane/alimentsdistinction/actions/runs/39817398299) : préparation `119959370754` et recette appelée `119959370948` **réussies**, puis opération distante `119960431819` **échouée** avec retour arrière vérifié; voir issue #4. L'ancien run ne doit pas être relancé.
- Protections conservées : nouvelle revue humaine `godaddy-temp` du run exact, vérification de l'approbation avant SSH, sauvegarde fraîche et retour arrière ciblé, Coming Soon, identité temporaire, concurrence sans annulation, thème et règles commerciales inchangés. La recette appelée ne reçoit aucun secret GoDaddy.
- Périmètre reste la préparation du français canadien. Aucune autorisation d'opération distante n'est déduite de la clôture sécurité ou de la réussite des tests. Ne pas approuver un job de configuration avant l'accord explicite de Julien pour ce périmètre.

- Issue #3 vérifiée **clôturée**, base de reprise `eae4fbf24120d13d005c3a0617acd24cac116253`. La Coordination autorise la préparation isolée; une opération GoDaddy reste soumise à une autorisation distincte et à sa revue GitHub.
- Dernier échec français `39699480039` : ShellCheck SC2016 sur le PHP intégré à une chaîne shell littérale. Extraction du calcul dans `scripts/locale/space.php`, avec les mêmes bornes numériques et la même marge P0. Aucun avertissement désactivé, aucun garde affaibli. Le helper est inclus dans le bundle et les simulations.
- Tests locaux de reprise : syntaxe PHP/bash et `git diff --check` réussis; **46 assertions DB/marge, 28 assertions fichiers langues, 7 contrôles Coming Soon, 4 simulations shell** réussis (succès et trois échecs avec retour arrière). Aucun SSH ni changement hébergé.
- Contrôles du workflow : branche exclusive, concurrence sans annulation, préparation + QA isolée du commit exact, `environment: godaddy-temp`, preuve d'approbation humaine du run avant SSH, empreinte SSH et identité temporaire conservées. L'API du connecteur ne prend pas en charge la lecture directe des règles d'environnement; leur état frais ne doit pas être annoncé comme vérifié par cette lecture. Le garde indépendant exige toujours une approbation humaine enregistrée avant toute connexion.
- Code corrigé : [5b05b745](https://github.com/mycomane/alimentsdistinction/commit/5b05b745c45df8369af315b42b41426cc5781a1e). [Préparation 39813094381](https://github.com/mycomane/alimentsdistinction/actions/runs/39813094381), job `119944335447` : **succès**; ShellCheck, syntaxe, 17 gardes fichiers, 46 assertions DB/marge, 28 assertions langues, 7 contrôles Coming Soon et 4 simulations shell réussis. Payload épinglé de 311 fichiers et 23 fichiers du thème inchangés vérifiés; paquet de revue publié.
- [Recette isolée 39813094307](https://github.com/mycomane/alimentsdistinction/actions/runs/39813094307), job `119944333190` : **succès désormais confirmé**. L'ancienne barrière avait déjà échoué; voir la correction de synchronisation ci-dessus. Un éventuel job protégé en attente ne vaut pas autorisation de configurer le serveur. Work ne l'approuve pas.
- Périmètre proposé inchangé : paquets officiels épinglés + WPLANG fr_CA; aucun texte légal enregistré ni règle commerciale. Thème 0.1.2/palette Actuel conservés. Le WordPress hébergé n'est pas déclaré français.
- Avant opération distante : autorisation explicite du périmètre fr_CA, revue GitHub propre au nouveau run, sauvegarde fraîche et retour arrière ciblé prévus dans le contrôleur. Pas de publication de détails serveur supplémentaires dans les issues.

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
