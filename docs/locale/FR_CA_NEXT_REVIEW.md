# Revue du nouveau lot fr_CA — 22 octobre 2026

Mandat : [autorisation de préparer, sans déployer](https://github.com/mycomane/alimentsdistinction/issues/4#issuecomment-6347149703). Base `c6e423df7b2e2c0f63feb83976c2ce8283144fad`, branche `feat/wordpress-woocommerce-theme`. Correction de propriété conservée exactement depuis `d026569f5092c1c1efb7c5d8050d47b02d295975`.

## Diff de workflow proposé

Dans `.github/workflows/configure-locale-fr-ca.yml`, job `deploy` seulement :

```diff
-    # Issue #4: diagnosis and isolated review only; no mutation can be queued.
-    if: github.ref == 'refs/heads/feat/wordpress-woocommerce-theme' && false
+    # Issue #4: preparation authorized; NEW human review required before SSH.
+    if: github.ref == 'refs/heads/feat/wordpress-woocommerce-theme'
```

Le test de contrat n'attend plus le blocage statique de diagnostic. Il vérifie toujours la dépendance aux deux réussites, le même commit, l'absence de contournement des échecs, l'environnement protégé et l'approbation humaine avant l'accès distant. Le workflow de diagnostic reste strictement en lecture seule; il n'est pas relancé par ce lot.

## Avant toute opération GoDaddy

- Nouveau commit et nouvelle exécution; jamais relancer les runs échoués.
- Préparation et recette réutilisable doivent réussir au **SHA exact**. Une seule recette navigateur, aucun doublon autonome ni sondage inter-workflows.
- Arrêt à **Waiting / Review deployments** de `godaddy-temp`. Reviewer humain `mycomane`; Work n'approuve pas.
- Le premier contrôle du job protégé exige la trace d'approbation humaine de cette exécution précise avant SSH. Empreinte SSH épinglée, branche et identité exacte `home/siteurl` du temporaire restent vérifiées. Aucun autre site n'est autorisé.

## Périmètre après une éventuelle approbation distincte

Installer les 311 fichiers officiels de traduction épinglés et appliquer uniquement `WPLANG=fr_CA` sur le WordPress temporaire. Les 23 fichiers du thème et les réglages protégés sont comparés avant/après. Coming Soon demeure actif et est vérifié en HTTP sur l'accueil et les quatre pages techniques.

La langue ne réécrit pas les textes persistés : confidentialité de commande, catégories, pages et autres contenus commerciaux/légaux seront traités séparément. Aucun compte ou produit fictif, commande, passerelle, règle de taxes/livraison/devise, Zoho, DNS ou domaine commercial ne fait partie du lot.

## Sauvegarde et récupération

La **nouvelle** sauvegarde sera créée après approbation et avant l'écriture publique, dans un stockage privé hors racine Web du même hébergement, identifié par le numéro de la nouvelle exécution. Aucun chemin privé résolu n'est publié. Elle comprend SQL compressé, thème complet, langues, état exact WPLANG/protections, inventaires et SHA256. Espace mesuré avec la marge existante, intégrité contrôlée et répétition privée de l'installation/restauration des fichiers avant bascule. Anciennes sauvegardes intactes.

Le journal privé suit les intentions et modifications. Premier échec après armement : arrêt et restauration ciblée de WPLANG et des seuls fichiers de langues connus, vérification des empreintes, du thème, des protections et de Coming Soon. Aucun import SQL automatique. Une empreinte/propriété inconnue ou une récupération incomplète provoque un arrêt signalé, sans forcer l'écrasement. La sauvegarde vérifiée sur l'hôte ne certifie pas une restauration intégrale hors site.

## Risques résiduels

La lecture seule a confirmé le désaccord de propriétaire dossier/fichier; la correction distingue désormais identité des fichiers et du dossier, avec journal privé attestant l'identité d'écriture, stabilité des dossiers, liens uniques et volume identique. Les contrôles P0 sont inchangés.

Une ACL ou politique GoDaddy peut encore refuser un rename malgré les précontrôles; ce sera un échec contrôlé, avec rollback ciblé si l'état reste connu. Une dérive simultanée peut imposer une récupération manuelle autorisée. Aucun chown arbitraire, changement de permission de dossier ou assouplissement global n'est prévu.

## Preuves disponibles et résultat attendu

- [39822268395](https://github.com/mycomane/alimentsdistinction/actions/runs/39822268395) : succès du correctif, 61 assertions complètes de propriété et recette française aux cinq largeurs, zéro commande soumise/passerelle activée dans l'installation synthétique; GoDaddy non modifié par ce run.
- [39820612012](https://github.com/mycomane/alimentsdistinction/actions/runs/39820612012) : diagnostic lecture seule approuvé réussi, preuve expurgée conservée dans `issue4-filesystem-booleans.json`.
- Nouveau lot : résultat et lien ajoutés à PROJECT_STATUS.md après préparation. **Aucun français hébergé ni succès de déploiement annoncé avant approbation et vérification réelle.**
