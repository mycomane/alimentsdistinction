# Recette privée — accès Abonné temporaire approuvé

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
