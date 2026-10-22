# Issue #4 — distinguer les préconditions avant toute adaptation

22 octobre 2026. Base `f450668419ba05549ae0ea747d9f63e647199478`, branche exclusive de développement.

## Mise à jour après le diagnostic approuvé

Le [job 119970652449](https://github.com/mycomane/alimentsdistinction/actions/runs/39820612012/job/119970652449) est **success**. Approbation humaine du run enregistrée et contrôlée avant SSH. À **13 h 16 min 14 s HAE**, seule `candidate_owner_matches_directory` est fausse; les treize autres contrôles sont vrais, notamment `existing_targets_owner_matches_candidate`. [Preuve JSON expurgée](issue4-filesystem-booleans.json). Le constat identifie donc une comparaison trop large avec le propriétaire du dossier; aucun problème de volume ou de lien n'a été observé par ce diagnostic. Les métadonnées historiques n'étaient pas enregistrées, mais la préparation existante a été retrouvée conforme aux empreintes/inventaire.

La recette isolée 39820611879 est réussie. Aucun job de mutation n'a été exécuté; l'opération approuvée était strictement le diagnostic. Les sections suivantes conservent le raisonnement préalable comme historique.

## Correctif opérationnel proposé — isolé, non déployé

La nouvelle politique conserve l'identité des **fichiers** plutôt que de l'assimiler au propriétaire du **dossier** :

1. L'espace de journalisation privé doit être canonique, inscriptible et non modifiable par le groupe/public. Tous les candidats doivent appartenir à son propriétaire, avoir un seul lien, le bon contenu et le même volume que la cible.
2. Avant toute mutation publique, un journal exclusif privé est créé; son propriétaire doit confirmer la même identité d'écriture. Cela ne dépend pas d'une extension POSIX optionnelle et ne modifie aucun propriétaire.
3. Chaque fichier existant ciblé doit appartenir à cette même identité. Le remplacement conserve le garde P0 d'atomicité, de mode, d'empreintes et de propriétaire. Un fichier étranger est refusé même si le dossier est inscriptible.
4. Pour un fichier nouveau, candidat/processus sont liés par le contrôle du journal; même volume, lien unique et hash sont revalidés avant et après rename. Aucun fichier inattendu n'est écrasé.
5. Les dossiers sont canoniques, inscriptibles et non inscriptibles publiquement. Leur inode, propriétaire, groupe, mode et volume sont scellés pour l'opération et recontrôlés avant chaque remplacement. Un changement en cours provoque un arrêt; aucun chown de dossier n'est tenté.
6. La récupération vérifie aussi les propriétaires/volumes de tous les fichiers à changer avant sa première restauration/suppression, puis conserve les gardes d'inventaire et d'empreintes. Une dérive de propriétaire inconnue impose un arrêt, pas un contournement.

Les métadonnées servent uniquement en mémoire et dans les contrôles privés; les erreurs publiées restent des codes fixes. Les 23 fichiers du thème, les 311 traductions épinglées, les gardes P0, la sauvegarde fraîche, le contrôle WPLANG et Coming Soon ne changent pas. Les permissions des dossiers existants ne sont jamais réécrites.

**Risque résiduel à valider :** les droits effectifs et ACL GoDaddy peuvent encore refuser un rename malgré le précontrôle. Dans ce cas, le contrôleur s'arrête et utilise son retour arrière ciblé; une dérive inconnue peut nécessiter une intervention de récupération. La sauvegarde reste sur le même hébergement, sans restauration intégrale hors site certifiée. Les nouvelles vérifications privées de journal et de stabilité sont sûres par refus mais n'ont pas été exécutées sur GoDaddy par ce diagnostic antérieur. Aucune compatibilité distante supplémentaire n'est anticipée.

Les tests ajoutés distinguent dossier d'un autre propriétaire avec fichiers existants et fichiers nouveaux (autorisés), candidat/fichier étranger (refusé), changement d'identité d'un dossier après une première modification (refusé), retour arrière d'un fichier dont le propriétaire a changé (refus avant toute autre restauration), et permissions d'espace privé/dossier dangereuses (refusées). La suite locale partielle ne remplace pas les tests complets du runner GitHub.

Le job de mutation reste désactivé pendant la revue. **Validation Coordination/Julien du correctif, puis nouvelle exécution avec revue humaine propre, obligatoires avant tout déploiement.** Les propositions conditionnelles historiques ci-dessous ne sont pas des opérations réalisées.

Preuve du correctif [d026569f](https://github.com/mycomane/alimentsdistinction/commit/d026569f5092c1c1efb7c5d8050d47b02d295975) : [préparation 119976329966](https://github.com/mycomane/alimentsdistinction/actions/runs/39822268395/job/119976329966) **success**, **61 assertions complètes** incluant les nouveaux cas de propriétaire/dossier, 28 gardes de langues, 17 gardes P0, 46 assertions DB/marge, sept contrôles Coming Soon et quatre simulations shell. Les six contrats workflow et les 311 fichiers/23 fichiers de thème ont aussi été vérifiés. La recette navigateur réutilisable `119976330761` est encore en cours au dernier contrôle; aucun résultat hébergé supplémentaire n'est revendiqué.

## Constat prouvé

[Run 39817398299](https://github.com/mycomane/alimentsdistinction/actions/runs/39817398299), job `119960431819` : revue humaine confirmée, identité temporaire validée, inventaire et marge de sauvegarde satisfaits, Coming Soon vérifié, sauvegarde et répétition privée réussies. Ensuite : `Locale filesystem/owner precondition failed`. Le rollback ciblé est confirmé; aucune restauration SQL ni installation fr_CA réussie à déclarer. Ne pas relancer ce run : ses préparations privées existent déjà.

La condition qui émet ce message est l'OR de trois refus : nombre de liens candidat différent de un, propriétaire candidat différent du propriétaire du dossier cible, ou volume candidat différent du volume du dossier cible. Le log ne contient aucune donnée qui départage ces conditions. La réussite sur une copie privée ne prouve pas que ses métadonnées correspondent à celles du dossier réel. **Attribuer l'échec au propriétaire seul serait une déduction non vérifiée.**

## Correctif limité à la précision du diagnostic

`files.php` conserve exactement les trois comparaisons; chaque refus reçoit un code distinct, sans identifiant numérique ni chemin. Aucun garde de remplacement P0, inventaire, manifeste, canonicalité ou retour arrière n'est supprimé.

`audit-fs.php` lit les sauvegardes/candidats existants de l'ancien run et retourne seulement une liste fermée de booléens :

- canonicalité de la source/cible et absence de liens dans l'inventaire;
- correspondance des empreintes candidates, nombre de liens égal à un, égalités propriétaire/dossier et volume/dossier;
- correspondance propriétaire/volume avec les fichiers cibles existants;
- droits d'écriture effectifs, présence de bits d'écriture/exécution, inventaire égal à la sauvegarde initiale.

Les archives/états de la sauvegarde sont recontrôlés contre SHA256SUMS avant lecture du baseline. Le script ne restaure, ne copie, ne renomme, ne supprime et ne modifie aucune permission. Les fichiers PHP de traduction sont hachés, jamais exécutés. Les avertissements et exceptions distants produisent un code fixe; les sorties SSH brutes ne sont pas publiées. Le workflow valide la liste des clés et exige des valeurs strictement booléennes avant affichage. `true` signifie que la propriété a pu être vérifiée; une indisponibilité rend le diagnostic non concluant, sans publication de métadonnées brutes.

Le test d'écriture reste une lecture des permissions/accessibilités : il ne prouve pas qu'un futur rename sera autorisé par toutes les ACL ou politiques de l'hébergeur. Il ne crée aucun fichier d'essai sur GoDaddy. Les lectures WP-CLI d'identité ignorent extensions/thème; aucune commande de modification d'option n'est utilisée.

## Nouveau run de diagnostic proposé — approbation distincte requise

`diagnose-locale-filesystem.yml` exécute d'abord les tests isolés, puis attend une **nouvelle revue humaine du run exact** dans `godaddy-temp`. Le garde de revue précède l'authentification SSH. Empreinte SSH et identité temporaire exactes conservées; les paramètres commerciaux ne sont pas lus dans les journaux.

L'opération distante ouvre le verrou existant en lecture et prend un verrou partagé sans attente; si une mutation détient le verrou, elle s'arrête. Le script est envoyé sur stdin, sans déposer de fichier. Elle lit uniquement la préparation existante et la cible; elle n'installe ni thème ni langue, ne fait pas de sauvegarde supplémentaire et ne crée aucun endpoint public. L'ancienne préparation n'est pas consommée.

Le workflow de **mutation** est volontairement désactivé pendant cette investigation. Il conserve préparation + recette fr_CA réutilisable du même commit; son état ne pourra être rétabli qu'avec un nouveau lot validé et une nouvelle approbation humaine. Aucune approbation de diagnostic ne vaudra approbation de mutation.

## Adaptations à examiner selon la mesure

| Résultat confirmé | Proposition à préparer ensuite |
| --- | --- |
| Propriétaire source/dossier différent, mais source/fichiers existants identique | Examiner une politique distinguant dossier et fichier existant, avec contrôle du propriétaire effectif autorisé pour les nouvelles traductions, stabilité des dossiers et permissions. Conserver le refus d'écraser un fichier d'un autre propriétaire. Ne pas appliquer cette politique sans preuve. |
| Propriétaire source/fichier existant différent | Faire confirmer l'identité d'écriture autorisée par GoDaddy; aucun chown arbitraire, aucun contournement global du garde. |
| Volume différent | Examiner une préparation privée hors racine Web sur le volume cible, sous l'identité autorisée, puis reprendre les contrôles d'atomicité et de rollback. Aucun staging PHP accessible publiquement. |
| Nombre de liens différent de un | Refuser; examiner l'origine du lien puis recréer seulement une copie régulière vérifiée dans un espace privé autorisé. |
| Droits, canonicalité, empreintes ou inventaire non conformes | Arrêt et investigation ciblée; aucun chmod global ni écrasement de dérive inconnue. |
| Tous les contrôles conformes maintenant | Cause historique non prouvée; ne pas choisir arbitrairement un correctif de propriété. |

Ces adaptations sont des propositions conditionnelles, **pas une correction opérationnelle déjà validée**. Elles nécessiteront leurs propres tests et la revue Coordination/Julien.

## Tests et limites

La suite `locale-ownership.php` utilise uniquement des répertoires jetables : propriétaires divergents de source/dossier/fichier, volume réellement distinct, hardlinks, symlinks de feuille/ancêtre/cible, traversée, permissions non inscriptibles, changement de propriétaire après un premier remplacement et rollback de ce cas partiel. La suite existante couvre aussi empreinte inconnue, interruption, inventaire tiers et rollback exact. Les assertions n'impriment aucune valeur de propriétaire/volume.

L'environnement Work a refusé la modification du propriétaire d'une fixture : le passage local partiel est explicite et n'est jamais utilisé en CI. GitHub exécutera la suite complète sous sudo dans son runner jetable, ainsi qu'un sous-processus non privilégié pour les permissions effectives. La recette fr_CA reste réutilisée au même commit, sans doublon autonome. Les résultats du nouveau commit seront consignés après exécution; aucun test distant n'est déclaré réussi avant approbation et mesure.

Le site commercial, master, DNS, domaines, Zoho, paiements, taxes, livraison, utilisateurs et produits restent hors de cette opération. Coming Soon et sauvegardes préservés. Une sauvegarde de fichiers/base vérifiée sur l'hôte ne certifie toujours pas une restauration intégrale hors site.

## Résultats GitHub obtenus

Code [a2724994](https://github.com/mycomane/alimentsdistinction/commit/a27249945dc56457db2ef5ad41bd06524edaff7b) : [job de tests 119970586230](https://github.com/mycomane/alimentsdistinction/actions/runs/39820612012/job/119970586230) **success**, 49 assertions de la suite complète (sans option partielle), 28 gardes existants, six contrats workflow. Les cas de propriétaire et de permissions non privilégiées ont donc bien été exécutés sur le runner jetable GitHub, contrairement au passage local partiel.

[Préparation 119970585696](https://github.com/mycomane/alimentsdistinction/actions/runs/39820611879/job/119970585696) **success**, paquets/thème/gardes inchangés et quatre simulations de retour arrière vérifiés. La recette navigateur réutilisée de ce même commit est encore en cours au dernier contrôle; sa réussite n'est pas anticipée.

Le [nouveau diagnostic 39820612012](https://github.com/mycomane/alimentsdistinction/actions/runs/39820612012) est **waiting**, job de lecture seule `119970652449`. API pending_deployments : environnement `godaddy-temp`, reviewer humain `mycomane`. **Validation de la Coordination puis nouvelle approbation limitée à cette lecture seule requises.** Aucun diagnostic distant n'a encore eu lieu et aucune condition uid/dev/nlink n'est attribuée à GoDaddy sans cette preuve. Aucun déploiement n'est demandé par ce run.
