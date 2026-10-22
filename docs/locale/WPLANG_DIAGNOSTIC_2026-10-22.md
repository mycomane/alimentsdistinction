# Diagnostic WPLANG isolé — 22 octobre 2026

Mandat : [dernier commentaire de Coordination](https://github.com/mycomane/alimentsdistinction/issues/4#issuecomment-6347458764). Base `09b3870364348b29d3f95ebb4193201359350469`, branche exclusive de développement. Aucun SSH ni changement GoDaddy, aucun ancien run relancé.

## Ce qui est établi

Le précédent run hébergé a passé l'application des fichiers puis échoué sur le garde combiné d'activation; retour arrière vérifié. Le journal ne départageait pas retour false de l'API et valeur relue divergente. Cette incertitude historique reste intacte.

La petite suite `test-wplang-diagnostic.php` utilise un WordPress 7.1.3/WooCommerce 11.2.1/SQLite neuf, identité loopback locale stricte, requêtes externes WordPress et courriels bloqués. Aucun serveur HTTP/navigateur n'est démarré. Les catalogues sont les 311 fichiers officiels épinglés; les injections SQL de faute sont limitées à cette base synthétique et ne forcent jamais fr_CA dans le contrôleur hébergé.

## Mécanisme reproduit

1. Sans catalogue core fr_CA : langue non disponible, sanitisation native refusée, API false, baseline intacte.
2. La liste vide est mise en cache par WordPress. Les octets officiels sont ensuite copiés dans la fixture sans passer par l'upgrader, comme une installation de fichiers externe.
3. Malgré la présence du catalogue, la liste périmée reste vide : sanitisation refusée, API false et ligne anglaise inchangée. Le comportement correspond à une branche possible de l'ancien garde, sans prouver qu'il s'agissait de cette branche sur GoDaddy.
4. Invalidation native ciblée des listes core/plugins : langue reconnue, sanitisation respectée, écriture par l'API officielle et relecture brute conformes. Les entrées de cache sans rapport et celle des thèmes restent conservées; retour exact de WPLANG vérifié.
5. Le phénomène inverse est également testé : une liste en cache peut annoncer un catalogue retiré par un rollback. La même invalidation ciblée après restauration des fichiers supprime cette annonce fantôme.

La reproduction utilise le cache objet du processus isolé. Un backend persistant pourrait conserver la même liste entre processus, mais **son état transitoire chez GoDaddy n'a pas été mesuré**. Ce rapport ne déduit ni Redis, ni filtre particulier, ni panne de base sur l'hôte.

## Branches de refus testées

| Cas synthétique réel | Résultat attendu |
| --- | --- |
| Catalogues absents ou langue masquée par un filtre de disponibilité | FR_CA_NOT_AVAILABLE, aucune écriture |
| Filtre pre-update ou sanitisation conservant l'ancienne valeur | WPLANG_API_FALSE, relecture brute néanmoins exécutée |
| Erreur d'écriture de la base synthétique | WPLANG_WRITE_FAILED, baseline inchangée |
| Erreur de SELECT brute synthétique | WPLANG_QUERY_FAILED, aucune donnée SQL publiée |
| API réussie puis ligne supprimée ou modifiée par injection de fixture | WPLANG_RAW_MISMATCH, état inconnu refusé par le rollback |
| Exception API portant une information factice sensible | WPLANG_API_EXCEPTION, détail non publié |
| Exception non autorisée à la sortie du contrôleur | LOCALE_STATE_REFUSED, aucune trace privée |

Après les injections d'absence/divergence, les réparations de fixture sont explicites et réservées à la base jetable; le contrôleur de récupération refuse d'écraser aveuglément cet état inconnu. Les 30 assertions préexistantes de WPLANG/autoload et leur vérification en processus neuf sont réutilisées.

## Correction candidate, non déployée

`ad_locale_activate()` vérifie toujours l'état protégé et le baseline, puis utilise la méthode publique de WordPress pour invalider uniquement la découverte des traductions core/plugins. Il exige la présence physique d'un catalogue lisible et sa reconnaissance native. Il ne filtre pas artificiellement la liste des langues et ne neutralise aucun filtre de sanitisation/pré-update.

L'appel à `update_option()` reste le seul moyen d'activer fr_CA. Son retour est conservé, l'erreur de requête est observée sans publication de son texte, puis la ligne brute est relue même si l'API renvoie false. Tout retour false reste bloquant, y compris si une ligne paraissait conforme; aucune acceptation idempotente n'est ajoutée. Les erreurs deviennent des codes fixes distincts.

L'entrée CLI hébergée limite les exceptions/avertissements publiés à une liste fermée. Les requêtes de contrôle évitent l'affichage brut des erreurs de base. La restauration exacte historique est conservée, avec une étape d'invalidation ciblée après récupération des fichiers. Aucun import SQL ni nouveau chown/permissivité de fichier.

## Protections et exécutions

Le workflow de configuration n'a plus de déclenchement push pendant cette phase et son job de mutation est désactivé. Le nouveau workflow `qa-wplang-isolated.yml` n'a ni secrets, SSH, environnement `godaddy-temp`, navigateur ou appel de recette lourde. Il lance les cas ciblés et les tests de récupération légers du même commit. Aucun ancien workflow n'est relancé.

Local : 40 assertions ciblées du premier passage, 30 assertions WPLANG existantes, vérification native dans un processus neuf, sept contrats et syntaxe réussis. Trois assertions supplémentaires sur la découverte après rollback sont incluses dans la suite finale attendue en CI fraîche; elles ne sont pas présentées comme exécutées localement lors du premier passage. L'absence de commande/passerelle a été vérifiée dans la fixture. Preuve JSON du nouveau run attendue avant conclusion de CI.

## Protocole futur proposé à la Coordination

1. Examiner le nouveau SHA, les tests et les codes de refus. Ne pas relancer le run échoué ni supprimer ses sauvegardes.
2. Si une observation distante est demandée, la limiter au contexte WP-CLI identique et à des booléens de présence/reconnaissance/sanitisation, avec nouvelle revue humaine lecture seule. Après rollback, ces observations ne reconstituent pas la liste en cache au moment de l'échec.
3. Une future configuration nécessiterait une autorisation distincte, nouvelle sauvegarde et nouvelle revue humaine. Après installation/inventaire des fichiers, observer les booléens de reconnaissance avant/après l'invalidation native, puis l'API et la lecture brute sous leurs gardes; vérifier en processus neuf, état protégé, Coming Soon et rollback ciblé.
4. Si fr_CA reste non reconnu, si un filtre refuse ou si la requête échoue : arrêt sous code fixe, sans écriture SQL forcée ni suppression des gardes. Ne pas présumer qu'une purge globale résoudrait le problème.

Le site commercial, master, DNS, paiements, règles commerciales et Zoho restent hors périmètre. La restauration intégrale hors site n'est toujours pas certifiée.

## Références primaires vérifiées

- [sanitize_option](https://developer.wordpress.org/reference/functions/sanitize_option/) : WPLANG est validé contre les langues disponibles, puis les filtres restent applicables.
- [update_option](https://developer.wordpress.org/reference/functions/update_option/) : le retour false ne prouve pas à lui seul une panne d'écriture.
- [get_available_languages](https://developer.wordpress.org/reference/functions/get_available_languages/) : découverte native des catalogues via le registre.
- [invalidate_mo_files_cache](https://developer.wordpress.org/reference/classes/wp_textdomain_registry/invalidate_mo_files_cache/) : API publique et invalidation ciblée selon le type de traduction. L'argument upgrader est inutilisé dans l'implémentation WordPress 7.1.3 testée.
