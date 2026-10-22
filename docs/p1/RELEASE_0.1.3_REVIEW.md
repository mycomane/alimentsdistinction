# P1 0.1.3 — protocole de livraison et dossier de revue

## Incident préalable à la revue

Le run protégé [39871446467](https://github.com/mycomane/alimentsdistinction/actions/runs/39871446467), SHA `ad4921dc7917bf225c4d4d388b7c2abb9770e79f`, a échoué avant approbation; job mutatif ignoré. SC2016 détecte deux fragments PHP shell (citation littérale intentionnelle); ceux-ci sont déplacés dans le contrôleur PHP du lot, sans désactiver ShellCheck. La recette de permutation avant/après a aussi rendu l'ancien panneau après installation des nouveaux fichiers : ajout d'une sonde HTTP PHP pour observer le cache d'opcodes, et désactivation explicite de celui-ci **uniquement pour le serveur de test isolé**. Aucune configuration GoDaddy changée.

Une CI sans environnement, SSH ou secrets réexécute les tests corrigés, y compris le job de préparation et MySQL jetable. **Aucun deuxième run protégé autorisé ou créé, aucune relance de 39871446467.** Après preuves vertes, la Coordination devra valider puis obtenir l'autorisation d'une nouvelle demande de revue. Le protocole ci-dessous décrit la future opération, pas une opération effectuée ou actuellement en attente d'approbation.


Autorisation : [Coordination / Julien #6353441551](https://github.com/mycomane/alimentsdistinction/issues/2#issuecomment-6353441551), 22 octobre 2026. Préparation et **une seule nouvelle demande de revue** autorisées; aucune approbation ni opération GoDaddy par Work. Le run et son SHA exact seront consignés dans PROJECT_STATUS.md après la CI.

## Lot exact

Base du thème hébergé 0.1.2 : `0771856d2251620fcf578143f353877e9cd2ca36`. Octets P1 acceptés : `4544ead988c2aeb3ded1f33520c8ecb8d2791d00`; seule différence supplémentaire dans le thème : `Version: 0.1.2` → `Version: 0.1.3` dans style.css. functions.php utilise déjà cette version pour design.css, style.css, commerce.css et theme.js; aucun changement de functions.php nécessaire.

Fichiers remplacés exclusivement :

- `footer.php`;
- `front-page.php`;
- `style.css`;
- `template-parts/navigation.php`.

Paquet déterministe : `aliments-distinction-0.1.3.zip`, racine unique `aliments-distinction/`, **23 fichiers**, empreinte SHA256 `9d5dd4f86704495c71cf7f79cd8327b8d4af80ac22eacd0a62a4a8b764c4000d`. `p1-013-manifest.json` contient inventaires SHA256 complets ancien/nouveau, site exact, version et commit source. Le générateur refuse inventaire supplémentaire/manquant, changement non approuvé et ZIP différent. Les 19 autres fichiers doivent rester identiques à la base hébergée.

Palette Actuel, maquette/CSS officiel, images, boutons d'achat, recettes et promesses commerciales inchangés. Confidentialité anglaise enregistrée exclue. Aucun tag ni release publié.

## Séquence et protections

Nouveau workflow `.github/workflows/update-godaddy-temp-p1-013.yml`, déclenché uniquement par un push modifiant ce fichier sur la branche autorisée. Les futures mises à jour documentaires ne le redéclenchent pas. Concurrence commune `godaddy-temp-site-mutation`, sans annulation automatique.

Avant revue, sans secret ni connexion hébergeur : syntaxe, paquet/périmètre, tests fichiers/rollback, orchestration avec faux transports, mesure SQL réutilisée sur MySQL jetable, puis attente de la CI ciblée fr_CA réussie **au même SHA exact**. Pas de nouvelle suite de 300 assertions : seules les attentes historiques d'infolettre dans sections-audit.cjs sont adaptées; son exclusion push ciblée évite de relancer cette suite pour ce seul entretien. workflow_call et workflow_dispatch de la suite complète restent disponibles et inchangés. La recette ciblée normalise uniquement l'en-tête 0.1.3 pour comparer les empreintes P1 approuvées; le nouveau générateur vérifie les octets bruts 0.1.3 et l'inventaire complet.

Job de livraison : `needs: [prepare, isolated-qa]`, `environment: godaddy-temp`. **Arrêt obligatoire à Waiting / Review deployments**. Work ne clique jamais Approve and deploy. Même si les règles d'environnement étaient absentes, la première étape refuserait tout SSH faute d'approbation enregistrée de l'utilisateur humain `mycomane` pour godaddy-temp sur ce run. Réexécution refusée (`GITHUB_RUN_ATTEMPT=1`). SHA du checkout égal à GITHUB_SHA; aucun téléchargement depuis une branche mutable dans la livraison.

Après approbation uniquement, le job prévu :

1. Vérifie la clé SSH épinglée avant authentification, puis home/siteurl exacts du temporaire avant transfert privé. Aucune substitution silencieuse de clé.
2. Prend verrou distant, refuse état de run existant, symlinks/hardlinks/inventaire inconnu, contrôle version précédente 0.1.2, fr_CA, WordPress/WooCommerce/pages attendus et absence de passerelle active.
3. Mesure la base par la procédure corrigée déjà éprouvée; aucune tolérance/relaxation ajoutée. Espace calculé avec thème **et langues**, marge conservée. Arrêt avant remplacement si insuffisant.
4. Sauvegarde fraîche privée hors racine publique : SQL gzip, thème 0.1.2 complet, langues, manifeste, état protégé et SHA256SUMS. Vérifie archives, SHA et copie des langues contre l'état capturé. **Aucune sauvegarde fraîche distante n'a été faite pendant la préparation.**
5. Extrait le thème en privé, répète application puis récupération des quatre fichiers; contrôle le manifeste complet et préserve la sauvegarde originale.
6. Remplace quatre fichiers par rename atomique par fichier, journalisé (pas transaction globale); invalide seulement la clé de cache d'en-tête du thème.
7. Contrôle inventaire complet, version 0.1.3, fr_CA/langues, options protégées, pages, inventaires produits/commandes/utilisateurs/tables commerciales. HTTP sans redirection : CSS officiel, CSS thème, CSS commerce et JS avec `ver=0.1.3` et empreintes exactes; Coming Soon anonyme sur accueil et quatre pages techniques.
8. En cas d'échec après armement, tente récupération exacte de la version 0.1.2 à partir de copies privées vérifiées, cache ciblé, inventaire/état protégé/HTTP/Coming Soon. Refuse écrasement d'un changement tiers inconnu. Aucune restauration SQL globale automatique. Coupure brutale/SIGKILL peut nécessiter intervention humaine sur journal privé.

## Tests et limites

Préparation locale réussie : validation thème/PHP/JS/design/7 ressources, paquet 23 fichiers, 31 assertions quatre fichiers (interruption après chacun des quatre remplacements et rollback), quatre scénarios du vrai script shell avec transports WP/HTTP simulés (succès, erreurs HTTP/cache/Coming Soon), quatre réponses simulées du garde d'approbation (absence, mauvais reviewer, mauvais environnement, approbation correcte). La CI est la preuve finale sur le SHA publié; résultats et lien à compléter après exécution. Les simulations n'établissent pas une restauration SQL réelle ou une compatibilité complète GoDaddy.

La recette navigateur réutilise les 58 assertions WordPress fr_CA à 390/1440, avant/après et deux états d'infolettre. En cas d'écart/débordement, diagnostiquer avant tout nouvel essai; 320/768/1024 supplémentaires si justifié. Ressources externes bloquées, mouvement réduit : aucune validation nouvelle des fontes/photos externes ou certification WCAG globale. Le test local de liens ne soumet aucune inscription réelle. Aucun compte, commande ou paiement hébergé créé.

Coming Soon empêche la recette visuelle anonyme du thème sur GoDaddy. Le protocole présent ne crée aucun compte de recette et ne contourne pas cette protection : contrôle mobile/ordinateur **isolé** requis; rendu hébergé authentifié demeure à vérifier via une session autorisée distincte. Aucune réussite hébergée n'est inférée des HTTP Coming Soon ou des sources statiques.

Sauvegardes conservées sur le même hébergeur, pas de restauration SQL globale ni reprise après perte d'hébergeur certifiées. Empreinte SSH épinglée historique non confirmée indépendamment auprès de GoDaddy. Cache CDN obsolète provoque arrêt/récupération, aucune purge globale ou modification commerciale introduite.

Recettes par produit reportées; promesses commerciales à revoir avec Jean-François et Meggy avant ouverture; confidentialité et partie anglaise séparées. Aucun site commercial, DNS, master, Zoho, paiement, taxe, livraison ou donnée client modifié.
