# P0 — recette hébergée après déploiement 0.1.1

**P0 corrigé, déployé et validé par recette automatisée ciblée sur le WordPress temporaire.**

Site : https://1302636.us22.myftpupload.com/ (Coming Soon conservé). [Run 39686849316](https://github.com/mycomane/alimentsdistinction/actions/runs/39686849316), job `119523045267`, commit exécuté `6b3031a74792827836d3bab57d181896b7d15d40`. Run terminé le 21 octobre 2026 à **18 h 34 HAE**, succès après approbation `mycomane` du run exact. Préparation `119522989146` réussie. Le thème n'a pas été redéployé pendant la recette.

## Résultats hébergés réellement observés

**88 contrôles frontend réussis, zéro échec, zéro erreur JavaScript `pageerror`.** Le navigateur utilisait uniquement le nouvel Abonné temporaire autorisé, sans droit d'administration, avec session serveur de 30 minutes. L'inventaire des 23 fichiers 0.1.1 a été vérifié avant création.

| Largeur | Écart de hauteur du héro | Écart maximal des six zones comparées | Débordement horizontal | Images locales cassées |
| --- | ---: | ---: | --- | ---: |
| 320 | 0,00 px | 0,00 px | Aucun | 0 |
| 390 | 0,00 px | 0,00 px | Aucun | 0 |
| 768 | 0,00 px | 0,00 px | Aucun | 0 |
| 1024 | 0,00 px | 0,00 px | Aucun | 0 |
| 1440 | 0,00 px | 0,00 px | Aucun | 0 |

Six zones : héro, zone gauche, photo, titre, boutons et bloc de réassurance. Positions et dimensions comparées à `index.html` servi sur loopback du runner; tolérance de test 1 px, écart effectivement mesuré 0,00 px. Les trois indicateurs de réassurance restent sur une ligne flex `nowrap`, sans découpe ni chevauchement. Les métadonnées de police/couleur du titre correspondent aussi aux valeurs de la référence; cela ne certifie pas l'accessibilité globale.

- Menu mobile à 320/390/768 : ouverture par Enter, fermeture par Échap et restitution du focus réussies.
- Ancres internes de l'accueil : cibles présentes aux cinq largeurs.
- Images locales chargées sans image cassée détectée; compteur externe cassé également nul dans ces scénarios.
- HTML authentifié : demande de `style.css?ver=0.1.1`; navigateur reçoit HTTP 200 et SHA256 exact `5a4c3a5a8ae6fccd66437151555b66d05c1f5c18dc56cfff3de3f66a92bde780`. Le correctif effectivement servi est donc confirmé dans la session réelle.
- Catalogue, panier, commande et compte : réponses HTTP 200 sur l'origine temporaire, thème visible, aucun débordement à 390 px.
- **Commande avec panier vide : destination finale classée `cart`**, formulaire de commande absent. Aucun produit ajouté ni formulaire soumis; le parcours transactionnel hébergé n'est pas déclaré validé.
- Coming Soon anonyme confirmé avant/après les contrôles privés.

## Nettoyage confirmé

Les étapes de révocation/suppression et d'effacement des fichiers sensibles du runner ont réussi. Marqueurs exacts du job :

```text
PASS: temporary subscriber removed and sessions revoked.
PASS: protected settings and product/order counts unchanged.
```

Le compte de recette n'existe plus et aucune session de recette n'est conservée pour une réutilisation. Aucun rôle élevé, produit, commande, paiement ou configuration commerciale créé par cette recette. Aucune capture, HTML authentifié ou cookie publié. Les scripts et l'état non authentifiant restent privés hors webroot conformément à la procédure.

## Chaîne de preuves et limites

- Déploiement technique : [39685183348](https://github.com/mycomane/alimentsdistinction/actions/runs/39685183348), thème 0.1.1, sauvegarde privée fraîche et répétition du rollback avant bascule. Voir [preuve de déploiement](evidence/p0-deployment-result.json).
- Recette isolée du même code de contrôle : [39686849309](https://github.com/mycomane/alimentsdistinction/actions/runs/39686849309), **300 assertions réussies**, aucun fatal/avertissement/dépréciation PHP compté et aucune commande. Produits et parcours panier/variations/stock testés seulement dans cet environnement jetable.
- Preuve hébergée persistée : [p0-hosted-review-result.json](evidence/p0-hosted-review-result.json), uniquement compteurs, mesures et résultats autorisés. Les logs GitHub constituent la source d'exécution; aucune session privée n'a été copiée dans cette preuve.

Il s'agit d'une recette automatisée de géométrie et de navigation ciblée. Aucune capture authentifiée n'a été exportée ni examinée manuellement. Les sections secondaires ne sont pas certifiées pixel par pixel; les contrastes/P1, lecteurs d'écran, performance, contenus commerciaux et parcours transactionnel GoDaddy restent distincts.

**Conclusion pour Coordination : les critères techniques P0 de l'issue #1 sont remplis.** Le thème est déployé, son rendu ciblé réel correspond à la référence aux cinq largeurs et le nettoyage est confirmé. La Coordination peut valider la clôture P0 et décider du lot P1. Aucune autorisation P1/palette, ouverture publique, production, paiement, domaine/DNS ou Zoho n'est inférée de ce résultat.
