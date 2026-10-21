# Recette privée — accès Abonné temporaire approuvé

**État courant :** recette P0 0.1.1 du run 39686849316 réussie, 88 contrôles, compte supprimé et sessions révoquées. Voir [le rapport final](P0_POSTDEPLOY_REVIEW_2026-10-21.md). Les résultats 33 contrôles/53 px ci-dessous concernent la version antérieure 0.1.0.

L’utilisateur a approuvé la proposition d’un compte de recette temporaire **Abonné**, avec session de 30 minutes et suppression après contrôle, sur le seul `https://1302636.us22.myftpupload.com`. Si Coming Soon empêche ce rôle de voir le thème, arrêter sans élever ses droits. Aucun nouveau droit administrateur, paiement, produit, commande, configuration commerciale, domaine ou accès au site commercial n’est inclus.

## Scénario préparé

Workflow `.github/workflows/review-godaddy-private.yml`, branche `feat/wordpress-woocommerce-theme`, environnement `godaddy-temp` avec reviewer obligatoire et clé SSH épinglée inchangés. Le lancement par push concerne uniquement ce nouveau workflow. Aucune réinstallation du thème ni répétition de l’activation WooCommerce.

1. Vérifier anonymement que l’accueil affiche toujours Coming Soon avant de provisionner l’accès.
2. Transférer uniquement les deux scripts d’opération dans un dossier privé hors document root, avec empreinte du paquet et identité exacte du WordPress vérifiées.
3. Refuser un rôle Abonné qui contient des capacités supplémentaires à `read` et `level_0`. Créer un seul utilisateur synthétique marqué par l’identifiant du run, avec mot de passe aléatoire non communiqué. Bloquer mails et requêtes HTTP WordPress pendant cette opération.
4. Créer une session native WordPress de cet utilisateur, expiration serveur à 30 minutes, et un cookie `logged_in` réservé au domaine temporaire. Aucun accès ou jeton d’un utilisateur existant n’est repris. Le cookie ne donne que les droits Abonné. Son transport se fait par SSH et sa sortie est redirigée vers un fichier privé du runner, jamais imprimée ou téléversée.
5. Accéder uniquement au frontend par GET/HEAD. Bloquer les formulaires POST, l’administration, la connexion, les actions panier/commerce et les requêtes externes sauf ressources graphiques connues. Si Coming Soon est encore visible, relever `subscriber_blocked_by_coming_soon` et arrêter les contrôles privés.
6. Si le thème est accessible, contrôler l’accueil à 320/390/768/1024/1440 px, le menu mobile, les images locales, les débordements, les quatre pages techniques et les erreurs JavaScript. Comparer les mesures des principales zones du héro à `index.html` servi uniquement sur loopback du runner. Le tri/catalogue avec produits fictifs reste couvert par l’installation isolée; aucun produit de test sur GoDaddy.
7. Publier uniquement les résultats synthétiques, compteurs et mesures de géométrie. **Aucune capture, aucun HTML authentifié, aucune donnée d’administration et aucun cookie ne sont publiés dans GitHub ou ses artefacts.** Les mesures ne constituent pas une inspection visuelle humaine ni une comparaison pixel par pixel.
8. Même si le contrôle échoue ou le rôle est bloqué, révoquer les sessions et supprimer uniquement le compte de ce run, après contrôle de son marqueur et de son rôle. Refuser une suppression si du contenu inattendu lui est attribué. Comparer les réglages protégés et nombres de produits/commandes, y compris HPOS si présent. Supprimer les fichiers sensibles du runner.

## Vérifications réalisées avant lancement distant

- Syntaxe PHP, Bash, JavaScript et YAML vérifiée.
- Dans le WordPress jetable local : création Abonné, validation effective du cookie natif et de sa durée, absence de droit d’édition, révocation, suppression, rejet du cookie après nettoyage et maintien du baseline protégés vérifiés avec succès.
- L’accès Abonné derrière Coming Soon sur GoDaddy n’est pas encore confirmé. La prochaine exécution protégée doit le déterminer.

## Limites et récupération

- L’approbation GitHub de l’environnement reste obligatoire, indépendamment de l’autorisation métier déjà reçue. Aucun secret n’est demandé dans la conversation.
- Le nettoyage est configuré avec `always()` après le provisionnement. Une perte totale du runner, une coupure SSH ou un arrêt brutal peuvent néanmoins empêcher la suppression du compte. La session expire côté serveur après 30 minutes; le mot de passe aléatoire n’est pas communiqué. Le fichier privé `~/.ad-private-qa/review-<run>.json` permet un nettoyage ultérieur par le même environnement protégé. Ne pas relancer aveuglément la création avec le même run.
- Aucune sauvegarde complète supplémentaire n’est créée : l’unique mutation approuvée est un accès de test réversible. La sauvegarde préactivation WooCommerce et ses limites sont documentées séparément.
- Si un réglage ou un nombre de contenus change concurremment, le scénario le signale sans restaurer ni écraser les modifications d’autrui.
- Le compte n’est pas élevé si le rôle Abonné est insuffisant. Aucune exclusion de Coming Soon, aucun lien public de contournement, aucune nouvelle extension, aucun changement DNS ou publication du site.
- La clé SSH reste celle épinglée dans la passation, sans nouvelle confirmation indépendante auprès de GoDaddy.

## État

Préparation et tests isolés terminés. Exécution distante et nettoyage sur GoDaddy **à confirmer par les résultats GitHub Actions**, pas encore déclarés réussis.

## Premier essai distant : arrêt avant toute mutation

Exécution `39528838153`, job `118989765201` : échec à l’étape de vérification anonyme de Coming Soon, après environ 45 secondes. Le contrôle n’a enregistré aucune assertion. Le transfert des scripts, le provisionnement et les contrôles privés ont tous été ignorés; **aucun compte n’a été créé par cette exécution** et aucun nettoyage de compte n’était donc nécessaire. La vérification de la clé SSH a réussi. L’exécution isolée parallèle `39528838044` a réussi.

Le délai observé correspond au timeout de navigation de 45 secondes. Le premier code masquait l’exception, donc il ne permet pas d’affirmer si la cause précise était l’attente `networkidle` ou un problème de connexion. La reprise attend désormais `domcontentloaded` pour les contrôles anonymes, tout en conservant les contrôles HTTP 200, origine exacte et texte Coming Soon. Elle n’attend plus que toutes les connexions annexes deviennent inactives pour ces contrôles. Les attentes des pages privées ne changent pas. Des catégories d’erreur fixes et une étape connue sont journalisées sans exception brute, URL privée ou cookie. Ce changement concerne uniquement le test; son effet doit être vérifié par une nouvelle exécution protégée.

## Résultat de la reprise — 21 octobre 2026

[Exécution 39531003568](https://github.com/mycomane/alimentsdistinction/actions/runs/39531003568), job `118996613341`, commit `765a36708e61d31331754f391b78553916cdd06b` : **succès confirmé**. Le contrôle anonyme avant provisionnement, la création du compte Abonné, les contrôles frontend et le nettoyage ont tous réussi. L’exécution isolée parallèle [39531003567](https://github.com/mycomane/alimentsdistinction/actions/runs/39531003567) a également réussi.

### Ce qui a réellement été vérifié sur GoDaddy

- Le rôle Abonné a permis de voir le thème derrière Coming Soon, sans élévation de droits.
- **33 assertions frontend réussies**, en plus du contrôle anonyme préalable au provisionnement. Aucune erreur JavaScript `pageerror` dans les scénarios.
- Accueil contrôlé aux largeurs **320, 390, 768, 1024 et 1440 px** : thème visible en session privée, aucun débordement horizontal, aucune image locale cassée détectée. Le compteur d’images externes cassées est aussi à zéro à ces cinq largeurs; cela n’est pas un audit des droits ni de la disponibilité future des ressources externes.
- Menu mobile : ouverture et fermeture par Échap vérifiées à 320/390/768 px.
- Catalogue, panier, commande et compte : réponses HTTP 200 sur l’origine temporaire, accessibles en session privée, sans débordement à 390 px.
- **Aucun formulaire de commande n’a été rendu dans ce scénario** (`checkoutFormCount=0`), cohérent avec un panier vide et un possible renvoi vers le panier. Le test ne relève pas l’URL finale exacte de la page de commande et ne prouve pas sa destination précise. Aucun produit, ajout au panier ou passage de commande n’a été exécuté sur GoDaddy.
- Coming Soon confirmé anonymement avant et après les contrôles privés.
- Journaux de nettoyage : **compte Abonné supprimé et sessions révoquées**, réglages protégés et nombres de produits/commandes inchangés. Les fichiers sensibles du runner ont été supprimés. Les scripts et l’état de suivi non authentifiant restent privés hors document root.
- Aucun déploiement du thème, paiement, modification commerciale, DNS, domaine ou opération Zoho.

### Mesures comparées à la maquette officielle

Les mesures du titre du héro et de ses boutons (position, dimensions, taille de police, famille déclarée et couleur) correspondent exactement à `index.html` aux cinq largeurs testées. Les principales zones du héro mesurées correspondent aussi à 768, 1024 et 1440 px.

À **320 et 390 px**, le héro et sa zone de texte sont **53 px plus hauts** que la référence; la photo commence donc 53 px plus bas. Le titre et les boutons conservent leurs mesures. Le thème ajoute `flex-wrap: wrap` à `.hero__trust` sur petit écran, ce qui constitue une piste pour cet écart; le test ne mesure pas individuellement les indicateurs de réassurance et ne prouve donc pas encore la cause. Aucun correctif CSS n’a été déployé.

Cette recette est une vérification automatisée du rendu réel et de mesures ciblées. **Aucune capture authentifiée n’a été exportée ni examinée manuellement**; ce n’est pas une comparaison pixel par pixel ni une validation de chaque section de la page. Les tests transactionnels avec produits simples, variations, rupture et quantités restent ceux de l’environnement isolé.

### Prochaine étape utile

Analyser en environnement isolé le bloc de réassurance mobile et préparer, si nécessaire, une correction qui conserve lisibilité et absence de débordement. Compléter la recette des sections et les points de contenu/contraste déjà identifiés. Tout nouveau déploiement du thème reste soumis à l’approbation explicite du responsable et aux protections GitHub. Aucune nouvelle création de compte n’est nécessaire pour analyser le code ou les données déjà relevées.

## Nouvelle autorisation — recette P0 après déploiement 0.1.1

Le 21 octobre 2026, Julien a répondu **« oui »** à la demande explicite d'un nouvel Abonné de test temporaire 30 minutes, consultation uniquement, puis suppression et révocation de session. Cet accord concerne cette recette post-déploiement; aucune permission P1/palette, produit, commande ou configuration commerciale n'en découle.

La version **0.1.1 est déjà déployée** par le run 39685183348; ne pas la redéployer. Le workflow `review-godaddy-private.yml` est actualisé pour une nouvelle exécution soumise à son propre reviewer `godaddy-temp`, avec historique d'approbation du run exact contrôlé. Préparation sans secrets : syntaxe et six scénarios géométriques. Avant création : origine temporaire/home, thème actif, inventaire complet des 23 fichiers 0.1.1, version et WooCommerce 11.2.1.

Le compte a uniquement le rôle Abonné (capacités read/level_0 contrôlées), mot de passe aléatoire jamais communiqué, aucune session d'utilisateur existant. Token natif valable au plus 30 minutes; pas de notification mail/HTTP de provisioning. Le navigateur ne peut consulter que le frontend autorisé via GET/HEAD; formulaires, administration et actions panier bloqués. Aucun rôle élevé si Coming Soon bloque le compte.

### Vérifications nouvelles

- HTML authentifié : requête de `style.css?ver=0.1.1`; réponse CSS sans redirection et SHA256 exact du fichier déployé.
- Largeurs 320/390/768/1024/1440 : six zones du héro, dont réassurance, comparées à `index.html` avec tolérance de 1 px, indicateurs sur trois colonnes sans découpe/chevauchement, absence de débordement et images locales chargées.
- Ancres de l'accueil; menu mobile Enter/Échap et restitution du focus; quatre pages techniques sans action commerciale. Destination finale de page classée seulement par clé technique connue, aucun slug ou paramètre privé arbitraire exporté.
- Coming Soon anonyme avant/après, erreurs JavaScript comptées sans message brut.
- Rapport limité aux géométries et compteurs : **aucune capture/HTML authentifié/cookie** publié.
- Nettoyage `always()` : révocation et suppression du seul compte marqué par le run, vérification des réglages et compteurs protégés; suppression des fichiers secrets du runner. Expiration de session 30 minutes en protection résiduelle si panne empêchant le nettoyage; pas de garantie de suppression en cas d'arrêt brutal.

### Tests préparatoires réellement exécutés

- Six scénarios du comparateur : référence correcte, anomalie +53 px détectée, découpe, chevauchement, zone absente et tolérance subpixel : réussis localement.
- Cycle Abonné testé dans WordPress/WooCommerce **isolé** avec thème 0.1.1 : cookie valide avec rôle seul et durée 30 minutes, révocation/suppression, refus de l'ancien cookie et baseline commercial stable : réussis. Session synthétique locale supprimée; aucune opération GoDaddy dans ce test.
- Syntaxe PHP/Bash/JS/YAML et présence de la dépendance/du nettoyage obligatoire vérifiées localement. ShellCheck/tests GitHub et exécution hébergée restent à confirmer par le nouveau run.

La nouvelle approbation GitHub sera l'étape bloquante avant tout provisionnement GoDaddy. L'issue #1 et PROJECT_STATUS.md recevront le lien et le résultat; aucun succès hébergé anticipé.

### Préparation GitHub confirmée — nouvelle recette P0

- Commit [`6b3031a74792827836d3bab57d181896b7d15d40`](https://github.com/mycomane/alimentsdistinction/commit/6b3031a74792827836d3bab57d181896b7d15d40).
- [Run recette 39686849316](https://github.com/mycomane/alimentsdistinction/actions/runs/39686849316) : préparation `119522989146` réussie, ShellCheck/syntaxe/six scénarios géométriques.
- Job distant `119523045267` : **waiting**, reviewer `mycomane` pour `godaddy-temp`. Aucun compte distant encore créé.
- L'autorisation de Julien pour ce nouvel Abonné est enregistrée; la **nouvelle revue GitHub reste obligatoire**, puis recette et nettoyage `always()`. Aucun redéploiement.
- QA isolé automatique **39686849309 / 119522988346 : succès**, 300 assertions et zéro fatal/avertissement/dépréciation PHP compté. Le résultat de la recette GoDaddy elle-même reste en attente d’approbation/exécution.

## Résultat final de la nouvelle recette P0 — succès et nettoyage

[Run 39686849316](https://github.com/mycomane/alimentsdistinction/actions/runs/39686849316), job `119523045267`, commit `6b3031a74792827836d3bab57d181896b7d15d40`, terminé le 21 octobre à 18 h 34 HAE : **succès après approbation**.

**88 contrôles frontend réussis** et zéro `pageerror`. Géométrie réelle des six zones du héro identique aux mesures de référence aux cinq largeurs (écart maximal 0,00 px). CSS demandé/reçu 0.1.1 et SHA exact; réassurance, images locales, débordements, ancres, menu/clavier/focus et pages techniques réussis. Checkout avec panier vide aboutit au panier; aucune commande ni action panier.

Coming Soon anonyme avant/après confirmé. Nettoyage réussi : **Abonné supprimé, sessions révoquées**, réglages et compteurs produits/commandes inchangés, fichiers sensibles du runner supprimés. Aucun compte actif conservé; pas de capture/HTML/cookie authentifié publié. Aucun redéploiement pendant cette recette.

[Rapport final](P0_POSTDEPLOY_REVIEW_2026-10-21.md) et [preuve structurée](evidence/p0-hosted-review-result.json). La recette est automatisée et ciblée; pas d'inspection manuelle pixel par pixel ni parcours transactionnel hébergé complet. Les demandes d'approbation « en attente » ci-dessus sont historiques et satisfaites pour ce run.
