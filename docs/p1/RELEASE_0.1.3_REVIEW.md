# P1 0.1.3 — protocole de livraison et dossier de revue

## P1 0.1.3 — déploiement temporaire réussi après approbation humaine

**22 octobre 2026, fin du run à 23:57:15 UTC.** [Run 39873774537](https://github.com/mycomane/alimentsdistinction/actions/runs/39873774537) désormais **completed/success**, SHA livré `6163fa04069b3390a03156ae97cc44df5b0c8f20`, job protégé `120157351181`. Vérification effectuée depuis les métadonnées, l'historique d'approbation et les journaux GitHub, après réception de la capture de Julien.

L'API confirme l'approbation **mycomane / godaddy-temp**; la première étape du job la confirme avant SSH. Work n'a pas approuvé ce run. L'état Waiting décrit plus bas est historique et a été levé par cette approbation humaine.

Les journaux confirment : identité exacte du WordPress temporaire avant transfert; mesure et espace acceptés sans assouplissement; sauvegarde fraîche **privée** SQL/thème/langues/manifeste/SHA et répétition privée d'application/récupération; remplacement strict des quatre fichiers approuvés; cache d'en-tête du thème invalidé de façon ciblée; inventaire complet et état protégé vérifiés en **0.1.3**, langue **fr_CA** conservée. HTTP : quatre sources CSS/JS exactes avec version 0.1.3, accueil et quatre pages techniques répondant 200 / **Coming Soon** après mutation. Aucune récupération d'incident n'a été nécessaire.

**Limites :** il s'agit des contrôles du workflow, pas d'une recette visuelle authentifiée du rendu hébergé sur ordinateur/téléphone. Aucun compte de recette créé; aucune transaction effectuée. La sauvegarde reste sur le même hébergeur; restauration SQL globale/hors site non certifiée. La confidentialité enregistrée contient toujours un texte anglais, sujet séparé. Palette, recettes et promesses commerciales hors lot; les invariants des réglages et données protégés sont vérifiés par le job.

**Prochaine étape :** revue de ces preuves par la Coordination, puis définition de la recette visuelle hébergée dans un accès autorisé. Issue #2 laissée ouverte. Cette constatation n'autorise aucune nouvelle connexion/modification GoDaddy, création de compte ou nouvelle exécution. Les états d'attente et décisions précédents ci-dessous sont historiques.


## P1 0.1.3 — deuxième demande en Waiting / Review deployments

**22 octobre 2026 — SHA exact du run et des tests : `6163fa04069b3390a03156ae97cc44df5b0c8f20`.** [Nouvelle exécution protégée 39873774537](https://github.com/mycomane/alimentsdistinction/actions/runs/39873774537) : **waiting**, tentative 1, environnement **godaddy-temp**, reviewer humain **mycomane**. API pending_deployments confirmée; historique d'approbations vide; job GoDaddy `120157351181` en waiting, sans étape commencée. **Work s'arrête à la revue, sans approbation ni SSH ni modification GoDaddy.**

Autorisation : [deuxième accord explicite de Julien / Coordination #6353797110](https://github.com/mycomane/alimentsdistinction/issues/2#issuecomment-6353797110). Une seule nouvelle exécution protégée créée. L'ancien run échoué `39871446467` n'a pas été relancé.

| Contrôle au même SHA | Résultat |
| --- | --- |
| `prepare`, job 120154268626 | success |
| `isolated-qa`, job 120154268231 | success; vérifie la CI ciblée au SHA exact |
| [CI ciblée 39873774433](https://github.com/mycomane/alimentsdistinction/actions/runs/39873774433), targeted-copy 120154267975 | success; **62 assertions fr_CA**, 390/1440, deux états d'infolettre, avant/après, focus/survol/liens/contact, géométrie hero et quatre versions de ressources |
| Même CI, release-safety 120154267799 | success |
| PHP / commandes / paiements isolés | zéro fatal/warning/deprecated dans les deux journaux présents; zéro commande créée, aucune passerelle active |

Préparation vérifiée : syntaxe PHP/JS/CSS, ShellCheck, intégrité maquette/7 ressources, garde du périmètre complet, **17** gardes fichiers, **31** assertions de remplacement/récupération des quatre fichiers et interruptions, **4** simulations du vrai script shell, **4** cas du garde d'approbation, **46** assertions parseur/espace et **6** scénarios MySQL jetable. Aucune protection de mesure SQL ou d'espace assouplie.

**Diff du déclenchement :** [commit 6163fa04](https://github.com/mycomane/alimentsdistinction/commit/6163fa04069b3390a03156ae97cc44df5b0c8f20), **une seule ligne de commentaire remplacée** dans `.github/workflows/update-godaddy-temp-p1-013.yml`, mentionnant cette nouvelle autorisation. Aucun code exécutable du workflow, thème, test, secret, reviewer ou ancien workflow changé. La présente mise à jour documentaire ne redéclenche pas le run; la livraison reste figée au SHA ci-dessus.

Lot inchangé : `footer.php`, `front-page.php`, `style.css`, `template-parts/navigation.php`; version/cache **0.1.3**, ZIP **23 fichiers**, SHA256 **9d5dd4f86704495c71cf7f79cd8327b8d4af80ac22eacd0a62a4a8b764c4000d**. Paquet/manifeste du run protégé : artefact `12467626613`. Preuves/captures de la nouvelle CI : `12467777111`; paquet isolé : `12467755498`.

**Étapes réservées à une approbation ultérieure de Julien sur ce run exact, après revue Coordination :** vérification SSH/identité du site temporaire, sauvegarde fraîche privée SQL/thème/langues/manifestes/SHA et espace, répétition privée du rollback, puis éventuelle application et vérification/rollback 0.1.2 selon le protocole. **Aucune sauvegarde fraîche hébergée ni essai distant réalisé dans cette phase.** Aucune autorisation de déploiement inférée.

Risques/limites conservés : cache CDN, rendu authentifié GoDaddy non testé, fontes/photos externes bloquées dans la fixture, 320/768/1024 non réexécutés dans ce lot ciblé, absence de restauration SQL globale/hors hébergeur certifiée, empreinte SSH historique non confirmée indépendamment; aucune certification WCAG globale. Palette Actuel, recettes, promesses commerciales et confidentialité persistante hors lot. Coming Soon doit demeurer actif; site commercial, master, DNS, Zoho, taxes, livraison, produits, clients, commandes et paiements non modifiés.

**Prochaine décision : validation technique de la Coordination, puis décision de Julien sur le nouveau run 39873774537. Work n'approuve aucun déploiement.** Les sections suivantes conservent l'historique des étapes précédentes.


## Résultat final — préparation corrigée validée, nouvelle revue à autoriser

**22 octobre 2026 — dernier commit technique testé `ef62260133a797334ece79193bbd8b9a4ea9238d`.** [CI isolée 39872741229](https://github.com/mycomane/alimentsdistinction/actions/runs/39872741229) **completed/success**, jobs navigateur `120150854756` et sécurité `120150855096`. Cette mise à jour de documentation est postérieure au SHA testé et ne change pas le lot. [Diff depuis la base 0.1.2](https://github.com/mycomane/alimentsdistinction/compare/0771856d2251620fcf578143f353877e9cd2ca36...ef62260133a797334ece79193bbd8b9a4ea9238d).

- **62 assertions WordPress fr_CA réussies** : 58 scénarios réutilisés + quatre vérifications des URL de cache/version 0.1.3 pour design.css, style.css, commerce.css, theme.js. Avant/après, deux états d'infolettre, 390/1440; contraste après 13,3903:1 dans les quatre scénarios; hero **0 px d'écart**; focus/survol/contact/liens et absence de débordement validés.
- Zéro erreur JS; journaux PHP serveur et WordPress présents : zéro fatal, warning ou deprecated; zéro commande créée, aucune passerelle active dans la fixture.
- Sécurité/préparation vertes : ShellCheck et syntaxes, 17 gardes fichiers, 31 assertions quatre fichiers/interruptions/récupération, quatre scénarios shell avec faux transports, quatre cas du garde d'approbation, 46 assertions du parseur/espace, six scénarios MySQL jetable. Aucun garde DB/espace assoupli.
- Paquet 23 fichiers et manifeste lié au SHA, artefact `12466593717`; preuves/captures `12467500473`. [Preuve JSON durable](evidence/release-013-2026-10-22.json). Deux captures finales inspectées (infolettre ordinateur configurée et pied de page téléphone). Le thème reste strictement le patch approuvé + en-tête 0.1.3; ZIP SHA256 inchangé `9d5dd4f86704495c71cf7f79cd8327b8d4af80ac22eacd0a62a4a8b764c4000d`.

**Cause du rendu ancien reproduite dans le runner** : SAPI `cli-server`, `opcache.enable=1`, `enable_cli=0`, revalidation 2 secondes. Une lecture immédiate après remplacement renvoie OLD par défaut **et** avec enable_cli seul désactivé; `opcache.enable=0` + `enable_cli=0` renvoie NEW. La désactivation porte uniquement sur le serveur isolé de permutation des fichiers. Aucun réglage de cache GoDaddy modifié. Le signal ShellCheck a été résolu en déplaçant le PHP du shell dans son contrôleur PHP, sans supprimer SC2016.

**État de livraison : aucun GoDaddy modifié, aucun SSH, aucune approbation.** L'unique run protégé autorisé [39871446467](https://github.com/mycomane/alimentsdistinction/actions/runs/39871446467) a échoué avant revue; job deploy ignoré, liste pending_deployments vide. Il n'est **pas** en Waiting / Review deployments. Aucun deuxième run protégé ni relance créé. Les deux passages suivants sont seulement des CI isolées sans environnement ni secrets.

La directive [#6353441551](https://github.com/mycomane/alimentsdistinction/issues/2#issuecomment-6353441551) autorisait **« une seule nouvelle exécution »**. **Décision nécessaire :** revue du dossier corrigé par la Coordination, puis nouvel accord explicite de Julien pour une autre demande protégée. La future création doit modifier intentionnellement le YAML dédié, déclenchera aussi les tests ciblés au même nouveau SHA, et doit s'arrêter à Review deployments. Ne pas relancer le run échoué ni approuver le prochain run depuis Work.

Le protocole ci-dessous demeure applicable après cette future approbation seulement : sauvegarde fraîche privée SQL/thème/langues/manifestes/SHA, mesure et espace, essai de récupération, remplacement strict de quatre fichiers, cache d'en-tête ciblé, invariants/HTTP/Coming Soon et rollback 0.1.2 sans restauration SQL globale. Aucune sauvegarde fraîche hébergée revendiquée ici. Rendu hébergé authentifié, ressources externes et conformité WCAG globale non validés; recettes, promesses commerciales et confidentialité anglaise différées.


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
