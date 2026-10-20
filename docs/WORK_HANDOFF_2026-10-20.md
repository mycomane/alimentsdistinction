# Passation à ChatGPT Work — Aliments Distinction
_Mise à jour : 20 octobre 2026. Source de vérité : dépôt GitHub et exécutions GitHub Actions citées. Document de coordination, non un ordre de déploiement._

## Mission et limites

Construire le **nouveau** site WordPress + WooCommerce Aliments Distinction **exclusivement depuis la maquette du dépôt** (`index.html` et ressources liées). L'ancien `alimentsdistinction.com` n'est ni source de design ni environnement à modifier.

- Dépôt : `mycomane/alimentsdistinction`.
- Branche de travail et destination GitHub exclusive : `feat/wordpress-woocommerce-theme`.
- Branche `master` : **ne pas modifier, fusionner, ni déployer**.
- WordPress temporaire autorisé : `https://1302636.us22.myftpupload.com/`.
- Le serveur d'hébergement appelle ce WordPress temporaire « Production Site », mais **ce n'est pas** le site commercial.
- Le site commercial `alimentsdistinction.com`, ses domaines et DNS doivent rester inchangés.
- Pas de migration depuis l'ancien site, pas de publication publique du nouveau site, pas de « Launch My Site » sans autorisation expresse.
- Pas de paiement réel ni modification des taxes, livraisons, devises, produits commerciaux, commandes ou données clients.
- Zoho Inventory : intégration prévue ultérieurement, **ne pas connecter ni modifier**.
- Aucun achat, extension payante, nouvel identifiant ou nouveau service sans accord.
- Demander l'approbation utilisateur avant toute autre configuration commerciale majeure ou déploiement non encore autorisé.
- Ne jamais coller, lire dans les journaux, imprimer, committer ou transmettre les secrets SSH/SFTP.
- Ne pas prétendre que des actions ou tests non réellement exécutés sont terminés.

## État du site vérifié

### Développement GitHub
- Thème personnalisé **Aliments Distinction 0.1.0**, dans `wordpress/aliments-distinction/`.
- Source visuelle `index.html`; composants accueil et préparatifs WooCommerce (catalogue, fiches, panier, commande), styles/images et navigation mobile.
- Validation du thème : `scripts/check-theme.py`.
- Packaging ZIP : `scripts/package-theme.py`.
- Documentation : `docs/VALIDATION.md`, `docs/WORDPRESS.md`.
- Le thème 0.1.0 a été développé initialement au commit `cf17c10ff102f10806a4ca99d6e35e94af769556`. Des commits ultérieurs de workflow, sans changement du design du thème, figurent sur la branche.

### Hébergement GoDaddy — audit réel via SSH/WP-CLI
- URL du site temporaire vérifiée depuis l'option WordPress `siteurl`.
- WordPress **7.1.3**.
- PHP CLI **8.2.34**.
- Thème avant migration : `twentytwentyfive` 1.5.
- WooCommerce **11.2.1 installé, mais inactif** à la date du dernier audit.
- GoDaddy Managed WordPress Ultimate.
- Connexion SSH réussie via GitHub Actions et environnement approuvé `godaddy-temp`.
- Le navigateur cloud de Work et celui utilisé séparément ont rencontré des erreurs réseau ; **ne plus dépendre de ce navigateur pour les tâches automatisables**.

### Sauvegarde privée effectuée (avant installation du thème)
- Exécution réussie : https://github.com/mycomane/alimentsdistinction/actions/runs/39504070900
- Dossier privé relatif au home distant : `~/.ad-private-backups/ad-temp-20261020-215554/`, hors du `document root` public.
- Éléments générés : `site-files.tar.gz`, `database.sql.gz`, `SHA256SUMS`, `RESTORE-READ-ME.txt` ; configuration parent incluse si applicable.
- Tar, gzip et checksums vérifiés pendant la génération et revérifiés par le workflow d'installation.
- **Limites : restauration non exécutée/testée, sauvegarde conservée sur le même hébergement, chiffrement au repos non confirmé, pas d'archive hors site certifiée.** Elle ne garantit pas une reprise après panne totale de l'hébergement.
- Une réactivation de l'ancien thème par WP-CLI est possible ; elle ne remplace pas une restauration complète.

### Installation et activation réalisées sur le seul site temporaire
- Exécution : https://github.com/mycomane/alimentsdistinction/actions/runs/39504562223
- **Succès confirmé dans les journaux** : ZIP installé, thème `aliments-distinction` activé, `wp eval` a chargé le thème sans erreur fatale et `siteurl` est restée l'URL temporaire.
- Workflow prévoyait réactivation automatique de `twentytwentyfive` en cas d'échec du scénario testé ; aucun retour arrière n'a été nécessaire.
- WooCommerce n'a pas été activé pendant cette opération. Aucun paiement, DNS, domaine, Zoho ni site commercial modifié par le workflow.

### Contrôle visuel / visibilité
- L'utilisateur a fourni une capture **authentifiée** de l'accueil WordPress temporaire où le **nouveau thème est visible** : logo, photo héro avec pièces de viande, typographies, couleurs, barre de navigation et boutons apparaissent.
- Le bandeau admin indiquait **« Your website is in Coming Soon mode »**. **Conserver cet état** ; pas de publication publique par « Launch My Site ».
- Contrôle HTTP non authentifié : https://github.com/mycomane/alimentsdistinction/actions/runs/39505088728 : HTTP 200 mais réponse de type Coming Soon, sans chemins du thème visibles dans le HTML public. Résultat attendu tant que l'accès public est protégé ; **cela n'est pas un test réussi du rendu public**.
- Tests visuels interactifs complets, responsive/mobile, navigation réelle et tunnels WooCommerce sur cet hébergement **restent à valider**. La capture bureau est un premier contrôle visuel, pas une recette exhaustive.

## GitHub Actions opérationnelles

Toutes les opérations sensibles se font via la branche de développement, et celles qui utilisent GoDaddy via l'environnement `godaddy-temp` dont l'utilisateur `mycomane` est reviewer. Ne pas désactiver les protections.

- `.github/workflows/check-godaddy-network.yml` — vérification DNS et port 22 sans authentification. Réussi : https://github.com/mycomane/alimentsdistinction/actions/runs/39499382586
- `.github/workflows/build-wordpress-theme.yml` — lint et ZIP WordPress, archive GitHub Actions. Réussi : https://github.com/mycomane/alimentsdistinction/actions/runs/39499524228
- `.github/workflows/verify-godaddy-ssh-login.yml` — authentification SSH sans commande modificatrice après contrôle de clé SSH épinglée. Réussi : https://github.com/mycomane/alimentsdistinction/actions/runs/39502897891
- `.github/workflows/audit-godaddy-wordpress.yml` — WordPress/PHP/thème/WooCommerce en lecture seule par SSH/WP-CLI. Réussi : https://github.com/mycomane/alimentsdistinction/actions/runs/39503401727
- `.github/workflows/backup-godaddy-temp.yml` — sauvegarde privée fichiers+SQL avec sommes de contrôle. Réussi : https://github.com/mycomane/alimentsdistinction/actions/runs/39504070900
- `.github/workflows/install-godaddy-temp-theme.yml` — installation/activation 0.1.0, garde-fous, tentative de rollback sur erreur. Réussi : https://github.com/mycomane/alimentsdistinction/actions/runs/39504562223
- `.github/workflows/smoke-test-temporary-site.yml` — contrôle HTTP public sans authentification ; site répond mais affiche Coming Soon. Réussi au sens du job uniquement : https://github.com/mycomane/alimentsdistinction/actions/runs/39505088728

**Sécurité des accès**
- Secrets enregistrés dans **l'environnement GitHub `godaddy-temp`** : `GODADDY_SSH_USERNAME` et `GODADDY_SSH_PASSWORD`; ne jamais demander à l'utilisateur de les copier dans le chat.
- GitHub Actions a une clé SSH ED25519 épinglée issue d'une première observation réseau ; **son empreinte n'a pas été indépendamment confirmée auprès de GoDaddy**. Vérifier par une source de confiance avant de considérer la chaîne comme hautement sécurisée.
- L'utilisateur a configuré une restriction de branche et les `Required reviewers`. Conserver l'approbation obligatoire.
- Attention : un workflow configuré sur événement `push` peut **redemander une approbation** après modification du YAML ; éviter des déclenchements non souhaités.
- Les workflows d'installation existants ont été conçus pour ne pas écraser un thème déjà installé ; **ne pas relancer aveuglément le premier workflow d'installation après l'activation réussie**.

## Suite de mission recommandée à Work

1. Prendre cette passation comme état de départ ; **ne pas réinstaller le thème 0.1.0** et ne pas redémarrer les opérations déjà validées.
2. Vérifier par des contrôles **en lecture seule** que `aliments-distinction` demeure actif et que WooCommerce est toujours inactif. Signaler toute divergence.
3. Produire un **plan de recette concret** : design fidèle à `index.html`, mobile/tablette, navigation, images, formulaires/CTA, accessibilité, catalogue, variations, inventaire de test, panier et tunnel de commande. Décrire explicitement les tests réalisables sans lever Coming Soon.
4. Pour les vérifications que Work ne peut pas faire dans son navigateur, utiliser GitHub connector / GitHub Actions et WP-CLI via `godaddy-temp` plutôt que redemander des copier-coller répétitifs. Limiter les changements aux scripts et à la branche de développement. Aucun accès non approuvé à GoDaddy.
5. Déterminer si **l'activation de WooCommerce** est compatible avec la version 0.1.0 et si elle impose des changements annexes (pages, base, onboarding, taxes, moyens de paiement). **Présenter les paramètres et demander une approbation explicite avant activation/configuration majeure**. N'activer aucun paiement.
6. Étudier une **stratégie d'aperçu privé et de test sur le WordPress temporaire** qui préserve Coming Soon, sans modifier DNS, site live ou restriction de visibilité.
7. Pour toute future modification du thème : travail GitHub limité à `feat/wordpress-woocommerce-theme`, validations automatiques, sauvegarde fraîche et restauration/rollback compris, approbation du responsable avant déploiement. Aucune fusion vers `master`.
8. Fournir un état d'avancement factuel en français, distinguer vérifié / à confirmer / approbation nécessaire, et proposer l'action la plus utile sans faire de l'utilisateur un intermédiaire entre outils.

## Limite de la passation

Ce fichier **ne donne aucune permission supplémentaire** par rapport aux autorisations explicites déjà formulées par l'utilisateur. Il n'autorise pas l'activation de WooCommerce, l'ouverture du site au public, une migration, un déploiement sur le site commercial ni un achat.
