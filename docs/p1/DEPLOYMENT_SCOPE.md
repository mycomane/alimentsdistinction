# P1 — périmètre proposé pour une livraison temporaire

**21 octobre 2026 — autorisation distincte de Julien reçue dans Work.** La préparation du nouveau mécanisme et le déploiement temporaire limité ci-dessous sont autorisés, sous réserve d’une nouvelle approbation GitHub pour le run exact. Version 0.1.2 préparée et tests locaux réussis; déploiement pas encore effectué.

Historique du périmètre présenté à Julien : Julien a validé l’aperçu et demandé de continuer. Cela confirme l’aspect des contrôles; une autorisation distincte de déploiement et la revue GitHub du run exact restent nécessaires conformément à l’issue #2. Aucun workflow protégé n’est modifié ou lancé dans cette phase.

## Résultat à livrer

Conserver « Actuel », palette de Jean-François et Meggy. Livrer uniquement l’apparence A du bouton d’ajout au panier de la fiche produit (actif, survol et désactivé) et la bordure du sélecteur de variation. Aucune modification de navigation, accueil, infolettre, images ou palette globale.

Code graphique testé : `206972b1ab2228b17b6131c66a9475e7f085f3da`. Recette isolée `39692488831` réussie avec 45 contrôles français supplémentaires; build `39692489014` réussi. L’aperçu de ce code est validé par Julien dans Work. Les changements de suivi seuls n’altèrent pas ce code.

Pour une livraison ultérieure, incrémenter le thème de **0.1.1 à 0.1.2** afin de renouveler les URLs des ressources. Les deux seuls fichiers de thème admis dans le futur manifeste seront :

| Fichier | Changement permis |
| --- | --- |
| `assets/css/commerce.css` | Bloc CSS ciblé déjà testé et validé visuellement |
| `style.css` | Ligne `Version: 0.1.1` → `Version: 0.1.2`, aucun autre changement |

Le numéro 0.1.2 est un **projet de livraison**, pas une version actuellement installée ou déjà empaquetée. Ne pas transmettre l’archive de préparation 0.1.1 comme une nouvelle release.

## Protections à conserver avant toute opération distante

1. Autorisation explicite de Julien pour cette livraison, puis préparation d’un nouveau mécanisme soumis à `godaddy-temp`. Ne pas réutiliser ou élargir silencieusement le workflow P0 : son manifeste refuse légitimement toute modification de `commerce.css`.
2. Exécution uniquement depuis `feat/wordpress-woocommerce-theme`, commit exact et inventaire de fichiers vérifiés. Approbation humaine `mycomane` enregistrée pour ce nouveau run avant accès aux secrets/SSH. Concurrence `godaddy-temp-site-mutation`, aucun auto-approve.
3. Hôte SSH et empreinte épinglés; `home` et `siteurl` strictement `https://1302636.us22.myftpupload.com`, thème actif `aliments-distinction` et version 0.1.1. Refus si les 23 fichiers installés diffèrent de la base P0 attendue.
4. Contrôles SQL stricts et espace libre conservés. Sauvegarde fraîche privée thème complet + SQL compressé + empreintes et manifeste, vérification des archives et essai de restauration dans un répertoire privé. Aucun achat.
5. Préparer et vérifier les deux fichiers hors du répertoire public, sauvegarder leur état puis remplacer avec un journal et un rollback des deux fichiers si une étape échoue. Ne pas qualifier deux renommages séparés de transaction atomique globale. Contrôler le résultat complet et renouveler seulement les caches nécessaires.
6. Avant/après : thème, WooCommerce, quatre pages, produits/commandes/utilisateurs, options protégées et taxes/livraisons stables; Coming Soon présent sur accueil et pages techniques. Vérifier version, empreintes et réponse HTTP des ressources. En cas d’échec, retour à la paire précédente, puis vérification de son état.

## Français : contrôle distinct, aucun réglage hébergé effectué

Les traductions natives ont été vérifiées dans une installation jetable `fr_FR`; elles ne nécessitent aucun filtre global du thème. La prochaine intervention distante autorisée devra relever en lecture seule la langue effective WordPress et la présence/version des paquets WooCommerce français. **Le CSS ne traduit pas un WordPress réglé en anglais.** Si une installation/mise à jour de traductions ou un réglage de langue est nécessaire, le signaler avant intervention hors périmètre.

Les produits, catégories et formats monétaires des fixtures ne constituent pas un catalogue à publier. Le contenu commercial final et les textes contractuels doivent être français et validés. Ne pas déduire la devise/taxes/livraisons de l’installation locale.

## Hors périmètre

Aucun nouveau compte de recette ou produit sur GoDaddy, aucune commande/paiement, aucun changement commercial ou connexion externe. Pas de désactivation Coming Soon. Aucune opération sur `master`, le domaine commercial, DNS ou Zoho. Pas de certification de transaction complète ou de localisation globale sur la seule base des captures isolées.

**Décision attendue après rapport : autoriser ou non la préparation puis le déploiement du lot 0.1.2 limité ci-dessus, avec nouvelle approbation GitHub obligatoire.** Aucune opération distante n’est actuellement en attente de revue GitHub.

## Préparation réalisée après autorisation

- `prepare-p1-release.py` verrouille les 23 fichiers et refuse tout changement hors des deux chemins approuvés; comparaison du bloc WooCommerce au commit graphique validé. ZIP exact vérifié.
- `p1-files.php` réutilise les gardes fichiers P0 sans les modifier; ajoute journal et retour arrière acceptant uniquement une combinaison connue ancien/nouveau des deux fichiers. 23 assertions avec interruption après le premier et le second remplacement.
- `update-p1-temp.sh` conserve les contrôles SQL stricts et marges disque, ajoute la vérification HTTP des deux CSS et le retour arrière de la paire. Quatre scénarios shell simulés réussis (succès et échecs HTTP/cache/Coming Soon); aucun de ces tests n’a accédé à GoDaddy.
- Nouveau workflow `update-godaddy-temp-p1.yml`, revue `godaddy-temp` humaine obligatoire; ancien workflow P0 intact. Ajout d’un déclencheur de QA isolée sur le nouveau workflow, sans retirer de contrôle. Le job protégé attend la réussite de cette QA sur le commit exact.
- Le diagnostic de langue est en lecture seule et n’écrit ni locale, ni traduction, ni texte enregistré. Aucun accès de recette ou donnée commerciale créé.

La prochaine barrière est la revue GitHub du nouveau run, pas une nouvelle demande d’autorisation de principe. Les références au projet de livraison ci-dessus décrivent le périmètre soumis initialement; le résultat effectif sera consigné dans PROJECT_STATUS.md.
