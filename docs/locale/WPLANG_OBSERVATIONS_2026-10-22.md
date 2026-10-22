# Sept observations WPLANG — recette isolée

Directive : [Coordination, issue #4](https://github.com/mycomane/alimentsdistinction/issues/4#issuecomment-6349384832). Base `a5c20f1c2a65067b9134c6bbad5f3f8c694a951a`. Correctif instrumenté : [196cf8f1](https://github.com/mycomane/alimentsdistinction/commit/196cf8f131b71c74b1287e59ae65de44ad3cbff3). [Diff exécutable et tests](https://github.com/mycomane/alimentsdistinction/compare/a5c20f1c2a65067b9134c6bbad5f3f8c694a951a...196cf8f131b71c74b1287e59ae65de44ad3cbff3).

## Périmètre

`ad_locale_observe()` accepte exactement les sept clés du protocole et des booléens stricts, puis écrit `WPLANG_OBSERVE KEY=0|1`. Clé inconnue ou valeur non booléenne : refus fixe, aucune sortie. Aucune donnée SQL, chemin privé, identité serveur ou exception sérialisée. La baseline reste vérifiée avant les observations. L'émetteur ne neutralise aucun filtre.

L'ordre de collecte est : catalogue lisible, reconnaissance avant/après invalidation native ciblée, retour API, disponibilité écriture, disponibilité SELECT indépendante, conformité brute. Les résultats de l'API et de l'erreur d'écriture sont figés avant le SELECT. Un retour API false n'empêche pas ce SELECT; une exception API ne produit aucun résultat fictif. Une requête brute échouée émet seulement sa disponibilité false, sans inventer la conformité. Les codes et leur priorité restent inchangés.

Le seul diff du code hébergé concerne `wp-functions.php`. `state.php`, le shell, le contrôleur fichiers, les sauvegardes et le rollback sont inchangés. Les deux fichiers de test réutilisent les cas existants et ajoutent leurs assertions de traces et un contrat d'ordre fichiers/activation/processus neuf. Aucun workflow, thème ou manifeste n'est modifié.

## Matrice attendue et vérifiée par les assertions

Ordre des colonnes : **C** CORE_READABLE, **B** RECOGNIZED_BEFORE, **A** RECOGNIZED_AFTER, **U** API_CHANGED, **W** WRITE_QUERY_OK, **Q** RAW_QUERY_OK, **R** RAW_MATCH. `—` signifie observation non atteinte, donc aucune ligne émise.

| Scénario isolé | C | B | A | U | W | Q | R | Issue attendue |
| --- | --- | --- | --- | --- | --- | --- | --- | --- |
| Catalogue absent | 0 | — | — | — | — | — | — | FR_CA_NOT_AVAILABLE |
| Cache périmé réparé | 1 | 0 | 1 | 1 | 1 | 1 | 1 | SUCCESS |
| Catalogue déjà reconnu | 1 | 1 | 1 | 1 | 1 | 1 | 1 | SUCCESS |
| Filtre de disponibilité | 1 | 0 | 0 | — | — | — | — | FR_CA_NOT_AVAILABLE |
| API false / pre-update | 1 | 1 | 1 | 0 | 1 | 1 | 0 | WPLANG_API_FALSE |
| Filtre de sanitisation | 1 | 1 | 1 | 0 | 1 | 1 | 0 | WPLANG_API_FALSE |
| Exception API | 1 | 1 | 1 | — | — | — | — | WPLANG_API_EXCEPTION |
| Erreur d'écriture synthétique | 1 | 1 | 1 | 0 | 0 | 1 | 0 | WPLANG_WRITE_FAILED |
| SELECT post-API échoué | 1 | 1 | 1 | 1 | 1 | 0 | — | WPLANG_QUERY_FAILED |
| Ligne brute absente | 1 | 1 | 1 | 1 | 1 | 1 | 0 | WPLANG_RAW_MISMATCH |
| Ligne brute divergente | 1 | 1 | 1 | 1 | 1 | 1 | 0 | WPLANG_RAW_MISMATCH |
| Baseline protégée divergente | — | — | — | — | — | — | — | LOCALE_STATE_REFUSED à la sortie protégée |

Les assertions comparent toute la sortie à la séquence exacte, sans texte supplémentaire. Elles réutilisent l'exception sensible factice et ajoutent des entrées clé/valeur sensibles factices : aucune ne doit être émise. La preuve JSON contient seulement les labels synthétiques fixes, booléens, codes et compteurs. Elle ne contient pas les lignes SQL ou les sorties refusées. Le cas SELECT post-API est réparé par le rollback exact existant dans la fixture; les cas de divergence inconnue continuent de refuser une restauration aveugle.

## Preuves

CI fraîche : [39840545859](https://github.com/mycomane/alimentsdistinction/actions/runs/39840545859), **completed/success**, SHA exact `196cf8f131b71c74b1287e59ae65de44ad3cbff3`, job `120040091571`. Journal final consulté : **62 assertions ciblées (43 existantes + 19 nouvelles), 30 assertions rollback existantes, quatre simulations shell et huit contrats workflow réussis**. Les douze lignes de la matrice sont vérifiées par comparaison exacte des sorties capturées; les assertions de schéma refusent aussi clé inconnue et valeur non booléenne sans sortie.

Vérification dans un processus neuf fr_CA/deux libellés natifs réussie, inventaire des 311 fichiers épinglés et thème 0.1.2 de 23 fichiers conformes. L'absence de commande et de passerelle active dans la fixture est confirmée. Le message historique du script de contrôle mentionne le navigateur; **aucun navigateur n'a été lancé**. Artefact `wplang-diagnostic-isolated-evidence`, ID `12453261072`, non expiré à la vérification. Les observations/codes synthétiques sont ajoutés au JSON existant, sans données hébergées.

Local : huit contrats workflow réussis (les sept existants et le contrat additionnel d'ordre), syntaxe PHP des deux fichiers modifiés et `git diff --check` réussis. Workflows, contrôleurs de sauvegarde/rollback et thème comparés inchangés. Les preuves historiques 43 + 30 assertions au SHA 71058a95 restent distinctes des résultats de cette nouvelle CI.

## État de sécurité et projet de demande ultérieure

Configuration toujours en `workflow_dispatch`, job `deploy` toujours bloqué par `&& false`, dépendance `prepare` et QA réutilisable du même SHA, `environment: godaddy-temp`, revue humaine du run exact avant SSH. Aucun nouveau workflow GoDaddy, ancien run relancé, SSH, compte ou changement distant. Le protocole de sauvegarde fraîche et de retour arrière du [dossier final](WPLANG_FINAL_REVIEW.md) demeure applicable.

**Projet de demande, non exécuté :** après validation du diff instrumenté et de la CI verte par la Coordination, demander à Julien une autorisation explicite distincte de réactiver le job de configuration pour le seul site temporaire. Une future exécution devra être nouvelle, épingler son SHA final, réussir ses propres prérequis, puis s'arrêter à la nouvelle revue humaine GitHub du run exact. Aucune autorisation n'est déduite de la présente recette et l'agent n'approuve jamais.

La cause historique GoDaddy reste inconnue; cache persistant, filtres, droits de base et ACL/rename peuvent encore refuser la configuration. La fixture SQLite n'en prouve pas le comportement. Fr_CA n'est pas déclaré installé sur l'hôte; aucune récupération intégrale hors site n'est certifiée. Issue #4 ouverte, site commercial/master/DNS/paiements/Zoho/données commerciales et Coming Soon hors intervention.
