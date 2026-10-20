# Activation WooCommerce — portée approuvée

L’utilisateur a approuvé le 20 octobre 2026 l’activation de WooCommerce 11.2.1 et la préparation de ses quatre pages techniques sur `https://1302636.us22.myftpupload.com`, après contrôle du PHP web et sauvegarde fraîche. Il ne s’agit pas d’une autorisation de publication publique, de produits, clients, commandes, paiements, taxes, devise, livraison, Zoho ou site commercial.

## Exécution

Workflow : `.github/workflows/enable-woocommerce-temp.yml`, sur la seule branche `feat/wordpress-woocommerce-theme`, derrière le reviewer existant de `godaddy-temp`. Son déclencheur `push` porte uniquement sur ce nouveau workflow. Les secrets existants ne sont jamais imprimés ou copiés dans le dépôt. La clé SSH épinglée est conservée; son absence de confirmation indépendante auprès de GoDaddy demeure une limite de la passation.

1. Vérifier l’identité WordPress, le thème actif et le statut inactif de WooCommerce. Refuser une répétition aveugle si le scénario a déjà commencé.
2. Confirmer Coming Soon sur une requête anonyme, sans cookie, sans suivre de redirection.
3. Capturer les affectations de pages et les valeurs de réglages protégés dans un état privé hors du répertoire public. Refuser l’activation si une passerelle connue est déjà activée dans ses options enregistrées.
4. Créer une nouvelle archive privée du site et un export SQL, vérifier les formats et SHA256. Conserver le paquet sur l’hébergement, hors du répertoire public. Pas d’export de base dans GitHub.
5. Contrôler le PHP web avec un fichier PHP de diagnostic temporaire, au nom aléatoire, ne renvoyant que la version, le SAPI et une liste limitée d’extensions. Ce n’est ni `phpinfo`, ni un compte, ni un accès WordPress. Le fichier expire après 120 secondes et est supprimé après contrôle ou lors de la sortie normale/en erreur. Une interruption brutale du processus peut laisser un fichier expiré à retirer lors du suivi. Aucun contenu, réglage, secret ou donnée client n’est renvoyé.
6. Activer WooCommerce et laisser son installation native créer les tables/options/rôles nécessaires. Limiter la création de pages aux quatre pages approuvées, sans générer de politique commerciale ou de contenu juridique. Réutiliser une page publiée compatible si elle existe; ne jamais écraser son contenu. Les nouvelles pages utilisent les shortcodes classiques déjà testés.
7. Vérifier les quatre affectations, le thème, les réglages protégés existants, l’absence de passerelle enregistrée active et la stabilité des nombres de produits/variations/commandes traditionnelles.
8. Vérifier Coming Soon sur l’accueil et les quatre pages par des requêtes anonymes. Aucune modification de l’option de visibilité.

## Retour arrière et limites

En cas d’échec après début d’activation : désactiver WooCommerce, restaurer les affectations et les réglages protégés initialement présents, mettre à la corbeille uniquement les pages créées et suivies par le scénario. Ne pas supprimer de pages existantes. La réactivation de l’ancien thème n’est pas nécessaire car ce scénario ne change pas le thème.

Ce retour arrière ne retire pas toutes les tables, options par défaut, tâches ou rôles de l’installation native WooCommerce. La restauration SQL complète est une opération distincte, nécessitant une décision de récupération explicite et la protection des changements concurrents. Le paquet de sauvegarde préparé fournit la base de cette récupération, mais une restauration complète n’a pas été testée. Ce n’est pas une sauvegarde hors site ni une garantie contre la perte totale de l’hébergement.

Les nouveaux réglages par défaut intrinsèques à l’activation ne sont pas une validation commerciale. L’assistant de configuration n’est pas exécuté. Aucun produit, client ou commande n’est créé par les scripts.

## Contrôles avant lancement

- Syntaxe PHP et Bash, YAML et scripts incorporés validés.
- Sur WordPress jetable 7.1.3 / WooCommerce 11.2.1 / PHP 8.3.6 : capture d’état, activation, création/affectation de quatre pages, vérification des réglages et des passerelles, retour arrière des affectations et mise à la corbeille des quatre nouvelles pages testés avec succès. Les pages préexistantes ont été conservées.
- Ce test isolé ne remplace pas les préconditions web et la sauvegarde vérifiées lors de l’exécution GoDaddy.
- Aucun fichier du thème n’est déployé par ce workflow.
