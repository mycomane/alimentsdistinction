# Audit PHP2Shell — 22 octobre 2026

Issue [#3](https://github.com/mycomane/alimentsdistinction/issues/3). Lecture seule, base `421b582ff3a127e577aeab515ef990d3cd2334c7`, branche `feat/wordpress-woocommerce-theme`. P1 suspendue. Toutes les heures de preuve ci-dessous sont en UTC.

## Conclusion

**PHP Web actuel et SAPI inconnus : impossible de déclarer le site corrigé.** La dernière preuve Web retrouvée annonce 8.2.34, antérieure à l'avis; la preuve CLI 8.2.35 plus récente ne la remplace pas. Priorité urgente à la confirmation du runtime Web par un accès administratif fonctionnel ou GoDaddy. Ni exploitation ni compromission démontrée par cet audit limité.

## Preuves et limites

| Élément | Preuve | Limite |
| --- | --- | --- |
| PHP Web historique 8.2.34 | [Job 118945599076](https://github.com/mycomane/alimentsdistinction/actions/runs/39515208953/job/118945599076), 2026-10-21T00:17:35.2048361Z : `PASS: PHP web 8.2.34 and required extension allowlist confirmed.` | SAPI non présent dans ce marqueur; version actuelle et rétroportage inconnus. Ancien mécanisme non réexécuté. |
| PHP CLI 8.2.35 | [Job 119554780896](https://github.com/mycomane/alimentsdistinction/actions/runs/39696116068/job/119554780896), état protégé du 21 octobre à 23:57:44 | Pas le processus servant les requêtes HTTP. |
| WP 7.1.3, Woo 11.2.1 actif, thème 0.1.2 | Même déploiement réussi; [preuve](p1/evidence/release-0.1.2-deployment.json) | Dernier état journalisé, pas un nouvel inventaire serveur. Le thème 0.1.1 mentionné dans l'issue est un état antérieur. |
| GoDaddy inaccessible | [Tableau de bord temporaire](https://host.godaddy.com/mwp/site/2f7ccde9-d228-4656-8d58-a68bd43af899/overview), tentative navigateur du 22 octobre à 04:09:34 : `ERR_SOCKS_CONNECTION_FAILED` | Échec réseau avant toute vérification de session déléguée; pas un refus d'identifiants. WP Admin non atteint. |
| HTTPS direct refusé | GET public `https://1302636.us22.myftpupload.com/?ad_security_readonly=20261022` : HTTP 403, pendant cet audit | Aucun runtime identifiable; aucun contournement, scan ou payload. |

Résultat navigateur conservé sous run `b0062cec-dc22-4534-8bac-070eb5dcfb0a`. Aucun nouveau run après cet échec. Aucun mot de passe demandé, fichier PHP public/phpinfo créé, secret ou cookie collecté. Aucun test d'exploitation.

## Avis officiels consultés le 22 octobre

- [Avis PHP du 21 octobre](https://www.php.net/archive/2026.php) et [annonce PHP](https://www.php.net/).
- [Centre canadien AL26-026](https://www.cyber.gc.ca/en/alerts-advisories/al26-026-critical-vulnerabilities-impacting-php-cve-2026-109618-cve-2026-109619).

Les deux CVE-2026-109618/109619 permettent une exécution de code non authentifiée; les SAPIs Web sont concernés. Versions corrigées minimales des branches concernées :

| Branche PHP | Correctif amont |
| --- | --- |
| 8.2 | 8.2.35 |
| 8.3 | 8.3.36 |
| 8.4 | 8.4.27 |
| 8.5 | 8.5.12 |

Un build fournisseur rétroporté exige une preuve explicite pour les deux CVE. Coming Soon ne constitue pas une protection du moteur PHP. Une version conforme ne certifie ni absence de compromission ni sécurité globale des extensions.

- [Alerte officielle WordPress](https://fr.wordpress.org/2026/10/21/mise-a-jour-de-securite-critique-de-php/) : les correctifs visent PHP, pas une nouvelle version WordPress pour ces CVE.
- [WordPress 7.1.3](https://wordpress.org/news/2026/10/wordpress-7-1-3-maintenance-and-security-release/) : publication de maintenance/sécurité du 6 octobre. La dernière version observée correspond à cette publication; aucune certification exhaustive des extensions n'en découle.
- [WooCommerce 11.2.1](https://developer.woocommerce.com/2026/10/09/woocommerce-11-2-1-release-notes/) : publication du 9 octobre, non annoncée comme correctif de sécurité; ne corrige pas l'interpréteur PHP.
- [Cycle de support PHP](https://www.php.net/supported-versions.php) : PHP 8.2 reçoit les correctifs de sécurité jusqu'au 31 décembre 2026; 8.3 jusqu'au 31 décembre 2027. La phrase du billet WordPress français donnant 2026 pour 8.3 contredit cette source PHP primaire, retenue ici.

## Prochaine vérification — lecture seule

1. Accéder normalement au tableau de bord GoDaddy du seul site temporaire, puis WP Admin par le SSO existant. Si connexion requise, Julien se connecte dans le navigateur; aucune réinitialisation ni collecte d'identifiants.
2. Outils → Santé du site → Informations → Serveur : relever PHP exact et SAPI, heure et identité du site. Ne publier que ces champs, pas le rapport complet contenant des informations sensibles. Un `wp eval` exécuté en CLI n'est pas une preuve Web.
3. Si l'accès reste bloqué, demander à GoDaddy l'attestation du build **servant HTTP** pour le site UUID `2f7ccde9-d228-4656-8d58-a68bd43af899`, sa SAPI, les correctifs des deux CVE et la prise en compte par tous les workers. Aucune demande au support n'a été envoyée par cet audit.
4. Faire l'inventaire frais WP/extensions et consulter leurs avis officiels; vérifier séparément les journaux disponibles sans exporter de données clients. Ces contrôles sont encore à réaliser.

## Plan conditionnel si PHP Web est affecté — NON EXÉCUTÉ

Avant tout changement : présenter les preuves à Julien, le correctif exact proposé et la fenêtre d'interruption; obtenir son accord distinct. Privilégier l'évaluation d'un correctif de la branche courante plutôt qu'un saut de branche improvisé. [Procédure officielle GoDaddy](https://www.godaddy.com/help/change-my-php-version-32202) : la sélection d'une branche ne prouve pas le patch effectif, à recontrôler après intervention.

Préconditions : sauvegarde fraîche fichiers/base, intégrité et méthode de restauration réellement utilisable; accès de secours, compatibilité WP/Woo/extensions/thème avec le build choisi, disponibilité d'un environnement isolé/staging et d'une méthode GoDaddy de retour à un runtime corrigé. La sauvegarde P1 sur le même hébergement (`p1-012-39696116068`) a été vérifiée pour les fichiers ciblés, **pas** comme restauration intégrale ni rollback PHP; elle ne suffit pas à certifier ce nouveau changement.

Risques : redémarrage/indisponibilité, incompatibilités PHP/extensions, différences CLI/Web, cache de workers, erreurs checkout. Éviter un rollback réexposant les CVE; si aucun runtime corrigé compatible n'existe, demander une mesure de confinement à GoDaddy et Julien, sans la mettre en œuvre de sa propre initiative.

Après autorisation et intervention : remesurer Web/SAPI et correctifs, contrôler HTTP/Coming Soon, accès administratif, erreurs PHP/JS, accueil/menu/mobile et écrans Woo sans paiement/commande. Conserver les preuves non sensibles. Toute automatisation utilisant les secrets de `godaddy-temp` requiert une nouvelle approbation humaine GitHub; aucune exécution protégée déclenchée ici.

## Site commercial et restrictions

Aucun accès ni changement au site commercial. Son état PHP est inconnu : vérification distincte à demander au propriétaire/à GoDaddy, sans extrapoler depuis le temporaire. Aucun changement master, DNS, domaine, Zoho, paiements, taxes, livraison, Coming Soon ou compte. Aucun achat. Aucun déploiement ni configuration française pendant cet audit.

## Résultats enregistrés

Lecture des directives, journaux historiques et avis primaires : effectuée. Vérification Web actuelle : **bloquée**, pas réussie. Inventaire serveur frais, contrôle de compromission et validation après correctif : **non effectués**. Aucun test applicatif relancé : il ne prouverait pas la version Web distante. Aucun workflow de déploiement lancé. Issue #3 reste ouverte; reprise P1 conditionnée à la résolution de cette priorité par la Coordination.
