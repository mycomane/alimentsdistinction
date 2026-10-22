# Incident WPLANG — 22 octobre 2026

Source : [run 39824523597](https://github.com/mycomane/alimentsdistinction/actions/runs/39824523597), job `119985019616`, commit `752fe13e7188c32cfd9d236f8be8cba43f1ec660`. Diagnostic effectué à partir des journaux GitHub après la capture de Julien, sans nouvelle opération GoDaddy.

## Ce qui a réussi

- Préparation et recette isolée du même commit.
- Nouvelle approbation humaine du run exact, vérifiée avant SSH (**13 h 43 min 45 s HAE**).
- Identité temporaire, marge d'espace, Coming Soon, capture d'état protégé, nouvelle sauvegarde SQL/thème/langues/SHA et répétition privée du retour arrière.
- Application des fichiers de traduction et contrôle de leur nouvel inventaire (**13 h 44 min 50 s HAE**). Le correctif de propriété a donc passé ce contrôle hébergé; la configuration complète de langue n'était pas encore validée.

## Nouvel échec

À **13 h 44 min 51 s HAE**, `ad_locale_activate()` lève `WPLANG activation failed`. Le garde de `scripts/locale/wp-functions.php` refuse si `update_option('WPLANG','fr_CA',...)` renvoie false **ou** si la ligne brute relue ne contient pas fr_CA. Le court-circuit ne journalise pas lequel de ces deux cas est arrivé.

L'erreur fatale affichée est l'exception de ce garde; elle ne permet pas de déduire un arrêt persistant du site. Aucune preuve actuelle n'établit la cause profonde : filtre/sanitisation, données disponibles au moment de l'appel, résultat d'écriture ou autre particularité d'environnement. Le refus ne doit pas être retiré, et une écriture SQL forcée ne constituerait pas un diagnostic.

## État restauré prouvé

À **13 h 45 min 02 s HAE** : `ROLLBACK VERIFIED: exact original locale and languages restored.` Les vérifications confirment langue anglaise et ligne WPLANG d'origine, inventaire des langues, thème et état protégé. Accueil et quatre pages techniques : HTTP 200, Coming Soon. Aucun import SQL. La sauvegarde fraîche a été créée et vérifiée sur le même hébergement; aucune restauration intégrale hors site n'est certifiée.

**Le run est failure; ne pas annoncer fr_CA hébergé.** Les fichiers appliqués ont été restaurés par le rollback. Les contrôles protégés de fin sont réussis; aucune extrapolation à un parcours transactionnel complet ou à une certification générale.

## Proposition à valider par la Coordination

Préparer un diagnostic à sortie fermée distinguant le retour de l'API et la valeur persistée, puis tester isolément refus de l'API, valeur inattendue, indisponibilité de requête, locale non disponible et retour arrière exact. Ne publier que des booléens/codes fixes; aucun contenu SQL, données privées ou chemin résolu. Les messages d'erreur et refus doivent rester bloquants.

La mesure historique n'est pas récupérable par une simple lecture après rollback. Une éventuelle nouvelle tentative d'écriture ou un diagnostic distant élargi devra faire l'objet d'une nouvelle autorisation et revue humaine. Ne pas relancer le run échoué, ne pas consommer son ancienne préparation, ne pas contourner l'API ou les protections pour forcer le succès.

Dans cette reprise : aucun nouveau workflow lancé, aucune modification de code ou du serveur, uniquement analyse et compte rendu. Coming Soon, site commercial, master, domaines/DNS, règles commerciales, paiements et Zoho restent hors intervention.
