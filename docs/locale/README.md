# Localisation française — préparation fr_CA

21 octobre 2026, America/Toronto. Le thème 0.1.2 est installé avec succès; l’interface native observée est en `en_US`. Julien demande de continuer vers le français.

## Rectification du diagnostic précédent

Les champs du premier diagnostic nommés `core_fr_pack` / `woo_fr_pack` vérifiaient le chemin construit depuis la locale actuelle **en_US**. Leur résultat `false` ne prouve pas l’absence des paquets français. Les logs bruts restent conservés; leur interprétation a été corrigée dans le suivi et la preuve JSON. Le nouvel inventaire ciblera explicitement `fr_CA` et `fr_FR`.

## Choix technique à tester

**Français du Canada (`fr_CA`)**, avec les traductions officielles correspondant aux versions installées WordPress 7.1.3 / WooCommerce 11.2.1. Sources vérifiées :

- https://api.wordpress.org/translations/core/1.0/?version=7.1.3
- https://api.wordpress.org/translations/plugins/1.0/?slug=woocommerce&version=11.2.1

[ZIP et empreintes épinglés](fr-ca-packages.json). Aucun téléchargement flottant lors d’une future opération : les SHA256 approuvés doivent correspondre, sinon arrêt et nouvelle revue. Préparateur `scripts/locale/prepare-packages.py` : chemins exclusivement fr_CA core et `plugins/woocommerce-fr_CA`, extensions MO/PO/JSON/L10N.PHP, refus des traversées/symlinks/doublons, volumes bornés et inventaire complet. Les paquets officiels contiennent des fichiers PHP de traduction; aucun code tiers ajouté au thème.

## Périmètre de configuration envisagé

- Installer les traductions officielles épinglées si nécessaire et modifier uniquement la langue WordPress `WPLANG` vers `fr_CA`.
- Conserver thème 0.1.2, Coming Soon, utilisateurs, produits et toutes les options commerciales (devises, adresses, taxes, livraison, paiements).
- Ne pas réécrire les textes de confidentialité, pages, noms de catégories ou produits enregistrés. Les contenus français restent un chantier éditorial distinct à valider.
- Nouvelle revue humaine GitHub `godaddy-temp` avant toute opération distante, sauvegarde privée fraîche SQL/fichiers concernés, contrôle d’inventaire avant/après et retour arrière précis de la langue et des seuls fichiers de traduction touchés.
- Aucun compte de recette ou produit fictif sur GoDaddy. Tests fonctionnels seulement dans l’installation jetable; aucune commande soumise.

## État

Payload officiel de 311 fichiers vérifié localement; tests isolés fr_CA à exécuter. Aucune nouvelle opération GoDaddy. Aucun résultat de recette anticipé.

## Préparation protégée et limites

La demande de Julien de poursuivre vers le français est mise en œuvre comme une configuration **fr_CA core/WooCommerce et WPLANG seulement**, toujours soumise à une nouvelle revue humaine GitHub du run exact. Pas de nouveau thème ni incrément : le thème reste 0.1.2 et ses 23 fichiers sont comparés au commit installé `ad3006ca7c618b9d71617232bec67fe7c9cd322a`.

- Paquets fixes : core SHA256 `b20bbbb9d283b5b69d1af2efbb6ef91b6dec496a39cac638fd8138326abd2031`, WooCommerce `74b3dfd48917f23a2487dc9a2cebc711c57e24b1bdd86729f2aa7fb2f036f949`.
- Sauvegarde fraîche privée SQL, thème, langues, valeur/autoload de WPLANG et empreintes. Essai privé de modification/restauration de fichiers avant modification publique, journal des remplacements, refus de toute dérive inconnue ou liée à une autre langue/extension.
- Comparaison des valeurs **et autoload** des options protégées, des pages, comptes/produits/commandes, paiements, taxes et livraisons. Seule WPLANG est autorisée à changer. Retour arrière de cette option : valeur/autoload exacts conservés, ou suppression si elle n’existait pas. La restauration utilise une requête ciblée sur le nom littéral WPLANG lorsque nécessaire, car l’API WordPress normalise certains anciens `en_US` en chaîne vide; **aucune importation SQL**.
- `state.php` refuse une origine différente du temporaire; son interface n’accepte pas de mode isolé. Les tests de la bibliothèque utilisent une identité strictement loopback + WP_ENVIRONMENT_TYPE local et n’accèdent pas à GoDaddy.
- Coming Soon vérifié en anglais et dans son libellé officiel fr_CA « Prochainement », avec rejet des mots cachés en script/style et du héro du site publiquement visible. Les options de visibilité restent également protégées.
- Aucun contenu légal enregistré remplacé automatiquement. Le texte de confidentialité anglais détecté restera inchangé par ce lot; les noms/pages/produits enregistrés ne sont pas traduits par la seule locale. Aucun paramètre de devise, pays commercial, taxe, livraison ou paiement modifié.

## Recette et diagnostic intermédiaires

Le premier run fr_CA `39697667048` a validé les deux libellés voulus mais a échoué sur deux attentes de vocabulaire français de France : les traductions canadiennes officielles sont « En rupture d’inventaire » et « Procéder à la commande ». Le test est désormais explicite selon fr_CA/fr_FR; aucune traduction officielle n’est changée pour satisfaire le test.

Le second passage `39698258841` a arrêté le test d’option avant la recette navigateur : une tentative d’injection de locale inconnue par l’API était normalisée/refusée par WordPress avant d’atteindre le garde testé. L’injection de cette faute est maintenant faite directement dans la **base jetable uniquement**, puis le garde doit refuser de l’écraser. Les erreurs de test remontent leur cause exacte, au lieu de la page générique de récupération WordPress.

Tests locaux réussis sur un WordPress jetable fraîchement préparé : **30 assertions WPLANG/protection/retour exact**, vérification dans un nouveau processus PHP de la locale fr_CA et de deux libellés natifs, **28 assertions de fichiers**, **7 assertions de visibilité**, **4 simulations du vrai shell** (succès, erreur après changement de WPLANG, échec de vérification, Coming Soon perdu). Les simulations n’établissent pas un résultat GoDaddy ou une restauration SQL complète.

La prochaine exécution GitHub doit confirmer ces tests et la recette navigateur du commit exact avant de rendre disponible la revue `godaddy-temp`. Ne pas annoncer le français installé sur GoDaddy avant le résultat réel de ce nouveau job protégé.
