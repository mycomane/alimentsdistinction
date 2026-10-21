# P0 — incident de mesure DB et nouvelle exécution protégée

**Mise à jour :** le run 39638388563 décrit comme en attente dans le bilan historique a depuis été approuvé puis a échoué sur `unexpected_output_shape`. Voir le dernier incident dans PROJECT_STATUS.md. Aucune nouvelle sauvegarde/bascule par ce run selon le chemin exécuté.

Directive : [dernier commentaire de l'issue #1](https://github.com/mycomane/alimentsdistinction/issues/1#issuecomment-6321369680), lu intégralement. Le run `39615680576` n'est **pas relancé**. Toute nouvelle opération GoDaddy nécessite une nouvelle approbation `godaddy-temp` du nouveau run.

## Échec réellement observé

Job distant `119277446903`, run `39615680576` : approbation `mycomane` vérifiée, identité temporaire/home privé confirmés et inventaire du thème validé, puis `Database size not measurable`. L'arrêt précède le dossier de sauvegarde P0, la copie du thème et le remplacement de `style.css`.

Le bundle privé `~/.ad-p0-ops-39615680576/` et le verrou peuvent rester présents. Aucun rollback du thème n'était nécessaire selon ce chemin d'exécution. **Pas de vérification distante post-échec réalisée dans cette correction** : ne pas transformer cette conclusion de contrôle de flux en certification de l'état courant complet du site.

## Cause et degré de certitude

L'ancien pipeline mélangeait `wp db size --size_format=b --format=csv`, extraction du dernier champ CSV et exigence d'entier ASCII. Le [code officiel de DB_Command::size](https://github.com/wp-cli/db-command/blob/main/src/DB_Command.php) consulté le 21 octobre montre que :

- la taille formatée contient une unité `B`;
- la sortie numérique seule retire cette unité uniquement si aucun `--format` n'est demandé;
- demander CSV passe par le formateur et conserve la taille avec son unité.

Ainsi, un champ tel que `5865472 B` (ou cité par le formateur CSV) est rejeté par la garde. **Cette incompatibilité est démontrée dans le code amont; elle explique vraisemblablement l'incident.** La version WP-CLI déployée chez GoDaddy et sa sortie exacte n'ont pas été relevées : nous ne prétendons pas avoir observé ces octets sur l'hébergement. Aucun diagnostic supplémentaire distant ne contourne l'approbation.

## Correction ciblée et bloquante en cas d'incertitude

La nouvelle mesure n'utilise plus un affichage CSV destiné à l'utilisateur. `measure-p0-db.sh` exécute, par `wp db query`, le SELECT de `p0-db-size.sql`, limité à `TABLE_SCHEMA = DATABASE()` et aux tables de base. Il renvoie uniquement trois agrégats : nombre de tables visibles, somme en octets de `DATA_LENGTH + INDEX_LENGTH`, nombre de statistiques manquantes. Aucun nom de base ou table n'est demandé en sortie. Pas d'export ou de mutation DB à cette étape.

Le parseur requiert une seule ligne TSV numérique canonique, statut zéro, stderr totalement vide, au moins une table, taille strictement positive et aucune statistique manquante. LF, CRLF et absence de fin de ligne sont acceptés. Il refuse CSV, unités, formats localisés, décimales, notation scientifique, avertissements, lignes supplémentaires, valeurs négatives/nulles et entiers non sûrs. **Aucun retrait opportuniste d'un avertissement ou suffixe, aucun repli vers une taille inventée.** Des privilèges insuffisants ou une indisponibilité MySQL bloquent le déploiement.

Les entiers sont bornés avant conversion (`≤ 9007199254740991`, PHP 64 bits requis). Le calcul d'espace conserve **4 copies du thème + 3 tailles de base arrondies au Kio supérieur + 100 Mio**, avec espace libre strictement supérieur. Les valeurs `du`/`df` sont elles aussi validées. La taille est celle des statistiques de stockage MySQL, pas une prédiction exacte de la taille du dump; la marge n'est pas réduite.

Les sorties brutes sont capturées dans un répertoire privé 0700 du bundle, puis supprimées sur sortie normale/erreur/signaux gérés. Seuls code de retour, catégorie fixe, longueurs et forme numérique/non numérique peuvent être journalisés en cas de refus; aucun texte SQL d'erreur brut. Un arrêt non interceptable peut laisser ces fichiers privés, jamais téléversés en artefact.

## Tests et protections

- **39 assertions locales** : entier valide, LF/CRLF, plusieurs formats/locale, vide, stdout/stderr pollués, statut d'échec, MySQL indisponible, bornes d'entiers, limite exacte d'espace libre et arrondi conservateur.
- **17 assertions filesystem/Coming Soon** précédentes : réussies localement, inchangées.
- Syntaxe PHP/Bash/Python/YAML et dépendances des jobs : vérifiées localement.
- Nouveau test GitHub avec **MySQL 8.0 jetable** sur loopback du runner : requête SQL réelle et tables synthétiques; wrapper de capture/parseur/nettoyage de production. Seul le transport `wp` est remplacé par un adaptateur de test vers ce MySQL. Scénarios normal, base vide, avertissement et indisponibilité. Résultats GitHub à confirmer avant approbation; ce test ne certifie pas les privilèges ou la version WP-CLI GoDaddy.
- La recette WordPress isolée complète reste une dépendance obligatoire du job distant.

Les gardes existantes demeurent : branche exacte, nouvel accord reviewer pour le run exact, empreinte SSH, URLs temporaires, inventaire des 23 fichiers/version 0.1.0, Coming Soon, sauvegarde SQL+thème vérifiée, répétition privée du rollback, bascule atomique et comparaison WooCommerce/pages/réglages. Aucun fichier du thème n'est modifié par cette correction d'exploitation; le ZIP 0.1.1 et son SHA restent identiques.

Le compte Abonné de recette reste **non autorisé et n'est pas créé**. Aucune opération sur `master`, le site commercial, les DNS, paiements ou Zoho.

Le SHA testé, les résultats GitHub et le nouveau run en attente sont reportés dans [PROJECT_STATUS.md](PROJECT_STATUS.md) et l'issue #1 dès confirmation.

## Résultats GitHub confirmés — nouvelle approbation attendue

- Commit : [`ee9cd17655d5454483be339fdb5a7d4828305d58`](https://github.com/mycomane/alimentsdistinction/commit/ee9cd17655d5454483be339fdb5a7d4828305d58).
- [Run 39638388563](https://github.com/mycomane/alimentsdistinction/actions/runs/39638388563).
- Préparation `119355138291` : **succès**, 39 assertions parseur/espace + 17 gardes fichiers/Coming Soon, ShellCheck et syntaxe, requête réelle sur MySQL jetable et quatre scénarios de capture/parseur/nettoyage réussis. Aucun détail brut d'erreur n'a traversé les tests de refus.
- QA WordPress `119355138814` : **succès**, 300 assertions, 0,00 px aux cinq largeurs, zéro commande et zéro fatal/avertissement/dépréciation PHP compté.
- Déploiement `119356247301` : **waiting**, nouvelle revue `godaddy-temp` par `mycomane` requise et confirmée. Aucun accès GoDaddy effectué par cette reprise.
- L'archive du thème reste identique : SHA256 `8162bc571bb5dea232badd562b5c9085c30187b1b4ff80f567f1f5e9fdb8497e`.

La recette GoDaddy authentifiée et un nouvel Abonné restent non autorisés; aucune création de compte. La mesure réelle, les privilèges MySQL et la sauvegarde fraîche GoDaddy seront vérifiés dans l'exécution protégée après nouvelle approbation, sans présumer de leur réussite.

## Diagnostic hébergé réussi et correction de la seconde cause

[Run 39639769111](https://github.com/mycomane/alimentsdistinction/actions/runs/39639769111), job `119360202463`, succès après approbation distincte. WP-CLI **2.12.0** confirmé. L'agrégat et la sonde `SELECT` de constantes ont la même structure : 14 octets, 10 chiffres, 2 tabulations, **2 LF finaux**, aucun autre caractère et stderr vide. Le parseur exigeait au plus une fin de ligne : c'est la cause démontrée de `unexpected_output_shape`.

La correction autorise seulement **0 à 2 séquences LF/CRLF finales** après une unique ligne TSV numérique. Aucun nettoyage généralisé, tolérance d'erreur ou changement des contrôles de valeurs/marge. La sortie avec trois fins de ligne, une ligne de données supplémentaire, un avertissement, espace ou tabulation parasite reste refusée.

Tests locaux : **46 assertions mesure/espace** et 17 gardes fichiers/visibilité réussies. Deux cas d'intégration supplémentaires appliquent à la sortie du vrai MySQL jetable le second LF observé et son équivalent CRLF, puis exercent le wrapper/parseur/nettoyage de production. Ces cas simulent le transport WP-CLI, sans prétendre exécuter sa distribution GoDaddy dans le runner.

Le diagnostic a aussi confirmé les 23 fichiers originaux, l'identité temporaire et Coming Soon sur les cinq URLs; il n'a réalisé aucun déploiement, cache purge, compte ou mutation DB. Les [preuves de structure](evidence/p0-hosted-db-shape.json) ne contiennent aucune valeur de base. Une nouvelle approbation sera nécessaire sur le prochain run de déploiement, après tests.

## Validation finale du correctif de fins de ligne

Commit testé : [`be5703eeaa434ada55cb33a961a0b08e11f6a862`](https://github.com/mycomane/alimentsdistinction/commit/be5703eeaa434ada55cb33a961a0b08e11f6a862). [Run 39685183348](https://github.com/mycomane/alimentsdistinction/actions/runs/39685183348).

- Préparation `119517310652` : **succès**, ShellCheck, 46 assertions mesure/espace + 17 protections fichiers/visibilité, six scénarios de transport/parseur/nettoyage sur MySQL jetable. Double LF et double CRLF validés; erreurs/avertissements/vide toujours refusés.
- QA `119517311211` : **succès**, 300 assertions, zéro commande et zéro fatal/avertissement/dépréciation PHP compté; géométrie P0 à 0,00 px sur les cinq largeurs.
- Déploiement `119518254594` : **waiting**, reviewer `mycomane` / `godaddy-temp` confirmé. Nouvelle approbation obligatoire, aucune opération distante de ce run à ce stade.

Archive 0.1.1 identique et toutes les protections conservées. La mesure des valeurs, l'espace libre et la sauvegarde fraîche GoDaddy restent des conditions bloquantes du job après nouvelle approbation; la preuve du diagnostic concernait la structure de sortie, pas une autorisation de déploiement. Aucun compte de recette créé.

## Résolution opérationnelle confirmée

Le run `39685183348`, job `119518254594`, a ensuite été approuvé et a **réussi** : mesure DB acceptée, marge d'espace satisfaite, sauvegarde fraîche et répétition du rollback validées, puis thème 0.1.1 déployé et CSS servi vérifié. Cette preuve confirme la correction sur le chemin GoDaddy réel, sans publier la taille ou le contenu DB. Voir PROJECT_STATUS.md pour les versions et limites de recette. Aucune nouvelle identité de test créée.
