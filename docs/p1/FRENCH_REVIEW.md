# P1 — recette française et validation de l’aperçu

> **Actualisation du 22 octobre :** fr_CA est installé et validé sur le WordPress temporaire par le run 39843113925, issue #4 clôturée. Le texte de confidentialité enregistré reste à faire valider/traduire; les observations de catégorie/monnaie de la fixture ne décrivent pas nécessairement l'hôte. [Audit français/UX et propositions actuels](CONTENT_UX_REVIEW_2026-10-22.md). Le reste de ce document conserve l'historique du 21 octobre, notamment les tests fr_FR et l'ancien état en_US.

**21 octobre 2026.** Julien a validé l’aperçu des contrôles et demandé de continuer. La palette « Actuel » choisie par Jean-François et Meggy est conservée. L’accord graphique ne déclenche aucune opération sur GoDaddy.

## Méthode et budget

La suite générale et ses 45 contrôles français de `39692488831` restent la référence pour le code graphique `206972b1ab2228b17b6131c66a9475e7f085f3da`. Elle n’est pas relancée : aucun nouveau changement de thème. Un workflow isolé sans environnement GoDaddy ni secrets réutilise le préparateur WordPress 7.1.3, WooCommerce 11.2.1, les paquets officiels `fr_FR` et les produits fictifs existants.

Le complément teste uniquement panier vide, tri du catalogue, rupture, sélection et réinitialisation de variation, ajout d’une quantité de deux au panier puis mise à jour à une unité, libellés de facturation et affichage de commande. L’envoi de la commande est bloqué côté navigateur, aucun bouton Commander n’est cliqué, aucune donnée personnelle n’est remplie, aucune passerelle active ni commande créée.

## Premier passage et diagnostic du test

[Run 39694636299](https://github.com/mycomane/alimentsdistinction/actions/runs/39694636299), code `5379b1da0205ec7608699ff2c14b32c80da325fa` : échec de deux attentes du test, pas d’erreur critique du site.

- Le texte officiel est « Votre panier est **actuellement** vide », alors que le test attendait la version sans cet adverbe.
- Sur mobile, le sous-total est affiché par le pseudo-élément CSS depuis `data-title="Sous-total"`. `innerText` ne restitue pas ce contenu généré. Le test doit vérifier cet attribut réellement rendu, sans modifier le produit pour satisfaire le test.

Correction du test : `0563c3a5a793e169959b6835b6316c0c3275ca25`. Aucun changement de thème, de CSS ou de traduction native. Les constats de contenu ci-dessous sont rapportés distinctement et ne sont pas effacés par la correction des attentes.

## Résultat du complément

[Run 39694834148](https://github.com/mycomane/alimentsdistinction/actions/runs/39694834148), job `119549577969`, code `0563c3a5a793e169959b6835b6316c0c3275ca25` : **succès, 23 contrôles fonctionnels supplémentaires**, zéro échec, zéro erreur JavaScript détectée. PHP : journaux serveur/WordPress présents, zéro fatal, avertissement ou dépréciation compté. Aucun ordre WooCommerce créé et aucune passerelle active. Ces résultats ne suppriment pas les constats de contenu anglais ci-dessous.

[Preuve JSON](evidence/french-storefront.json) · [Diagnostics PHP](evidence/storefront-php-diagnostics.json) · [Sélecteur non choisi](evidence/french-variable-unselected.jpg) · [Panier français](evidence/french-cart.jpg) · [Commande avec texte de confidentialité anglais à traiter](evidence/french-checkout.jpg). Captures uniquement synthétiques, examinées; aucun accès GoDaddy. Budget : deux passages ciblés, aucune relance des 300 assertions et aucune modification du thème.

## Constats de contenu français — à traiter avant publication

1. **Texte de confidentialité de commande en anglais dans la fixture.** L’installation initiale anglaise a enregistré `woocommerce_checkout_privacy_policy_text`; sélectionner `fr_FR` ne réécrit pas cette valeur. Lecture en mode SQLite `mode=ro` de la fixture locale existante : valeur anglaise confirmée. Le code WooCommerce `wc_get_privacy_policy_text()` lit l’option enregistrée avant le texte traduit par défaut. Il faut vérifier ce contenu sur l’hébergement lors d’une intervention autorisée, puis faire valider les textes français; aucun texte légal n’est remplacé automatiquement.
2. **Catégorie « Uncategorized » de la fixture initiale.** C’est un nom de catégorie enregistré, pas une chaîne du thème. Même distinction pour les noms/descriptions de produits/pages. Aucun catalogue synthétique ne doit être transféré sur GoDaddy.
3. **Monnaie et adresse de démonstration.** Les fixtures conservent les valeurs et formats initiaux de l’installation jetable. Elles ne valident ni devise, taxes, livraison, zone commerciale ou règles d’adresse du futur site.
4. **Langue du site hébergé non inspectée dans cette phase.** Les tests attestent des interfaces natives françaises sous `fr_FR`, pas de la traduction complète ou des contenus du WordPress GoDaddy. Les corrections CSS seules n’installent pas un paquet de langue et ne réécrivent aucun contenu enregistré.

Le contrôle de l’écran de commande est une inspection avec panier fictif et une mise à jour de quantité en local, **pas une validation transactionnelle complète**. Les contrastes de la palette conservée restent ceux déjà documentés; aucun nouvel accord global A/B n’est inféré.

## Suite proposée

[Périmètre du futur lot temporaire 0.1.2](DEPLOYMENT_SCOPE.md) : les deux seuls fichiers de thème concernés seraient `commerce.css` (bloc approuvé) et `style.css` (version/cache). Aucun déploiement ni modification de workflow protégé préparé implicitement. L’autorisation de cette livraison sera distincte, suivie d’une nouvelle approbation GitHub pour le run exact.

## Diagnostic hébergé après livraison des styles

Le [déploiement 39696116068](https://github.com/mycomane/alimentsdistinction/actions/runs/39696116068) a relevé la langue en lecture seule : **en_US**, inventaire français non établi, libellés « Add to cart » et « Choose an option », marqueur anglais dans le texte de confidentialité enregistré. Le risque n’est donc plus seulement théorique pour le WordPress temporaire. **Les styles 0.1.2 sont déployés; la localisation française hébergée reste à réaliser dans un lot distinct.** Aucun texte privé extrait, aucune option/locale/traduction modifiée. Les tests `fr_FR` isolés attestent du fonctionnement des traductions lorsqu’elles sont installées, pas de leur présence sur GoDaddy.
