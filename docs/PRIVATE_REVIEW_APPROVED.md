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
