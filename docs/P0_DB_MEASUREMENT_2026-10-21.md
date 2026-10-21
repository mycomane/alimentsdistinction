# P0 — incident de mesure DB et nouvelle exécution protégée

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
