# P0 — mise à jour contrôlée 0.1.0 → 0.1.1

**État supersédant les mentions « en attente » ci-dessous :** le run 39615680576 a été approuvé puis a échoué sur la mesure DB avant sauvegarde/bascule. Ne pas le relancer. Voir [le diagnostic et la reprise protégée](P0_DB_MEASUREMENT_2026-10-21.md) et PROJECT_STATUS.md pour le nouveau run.

Autorisation : [issue #1](https://github.com/mycomane/alimentsdistinction/issues/1), confirmée par Julien dans Work le 21 octobre 2026. **Le passage GitHub `godaddy-temp` reste obligatoire.** Cette fiche décrit la préparation; aucun succès distant n'est anticipé.

## Paquet et périmètre exact

La référence installée est le thème du commit `cf17c10ff102f10806a4ca99d6e35e94af769556`. Le script `prepare-p0-release.py` compare chacun de ses **23 fichiers** au candidat et refuse toute différence autre que :

- suppression de `  .hero__trust { flex-wrap: wrap; }` dans `style.css`;
- en-tête `Version: 0.1.0` remplacé par `Version: 0.1.1` dans ce même fichier.

Le thème utilise déjà sa version pour les URL CSS/JS : le nouveau CSS sera demandé avec `?ver=0.1.1`. Aucun PHP du thème, image, maquette, texte, palette ou gabarit WooCommerce modifié. Le ZIP installable contient exclusivement `aliments-distinction/`; il est conservé pour revue, mais le workflow ne téléverse et ne remplace que le fichier CSS nécessaire. L'installateur initial n'est pas relancé.

Empreintes déterministes préparées :

| Objet | SHA256 |
| --- | --- |
| ZIP 0.1.1 | `8162bc571bb5dea232badd562b5c9085c30187b1b4ff80f567f1f5e9fdb8497e` |
| Ancien style.css | `9ecf39c14fcd2a0216f53010a2c5c1fd1d8751c496a3d53a5237bdb26f95fe99` |
| Nouveau style.css | `5a4c3a5a8ae6fccd66437151555b66d05c1f5c18dc56cfff3de3f66a92bde780` |

## Workflow et portes de contrôle

`.github/workflows/update-godaddy-temp-p0.yml` : déclenchement par modification de ce fichier seulement, sur la branche exacte `feat/wordpress-woocommerce-theme`. Deux jobs sans secrets précèdent le déploiement : préparation/lint/tests des gardes et recette WordPress isolée réutilisée. Le job distant dépend de leur succès et de l'environnement protégé `godaddy-temp`.

Même après ouverture du job, l'historique d'approbation du **run exact** doit contenir un accord `approved` de `mycomane` pour cet environnement. Si l'historique ne peut pas être lu ou si l'accord manque, arrêt avant SSH. Le code n'approuve pas son propre déploiement et ne modifie aucune protection GitHub.

Les secrets ne sont utilisés que dans ce job protégé. Vérification de l'empreinte SSH épinglée avant authentification; pas de sortie de secret ou de SQL. Concurrence commune `godaddy-temp-site-mutation`, plus verrou distant `flock` pour cette opération.

## Préconditions distantes et sauvegarde

Les contrôles juste avant remplacement doivent confirmer :

- `siteurl` **et** `home` exactement `https://1302636.us22.myftpupload.com`;
- WordPress 7.1.3, PHP CLI ≥ 8.1, WooCommerce 11.2.1 déjà actif;
- thème/parent `aliments-distinction`, version 0.1.0, pages 6/7/8/9 inchangées;
- Coming Soon et HTTP 200 anonymes sur accueil et quatre pages techniques;
- empreintes/inventaire complet identiques aux 23 fichiers de référence, aucun lien symbolique/fichier spécial/lien physique;
- propriétaire et système de fichiers compatibles avec un `rename()` atomique, dossier et fichier inscriptibles;
- espace libre suffisant pour le thème, copies de retour arrière, SQL mesuré et marge de 100 Mio;
- aucune passerelle enregistrée comme activée; empreintes privées des options commerciales/visibilité, pages, taxes/livraisons et compteurs produits/commandes/utilisateurs.

Sauvegarde fraîche dans `~/.ad-private-backups/p0-011-<run>/`, hors webroot, parent privé 0700 : thème précédent complet, archive tar.gz, SQL gzip et SHA256SUMS. Vérifications gzip/tar/checksums, extraction du thème complet dans un dossier privé puis comparaison des 23 empreintes. Répétition réelle de l'échange CSS aller/retour **dans ce dossier privé**, avec le même helper que celui du remplacement. L'état installé est revérifié après cette préparation.

Ce paquet est conservé sur le même hébergement. Il ne constitue pas une sauvegarde hors site ni une restauration SQL éprouvée; aucune restauration SQL n'est autorisée par cette mission.

## Bascule, cache et retour arrière

Un seul `rename()` atomique remplace `style.css` depuis un fichier préparé hors webroot, sur le même système de fichiers. Le fichier cible ne disparaît pas entre deux commandes; aucun répertoire de thème, PHP ou ressource graphique n'est échangé. Permissions préservées. Les empreintes source/cible sont vérifiées immédiatement avant le `rename()` pour refuser une édition concurrente.

Seule la clé de cache d'en-tête du thème actif, dans le groupe WordPress `themes`, est invalidée pour cette installation. Aucun flush global, aucune purge CDN, aucun changement d'option/transient, aucun redémarrage PHP. Le CSS servi à l'URL exacte `style.css?ver=0.1.1` doit avoir l'empreinte attendue; sinon échec et retour arrière, sans élargir la purge automatiquement.

Après bascule : inventaire complet 0.1.1, chargement WP-CLI du thème/WooCommerce, version effective, options/pages/compteurs inchangés, CSS HTTP exact et Coming Soon vérifiés. L'échec déclenche le retour du CSS 0.1.0 depuis la copie privée, par le même échange atomique, suivi des contrôles de l'ancien thème complet et de la visibilité. **Aucune activation de Twenty Twenty-Five et aucune restauration SQL.** Une dérive concurrente est signalée, jamais écrasée sans contrôle.

Un arrêt brutal du runner/serveur peut empêcher le trap de s'exécuter. La copie complète et `RESTORE-READ-ME.txt` restent disponibles dans le dossier privé : comparer d'abord l'état en place aux empreintes ancien/nouveau, puis reprendre le rollback ciblé par le même environnement protégé. Aucune relance aveugle d'un run déjà initialisé. Tout échec du rollback impose arrêt et alerte.

## Tests préparatoires réellement exécutés

- `scripts/check-theme.py` : CSS de maquette et sept images inchangés, structure, syntaxe PHP/JS réussis.
- `scripts/package-theme.py` + `prepare-p0-release.py` : ZIP intègre, inventaire exact, comparaison des 23 fichiers et différences autorisées uniquement.
- Syntaxe PHP/Bash/YAML : réussie localement.
- `tests/p0-files.php` : **17 assertions** sur fichiers réels temporaires, dont refus de dérive, de contenu inattendu, de symlinks/hardlinks, conservation des permissions et remplacement/retour arrière atomiques. Aucun GoDaddy ni base WordPress utilisés.
- ShellCheck et recette responsive 320/390/768/1024/1440 : doivent être confirmés par le nouveau run GitHub avant la porte d'approbation. Les 300 assertions du run antérieur `39610664542` ne sont pas présentées comme résultat de ce nouveau candidat.

## Limite de validation hébergée à lever

L'ancien Abonné de recette a été supprimé. Cette mise à jour **ne crée aucun compte et ne forge aucune session d'utilisateur existant**. Les contrôles anonymes peuvent vérifier Coming Soon et les octets du CSS, mais pas le rendu du thème masqué.

Une **autorisation spécifique** est donc nécessaire pour un nouvel Abonné temporaire, limité à 30 minutes, frontend en lecture seule, sessions révoquées et compte supprimé après recette, selon la méthode précédente. Sans cet accord, le rapport distinguera « fichiers déployés et contrôles techniques réussis » et « géométrie hébergée authentifiée non confirmée ». Pas de capture HTML/cookie authentifié publiée.

Le statut opérationnel, les commits et liens d'exécution sont tenus à jour dans [PROJECT_STATUS.md](PROJECT_STATUS.md). La palette P1 et les configurations commerciales restent hors autorisation.

## Premier contrôle GitHub de préparation

Le run `39615415227` a refusé de poursuivre dans ShellCheck : calcul de taille à arrondir plus prudemment et trois fragments PHP inline signalés pour leurs variables littérales. Aucun job distant exécuté. Les fragments PHP sont désormais dans le helper testé, et trois assertions supplémentaires vérifient que « Coming Soon » présent uniquement dans un script/style ne suffit pas. Nouvelle exécution requise avant approbation.

## Résultat final de préparation — prêt pour approbation

Commit testé : [`813b8a1dee186c1a3f5942976394cc44dd61f325`](https://github.com/mycomane/alimentsdistinction/commit/813b8a1dee186c1a3f5942976394cc44dd61f325). [Run 39615680576](https://github.com/mycomane/alimentsdistinction/actions/runs/39615680576).

- Préparation `119276267562` : **succès**, ShellCheck inclus, ZIP et 23 empreintes exacts, 17 assertions de gardes/remplacement/retour arrière.
- Recette isolée `119276268010` : **succès**, 300 assertions ciblées, aucune commande créée, aucune erreur fatale/avertissement/dépréciation PHP comptée. Écart P0 **0,00 px aux cinq largeurs 320/390/768/1024/1440**.
- Job distant `119277446903` : **waiting**, reviewer `mycomane` pour `godaddy-temp` confirmé. Il n'a encore exécuté aucune connexion ni écriture GoDaddy.
- L'approbation attendue se fait dans le run : **Review deployments → godaddy-temp → Approve and deploy**. La sauvegarde fraîche et les contrôles de rollback distants seront alors exécutés avant toute bascule, sans anticiper leur réussite.
- La recette visuelle hébergée authentifiée reste soumise à l'accord spécifique pour un nouvel Abonné de test, 30 minutes et suppression après contrôle.

Aucun changement de palette, aucune configuration WooCommerce, DNS, paiement, Zoho ou production. Le rapport actuel est « prêt pour approbation », **pas « déployé »**.
