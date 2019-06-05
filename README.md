# Verifone e-commerce - Magento 2

Extension Magento 2 pour la solution de paiement Verifone e-commerce

## A propos

En une seule intégration, offrez plusieurs méthodes de paiement, bénéficiez d'une page de paiement personalisée et sécurisée, multi-langues et multi-devises et offrez le paiement à la livraison ou en 3 fois sans frais pour vos clients.

## Installation

1. Assurez-vous de disposer de clés d'authentification Magento pour votre site (http://devdocs.magento.com/guides/v2.0/install-gde/prereq/connect-auth.html)

2. Aller dans le dossier racine de Magento2 en ligne de commande (`<your Magento install dir>`)

3. Exécuter la commande suivante pour la récupération des fichiers du module via Composer (un dossier `paybox` sera créé dans le sous-dossier `vendor`). Vous aurez besoin de votre `Public key` comme identifiant et de votre `Private key` comme mot de passe :

    ```sh
	composer require paybox/epayment
    ```

4. Exécuter les commandes suivantes pour l'installation, l'activation et le déploiement du module :

    ```sh
    # Installation du module
    php bin/magento setup:upgrade
    # Nettoyage du cache
    php bin/magento cache:clean
    # Déploiement des ressources Front Office pour l'étape de paiement du tunnel de commande pour chaque langue du site (l'option <lang> est une liste séparée par des espaces de codes langue au format ISO-636, la liste est disponible en lançant la commande php bin/magento info:language:list)
    php bin/magento setup:static-content:deploy <lang> (exemple : fr_FR)
    ```

5. Vous pouvez alors configurer le module dans votre Back Office via le nouvel onglet Paybox du menu Boutiques \ Configuration. Il est important de bien rentrer vos identifants (mot de passe, clé HMAC) sans quoi il est possible qu'une erreur 500 s'affiche.

## Configuration

Le paramétrage par défaut correspond à l'environnement de test Paybox où tous les modes de paiement sont disponibles et où toutes les cartes sont activables, aucun paiement réel ne sera effectué.

Pour utiliser le module en réel en environnement de production, vous devez avoir souscrit un contrat auprès de Paybox, disposer de vos identifiants, configurer les options, modes de paiement et cartes en fonction de votre contrat.

## Problèmes connus (known issues) 

### Versions 2.2.1, 2.2.2
La sauvegarde des paramètres du module dans le BackOffice pose problème (Account Settings): les champs `password` et `hmacalgo` étant réencryptés à chaque sauvegarde.

### Problème en partie résolu sur Magento 2.3.x (2.3-dev)
Commit 305bf94b7401065ea0aaf2c18af93ce7b8dfca15)
Ces champs ne sont en effet plus réencryptés à la sauvegarde, mais ils ne sont plus lus du fichier de configuration (etc/config.xml)