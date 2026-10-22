# Nouvelle revue fr_CA — 22 octobre 2026

## Exécution à examiner

- [Run protégé 39843113925](https://github.com/mycomane/alimentsdistinction/actions/runs/39843113925), nouvelle exécution; aucun ancien run relancé.
- SHA immuable du run : **2949707b119f158461fdfa9a7a634a359af51a97** sur `feat/wordpress-woocommerce-theme`.
- [Diff de préparation depuis la base](https://github.com/mycomane/alimentsdistinction/compare/deda077b81fb200044172f75d30c0db247ffce7a...2949707b119f158461fdfa9a7a634a359af51a97) : déclenchement temporaire branche/chemin, contrat correspondant, marqueur et suivi uniquement. Le contrôleur WPLANG instrumenté et ses protections ne changent pas.
- [Retrait du déclenchement temporaire 065a2c7e](https://github.com/mycomane/alimentsdistinction/commit/065a2c7e559e1fa372ea2c8e48790d1430f47017) après mise en file. Le marqueur est conservé inchangé : aucun second déclenchement fr_CA demandé par ce retrait. Le HEAD de suivi n'est pas le SHA exécuté; une future approbation porterait exclusivement sur le run et son SHA ci-dessus.

Julien a explicitement autorisé ce déclenchement temporaire dans Work, pour débloquer l'absence de bouton manuel, **sans autoriser Work à approuver le déploiement**. [Trace de l'autorisation complémentaire dans l'issue #4](https://github.com/mycomane/alimentsdistinction/issues/4#issuecomment-6349782802). Elle ne remplace aucune revue humaine de l'environnement.

## Résultats du même SHA

Préparation [120049183824](https://github.com/mycomane/alimentsdistinction/actions/runs/39843113925/job/120049183824) : **success**, journal contrôlé. Huit contrats; 17 assertions fichiers P0; 46 parser/espace SQL; 28 chemins/inventaires/langues/rollback; sept contrôles Coming Soon; 61 assertions complètes propriétaire/volume/liens/droits/récupération; quatre simulations shell. Syntaxe, 311 fichiers officiels épinglés et thème 0.1.2 de 23 fichiers inchangé vérifiés.

CI ciblée [39843113865](https://github.com/mycomane/alimentsdistinction/actions/runs/39843113865), job `120049182177` : **success**, journal contrôlé sur le même SHA. 62 assertions WPLANG et traces fermées, 30 rollback, quatre simulations et huit contrats; fr_CA/deux libellés natifs vérifiés dans un processus neuf. Aucun navigateur/SSH/GoDaddy dans cette CI ciblée. Le message historique du contrôle d'absence de commandes mentionne le navigateur, sans en exécuter ici.

La recette réutilisable `isolated-qa / french-storefront`, job `120049184228`, a **réussi sur le même SHA** : 30 assertions WPLANG/rollback, validation fr_CA/libellés en processus neuf, **24 contrôles du parcours français et 45 contrôles responsive sans échec**, aucune commande soumise ni passerelle active; journaux PHP/WordPress sans fatal, warning ou deprecated. Ces résultats viennent du journal de ce nouveau run, sans substitution de preuves historiques.

Deux observations éditoriales connues de la fixture restent ouvertes : texte de confidentialité enregistré et catégorie créés en anglais. Aucun contenu hébergé n'est réécrit par le contrôleur de langue. La présence de l'interface de commande testée ne constitue pas une commande ou un paiement complet validé.

Artefacts du run, non expirés à la vérification : `locale-fr-ca-review-package` ID `12454231661` et `locale-fr-ca-isolated` ID `12453981820` (preuves et captures de fixture).

## Point d'arrêt confirmé

Le run est **waiting**, tentative 1, job protégé `120049875766` **waiting**. L'API `pending_deployments` confirme l'environnement `godaddy-temp` et le reviewer humain `mycomane`. L'API `approvals` ne retourne aucune approbation à la vérification. Work n'en soumet aucune. Le job protégé n'a pas exécuté ses étapes; aucune connexion SSH ou modification GoDaddy n'est effectuée par cette préparation. **Arrêt à Review deployments atteint.**

Le déclencheur temporaire est retiré au HEAD; le run en attente demeure attaché à son propre SHA 2949707b. Ce retrait n'est pas un changement du contenu à approuver et ne crée pas une seconde préparation. Consulter le SHA du run, pas le HEAD documentaire, pour la décision.

## Périmètre futur après une éventuelle approbation humaine

Le travail hébergé resterait limité au site `https://1302636.us22.myftpupload.com`, aux 311 fichiers fr_CA officiels et à l'option WPLANG via `update_option`. Reconnaissance avant/après invalidation native ciblée, retour API et lecture brute sont observés par sept booléens/codes fixes sans données SQL, identités ou chemins privés. Vérification finale de locale/libellés dans une invocation WP-CLI distincte.

Avant toute mutation des langues/WPLANG, **après** la nouvelle revue humaine du run exact : identité stricte, absence de dérive, thème/manifeste inchangés, espace SQL/disque, sauvegarde fraîche privée SQL/thème/langues/baseline/SHA, vérification gzip/SHA et répétition privée application/rollback, Coming Soon sur accueil/quatre pages et baseline protégée. Aucune ancienne sauvegarde substituée. Ces opérations hébergées ne sont pas déclarées exécutées au stade de revue.

Toute précondition non confirmée arrête l'opération. Après armement, échec déclenche la restauration ciblée connue WPLANG/autoload et fichiers, invalidation de découverte, vérification en processus neuf et Coming Soon. Dérive inconnue : refus et arrêt, aucun écrasement forcé. Aucune purge globale, SQL forçant fr_CA ou import automatique de sauvegarde SQL. `ROLLBACK VERIFIED` seulement si tous les contrôles réussissent.

## Risques et limite d'autorisation

Cause historique GoDaddy non prouvée; cache persistant, filtres WordPress, DB et ACL/rename peuvent encore refuser. Les tests SQLite et simulations ne garantissent pas le comportement hébergé. Récupération intégrale hors site non certifiée. Fr_CA n'est pas déclaré installé sur GoDaddy à ce stade.

Work s'arrête à **Review deployments**, ne clique jamais Approve and deploy et ne soumet aucune approbation API. La Coordination examine les preuves et le SHA; la décision humaine de déploiement reste distincte. Site commercial, master, DNS/domaines, Zoho, paiements, règles commerciales, produits, commandes et clients restent hors intervention. Coming Soon est maintenu.
