# Issue #4 — distinguer les préconditions avant toute adaptation

22 octobre 2026. Base `f450668419ba05549ae0ea747d9f63e647199478`, branche exclusive de développement.

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
